<?php

    namespace api\modules\whattsapp;

    class Module extends \yii\base\Module
    {
        
        public function init()
		{
            parent::init();
            \Yii::configure($this, require(__DIR__ . '/config.php'));
            
        }
    }

?>