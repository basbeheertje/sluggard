<?php

use yii\db\Migration;

class m160401_151519_create_device extends Migration
{
    public function up()
    {
        $this->createTable('device', [
            'id'                => $this->primaryKey(),
            'name'              => $this->text()->notNull(),
            'device_type'       => $this->integer()->notNull()->defaultValue(1),
            'imei'              => $this->text(),
            'mac'               => $this->text(),
            'ip'                => $this->text(),
            'number'            => $this->text(),
            'brand'             => $this->text(),
            'version'           => $this->text(),
            'updated_at'        => $this->timestamp()->notNull(),
            'created_at'        => $this->timestamp()->notNull(),
            'creator'           => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-device-creator', 'device', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-device-creator','device');
        
        $this->truncateTable('device');
        
        $this->dropTable('device');
    }
}
