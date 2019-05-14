<?php

use yii\db\Migration;

/**
 * Class m190514_085441_add_access_token_and_avatar_columns_to_user_tabl
 */
class m190514_085441_add_access_token_and_avatar_columns_to_user_tabl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'access_token', $this
            ->string()
            ->after('password_reset_token')
            ->defaultValue(null));
        $this->addColumn('{{%user}}', 'avatar', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'access_token');
        $this->dropColumn('{{%user}}', 'avatar');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190514_085441_add_access_token_and_avatar_columns_to_user_tabl cannot be reverted.\n";

        return false;
    }
    */
}
