<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190112_164547_add_versioning
 */
class m190112_164547_add_versioning extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $db = \Yii::$app->db;
        $query = new Query();
        if ($db->schema->getTableSchema("{{%eg_comment}}", true) !== null)
        {
			       $this->addColumn('{{%eg_comment}}', 'item_version', $this->Integer() . " DEFAULT 0 AFTER item_id");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%eg_comment}}', 'item_version');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190112_164547_add_versioning cannot be reverted.\n";

        return false;
    }
    */
}
