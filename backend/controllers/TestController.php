<?php


namespace backend\controllers;

use common\models\Project;
use common\models\Task;
use yii\web\Controller;

class TestController extends Controller
{

    public function actionIndex()
    {
        $task = Task::findOne(1);
        var_dump($task->getTaskUserRoles());exit;
    }
}