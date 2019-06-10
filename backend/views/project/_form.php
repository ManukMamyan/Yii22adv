<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Project;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */
/* @var $user_id array */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'active')->dropDownList(Project::STATUSES_LABELS) ?>

    <?php if (!$model->isNewRecord) { ?>

        <?= $form->field($model, Project::RELATION_PROJECT_USER)
            ->widget(MultipleInput::class,
                //https://github.com/unclead/yii2-multiple-input/wiki/Usage
                [
                    'id' => 'project-users-widget',
                    'max' => 10,
                    'min' => 0,
                    'addButtonPosition' => MultipleInput::POS_HEADER,
                    'columns' =>
                        [
                            [
                                'name' => 'project_id',
                                'type' => 'hiddenInput',
                                'defaultValue' => $model->id
                            ],
                            [
                                'name' => 'user_id',
                                'type' => 'dropDownList',
                                'title' => 'User',
                                'items' => $user_id
                            ],
                            [
                                'name' => 'role',
                                'type' => 'dropDownList',
                                'title' => 'Role',
                                'items' => \common\models\ProjectUser::ROLES_LABELS
                            ],
                        ],
                ]
            )
        ?>

    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
