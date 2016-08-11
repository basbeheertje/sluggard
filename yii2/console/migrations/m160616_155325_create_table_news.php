<?php

use yii\db\Migration;

class m160616_155325_create_table_news extends Migration
{
    public function up()
    {
        $this->createTable('news', [
            'id'            => $this->primaryKey(),
            'title'         => $this->text()->notNull(),
            'short'         => $this->text()->notNull(),
            'image'         => $this->text()->notNull(),
            'author'        => $this->text()->notNull(),
            'rss_id'        => $this->integer(),
            'link'          => $this->text(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-news-creator', 'news', 'creator', 'user', 'id', 'CASCADE');
        
        $this->addForeignKey('fk-news-rss', 'news', 'rss_id', 'rss', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropTable('news');
    }
}
