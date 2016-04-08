<?php

use yii\db\Migration;

class m160331_144718_create_contact_company extends Migration
{
    public function up()
    {
        
        $this->createTable('company', [
            'id'            => $this->primaryKey(),
            'name'          => $this->text()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->createTable('company_address', [
            'id'            => $this->primaryKey(),
            'company_id'    => $this->integer()->notNull(),
            'address_id'    => $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-company_address-company_id', 'company_address', 'company_id', 'company', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_address-address_id', 'company_address', 'address_id', 'address', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_address-creator', 'company_address', 'creator', 'user', 'id', 'CASCADE');
        
        $this->createTable('contact_company', [
            'id'            => $this->primaryKey(),
            'contact_id'    => $this->integer()->notNull(),
            'company_id'    => $this->integer()->notNull(),
            'title'         => $this->text()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-contact_company-company_id', 'contact_company', 'company_id', 'company', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_company-contact_id', 'contact_company', 'contact_id', 'contacts', 'id', 'CASCADE');
        $this->addForeignKey('fk-contact_company-creator', 'contact_company', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact_company-creator','contact_company');
        $this->dropForeignKey('fk-contact_company-contacts_id','contact_company');
        $this->dropForeignKey('fk-contact_company-company_id','contact_company');
        $this->truncateTable('contact_company');
        $this->dropTable('contact_company');
        
        $this->dropForeignKey('fk-company_address-company_id','company_address');
        $this->dropForeignKey('fk-company_address-address_id','company_address');
        
        $this->truncateTable('company_address');
        $this->truncateTable('company');
        
        $this->dropTable('company_address');
        $this->dropTable('company');
        
    }
}
