<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180608_182401_add_comment_management
 */
class m180608_182401_add_comment_management extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$db = \Yii::$app->db;
		$query = new Query();
        if ($db->schema->getTableSchema("{{%auth_item}}", true) !== null)
		{
			if (!$query->from('{{%auth_item}}')->where(['name' => '/comment/admin/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/comment/admin/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'comment_management'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'comment_management',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'comment_manager'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'comment_manager',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'administrator'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'administrator',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_item_child}}", true) !== null)
		{
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'comment_management', 'child' => '/comment/admin/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'comment_management',
					'child'		=> '/comment/admin/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'comment_manager', 'child' => 'comment_management'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'comment_manager',
					'child'		=> 'comment_management'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'administrator', 'child' => 'comment_manager'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'administrator',
					'child'		=> 'comment_manager'
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_assignment}}", true) !== null)
		{
			if (!$query->from('{{%auth_assignment}}')->where(['item_name' => 'administrator', 'user_id' => 1])->exists())
				$this->insert('{{%auth_assignment}}', [
					'item_name'	=> 'administrator',
					'user_id'	=> 1,
					'created_at' => time()
				]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		// it's not safe to remove auth data in migration down
    }
}
