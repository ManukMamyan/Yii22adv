<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'active',
            'creator_id:datetime',
            'updater_id:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    </br>
    </br>
    </br>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns' =>
                [
                    [
                        'attribute' => 'Username',
                        'format' => 'html',
                        'value' => function (\common\models\ProjectUser $model) {
                            return Html::a($model->user->username, ['user/view', 'id' => $model->user_id]);
                        }

                    ],
                    [
                        'attribute' => 'Role',
                        'value' => function (\common\models\ProjectUser $model) {
                            return $model->role;
                        }
                    ],

                ],
            'summary' => false,
        ]); ?>


</div>
