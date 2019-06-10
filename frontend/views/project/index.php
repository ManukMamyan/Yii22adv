<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => function (\common\models\Project $model) {
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                }
            ],
            [
                'attribute' => \common\models\Project::RELATION_PROJECT_USER . '.role',
                'format' => 'html',
                'value' => function (\common\models\Project $model) {
                    return join(',', Yii::$app->projectService->getRoles($model, Yii::$app->user->identity));
                }
            ],
            [
                'attribute' => 'active',
                'filter' => common\models\Project::STATUSES_LABELS,
                'value' => function (common\models\Project $model) {
                    return common\models\Project::STATUSES_LABELS[$model->active];
                }

            ],
            'description:ntext',
            [
                'attribute' => 'created_by',
                'format' => 'html',
                'value' => function (common\models\Project $model) {
                    return Html::a($model->creator->username, ['user/view', 'id' => $model->creator_id]);
                }
            ],
            [
                'attribute' => 'updated_by',
                'format' => 'html',
                'value' => function (common\models\Project $model) {
                    return Html::a($model->updater->username, ['user/view', 'id' => $model->updater_id]);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
