<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use yii\web\BadRequestHttpException;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\HttpException;
use yii\widgets\ActiveForm;
use app\models\UserProfile;
use yii\web\NotFoundHttpException;
use app\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Exception;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionSignup()
    {
        $model = new SignupForm();

        $modelsAddress = [new UserProfile];

        if ($model->load(Yii::$app->request->post())) {

            $modelsAddress = Model::createMultiple(UserProfile::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());


            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();


            if ($user = $model->signup()) {


                $valid = Model::validateMultiple($modelsAddress) && $valid;

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {

                            foreach ($modelsAddress as $modelAddress) {
                                $modelAddress->user_id = $user->id;
                                if (! ($flag = $modelAddress->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }

                        if ($flag) {
                            $transaction->commit();
                            if (Yii::$app->getUser()->login($user)) {

                                return $this->goHome();
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }


            }
        }
        return $this->render('signup', [
            'model' => $model,
            'modelsAddress' => (empty($modelsAddress)) ? [new UserProfile] : $modelsAddress
        ]);
    }
//
//if ($valid) {
//$transaction = \Yii::$app->db->beginTransaction();
//try {
//if ($flag = $modelMain->save(false)) {
//foreach ($modelTrouble as $modelAddress) {
//$modelAddress->item_id = $modelMain->id;
//if (! ($flag = $modelAddress->save(false))) {
//$transaction->rollBack();
//break;
//}
//}
//
//foreach ($modelFeedback as $modelF) {
//    $modelF->item_id = $modelMain->id;
//    if (! ($flag = $modelF->save(false))) {
//        $transaction->rollBack();
//        break;
//    }
//}
//}
//if ($flag) {
//    $transaction->commit();
//    Yii::$app->response->refresh();
//
//    return $this->render('line-form', [
//        'modelMain' => $modelMain,
//        'modelTrouble' => (empty($modelTrouble)) ? [new LinePersonnelTrouble()] : $modelTrouble,
//        'modelFeedback' => (empty($modelFeedback)) ? [new LinePersonnelFeedback()] : $modelFeedback
//    ]);
//}
//} catch (Exception $e) {
//    $transaction->rollBack();
//}
//            }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
        return $this->render('passwordResetRequestForm', [
            'model' => $model,
        ]);
    }
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }
        return $this->render('resetPasswordForm', [
            'model' => $model,]);
    }
    public function actionAjaxLogin() {
        if (Yii::$app->request->isAjax) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->login()) {
                    return $this->goBack();
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
            }
        } else {
            throw new HttpException(404 ,'Page not found');
        }
    }
//    public function actionAddAdmin() {
//        $model = User::find()->where(['username' => 'admin'])->one();
//        if (empty($model)) {
//            $user = new User();
//            $user->username = 'admin';
//            $user->email = 'admin@admin.ua';
//            $user->setPassword('admin');
//            $user->generateAuthKey();
//            if ($user->save()) {
//                echo 'good';
//            }
//        }
//    }


}