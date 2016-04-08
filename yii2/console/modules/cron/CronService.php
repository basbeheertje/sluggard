<?php

    namespace console\modules\cron;

    use console\modules\cron\components\core\CronCore;
    use console\modules\cron\tasks\GoogleContactsTask;
    use console\modules\cron\tasks\GoogleLocationTask;

    class CronService extends CronCore {

        public static function registerTasks(){
            
            self::registerMinuteTask(new GoogleLocationTask);
            
            self::register5MinuteTask(new GoogleContactsTask);
            
	}

    }