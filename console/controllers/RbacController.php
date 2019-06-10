<?php

namespace console\controllers;

use common\services\AuthItems;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем роль "tester".
        $tester = $auth->createRole(AuthItems::ROLE_TESTER);
        $auth->add($tester);

        // добавляем роль "developer".
        $developer = $auth->createRole(AuthItems::ROLE_DEVELOPER);
        $auth->add($developer);
        $auth->addChild($developer, $tester);

        // добавляем роль "admin" и даём разрешения роли "user"
        $manager = $auth->createRole(AuthItems::ROLE_MANAGER);
        $auth->add($manager);
        $auth->addChild($manager, $developer, $tester);


        // Назначение ролей пользователям
        $auth->assign($manager, 1);
        $auth->assign($developer, 2);
        $auth->assign($tester, 3);
    }
}