<?php

namespace common\services;

use common\models\Project;
use common\models\ProjectUser;
use common\models\Task;
use common\models\User;
use yii\base\Component;
use yii\base\Event;

class TakeTaskEvent extends Event
{
    /** @var Task */
    public $task;

    /** @var User */
    public $user;

}

class TaskService extends Component
{

    const EVENT_ASSIGN_TASK = 'event_assign_task';
    const EVENT_COMPLETE_TASK = 'event_complete_task';

    /**
     * Метод, запускающий событие взятия задачи в работу
     * @param \common\models\User $user
     * @param \common\models\Task $task
     */
    public function assignTaskEvent(Task $task, User $user)
    {
        $event = new TakeTaskEvent();
        $event->task = $task;
        $event->user = $user;
        $this->trigger(self::EVENT_ASSIGN_TASK, $event);
    }

    /**
     * Метод, запускающий событие окончания работы над задачей
     * @param \common\models\User $user
     * @param \common\models\Task $task
     */
    public function completeTaskEvent(Task $task, User $user)
    {
        $event = new TakeTaskEvent();
        $event->task = $task;
        $event->user = $user;
        $this->trigger(self::EVENT_COMPLETE_TASK, $event);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Project $project
     * @return boolean
     */
    public function canManage(Project $project, User $user)
    {
        return \Yii::$app->projectService->hasRole($project, $user, ProjectUser::ROLE_MANAGER);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Task $task
     * @return boolean
     */
    public function canTake(Task $task, User $user)
    {
        return \Yii::$app->projectService
                ->hasRole($task->project, $user, ProjectUser::ROLE_DEVELOPER) && (!$task->executor_id);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Task $task
     * @return boolean
     */
    public function canComplete(Task $task, User $user)
    {
        return ($task->executor_id === $user->id) && (!$task->completed_at);
    }

    /**
     * @param \common\models\User $user
     * @param \common\models\Task $task
     * @return boolean
     */
    public function takeTask(Task $task, User $user)
    {
        $task->executor_id = $user->id;
        $task->started_at = time();
        return $task->save();
    }

    /**
     * @param \common\models\Task $task
     * @return boolean
     */
    public function completeTask(Task $task)
    {
        $task->completed_at = time();
        return $task->save();
    }

}