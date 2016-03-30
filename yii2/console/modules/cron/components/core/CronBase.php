<?php

namespace console\modules\cron\components\core;

use Yii;
use yii\base\Component;
use yii\console\Exception;

class CronBase extends Component {

    /** @var CronTask[] $tasks */
    protected $tasks = [];

    public function registerTask(CronTask $task) {
        if (isset($this->tasks[ $task->getId() ])) {
            return false;
        }
        $this->tasks[ $task->getId() ] = $task;
        return true;
    }

    public function run() {
        $this->beforeRun();
        $this->runTasks();
        $this->afterRun();
    }

    protected function beforeRun() {
    }

    protected function runTasks() {
        foreach ($this->tasks as $task) {
            try {
                $task->start();
            } catch (Exception $e) {
                Yii::error("Crontask `{$task->getId()}` failed", 'crontask');
                Yii::error($e->getMessage(), 'crontask');
            }
        }
    }

    protected function afterRun() {
    }

}