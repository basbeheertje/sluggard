<?php

use yii\db\Migration;

class m160330_134046_user_settings extends Migration
{
    public function up()
    {
		
	$this->createTable('setting_types', [
            'id'        => $this->primaryKey(),
            'name'      => $this->text() . ' NOT NULL',
            'comment'   => $this->text() . ' NOT NULL',
        ]);
		
	$this->createTable('user_settings', [
            'id'                => $this->primaryKey(),
            'user_id'           => $this->integer() . ' NOT NULL',
            'setting_types_id'  => $this->integer() . ' NOT NULL',
            'value'             => $this->text() . ' NOT NULL',
        ]);
		
	$this->addForeignKey('fk-user_settings-user_id', 'user_settings', 'user_id', 'user', 'id', 'CASCADE');
	$this->addForeignKey('fk-user_settings-setting_types', 'user_settings', 'setting_types_id', 'setting_types', 'id', 'CASCADE');
		
    }

    public function down()
    {
        $this->truncateTable('user_settings');
        $this->truncateTable('setting_types');
        $this->dropForeignKey('fk-user_settings-user_id');
        $this->dropForeignKey('fk-user_settings-setting_types');
        $this->dropTable('setting_types');
	$this->dropTable('user_settings');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
