<?php

namespace common\components;

use  \yii\base\Component;
use \common\models\GoogleUser;
use common\components\GoogleAPIHelper;

/**
 * Description of GoogleMailHelper
 * Class GoogleMailHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class GoogleMailHelper extends Component {
    
    /** @const string SCOPE */
    const SCOPE = 'https://mail.google.com/';
    
    /**
     * @since 2016-03-31
     * @author Bas van Beers
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['gmail']){
            return true;
        }
        
        return false;
        
    }
    
}