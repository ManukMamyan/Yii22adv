<?php

use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $user common\models\User
 * @var $task common\models\Task
 */

?>
 Здравствуйте <?= Html::encode($user->username) ?>
 В проекте <?= Html::encode($task->project->title) ?> разработчик <?= $task->executor->username ?>
 завершил задачу <?= $task->title ?>
