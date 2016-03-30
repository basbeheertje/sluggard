<?php


namespace console\controllers;


use console\modules\cron\components\core\CronCore;
use console\modules\cron\CronService;
use yii\console\Controller;

class CronController extends Controller {

    public function init() {
        parent::init();
        CronService::registerTasks();
    }

    public function actionStatus() {
        $status = CronCore::isActive() ? 'active' : 'inactive';
        echo 'Currently the Cron-Service is ' . $status;
    }

    public function actionIndex() {
        echo "Available actions: minute, 5minute, hour, 12hour, day, week, month";
    }

    public function actionMinute() {
        CronCore::runMinuteCron();
    }

    public function action5minute() {
        CronCore::run5MinuteCron();
    }

    public function actionHour() {
        CronCore::runHourCron();
    }

    public function action12hour() {
        CronCore::run12HourCron();
    }

    public function actionDay() {
        CronCore::runDayCron();
    }

    public function actionWeek() {
        CronCore::runWeekCron();
    }

    public function actionMonth() {
        CronCore::runMonthCron();
    }
}