<?php

use yii\db\Migration;

class m160407_074155_alter_GoogleUser_Creator_CreatedAt_UpdatedAt extends Migration
{
    public function up()
    {
        $this->addColumn('google_user', 'updated_at', $this->timestamp()->notNull());
        $this->addColumn('google_user', 'created_at', $this->timestamp()->notNull());
        $this->addColumn('google_user', 'creator', $this->integer()->notNull());
        $this->addForeignKey('fk-google_user-creator', 'google_user', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-google_user-creator','google_user');
        $this->dropColumn('google_user', 'creator');
        $this->dropColumn('google_user', 'created_at');
        $this->dropColumn('google_user', 'updated_at');
    }

}
