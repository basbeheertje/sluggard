To add new functions to the cronjob you have to create a new class that extends CronTask.

Example:

    class ExampleTask extends CronTask {
        function execute() {
            echo 'The code executed in this task!';
        }
    
        function beforeExecute(){
            //Run code before execution; not required
        }
    
        function afterExecute(){
            //Run code after execution; not required
        }
    }

To add the task to the cron job schedule add the task to the CronService in the registerTasks function.

    class CronService extends CronCore{
        public static function registerTasks(){
            self::registerMinuteTask(new ExampleTask());
        }
    }

These are the possible intervals of when tasks can be executed:
- registerMinuteTask
- register5MinuteTask
- registerHourTask
- register12HourTask
- registerDayTask
- registerWeekTask
- registerMonthTask