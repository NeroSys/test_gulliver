<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/19/18
 * Time: 6:11 PM
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя') ?>
            <?= $form->field($model, 'lastName')->textInput(['autofocus' => true])->label('Фамилия') ?>
            <?= $form->field($model, 'male')->dropDownList([ 'prompt'=>'выберите пол',
                '0' => 'мужской',
                '1' => 'женский',

            ])->label('Пол') ?>
            <?= $form->field($model, 'email')->label('Email') ?>
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
            <div class="form-group">
                <?= Html::submitButton('Создать пользователя', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>