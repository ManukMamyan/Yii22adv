<?php

namespace common\services;

use yii\base\Component;

class NotificationService extends Component
{
    protected $emailService;

    public function __construct(EmailServiceInterface $emailService, array $config = [])
    {
        parent::__construct($config);
        $this->emailService = $emailService;
    }

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
        $this->emailService->send($user->email, $subject, $views, $data);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Task $task
     */
    public function notifyAboutTakingTask($user, $task)
    {
        $views = ['html' => 'assignTask-html', 'text' => 'assignTask-text'];
        $subject = 'Task was taken';
        $data = ['user' => $user, 'task' => $task];
        $this->emailService->send($user->email, $subject, $views, $data);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Task $task
     */
    public function notifyAboutCompletingTask($user, $task)
    {
        $views = ['html' => 'completeTask-html', 'text' => 'completeTask-text'];
        $subject = 'Task was completed';
        $data = ['user' => $user, 'task' => $task];
        $this->emailService->send($user->email, $subject, $views, $data);
    }


}