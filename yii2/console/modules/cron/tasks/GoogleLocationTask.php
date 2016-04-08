<?php

namespace console\modules\cron\tasks;

use console\modules\cron\components\core\CronTask;
use common\components\GoogleAPIHelper;
use common\components\GoogleHistoryAPIHelper;
use common\models\GoogleUser;
use common\models\GoogleUserLocation;

class GoogleLocationTask extends CronTask {

    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        
        if(GoogleAPIHelper::isEnabled() && GoogleHistoryAPIHelper::isEnabled()){
            /** @var GoogleUser $GoogleUsers */
            $GoogleUsers = $this->getAllGoogleUsers();

            /** @var GoogleUser $GoogleUser */
            foreach($GoogleUsers as $GoogleUser){
                
                /** @var array $locations */
                $locations = GoogleHistoryAPIHelper::downloadKMLFile($GoogleUser->email,$GoogleUser->password);

                $secretKey = \Yii::$app->params['security']['basic'];
                
                if($locations === false){
                    
                    return false;
                    
                }
                
                /** @var Object $location */
                foreach($locations as $location){
                    
                    /** @var GoogleUserLocation $GoogleUserLocation */
                    $GoogleUserLocation = new GoogleUserLocation();
                    $GoogleUserLocation->date = $location->date;
                    $GoogleUserLocation->time = $location->time;
                    $GoogleUserLocation->longitude = $location->longitude;
                    $GoogleUserLocation->latitude = $location->latitude;
                    $GoogleUserLocation->height = $location->height;
                    $GoogleUserLocation->google_user_id = $GoogleUser->id;
                    $GoogleUserLocation->updated_at = date('Y-m-d H:i:s');
                    $GoogleUserLocation->created_at = date('Y-m-d H:i:s');
                    $GoogleUserLocation->creator = 1;
                    
                    if(!$GoogleUserLocation->duplicate()){
                        $GoogleUserLocation->save();
                        \Yii::info('New Google User Location ['.$GoogleUserLocation->id.']{1}', 'frontend');
                    }
                    
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