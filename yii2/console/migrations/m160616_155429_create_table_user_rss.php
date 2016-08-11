<?php

use yii\db\Migration;

class m160616_155429_create_table_user_rss extends Migration
{
    public function up()
    {
        $this->createTable('user_rss', [
            'id'            => $this->primaryKey(),
            'rss_id'        => $this->integer()->notNull(),
            'user_id'       => $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-user_rss-rss', 'user_rss', 'rss_id', 'rss', 'id', 'CASCADE');
        
        $this->addForeignKey('fk-user_rss-user', 'user_rss', 'user_id', 'user', 'id', 'CASCADE');
        
        $this->addForeignKey('fk-user_rss-creator', 'rss_user', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('user_rss');
    }
}
