<?php


namespace console\modules\cron;


use yii\base\Module;

class CronModule extends Module {

    /** @var bool If false no cron tasks will be run */
    public $active = true;

    /** @var bool states whether the server is a cron server */
    public $cronserver = false;

    public function init(){
        parent::init();
        $this->setComponents([
            'CronService' => [
                'class' => 'console\modules\cron\components\CronService',
            ],
        ]);
    }

}