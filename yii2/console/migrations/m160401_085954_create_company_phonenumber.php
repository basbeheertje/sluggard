<?php

use yii\db\Migration;

class m160401_085954_create_company_phonenumber extends Migration
{
    public function up()
    {
        $this->createTable('company_phonenumber', [
            'id'                => $this->primaryKey(),
            'company_id'        => $this->integer()->notNull(),
            'phonenumber_id'    => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-company_phonenumber-company_id', 'company_phonenumber', 'company_id', 'company', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_phonenumber-phonenumber_id', 'company_phonenumber', 'phonenumber_id', 'phonenumber', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_phonenumber-creator', 'company_phonenumber', 'creator', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact_company-creator');
        $this->dropForeignKey('fk-company_phonenumber-phonenumber_id');
        $this->dropForeignKey('fk-company_phonenumber-company_id');
        
        $this->truncateTable('company_phonenumber');
        
        $this->dropTable('company_phonenumber');
    }
}
