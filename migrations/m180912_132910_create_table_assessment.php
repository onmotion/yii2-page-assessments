<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-vuejs-assets
 */

use yii\db\Migration;

class m180912_132910_create_table_assessment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%assessment}}', [
            'assessment_id' => $this->primaryKey()->unsigned(),
            'assessment_object_id' => $this->integer()->unsigned(),
            'assessment_object_class' => $this->string(),
            'assessment_value' => $this->tinyInteger(),
            'assessment_comment' => $this->string(),
            'assessment_user_id' => $this->integer(),
            'assessment_created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'assessment_updated_at' => $this->timestamp(),
            'assessment_user_ip' => $this->string(15),
            'assessment_url' => $this->string(),
            'assessment_question' => $this->string(),
            'assessment_is_declined' => $this->boolean()->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('idx_search2', '{{%assessment}}', ['assessment_question', 'assessment_url', 'assessment_user_ip']);
        $this->createIndex('idx_url', '{{%assessment}}', 'assessment_url');
        $this->createIndex('idx_search', '{{%assessment}}', ['assessment_question', 'assessment_url', 'assessment_user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%assessment}}');
    }
}
