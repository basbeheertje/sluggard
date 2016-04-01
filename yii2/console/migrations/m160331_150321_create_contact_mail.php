<?php

use yii\db\Migration;

class m160331_150321_create_contact_mail extends Migration
{
    public function up()
    {
        
        $this->createTable('mailaddress', [
            'id'            => $this->primaryKey(),
            'name'          => $this->integer()->notNull(),
            'address'       => $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->createTable('contact_mail', [
            'id'                => $this->primaryKey(),
            'contacts_id'       => $this->integer()->notNull(),
            'mailaddress_id'    => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-contact_mail-mailaddress_id', 'contact_mail', 'mailaddress_id', 'mailaddress', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_mail-contacts_id', 'contact_mail', 'contacts_id', 'contacts', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_mail-creator', 'contact_mail', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact_mail-creator','contact_mail');
        $this->dropForeignKey('fk-contact_mail-contacts_id','contact_mail');
        $this->dropForeignKey('fk-contact_mail-mailaddress_id','contact_mail');
        
        $this->truncateTable('contact_mail');
        $this->truncateTable('mailaddress');
        
        $this->dropTable('contact_mail');
        $this->dropTable('mailaddress');
    }
}
