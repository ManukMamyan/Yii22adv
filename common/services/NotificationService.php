<?php

namespace common\services;

use yii\base\Component;

class NotificationService extends Component
{
    /**
     * @param \common\models\User $user
     * @param \common\models\Project $project
     * @param string $role
     */
    public function notifyAboutNewProjectRole($user, $project, $role)
    {
        $views = ['html' => 'assignRole-html', 'text' => 'assignRole-text'];
        $subject = 'Role change';
        $data = ['user' => $user, 'project' => $project, 'role' => $role];
        \Yii::$app->emailService->send($user->email, $subject, $views, $data);
    }

}