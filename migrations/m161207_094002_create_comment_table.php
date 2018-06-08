<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m161207_094002_create_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%eg_comment}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(32),
            'item_id' => $this->integer(11),
            'service_id' => $this->integer(11),
            'comment_id' => $this->integer(11)->defaultValue(0),
            'level' => $this->integer(11)->defaultValue(0),
            'user_id' => $this->integer(11),
            'name' => $this->string(64)->notNull(),
            'email' => $this->string(128)->notNull(),
            'subject' => $this->string(128),
            'description' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'update_time' => $this->timestamp()->notNull(),
            'creation_time' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%eg_comment}}');
    }
}
