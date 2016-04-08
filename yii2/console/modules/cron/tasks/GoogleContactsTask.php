<?php

namespace console\modules\cron\tasks;

use console\modules\cron\components\core\CronTask;
use common\components\GoogleContactsHelper;
use common\models\GoogleUser;
use common\models\GoogleContact;
use common\models\Contact;

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
            
            $lastContact = $this->getLastContactFromGoogleUser($GoogleUser);
            
            if($lastContact === false || is_null($lastContact)){
                
                /** @var array $GoogleContacts */
                $GoogleContacts = GoogleContactsHelper::getAllContacts($GoogleUser);
                
                foreach($GoogleContacts as $googleContact){
                    GoogleContactsHelper::addContact($googleContact, $GoogleUser);
                }
                
                exit;
                
                /** START PLAYGROUND */
                
                $json = json_encode($GoogleContacts);
                foreach($GoogleContacts as $key => $value){
                    echo $key."<br/>\r\n";
                }
                
                file_put_contents('contacts.txt',$json);
                
                //var_dump($GoogleContacts);
                
                echo 'THERE ARE NO CONTACTS!';
                
                /** END PLAYGROUND */
                
            }else{
                
                var_dump($lastContact);
                print_r($lastContact);
                echo 'THERE IS AN CONTACT!';
                
            }
            
        }
		
    }
    
    protected static function getAllGoogleUsers(){
        
        /** @var GoogleUser $GoogleUsers */
        $GoogleUsers = GoogleUser::find()
            ->where(['contacts' => 1])
            ->all();
        
        return $GoogleUsers;
        
    }
    
    protected static function getContactsFromGoogleUser(GoogleUser $GoogleUser){
        
        $contacts = GoogleContactsHelper::getAllContacts($GoogleUser);
        
        return $contacts;
        
    }
    
    protected static function getLastContactFromGoogleUser(GoogleUser $GoogleUser){
        
         /** @var GoogleContact $GoogleContacts */
        $GoogleContacts = GoogleContact::find()
            ->where(['google_user_id' => $GoogleUser->id])
            ->orderBy(['updated' => SORT_DESC])
            ->one();
        
        return $GoogleContacts;
        
    }
    
}