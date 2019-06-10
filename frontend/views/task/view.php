<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p> <?php if (\Yii::$app->taskService->canTake($model, Yii::$app->user->identity)) : ?>
            <?= Html::a('Take Task', ['task/take', 'id' => $model->id],
                [
                    'class' => 'btn btn-primary',
                    'data' => [
                        'confirm' => 'Берете задачу?',
                        'method' => 'post',
                    ]
                ])
            ?>
        <?php endif; ?>
        <?php if (\Yii::$app->taskService->canComplete($model, Yii::$app->user->identity)) : ?>
        <?= Html::a('Complete Task', ['task/complete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Задача завершена?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'project_id',
            'executor_id',
            'started_at:datetime',
            'completed_at:datetime',
            'creator_id',
            'updater_id',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php echo \yii2mod\comments\widgets\Comment::widget([
        'model' => $model,
    ]); ?>

</div>
