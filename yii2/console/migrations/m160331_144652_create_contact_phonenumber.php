<?php

use yii\db\Migration;

class m160331_144652_create_contact_phonenumber extends Migration
{
    public function up()
    {
        
        $this->createTable('phonetypes', [
            'id'            => $this->primaryKey(),
            'name'          => $this->text()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->insert('phonetypes', [
            'name'          => 'home',
            'updated_at'    => date('Y-m-d H:i:s'),
            'created_at'    => date('Y-m-d H:i:s'),
            'creator'       => 1,
        ]);
        
        $this->insert('phonetypes', [
            'name'          => 'mobile',
            'updated_at'    => date('Y-m-d H:i:s'),
            'created_at'    => date('Y-m-d H:i:s'),
            'creator'       => 1,
        ]);
        
        $this->addForeignKey('fk-phonetypes-creator', 'phonetypes', 'creator', 'user', 'id', 'CASCADE');
        
        $this->createTable('phonenumber', [
            'id'            => $this->primaryKey(),
            'number'        => $this->integer()->notNull(),
            'phonetypes_id' => $this->integer()->notNull()->defaultValue(1),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-phonenumber-phonetypes_id', 'phonenumber', 'phonetypes_id', 'phonetypes', 'id', 'CASCADE');
        $this->addForeignKey('fk-phonenumber-creator', 'phonenumber', 'creator', 'user', 'id', 'CASCADE');
        
        $this->createTable('contact_phonenumber', [
            'id'                => $this->primaryKey(),
            'contacts_id'       => $this->integer()->notNull(),
            'phonenumber_id'    => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-contact_phonenumber-phonenumber_id', 'contact_phonenumber', 'phonenumber_id', 'phonenumber', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_phonenumber-contacts_id', 'contact_phonenumber', 'contacts_id', 'contacts', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_phonenumber-creator', 'contact_phonenumber', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact_phonenumber-creator','contact_phonenumber');
        $this->dropForeignKey('fk-contact_phonenumber-contacts_id','contact_phonenumber');
        $this->dropForeignKey('fk-contact_phonenumber-phonenumber_id','contact_phonenumber');
        
        $this->truncateTable('contact_phonenumber');
        $this->dropTable('contact_phonenumber');
        
        $this->dropForeignKey('fk-phonenumber-creator','phonenumber');
        $this->dropForeignKey('fk-phonenumber-phonetypes_id','phonenumber');
        $this->truncateTable('phonenumber');
        $this->dropTable('phonenumber');
        
        $this->dropForeignKey('fk-phonetypes-creator','phonetypes');
        $this->truncateTable('phonetypes');
        $this->dropTable('phonetypes');
    }
}
