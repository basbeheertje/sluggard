<?php

use yii\db\Migration;

class m160407_144055_alter_GoogleContact_Contacts_id extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk-google_contact-contacts_id','google_contact');
        $this->renameColumn('google_contact','contacts_id','contact_id');
        $this->addForeignKey('fk-google_contact-contacts_id', 'google_contact', 'contact_id', 'contact', 'id', 'CASCADE');
    }

    public function down()
    {
        echo "m160407_144055_alter_GoogleContact_Contacts_id cannot be reverted.\n";

        return false;
    }
}
