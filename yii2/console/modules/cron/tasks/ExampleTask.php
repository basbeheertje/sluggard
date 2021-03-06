<?php

namespace console\modules\cron\tasks;

use console\modules\cron\components\core\CronTask;

class ExampleTask extends CronTask {

    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        echo 'The Example task is executed!';
    }
}