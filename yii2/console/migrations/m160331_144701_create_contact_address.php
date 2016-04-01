<?php

use yii\db\Migration;

class m160331_144701_create_contact_address extends Migration
{
    public function up()
    {
        
        $this->createTable('address', [
            'id'                => $this->primaryKey(),
            'street'            => $this->text()->notNull(),
            'number'            => $this->text()->notNull(),
            'zipcode'           => $this->text()->notNull(),
            'place'             => $this->text()->notNull(),
            'province'          => $this->text()->notNull(),
            'country'           => $this->text()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->createTable('contact_address', [
            'id'                => $this->primaryKey(),
            'contacts_id'       => $this->integer()->notNull(),
            'address_id'        => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-contact_address-address_id', 'contact_address', 'address_id', 'address', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_address-contacts_id', 'contact_address', 'contacts_id', 'contacts', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_address-creator', 'contact_address', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact_address-creator','contact_address');
        $this->dropForeignKey('fk-contact_address-contacts_id','contact_address');
        $this->dropForeignKey('fk-contact_address-address_id','contact_address');
        $this->truncateTable('contact_address');
        $this->truncateTable('address');
        $this->dropTable('contact_address');
        $this->dropTable('address');
    }
}
