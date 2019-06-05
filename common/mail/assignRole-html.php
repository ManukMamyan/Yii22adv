<?php
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $user common\models\User
 * @var $project common\models\Project
 * @var $role string
 */

?>

<div>
    <p> Здравствуйте <?= Html::encode($user->username)?> </p>
    <p> В проекте  <?= Html::encode($project->title)?> ваша роль изменена. Теперь вы <?= $role ?>  </p>
</div>
