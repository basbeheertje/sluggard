<?php

use yii\db\Migration;

class m160401_133547_defaultuser extends Migration
{
    public function up()
    {
        /** creating default user with password example and username user */
        $this->insert('user', [
            'username'              => 'user',
            'auth_key'              => '0h9ZewAPRe5HNCVwDs1NwMkuHXw8WtER',
            'password_hash'         => '$2y$13$703g1DyaFFgmLY.Y1nyAF.TDsz9EiGFvDaHex/B4v5jbrbLzK0fBW',
            'password_reset_token'  => NULL,
            'email'                 => 'user@example.com',
            'status'                => 10,
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->delete('user',['username' => 'user']);
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
