<?php

use yii\db\Migration;

/**
 * Class m210322_093203_create_base_structure
 */
class m210322_093203_create_base_structure extends Migration
{
    public function up()
    {
 
        $this->createTable('{{%currencies}}', [
            'id' => $this->char(36)->notNull(),
            'code_number' => $this->integer()->notNull()->unique(),
            'code_symbol' => $this->string(3)->notNull(),
            'title' => $this->string(),
            'rates' => $this->json()
        ]);
        $this->createIndex('idx-currencies-id', '{{%currencies}}', 'id');
    }
 
    public function down()
    {
        $this->dropTable('{{%currencies}}');
    }
}
