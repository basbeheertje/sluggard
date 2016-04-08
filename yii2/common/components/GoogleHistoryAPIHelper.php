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
class GoogleHistoryAPIHelper extends Component {
    
    const downloadlink = 'https://www.google.com/maps/timeline/kml';
    const cookiefile = 'cookie.txt';
    const useragent = '"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)"';
    const connectiontimeout = 30;
    const serviceloginlink = 'https://accounts.google.com/ServiceLogin?hl=en&service=alerts';
    
    /**
     * @since 2016-03-31
     * @author Bas van Beers
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['location']){
            return true;
        }
        
        return false;
        
    }
    
    public static function downloadKMLFile($username,$password){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::connectiontimeout);
        curl_setopt($ch, CURLOPT_USERAGENT, self::useragent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, self::cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, self::cookiefile);
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::connectiontimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        curl_setopt($ch, CURLOPT_URL, self::serviceloginlink.'&continue='.self::downloadlink);
        $data = curl_exec($ch);

        $formFields = self::getFormFields($data);

        $formFields['Email']  = $username;
        $formFields['Passwd'] = $password;
        unset($formFields['PersistentCookie']);

        $post_string = '';
        foreach($formFields as $key => $value) {
            $post_string .= $key . '=' . urlencode($value) . '&';
        }

        $post_string = substr($post_string, 0, -1);

        curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/ServiceLoginAuth');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);

        $result = curl_exec($ch);

        if (strpos($result, '<title>Redirecting') === false && strpos($result, '<title>Omleiding') === false && strpos($result, '<kml xmlns="') === false ) {

            return false;

        } else {

            $xmlstring = str_replace('</coord>','</coord></Track>',str_replace('<when>','<Track><when>',str_replace('gx:coord','coord',str_replace('gx:Track','Tracks',$result))));
            $xml = simplexml_load_string($xmlstring);

            $Tracks = $xml->Document->Placemark->Tracks->Track;

            $Tracks = self::convertToObjects($Tracks);
            
            return $Tracks;

        }
        
    }
    
    protected static function getFormFields($data){
        if (preg_match('/(<form.*?id=.?gaia_loginform.*?<\/form>)/is', $data, $matches)) {
            $inputs = self::getInputs($matches[1]);

            return $inputs;
        } else {
            die('didnt find login form');
        }
    }

    protected static function getInputs($form){
        $inputs = array();

        $elements = preg_match_all('/(<input[^>]+>)/is', $form, $matches);

        if ($elements > 0) {
            for($i = 0; $i < $elements; $i++) {
                $el = preg_replace('/\s{2,}/', ' ', $matches[1][$i]);

                if (preg_match('/name=(?:["\'])?([^"\'\s]*)/i', $el, $name)) {
                    $name  = $name[1];
                    $value = '';

                    if (preg_match('/value=(?:["\'])?([^"\'\s]*)/i', $el, $value)) {
                        $value = $value[1];
                    }

                    $inputs[$name] = $value;
                }
            }
        }

        return $inputs;
    }

    protected static function convertToObjects($tracks){
		
	$Objects = array();
		
	foreach($tracks as $track){
			
            $track->when = self::convertDateFromGoogle($track->when);
			
            $object = new \stdClass();
            $object->date = date('Y-m-d',strtotime($track->when));
            $object->time = date('H:i:s',strtotime($track->when));
			
            $coordinates = \explode(' ',$track->coord);
			
            $object->longitude = $coordinates[0];
            $object->latitude = $coordinates[1];
            $object->height = $coordinates[2];
			
            $Objects[] = $object;
			
        }
		
	return $Objects;
		
    }
	
    protected static function convertDateFromGoogle($date){
		
	if(self::isDaylightTime()){
            return \date("Y-m-d H:i:s", strtotime('+9 hours', strtotime($date)));
	}else{
            return \date("Y-m-d H:i:s", strtotime('+8 hours', strtotime($date)));
	}
		
    }
	
    public static function isDaylightTime(){
		
        $type = (int) \date('I');
		
	if($type === 1){
            return true;
	}else{
            return false;
	}
		
    }
    
}