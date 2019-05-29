<?php

namespace frontend\modules\api\models;

use yii\helpers\StringHelper;

/**
 * Class Project
 * @package frontend\modules\api\models
 *
 * @property Task[] $projectTasks
 */
class Project extends \common\models\Project
{
    const RELATION_TASK_PROJECT = 'projectTasks';

    public function fields()
    {
        return [
            'id',
            'title',
            'description_short' => function () {
                return StringHelper::truncate($this->description, 50, '...');
            },
            'active',
        ];
    }

    public function extraFields()
    {
        return [self::RELATION_TASKS_PROJECT_ID];
    }

    public function getProjectTasks ()
    {
        return $this->hasMany(Task::class, ['project_id' => 'id']);
    }
}