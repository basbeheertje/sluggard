<?php

use yii\db\Migration;

class m160331_105624_create_google extends Migration
{
    public function up()
    {
        $this->createTable('google_user', [
            'id'            => $this->primaryKey(),
            'google_id'     => $this->text()->notNull(),
            'email'         => $this->text()->notNull(),
            'name'          => $this->text()->notNull(),
            'given_name'    => $this->text()->notNull(),
            'family_name'   => $this->text()->notNull(),
            'picture'       => $this->text()->notNull(),
            'locale'        => $this->text()->notNull(),
            'auth_code'     => $this->text()->notNull(),
            'access_token'  => $this->text()->notNull(),
            'contacts'      => $this->boolean()->notNull(),
            'calendar'      => $this->boolean()->notNull(),
            'drive'         => $this->boolean()->notNull(),
            'mail'          => $this->boolean()->notNull(),
            'plus'          => $this->boolean()->notNull(),
        ]);
        
        $this->createTable('user_google_link', [
            'id'                => $this->primaryKey(),
            'google_user_id'    => $this->integer()->notNull(),
            'user_id'           => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-user_google_link-google_user_id', 'user_google_link', 'google_user_id', 'google_user', 'id', 'CASCADE');
	$this->addForeignKey('fk-user_google_link-user_id', 'user_google_link', 'user_id', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->truncateTable('user_google_link');
        $this->truncateTable('google_user');
        $this->dropForeignKey('fk-user_google_link-google_user_id','user_google_link');
        $this->dropForeignKey('fk-user_google_link-user_id','user_google_link');
        $this->dropTable('user_google_link');
        $this->dropTable('google_user');
    }
}
