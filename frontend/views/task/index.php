<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Project;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $projects array */
/* @var $activeUsers array */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'project_id',
                'label' => 'Project',
                'filter' => $projects,
                'format' => 'html',
                'value' => function (\common\models\Task $model) {
                    return Html::a($model->project->title, ['project/view', 'id' => $model->project->id]);
                }
            ],
            [
                'attribute' => 'title',
                'label' => 'Task'
            ],
            [
                'attribute' => 'description',
                'filter' => false
            ],
            [
                'attribute' => 'executor_id',
                'label' => 'Executor',
                'format' => 'html',
                'filter' => $activeUsers,
                'value' => function (\common\models\Task $model) {
                    return Html::a($model->executor->username, ['user/view', 'id' => $model->executor_id]);
                }
            ],
            'started_at:datetime',
            'completed_at:datetime',
            [
                'attribute' => 'creator_id',
                'label' => 'Creator',
                'format' => 'html',
                'filter' => $activeUsers,
                'value' => function (\common\models\Task $model) {
                    return Html::a($model->creator->username, ['user/view', 'id' => $model->creator_id]);
                }
            ],
            [
                'attribute' => 'updater.username',
                'label' => 'Updater',
                'format' => 'html',
                'value' => function (\common\models\Task $model) {
                    return Html::a($model->updater->username, ['user/view', 'id' => $model->updater_id]);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {take} {completed}',
                'buttons' => [
                    'take' => function ($url, \common\models\Task $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('hand-right');
                        return Html::a($icon, ['task/take', 'id' => $model->id], ['data' => [
                            'confirm' => 'Берете задачу?',
                            'method' => 'post',
                        ]]);
                    },
                    'completed' => function ($url, \common\models\Task $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('thumbs-up');
                        return Html::a($icon, ['task/complete', 'id' => $model->id], ['data' => [
                            'confirm' => 'Задача завершена?',
                            'method' => 'post',
                        ]]);
                    }
                ],
                'visibleButtons' => [
                    'update' => function (\common\models\Task $model, $key, $index) {
                        return Yii::$app->taskService->canManage($model->project, Yii::$app->user->identity);
                    },
                    'delete' => function (\common\models\Task $model, $key, $index) {
                        return Yii::$app->taskService->canManage($model->project, Yii::$app->user->identity);
                    },
                    'take' => function (\common\models\Task $model, $key, $index) {
                        return \Yii::$app->taskService->canTake($model, Yii::$app->user->identity);
                    },
                    'completed' => function (\common\models\Task $model, $key, $index) {
                        return \Yii::$app->taskService->canComplete($model, Yii::$app->user->identity);
                    }
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
