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

        $this->insert('{{%auth_item}}', [
            'name' => '/comment/admin/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'comment_management',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'comment_management',
            'child' => '/comment/admin/*',
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'comment_manager',
            'type' => 1,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'comment_manager',
            'child' => 'comment_management',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'comment_manager',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'comment_manager',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'comment_manager',
            'child' => 'comment_management',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'comment_manager',
            'type' => 1,
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'comment_management',
            'child' => '/comment/admin/*',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'comment_management',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/comment/admin/*',
            'type' => 2,
        ]);
        $this->dropTable('{{%eg_comment}}');
    }
}
