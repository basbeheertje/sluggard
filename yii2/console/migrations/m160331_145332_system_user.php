<?php

use yii\db\Migration;

class m160331_145332_system_user extends Migration
{
    public function up()
    {
        $this->delete('user', ['id' => 1]);
        $this->insert('user', [
            'id'                    => '1',
            'username'              => 'system',
            'auth_key'              => 'empty',
            'password_hash'         => 'empty',
            'password_reset_token'  => 'empty',
            'email'                 => 'system@example.com',
            'status'                => 0,
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        echo "m160331_145332_system_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
