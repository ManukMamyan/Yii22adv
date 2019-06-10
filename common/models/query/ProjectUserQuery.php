<?php

namespace common\models\query;

use common\models\ProjectUser;

/**
 * This is the ActiveQuery class for [[\common\models\ProjectUser]].
 *
 * @see \common\models\ProjectUser
 */
class ProjectUserQuery extends \yii\db\ActiveQuery
{
    public function byUser($userId, $role = null)
    {
        $this->andWhere(['user_id' => $userId]);
        if ($role) {
            $this->andWhere(['role' => $role]);
        }
        return $this;
    }

    /**
     * Метод, который добовляет условие,
     * позволяющее получить все записи о проеке,
     * в котором у пользователя есть какая либо роль
     *
     * @param $userId integer
     * @return ProjectUserQuery
     */
    public function usersOfProject($userId)
    {
        $query = ProjectUser::find()->select('project_id')->byUser($userId);
        return $this->andWhere(['project_id' => $query]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProjectUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProjectUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
