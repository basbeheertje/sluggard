<?php

use yii\db\Migration;

class m160406_201855_alter_google_user extends Migration
{
    public function up()
    {
        $this->addColumn('google_user', 'password', $this->text()->notNull());
    }

    public function down()
    {
        $this->dropColumn('google_user', 'password');
    }

}
