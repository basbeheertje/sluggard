<?php

namespace console\modules\cron\tasks;

use console\modules\cron\components\core\CronTask;
use common\components\GoogleAPIHelper;
use common\components\GoogleDriveHelper;
use common\models\GoogleUser;

class GoogleDriveTask extends CronTask {

    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        
        if(GoogleAPIHelper::isEnabled() && GoogleDriveHelper::isEnabled()){
            /** @var GoogleUser $GoogleUsers */
            $GoogleUsers = $this->getAllGoogleUsers();

            /** @var GoogleUser $GoogleUser */
            foreach($GoogleUsers as $GoogleUser){
                
                /** @var array $users */
                $users = $GoogleUser->users();
                
                if($GoogleUser->drive === 1){
                    
                    echo "Google drive is enabled for :".$GoogleUser->email."\r\n";
                    
                }
                
            }
        }
		
    }
    
    protected static function getAllGoogleUsers(){
        
        /** @var GoogleUser $GoogleUsers */
        $GoogleUsers = GoogleUser::find()
            ->where(['location' => 1])
            ->all();
        
        return $GoogleUsers;
        
    }
	
}