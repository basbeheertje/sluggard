<?php

namespace frontend\modules\imdb\cron\tasks;

use console\modules\cron\components\core\CronTask;
use frontend\modules\imdb\components\MovieMeterHelper;

class MovieMeterAction extends CronTask {
    
    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        
        MovieMeterHelper::scrape('http://www.moviemeter.nl/list/topmisc/action');
        
    }
    
}