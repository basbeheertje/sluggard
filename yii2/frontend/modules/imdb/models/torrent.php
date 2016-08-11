<?php

namespace frontend\modules\imdb\models;

use frontend\modules\imdb\models\subtitle;

/**
 * Description of torrent
 *
 * @author Bas van Beers
 */
class torrent {
    
    public $url;
    public $title;
    public $site;
    public $rating;
    public $peers;
    public $seeds;
    public $size;
    public $age;
    public $magnet;
    public $torrentfile;
    public $subtitle;
    
    public function isGoodEnough(){
        
        // If number of seeds is lower than the minimum
        if($this->seeds < \Yii::$app->params['torrents']['minimumseeds']){
            return false;
        }
        
        // If size is lower than minimum size
        if($this->size < \Yii::$app->params['torrents']['minimumsize']){
            return false;
        }
        
        // If size is lower than maximum size
        if($this->size > \Yii::$app->params['torrents']['maximumsize']){
            return false;
        }
        
        // If there are subtitles mentioned in the title
        if (strpos(strtolower($this->title), ' subs') !== false) {
            return false;
        }
                
        // If russian is mentioned in the title
        if (strpos(strtolower($this->title), ' rus ') !== false) {
            return false;
        }
        
        // If french is mentioned in the title
        if (strpos(strtolower($this->title), ' french ') !== false) {
            return false;
        }
        
        // If dvdscreener is mentioned in the title
        if (strpos(strtolower($this->title), 'dvdscr') !== false) {
            return false;
        }
        
        // If web is mentioned in the title
        if (strpos(strtolower($this->title), ' web') !== false) {
            return false;
        }
        
        // If web is mentioned in the title
        if (strpos(strtolower($this->title), ' screener') !== false) {
            return false;
        }
        
        return true;
        
    }
    
    public function hasSubtitle(){
        
        if(!empty($this->subtitle)){
            return true;
        }
        
        return false;
        
    }
    
}
