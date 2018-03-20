<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'lastName',
            [
                'attribute'=> 'male',
                'format' => 'html',
                'value' => function($model){
                    return $model->male ? '<span class="text-primary">Женский</span>' : '<span class="text-primary">Мужской</span>';
                }
            ],
            'male',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

    <h2>Addresses</h2>


    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider(['query' => $model->getUserProfiles()]),
        'layout' => "{items}\n{pager}",
        'columns' => [
                'codePostal',
                'country',
                'city',
                'street',
                'houseNumber',
                'flatNumber',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete} {link}',
                'buttons' => [
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            ['user-profile/delete', 'id' => $model->id, 'user_id' => $model->id],
                            ['data-method' => 'post']
                        );
                    },
                ],
                'controller' => 'user-profile',

            ],
        ],
    ]); ?>

</div>
