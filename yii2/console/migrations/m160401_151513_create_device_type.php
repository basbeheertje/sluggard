<?php

use yii\db\Migration;

class m160401_151513_create_device_type extends Migration
{
    public function up()
    {
        $this->createTable('device_type', [
            'id'                => $this->primaryKey(),
            'name'              => $this->integer()->notNull(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-device_type-creator', 'device_type', 'creator', 'user', 'id', 'CASCADE');
    
        $this->insert('device_type', [
            'id'                => '1',
            'name'              => 'cellphone',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
            'creator'           => '1',
        ]);
        
    }

    public function down()
    {
        $this->dropForeignKey('fk-device_type-creator','device_type');
        
        $this->delete('device_type', ['id' => 1]);
        
        $this->truncateTable('device_type');
        
        $this->dropTable('device_type');
    }
}
