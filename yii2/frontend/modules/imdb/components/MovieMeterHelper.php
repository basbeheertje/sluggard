<?php

namespace frontend\modules\imdb\components;

use  \yii\base\Component;
use frontend\modules\imdb\models\movie;
use Imdb;

/**
 * Description of GoogleAPIHelper
 * Class GoogleAPIHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class MovieMeterHelper extends Component {
    
    public static function scrape($url){
        
        $ch = curl_init();
        
        $cookie = 'cok=1';
        $ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
        
        $headers   = array();
        $headers[] = 'Cookie: ' . $cookie;
        $headers[] = "REMOTE_ADDR: $ip";
        $headers[] = "HTTP_X_FORWARDED_FOR: $ip";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
        $html = curl_exec($ch);
        curl_close($ch);
        
        return self::extractMovies($html);
        
    }
    
    private static function extractMovies($html){
        
        $movies = [];
        
        $movieselement = self::match_all('/<div id="topList">(.*?)<div id="footer">/ms', $html, 0);
        
        if(!isset($movieselement[0])){
            $movieselement = self::match_all('/<div id="cinemaListings">(.*?)<div id="footer">/ms', $html, 0);
        }
        $movieselement = $movieselement[0];
        $movieselement = str_replace('<div id="footer">','',$movieselement);
        
        foreach (self::match_all('/<div class="film_row">(.*?)<\/a>/ms', $movieselement, 1) as $movieelement) {
            
            $movieelement = $movieelement . "</a>";
            
            $movie = self::extractMovie($movieelement);
            
            if($movie){                
                $movies[] = $movie;
            }
            
        }
        
        if(empty($movies)){
            
            $new = self::match_all('/class="film_row">(.*?)<\/a>/ms', $movieselement, 1);
            
            foreach ($new as $movieelement) {
            
                $movieelement = $movieelement . "</a>";
            
                $movie = self::extractMovie($movieelement);

                if($movie){                
                    $movies[] = $movie;
                }
                
            }
            
        }
        
        return $movies;
        
    }
    
    private static function extractMovie($movieelement){
        
        $cacheDir = realpath(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        
        $title = self::match('/<a class="tooltip" href="http:\/\/www.moviemeter.nl\/film\/\d+">(.*?)<\/a>/msi', $movieelement, 1);

        if(empty($title)){
            $title = self::match('/<a class="tooltip" href="http:\/\/www.moviemeter.nl\/film\/\d+" >(.*?)<\/a>/msi', $movieelement, 1);
        }
        
        $search = new \Imdb\TitleSearch(); // Optional $config parameter
        $results = $search->search($title, [\Imdb\TitleSearch::MOVIE]); // Optional second parameter restricts types returned

        foreach ($results as $result) { /* @var $result \Imdb\Title */
            
            $movieId = $result->imdbID();
            
            $cachedName = $cacheDir.'tt'.$movieId.'.txt';
            
            if(!file_exists($cachedName)){
            
                $movie = new movie();
                
                $config = new \Imdb\Config();
                $config->language = 'nl-NL';
                $imdb = new \Imdb\Title($movieId, $config);
                
                $movie->id = (string) 'tt'.$movieId;
                $movie->title = (string) $imdb->title();
                $movie->year = (string) $imdb->year();
                $movie->rating = (string) $imdb->rating();
                $movie->poster = (string) $imdb->photo();
                $movie->url = (string) 'http://www.imdb.com/title/tt'.$movieId.'/';
                $movie->rank = (string) 0;
                
                if(\Yii::$app->params['torrents']['enabled']){
                    $movie->getTorrents();
                }
                $movie->getTrailers();
                
                $objData = serialize($movie);
                
                file_put_contents($cachedName, $objData);
                
            }else{
                
                $objData = file_get_contents($cachedName);
                $movie = unserialize($objData);
                
            }
            
            return $movie;
            
        }
        
        return false;
        
    }
    
    private static function match($regex, $str, $i = 0){
        if(preg_match($regex, $str, $match) == 1)
            return $match[$i];
        else
            return false;
    }
    
    private static function match_all($regex, $str, $i = 0){
        if(preg_match_all($regex, $str, $matches) === false)
            return false;
        else
            return $matches[$i];
    }
    
}