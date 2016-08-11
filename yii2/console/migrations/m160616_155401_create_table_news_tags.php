<?php

use yii\db\Migration;

class m160616_155401_create_table_news_tags extends Migration
{
    public function up()
    {
        $this->createTable('news_tags', [
            'id'            => $this->primaryKey(),
            'tag'           => $this->text()->notNull(),
            'news_id'          => $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-news_tags-news_id', 'news_tags', 'news_id', 'news', 'id', 'CASCADE');
        
        $this->addForeignKey('fk-news_tags-creator', 'news_tags', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('news_tags');
    }
}
