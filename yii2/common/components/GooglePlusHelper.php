<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;
use common\components\GoogleAPIHelper;

/**
 * Description of GooglePlusHelper
 * Class GooglePlusHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class GooglePlusHelper extends Component {
    
    /** @const string SCOPE */
    const SCOPE = 'https://www.googleapis.com/auth/plus.login';
    
    /**
     * @since 2016-03-31
     * @author Bas van Beers
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['plus']){
            return true;
        }
        
        return false;
        
    }
    
}