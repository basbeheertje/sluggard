<?php

use yii\db\Migration;

class m160616_155423_create_table_rss extends Migration
{
    public function up()
    {
        $this->createTable('rss', [
            'id' => $this->primaryKey(),
            'url'=> $this->text()->notNull(),
            'name' => $this->text()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-rss-creator', 'rss', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('rss');
    }
}
