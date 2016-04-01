<?php

use yii\db\Migration;

class m160331_150446_alter_contacts extends Migration
{
    public function up()
    {
        //Drop ForeignKeys for all related tables
        $this->dropForeignKey('fk-contact_mail-contacts_id','contact_mail');
        $this->dropForeignKey('fk-contact_company-contact_id','contact_company');
        $this->dropForeignKey('fk-contact_address-contacts_id','contact_address');
        $this->dropForeignKey('fk-contact_phonenumber-contacts_id','contact_phonenumber');
        
        //Rename Table contacts to contact
        $this->renameTable('contacts','contact');
        
        //Rename all related tables columns
        $this->renameColumn('contact_mail','contacts_id','contact_id');
        $this->renameColumn('contact_address','contacts_id','contact_id');
        $this->renameColumn('contact_phonenumber','contacts_id','contact_id');
        
        //Add new foreignkeys
        $this->addForeignKey('fk-contact_mail-contact_id', 'contact_mail', 'contact_id', 'contact', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_company-contact_id', 'contact_company', 'contact_id', 'contact', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_address-contact_id', 'contact_address', 'contact_id', 'contact', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_phonenumber-contact_id', 'contact_phonenumber', 'contact_id', 'contact', 'id', 'CASCADE');
    }

    public function down()
    {
        echo "m160331_150446_alter_contacts cannot be reverted.\n";

        return false;
    }
}
