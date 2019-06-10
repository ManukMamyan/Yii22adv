<?php

namespace common\models\query;

use common\models\ProjectUser;
use common\models\User;

/**
 * This is the ActiveQuery class for [[\common\models\User]].
 *
 * @see \common\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * Добавляет условие выборки только активных пользователей
     * @return $this
     */
    public function onlyActive()
    {
        $this->andWhere(['status' => User::STATUS_ACTIVE]);

        return $this;
    }

    /**
     * Метод, создающий запрос,
     * который позволяет получить всех пользователей,
     * у которых есть роли в проектах. При этом, это только проекты,
     * в которых учавствует пользователь, id которого мы передаем в метод.
     *
     * @param $userId integer
     * @return UserQuery
     */
    public function allUsersOfProject($userId)
    {
        $query = ProjectUser::find()->usersOfProject($userId)->select('user_id');
        return $this->andWhere(['id' => $query]);
    }

}