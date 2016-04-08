<?php

    namespace frontend\modules\google;

    class Module extends \yii\base\Module
    {
        
        public function init()
		{
            parent::init();
            \Yii::configure($this, require(__DIR__ . '/config.php'));
            
        }
    }

?>