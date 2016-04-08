<?php

use yii\db\Migration;

class m160407_094818_create_google_user_locations extends Migration
{
    public function up()
    {
        $this->createTable('google_user_location', [
            'id'            => $this->primaryKey(),
            'date'          => $this->date()->notNull(),
            'time'          => $this->time()->notNull(),
            'longitude'     => $this->text()->notNull(),
            'latitude'      => $this->text()->notNull(),
            'height'        => $this->text()->notNull(),
            'google_user_id'=> $this->integer()->notNull(),
            'updated_at'    => $this->timestamp()->notNull(),
            'created_at'    => $this->timestamp()->notNull(),
            'creator'       => $this->integer()->notNull()
        ]);
        
        $this->addForeignKey('fk-google_user_location-google_user_id', 'google_user_location', 'google_user_id', 'google_user', 'id', 'CASCADE');
	$this->addForeignKey('fk-google_user_location-creator', 'google_user_location', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->truncateTable('google_user_location');
        $this->dropForeignKey('fk-google_user_location-creator','google_user_locations');
        $this->dropForeignKey('fk-google_user_location-google_user_id','google_user_locations');
        $this->dropTable('google_user_location');
    }
}
