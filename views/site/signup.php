<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/19/18
 * Time: 6:11 PM
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

$this->title = 'Авторизация на сайте';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните поля для авторизации на сайте:</p>
    <div class="row">
        <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя') ?>
            <?= $form->field($model, 'lastName')->textInput(['autofocus' => true])->label('Фамилия') ?>
            <?= $form->field($model, 'male')->dropDownList([ 'prompt'=>'выберите пол',
                '0' => 'мужской',
                '1' => 'женский',

            ])->label('Пол') ?>
            <?= $form->field($model, 'email')->label('Email') ?>
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>


            <div class="panel panel-default">
                <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> Addresses</h4></div>
                <div class="panel-body">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsAddress[0],
                        'formId' => 'form-signup',
                        'formFields' => [
                            'codePostal',
                            'country',
                            'city',
                            'street',
                            'houseNumber',
                            'flatNumber',
                        ],
                    ]); ?>

                    <div class="container-items"><!-- widgetContainer -->
                        <?php foreach ($modelsAddress as $i => $modelAddress): ?>
                            <div class="item panel panel-default"><!-- widgetBody -->
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left">Address</h3>
                                    <div class="pull-right">
                                        <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                        <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">

                                            <?php
                                            // necessary for update action.
                                            if (! $modelAddress->isNewRecord) {
                                                echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                                            }
                                            ?>
                                            <?= $form->field($modelAddress, "[{$i}]codePostal")->textInput(['maxlength' => true]) ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelAddress, "[{$i}]country")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelAddress, "[{$i}]city")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div><!-- .row -->
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <?= $form->field($modelAddress, "[{$i}]street")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-4">
                                                    <?= $form->field($modelAddress, "[{$i}]houseNumber")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-4">
                                                    <?= $form->field($modelAddress, "[{$i}]flatNumber")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div><!-- .row -->


                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php DynamicFormWidget::end() ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Создать пользователя', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php $script = <<< JS

        $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
            console.log("beforeInsert");
        });

        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            console.log("afterInsert");
        });

        $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
            if (! confirm("Are you sure you want to delete this item?")) {
                return false;
            }
            return true;
        });

        $(".dynamicform_wrapper").on("afterDelete", function(e) {
            console.log("Deleted item!");
        });

        $(".dynamicform_wrapper").on("limitReached", function(e, item) {
            alert("Limit reached");
        });

JS;
$this->registerJs($script, yii\web\View::POS_END );
?>