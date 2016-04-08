<?php

use yii\db\Migration;

class m160408_081528_alter_Contact_Birthday extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'birthday', $this->date());
    }

    public function down()
    {
        $this->dropColumn('contact', 'birthday');
    }

}
