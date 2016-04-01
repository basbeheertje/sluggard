<?php

namespace console\modules\cron\tasks;

use console\modules\cron\components\core\CronTask;
use common\components\GoogleContactsHelper;
use common\models\GoogleUser;

class GoogleContactsTask extends CronTask {

    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        
        /** @var GoogleUser $GoogleUsers */
	$GoogleUsers = $this->getAllGoogleUsers();
		
        /** @var GoogleUser $GoogleUser */
        foreach($GoogleUsers as $GoogleUser){
            
            $contacts = $this->getContactsFromGoogleUser($GoogleUser);
            
        }
        
	echo 'Finished';
		
    }
    
    protected static function getAllGoogleUsers(){
        
        /** @var GoogleUser $GoogleUsers */
        $GoogleUsers = GoogleUser::findAll();
        
        return $GoogleUsers;
        
    }
    
    protected static function getContactsFromGoogleUser(GoogleUser $GoogleUser){
        
        $contacts = GoogleContactsHelper::getAllContacts($GoogleUser);
        
        return $contacts;
        
    }
	
}