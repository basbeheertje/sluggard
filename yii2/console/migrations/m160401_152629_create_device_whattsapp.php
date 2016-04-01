<?php

use yii\db\Migration;

class m160401_152629_create_device_whattsapp extends Migration
{
    public function up()
    {
        $this->createTable('device_whattsapp', [
            'id'            => $this->primaryKey(),
            'device_id'     => $this->integer()->notNull(),
            'username'      => $this->text()->notNull(),
            'nickname'      => $this->text()->notNull(),
            'coderequest'   => $this->text()->notNull(),
            'code'          => $this->text()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-device_whattsapp-creator', 'device_whattsapp', 'creator', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-device_whattsapp-device_id', 'device_whattsapp', 'device_id', 'device', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-device_whattsapp-device_id','device_whattsapp');
        $this->dropForeignKey('fk-device_whattsapp-creator','device_whattsapp');
        
        $this->truncateTable('device_whattsapp');
        
        $this->dropTable('device_whattsapp');
    }
}
