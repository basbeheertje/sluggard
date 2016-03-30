<?php

namespace console\modules\cron\components\core;

use console\modules\cron\components\period\Cron12Hour;
use console\modules\cron\components\period\Cron5Minute;
use console\modules\cron\components\period\CronDay;
use console\modules\cron\components\period\CronHour;
use console\modules\cron\components\period\CronMinute;
use console\modules\cron\components\period\CronMonth;
use console\modules\cron\components\period\CronWeek;
use console\modules\cron\CronModule;
use Yii;
use yii\base\Component;

class CronCore extends Component {

    private static $instance;
    private $minuteCron;
    private $fiveMinuteCron;
    private $hourCron;
    private $twelveHourCron;
    private $dayCron;
    private $weekCron;
    private $monthCron;

    /**
     * @return CronCore
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new CronCore();
        }
        return self::$instance;
    }

    public static function registerMinuteTask(CronTask $task) {
        return self::getInstance()->getMinuteCron()->registerTask($task);
    }

    public static function register5MinuteTask(CronTask $task) {
        return self::getInstance()->get5MinuteCron()->registerTask($task);
    }

    public static function registerHourTask(CronTask $task) {
        return self::getInstance()->getHourCron()->registerTask($task);
    }

    public static function register12HourTask(CronTask $task) {
        return self::getInstance()->get12HourCron()->registerTask($task);
    }

    public static function registerDayTask(CronTask $task) {
        return self::getInstance()->getDayCron()->registerTask($task);
    }

    public static function registerWeekTask(CronTask $task) {
        return self::getInstance()->getWeekCron()->registerTask($task);
    }

    public static function registerMonthTask(CronTask $task) {
        return self::getInstance()->getMonthCron()->registerTask($task);
    }

    public static function runMinuteCron() {
        if (self::isActive()) {
            self::getInstance()->getMinuteCron()->run();
        }
    }

    public static function run5MinuteCron() {
        if (self::isActive()) {
            self::getInstance()->get5MinuteCron()->run();
        }else{
            echo "Staat uit";
        }
    }

    public static function runHourCron() {
        if (self::isActive()) {
            self::getInstance()->getHourCron()->run();
        }
    }

    public static function run12HourCron() {
        if (self::isActive()) {
            self::getInstance()->get12HourCron()->run();
        }
    }

    public static function runDayCron() {
        if (self::isActive()) {
            self::getInstance()->getDayCron()->run();
        }
    }

    public static function runWeekCron() {
        if (self::isActive()) {
            self::getInstance()->getWeekCron()->run();
        }
    }

    public static function runMonthCron() {
        if (self::isActive()) {
            self::getInstance()->getMonthCron()->run();
        }
    }

    /**
     * @return CronMinute
     */
    protected function getMinuteCron() {
        if (!isset($this->minuteCron)) {
            $this->minuteCron = new CronMinute();
        }
        return $this->minuteCron;
    }

    /**
     * @return CronMinute
     */
    protected function get5MinuteCron() {
        if (!isset($this->fiveMinuteCron)) {
            $this->fiveMinuteCron = new Cron5Minute();
        }
        return $this->fiveMinuteCron;
    }

    /**
     * @return CronHour
     */
    protected function getHourCron() {
        if (!isset($this->hourCron)) {
            $this->hourCron = new CronHour();
        }
        return $this->hourCron;
    }

    /**
     * @return CronHour
     */
    protected function get12HourCron() {
        if (!isset($this->twelveHourCron)) {
            $this->twelveHourCron = new Cron12Hour();
        }
        return $this->twelveHourCron;
    }

    /**
     * @return CronDay
     */
    protected function getDayCron() {
        if (!isset($this->dayCron)) {
            $this->dayCron = new CronDay();
        }
        return $this->dayCron;
    }

    /**
     * @return CronWeek
     */
    protected function getWeekCron() {
        if (!isset($this->weekCron)) {
            $this->weekCron = new CronWeek();
        }
        return $this->weekCron;
    }

    /**
     * @return CronMonth
     */
    protected function getMonthCron() {
        if (!isset($this->monthCron)) {
            $this->monthCron = new CronMonth();
        }
        return $this->monthCron;
    }

    /**
     * @return CronModule|null the module instance, null if the module does not exist.
     */
    private static function getCronModule() {
        return Yii::$app->getModule('CronService');
    }

    /**
     * @return bool Checks if the cron-module currently has an active state; default false
     */
    public static function isActive() {
        if (!self::getCronModule()) {
            return false;
        }
        return self::getCronModule()->active;
    }

    /**
     * @return bool Checks if the cron-module currently has is installed as a cron server; default false
     */
    public static function isCronServer() {
        if (!self::getCronModule()) {
            return false;
        }
        return self::getCronModule()->cronserver;
    }

}