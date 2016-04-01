<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;
use common\components\GoogleAPIHelper;

/**
 * Description of GoogleContactsHelper
 * Class GoogleContactsHelper
 * @since 2016-03-31
 * @author Bas van Beers
 * 
 */
class GoogleContactsHelper extends Component {
    
    /** @const string SCOPE */
    const SCOPE = 'https://www.google.com/m8/feeds';
    
    /**
     * @access public
     * @author Bas van Beers
     * @since 2016-03-31
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['contacts']){
            return true;
        }
        
        return false;
        
    }
    
    /**
     * @access public
     * @author Bas van Beers
     * @since 2016-03-31
     * @param GoogleUser $GoogleUser
     * @return array $response
     */
    public static function getAllContacts(GoogleUser $GoogleUser){
        
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&oauth_token='.$GoogleUser->access_token;
        $response =  json_decode(file_get_contents($url));
        
        return $response;
        
    }
    
    public static function convertToContact(){
        
        
        
    }
    
}