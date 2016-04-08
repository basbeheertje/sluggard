<?php

use yii\db\Migration;

class m160407_092937_alter_GoogleUser_LocationTracking extends Migration
{
    public function up()
    {
        $this->addColumn('google_user', 'location', $this->boolean()->notNull());
    }

    public function down()
    {
        $this->dropColumn('google_user', 'location');
    }
}
