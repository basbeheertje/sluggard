<?php

use yii\db\Migration;

class m160401_101735_create_company_mail extends Migration
{
    public function up()
    {
        $this->createTable('company_mail', [
            'id'                => $this->primaryKey(),
            'company_id'        => $this->integer()->notNull(),
            'mailaddress_id'    => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-company_mail-mailaddress_id', 'company_mail', 'mailaddress_id', 'mailaddress', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_mail-company_id', 'company_mail', 'company_id', 'company', 'id', 'CASCADE');
        $this->addForeignKey('fk-company_mail-creator', 'company_mail', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-company_mail-creator','company_mail');
        $this->dropForeignKey('fk-company_mail-company_id','company_mail');
        $this->dropForeignKey('fk-company_mail-mailaddress_id','company_mail');
        
        $this->truncateTable('company_mail');
        
        $this->dropTable('company_mail');
    }
}
