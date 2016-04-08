<?php

namespace common\components;

use  \yii\base\Component;

/**
 * Description of LocationHelper
 * Class LocationHelper
 * @since 2016-04-06
 * @author Bas van Beers
 */
class LocationHelper extends Component {
    
    /**
     * Converts an address into Coordinates
     * @param string $longitude
     * @param string $latitude
     * @return boolean
     */
    public function CoordinatesToAddress($longitude,$latitude){
        
        /** @var string $lat */
        $lat = $latitude;
        
        /** @var string $lng */
        $lng = $longitude;

        /** @var string $url */
        $url = sprintf("https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s", $lat, $lng);

        /** @var string $content */
        $content = file_get_contents($url); // get json content

        /** @var array $metadata */
        $metadata = json_decode($content, true); //json decoder

        if(count($metadata['results']) > 0) {
            /** @var array $result */
            $result = $metadata['results'][0];

            // save it in db for further use
            return $result['formatted_address'];

        }
        
        return false;
        
    }
    
    /**
     * Returns the coordinates of an address
     * @param string $address
     * @return boolean|array
     */
    public function AddressToCoordinates($address){
        
        /** @var string $address */
        $address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern
 
        /** @var string $url */
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=".$address;
 
        /** @var string $response */
        $response = file_get_contents($url);
 
        /** @var array $json */
        $json = json_decode($response,TRUE); //generate array object from the response from the web
 
        return(
            [
                'latitude'  => $json['results'][0]['geometry']['location']['lat'],
                'longitude' => $json['results'][0]['geometry']['location']['lng'],
            ]
        );
        
    }
    
}