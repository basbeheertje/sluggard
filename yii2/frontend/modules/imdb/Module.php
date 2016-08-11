<?php

    namespace frontend\modules\imdb;

    class Module extends \yii\base\Module
    {
        
        public function init()
		{
            parent::init();
            \Yii::configure($this, require(__DIR__ . '/config.php'));
            
        }
    }

?>