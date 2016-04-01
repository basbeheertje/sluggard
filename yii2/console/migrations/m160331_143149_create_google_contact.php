<?php

use yii\db\Migration;

class m160331_143149_create_google_contact extends Migration
{
    public function up()
    {
        
        $this->createTable('google_contact', [
            'id'                => $this->primaryKey(),
            'google_user_id'    => $this->integer()->notNull(),
            'contacts_id'       => $this->integer()->notNull(),
            'etag'              => $this->text()->notNull(),
            'updated'           => $this->text()->notNull(),
            'create_at'         => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-google_contact-google_user_id', 'google_contact', 'google_user_id', 'google_user', 'id', 'CASCADE');
	$this->addForeignKey('fk-google_contact-contacts_id', 'google_contact', 'contacts_id', 'contacts', 'id', 'CASCADE');
        $this->addForeignKey('fk-google_contact-creator', 'google_contact', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-google_contact-creator','google_contact');
        $this->dropForeignKey('fk-google_contact-contacts_id','google_contact');
        $this->dropForeignKey('fk-google_contact-google_user_id','google_contact');
        
        $this->truncateTable('google_contact');
        
        $this->dropTable('google_contact');
    }
}
