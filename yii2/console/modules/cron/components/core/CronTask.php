<?php

namespace console\modules\cron\components\core;

use yii\base\Component;

abstract class CronTask extends Component {

    /** @var bool When true it will only execute when the cron-module is installed as cron-server. Default is false */
    public $cronServerOnly = false;

    /** @var bool When true it will only execute when the cron-module is NOT installed as cron-server. Default is false */
    public $normalServerOnly = false;

    protected $id;
    protected $status;
    protected $name;

    /**
     * Runs before execute(). Typically runs functions that determine if the task should be executed.
     * Default implementation checks on type of task & type of cron service.
     * @return bool The return value determines whether the task should be executed.
     */
    protected function beforeExecute() {
        if(CronCore::isCronServer() && $this->normalServerOnly){
            return false;
        }
        if(!CronCore::isCronServer() && $this->cronServerOnly){
            return false;
        }
        if($this->cronServerOnly && $this->normalServerOnly){
            \Yii::warning($this->getId() . ' will never be executed!', 'CRON');
        }
        return true;
    }

    /** @return bool Whether the function is executed successfully */
    abstract function execute();

    protected function afterExecute() {
    }

    /**
     * @return bool
     */
    public function start() {
        if ($this->beforeExecute()) {
            $result = $this->execute();
            $this->afterExecute();
            return $result;
        }
        return false;
    }

    public function run() {
        $this->start();
    }

    public function __construct($name = null, array $config = []) {
        $this->setName($name);
        $this->generateId();
        parent::__construct($config);
    }

    protected function generateId() {
        $this->id = get_called_class();
        if (isset($this->name)) {
            $this->id = $this->name;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getStatus() {
        return $this->status;
    }

}