<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;
use common\components\GoogleCalendarHelper;
use common\components\GoogleContactsHelper;
use common\components\GoogleDriveHelper;
use common\components\GoogleMailHelper;
use common\components\GooglePlusHelper;
use common\components\GoogleHitoryAPIHelper;

/**
 * Description of GoogleAPIHelper
 * Class GoogleAPIHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class GoogleAPIHelper extends Component {
    
    const SCOPE = 'https://www.googleapis.com/auth/userinfo.profile';
    
    /**
     * isEnabled allows to check if Google API is enabled by config
     * @return boolean
     */
    public static function isEnabled(){
        
        if(isset(\Yii::$app->params['google']['enabled']) && \Yii::$app->params['google']['enabled']){
            return true;
        }
        
        return false;
        
    }
    /**
     * Add the needed scopes to the Google_Client object
     * @param \Google_Client $client
     * @return \Google_Client
     */
    public static function addScopes(\Google_Client $client){
        
        if(GoogleAPIHelper::isEnabled()){
            if(GoogleContactsHelper::isEnabled()){
                $client->addScope(GoogleContactsHelper::SCOPE);
            }
            if(GoogleCalendarHelper::isEnabled()){
                $client->addScope(GoogleCalendarHelper::SCOPE);
            }
            if(GoogleDriveHelper::isEnabled()){
                $client->addScope(GoogleDriveHelper::SCOPE);
            }
            if(GoogleMailHelper::isEnabled()){
                $client->addScope(GoogleMailHelper::SCOPE);
            }
            $client->addScope('https://www.googleapis.com/auth/userinfo.email');
            $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
            $client->addScope('https://www.googleapis.com/auth/glass.location');
            $client->addScope('https://www.googleapis.com/auth/glass.timeline');
            $client->addScope('https://www.googleapis.com/auth/contacts');
            $client->addScope('https://www.googleapis.com/auth/contacts.readonly');
            if(GooglePlusHelper::isEnabled()){
                $client->addScope(GooglePlusHelper::SCOPE);
            }
        }
        return $client;
        
    }
    
    /**
     * Retrieves info about the Google User
     * @param \Google_Client $client
     * @return type
     */
    public static function getUserInfo(\Google_Client $client){
        
        $access_token = json_decode($client->getAccessToken())->access_token;
        
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?oauth_token='.$access_token;

        $response =  file_get_contents($url);
            
        $j = json_decode($response);
        
        return $j;
        
    }
    
    public static function refreshToken($accesstoken){
        
        if($client->isAccessTokenExpired()) {
            $client->authenticate();
            $NewAccessToken = json_decode($client->getAccessToken());
            $client->refreshToken($NewAccessToken->refresh_token);
        }
        
        return $client;
        
    }
    
}