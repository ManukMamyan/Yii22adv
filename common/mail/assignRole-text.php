<?php
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $user common\models\User
 * @var $project common\models\Project
 * @var $role string
 */

?>
 Здравствуйте <?= Html::encode($user->username)?>
В проекте  <?= Html::encode($project->title)?> ваша роль изменена. Теперь вы <?= $role ?>
