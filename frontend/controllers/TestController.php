<?php


namespace frontend\controllers;


use common\models\Project;
use common\models\ProjectUser;
use common\models\User;
use yii\web\Controller;

class TestController extends Controller
{

    public function actionIndex()
    {

        $activeUsers = User::find()->allUsersOfProject(\Yii::$app->user->id)
            ->onlyActive()
            ->select('username')
            ->indexBy('id')
            ->column();
        var_dump($activeUsers);exit;
    }
}