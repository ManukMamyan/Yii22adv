<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем роль "user".
        $user = $auth->createRole('user');
        $auth->add($user);

        // добавляем роль "admin" и даём разрешения роли "user"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user);


        // Назначение ролей пользователям
        $auth->assign($admin, 1);
        $auth->assign($user, 2);
        $auth->assign($user, 3);
    }
}