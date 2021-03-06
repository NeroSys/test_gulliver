<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/19/18
 * Time: 7:00 PM
 */

namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\models\LoginForm;

class LoginFormWidget extends Widget {

    public function run() {
        if (Yii::$app->user->isGuest) {
            $model = new LoginForm();
            return $this->render('loginFormWidget', [
                'model' => $model,
            ]);
        } else {
            return false;
        }
    }

}