<?php

    namespace console\modules\cron;

    use console\modules\cron\components\core\CronCore;
    use console\modules\cron\tasks\GoogleContactsTask;
    use console\modules\cron\tasks\GoogleLocationTask;
    use console\modules\cron\tasks\GoogleDriveTask;
    use frontend\modules\imdb\cron\tasks\IMDBtop250;
    use frontend\modules\imdb\cron\tasks\MovieMeterAction;
    use frontend\modules\imdb\cron\tasks\MovieMeterAdventure;
    use frontend\modules\imdb\cron\tasks\MovieMeterAlternative;
    use frontend\modules\imdb\cron\tasks\MovieMeterAnimation;
    use frontend\modules\imdb\cron\tasks\MovieMeterCinema;
    use frontend\modules\imdb\cron\tasks\MovieMeterComedy;
    use frontend\modules\imdb\cron\tasks\MovieMeterCrime;
    use frontend\modules\imdb\cron\tasks\MovieMeterDocumentary;
    use frontend\modules\imdb\cron\tasks\MovieMeterRecent;
    use frontend\modules\imdb\cron\tasks\MovieMeterWar;

    class CronService extends CronCore {

        public static function registerTasks(){
            
            self::registerMinuteTask(new GoogleLocationTask);
            
            self::register5MinuteTask(new GoogleContactsTask);
            
            self::registerHourTask(new IMDBtop250);
            
            self::registerHourTask(new MovieMeterAction);
            
            self::registerHourTask(new MovieMeterAdventure);
            
            self::registerHourTask(new MovieMeterAnimation);
            
            self::registerHourTask(new MovieMeterCinema);
            
            self::registerHourTask(new MovieMeterComedy);
            
            self::registerHourTask(new MovieMeterCrime);
            
            self::registerHourTask(new MovieMeterDocumentary);
            
            self::registerHourTask(new MovieMeterRecent);
            
            self::registerHourTask(new MovieMeterWar);
            
            self::registerDayTask(new GoogleDriveTask);
            
	}

    }