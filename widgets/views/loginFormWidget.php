<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/19/18
 * Time: 7:01 PM
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

Modal::begin([
    'header'=>'<h4>Вход</h4>',
    'id'=>'login-modal',
]);
?>

    <p>Пожалуйста, заполните поля для входа:</p>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'action' => ['site/ajax-login']
]);
echo $form->field($model, 'username')->textInput()->label('Имя');
echo $form->field($model, 'password')->passwordInput()->label('Пароль');
echo $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня');
?>

    <div>
        Если Вы забыли свой пароль Вы можете <?= Html::a('его восстановить', ['site/request-password-reset']) ?>.
    </div>
    <div class="form-group">
        <div class="text-right">

            <?php
            echo Html::button('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']);
            echo Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']);
            ?>

        </div>
    </div>

<?php
ActiveForm::end();
Modal::end();