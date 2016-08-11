<?php

namespace frontend\modules\imdb\components;

use  \yii\base\Component;
use frontend\modules\imdb\models\subtitle;

/**
 * Description of SubtitleHelper
 * Class SubtitleHelper
 * @since 2016-03-31
 * @author Bas van Beers
 * @const NLsubtitlesDomain
 * @const ondertitel
 */
class SubtitleHelper extends Component {
    
    const NLsubtitlesDomain = '';
    const ondertitel = 'http://www.ondertitel.com';
    
    /**
     * Searches different sites for subtitles
     * @param string $title
     * @return subtitle[]
     */
    public static function find($title){
        
        return self::getFromOndertitel($title);
        
    }
    
    /**
     * Returns subtitles from ondertitel.com by title
     * @param string $title
     * @return subtitle[]
     */
    public static function getFromOndertitel($title){
        
        /** @var string $url */
        $url = self::ondertitel.'/zoeken.php?trefwoord='.urlencode($title).'&zoeken=';
        
        /** @var array $Subtitles */
        $Subtitles = array();
        
        /** @var curl $curl */
        $curl = curl_init();

        curl_setopt_array($curl, Array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => 'UTF-8',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
            CURLOPT_TIMEOUT        => 120,      // timeout on response 
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
        ));

        /** @var string $html */
        $html = curl_exec($curl);
        curl_close($curl);
        
        /** @var array $OwnElements */
        $OwnElements = self::match_all('/<ul class="ondertitels">(.*?)<\/ul>/ms', str_replace($title,'',self::match_all('/<div id="subs_ondertit">(.*?)<ul class="navigation bottom">/ms', $html, 0)[0]), 0);
        //$OwnElements = str_replace($title,'',$OwnElements[0]);
        
        //$OwnElements = self::match_all('/<ul class="ondertitels">(.*?)<\/ul>/ms', $OwnElements, 0);
        if(!empty($OwnElements)){
            /** @var string $OwnElements */
            $OwnElements = str_replace($title,'',$OwnElements[0]);

            foreach (self::match_all('/<li class="">(.*?)<\/li>/ms', $OwnElements, 1) as $OwnElement) {
                /** @var subtitle $Subtitle */
                $Subtitle = new subtitle();
                $Subtitle->url = self::match('/<a href="(.*?)"/msi', $OwnElement, 1);
                
                /** @var array $SubIDs */
                $SubIDs = explode('/',self::match('/\/(.*?).html/msi', $OwnElement, 1));
                
                /** @var string $SubID */
                $SubID = $SubIDs[count($SubIDs)-1];
                
                $Subtitle->title = self::match('/<i>(.*?) - \d* hits<\/i>/msi', $OwnElement, 1);
                $Subtitle->filelink = false;//self::ondertitel.'/srt_download_unz.php?id='.$SubID.'&userfile='.urlencode($Subtitle->title).'.zip';
                $Subtitle->site = self::ondertitel;

                $Subtitles[] = $Subtitle;

            }
        }
        
        /** @var array $OtherElements */
        $OtherElements = str_replace($title,'',self::match_all('/<div id="subs_extern">(.*?)<\/div>/ms', $html, 0)[0]) . "</ulend>";
        //$OtherElements = str_replace($title,'',$OtherElements[0]);
        //$OtherElements = $OtherElements . "</ulend>";
        
        /** @var array $OtherElements */
        $OtherElements = self::match_all('/<ul class="ondertitels">(.*?)<\/ulend>/ms', $OtherElements, 0);
        if(!empty($OtherElements)){
            /** @var array $OtherElements */
            $OtherElements = str_replace($title,'',$OtherElements[0]);

            foreach (self::match_all('/<li class="">(.*?)<\/li>/ms', $OtherElements, 1) as $OtherElement) {

                /** @var subtitle $Subtitle */
                $Subtitle = new subtitle();
                $Subtitle->url = self::match('/<a original-title="\s*" href="(.*?)"/msi', $OtherElement, 1);
                
                /** @var array $SubIDs */
                $SubIDs = explode('/',self::match('/\/(.*?).html/msi', $Subtitle->url, 1));
                
                /** @var string $SubID */
                $SubID = $SubIDs[count($SubIDs)-1];
                
                $Subtitle->title = self::match('/<i>(.*?) - \d* hits<\/i>/msi', $OtherElement, 1);
                $Subtitle->filelink = self::ondertitel.'/srt_download_unz.php?id='.$SubID.'&userfile='.urlencode($Subtitle->title).'.zip';

                $Subtitle->site = explode('/',str_replace('www.','',str_replace('https://','',str_replace('http://','',$Subtitle->url))))[0];

                $Subtitles[] = $Subtitle;

            }
            
        }
        
        return $Subtitles;
        
    }
    
    /**
     * 
     * @param string $regex
     * @param string $str
     * @param integer $i
     * @return boolean|array
     */
    private static function match($regex, $str, $i = 0){
        if(preg_match($regex, $str, $match) == 1)
            return $match[$i];
        else
            return false;
    }
    
    /**
     * 
     * @param string $regex
     * @param string $str
     * @param integer $i
     * @return boolean|array
     */
    private static function match_all($regex, $str, $i = 0){
        if(preg_match_all($regex, $str, $matches) === false)
            return false;
        else
            return $matches[$i];
    }
    
}