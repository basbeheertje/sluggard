<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;
use common\components\GoogleAPIHelper;

/**
 * Description of GoogleCalendarHelper
 * Class GoogleCalendarHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class GoogleCalendarHelper extends Component {
    
    /** @const string SCOPE */
    const SCOPE = 'https://www.googleapis.com/auth/calendar';
    
    /**
     * @since 2016-03-31
     * @author Bas van Beers
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['calendar']){
            return true;
        }
        
        return false;
        
    }
    
}