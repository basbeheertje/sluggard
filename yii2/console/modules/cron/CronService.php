<?php

	namespace console\modules\cron;

	use console\modules\cron\components\core\CronCore;
	use console\modules\cron\tasks\GoogleContactsTask;

	class CronService extends CronCore {

		public static function registerTasks(){
			self::registerMinuteTask(new GoogleContactsTask);
		}

	}