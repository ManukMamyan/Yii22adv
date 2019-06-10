<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.comments' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/comments/messages',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@console/rbac/items.php',
            'assignmentFile' => '@console/rbac/assignments.php',
        ],
        'emailService' => [
            'class' => common\services\EmailService::class
        ],
        'notificationService' => [
            'class' => common\services\NotificationService::class
        ],
        'taskService' => [
            'class' => common\services\TaskService::class,
            'on ' . common\services\TaskService::EVENT_ASSIGN_TASK =>
            function(\common\services\TakeTaskEvent $event)
            {
                Yii::$app->notificationService
                    ->notifyAboutTakingTask($event->user, $event->task);
            },
            'on ' . common\services\TaskService::EVENT_COMPLETE_TASK =>
                function(\common\services\TakeTaskEvent $event)
                {
                    Yii::$app->notificationService
                        ->notifyAboutCompletingTask($event->user, $event->task);
                },
        ],
        'projectService' => [
            'class' => common\services\ProjectService::class,
            'on ' . \common\services\ProjectService::EVENT_ASSIGN_ROLE =>
                function (\common\services\AssignRoleEvent $event) {
                    Yii::$app->notificationService->notifyAboutNewProjectRole($event->user, $event->project, $event->role);
                },
        ],
    ],
    'modules' => [
        'chat' => [
            'class' => 'common\modules\chat\Module',
        ],
        'comment' => [
            'class' => 'yii2mod\comments\Module',
        ],
    ],
];
