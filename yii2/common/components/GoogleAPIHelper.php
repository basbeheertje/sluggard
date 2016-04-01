<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;

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
    
}