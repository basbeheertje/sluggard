<?php

use yii\db\Migration;

class m160401_094814_alter_contact extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'updated_at', $this->timestamp()->notNull());
        $this->addColumn('contact', 'created_at', $this->timestamp()->notNull());
        $this->addColumn('contact', 'creator', $this->integer()->notNull());
        $this->addForeignKey('fk-contact-creator', 'contact', 'creator', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-contact-creator','contact');
        $this->dropColumn('contact', 'creator');
        $this->dropColumn('contact', 'created_at');
        $this->dropColumn('contact', 'updated_at');
    }

}
