<?php

use yii\db\Migration;

class m160401_152119_create_user_devices extends Migration
{
    public function up()
    {
        $this->createTable('user_devices', [
            'id'            => $this->primaryKey(),
            'device_id'     => $this->integer()->notNull(),
            'user_id'       => $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-user_devices-creator', 'user_devices', 'creator', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-user_devices-device_id', 'user_devices', 'device_id', 'device', 'id', 'CASCADE');
        $this->addForeignKey('fk-user_devices-user_id', 'user_devices', 'user_id', 'user', 'id', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-user_devices-user_id','user_devices');
        $this->dropForeignKey('fk-user_devices-device_id','user_devices');
        $this->dropForeignKey('fk-user_devices-creator','user_devices');
        
        $this->truncateTable('user_devices');
        
        $this->dropTable('user_devices');
    }
}
