<?php

namespace frontend\modules\api\models;

/**
 * Class Task
 * @package frontend\modules\api\models
 * @property Project $project
 */
class Task extends \common\models\Task
{
    const RELATION_TASKS_PROJECT_ID = 'project';

    public function fields()
    {
        return [
            'id',
            'title',
            'description_short' => function () {
                return substr($this->description, 0, 50);
            },
        ];
    }

    public function extraFields()
    {
        return [self::RELATION_TASKS_PROJECT_ID];
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

}