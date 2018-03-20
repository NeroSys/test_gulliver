<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Admin | ';
?>

<div class="admin-default-index container">
    <h1>Users</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

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
//            'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {link}',
                'buttons' => [
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            ['user/delete', 'id' => $model->id],
                            ['data-method' => 'post']
                        );
                    },
                ],
                'controller' => 'user',

            ],
        ],
    ]); ?>
</div>
