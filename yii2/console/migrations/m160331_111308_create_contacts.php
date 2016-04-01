<?php

use yii\db\Migration;

class m160331_111308_create_contacts extends Migration
{
    public function up()
    {
        $this->createTable('contacts', [
            'id'        => $this->primaryKey(),
            'main_name' => $this->text()->notNull(),
        ]);
        
        $this->createTable('user_contacts_link', [
            'id'            => $this->primaryKey(),
            'contacts_id'   => $this->integer()->notNull(),
            'user_id'       => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-user_contacts_link-contacts_id', 'user_contacts_link', 'contacts_id', 'contacts', 'id', 'CASCADE');
	$this->addForeignKey('fk-user_contacts_link-user_id', 'user_contacts_link', 'user_id', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->truncateTable('user_contacts_link');
        $this->truncateTable('contacts');
        $this->dropForeignKey('fk-user_contacts_link-contacts_id','user_contacts_link');
        $this->dropForeignKey('fk-user_contacts_link-user_id','user_contacts_link');
        $this->dropTable('user_contacts_link');
        $this->dropTable('contacts');
    }
}
