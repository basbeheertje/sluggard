<?php

namespace frontend\modules\imdb\components;

use  \yii\base\Component;
use frontend\modules\imdb\models\movie;

/**
 * Description of GoogleAPIHelper
 * Class GoogleAPIHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class IMDBHelper extends Component {
    
    /**
     * Converts to right format
     * @param integer $bytes
     * @param integer $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2) { 
        
        /** @var array $units */
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

        /** @var integer $bytes */
        $bytes = max($bytes, 0); 
        
        /** @var integer $pow */
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
    
    /**
     * Returns all the movies from the top 250 of IMDB
     * @return movie[]
     */
    public static function getMovies(){
        
        /** @var array $movies */
        $movies = [];
        
        /** @var string $cacheDir */
        $cacheDir = realpath(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        
        /** @var array $TMPmovies */
        $TMPmovies = self::getTop250();
        
        foreach($TMPmovies as $TMPmovie){
            
            /** @var string $cachedName */
            $cachedName = $cacheDir.$TMPmovie['id'].'.txt';
            
            if(!file_exists($cachedName)){
            
                /** @var movie $movie */
                $movie = new movie();
                
                $movie->id = (string) $TMPmovie['id'];
                $movie->title = (string) $TMPmovie['title'];
                $movie->year = (string) $TMPmovie['year'];
                $movie->rating = (string) $TMPmovie['rating'];
                $movie->poster = (string) $TMPmovie['poster'];
                $movie->url = (string) $TMPmovie['url'];
                $movie->rank = (string) $TMPmovie['rank'];
                
                if(\Yii::$app->params['torrents']['enabled']){
                    $movie->getTorrents();
                }
                $movie->getTrailers();
                
                /** @var array $objData */
                $objData = serialize($movie);
                
                file_put_contents($cachedName, $objData);
            
            }else{
                
                /** @var array $objData */
                $objData = file_get_contents($cachedName);
                
                /** @var movie $movie */
                $movie = unserialize($objData);
                
            }
            $movies[] = $movie;
            
        }
        
        return $movies;
        
    }
    
    /**
     * gets the top250 of movies from IMDB
     * @return array
     */
    public static function getTop250(){
        /** @var string $html */
        $html = self::geturl("http://www.imdb.com/chart/top?sort=us,desc&mode=simple&page=1");
        
        /** @var array $top250 */
        $top250 = array();
        
        /** @var integer $rank */
        $rank = 1;
        
        /** @var string $html */
        $html = self::match_all('/<tbody class="lister-list">(.*?)<\/tbody>/ms', $html, 0)[0];
        //$html = $html[0];
        
        foreach (self::match_all('/<tr>(.*?)<\/tr>/ms', $html, 1) as $m) {
            
            /** @var string $id */
            $id = self::match('/<td class="titleColumn">.*?<a href="\/title\/(tt\d+)\/.*?"/msi', $m, 1);
            
            /** @var string $title */
            $title = self::match('/<td class="titleColumn">.*?<a.*?>(.*?)<\/a>/msi', $m, 1);
            
            /** @var string $year */
            $year = self::match('/<td class="titleColumn">.*?<span class="secondaryInfo">\((.*?)\)<\/span>/msi', $m, 1);
            
            /** @var string $rating */
            $rating = self::match('/<td class="ratingColumn"><strong.*?>(.*?)<\/strong>/msi', $m, 1);
            
            /** @var string $poster */
            $poster = self::match('/<td class="posterColumn">.*?<img src="(.*?)"/msi', $m, 1);
            $poster = preg_replace('/_V1.*?.jpg/ms', "_V1._SY200.jpg", $poster);
            
            /** @var string $url */
            $url = "http://www.imdb.com/title/${id}/";
            
            $top250[] = array("id"=>$id, "rank"=>$rank, "title"=>$title, "year"=>$year, "rating"=>$rating, "poster"=>$poster, "url"=>$url);
            
            /** @var integer $rank */
            $rank++;
        }
        return $top250;
    }
    
    /**
     * Get movie information by IMDb Id.
     * @param string $imdbId
     * @param boolean $getExtraInfo
     * @return array
     */
    public static function getMovieInfoById($imdbId, $getExtraInfo = true)
    {
        $arr = array();
        $imdbUrl = "http://www.imdb.com/title/" . trim($imdbId) . "/";
        return self::scrapeMovieInfo($imdbUrl, $getExtraInfo);
    }
    
    /**
     * scrapes an url for information
     * @param string $imdbUrl
     * @param boolean $getExtraInfo
     * @return string
     */
    private static function scrapeMovieInfo($imdbUrl, $getExtraInfo = true){
        
        /** @var array $arr */
        $arr = array();
        
        /** @var string $html */
        $html = self::geturl("${imdbUrl}combined");
        
        /** @var string $title_id */
        $title_id = self::match('/<link rel="canonical" href="http:\/\/www.imdb.com\/title\/(tt\d+)\/combined" \/>/ms', $html, 1);
        if(empty($title_id) || !preg_match("/tt\d+/i", $title_id)) {
            $arr['error'] = "No Title found on IMDb!";
            return $arr;
        }
        $arr['title_id'] = $title_id;
        $arr['imdb_url'] = $imdbUrl;
        $arr['title'] = str_replace('"', '', trim(self::match('/<title>(IMDb \- )*(.*?) \(.*?<\/title>/ms', $html, 2)));
        $arr['original_title'] = trim(self::match('/class="title-extra">(.*?)</ms', $html, 1));
        $arr['year'] = trim(self::match('/<title>.*?\(.*?(\d{4}).*?\).*?<\/title>/ms', $html, 1));
        $arr['rating'] = self::match('/<b>(\d.\d)\/10<\/b>/ms', $html, 1);
        $arr['genres'] = self::match_all('/<a.*?>(.*?)<\/a>/ms', self::match('/Genre.?:(.*?)(<\/div>|See more)/ms', $html, 1), 1);
        $arr['directors'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Directed by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['writers'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Writing credits<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['cast'] = self::match_all_key_value('/<td class="nm"><a.*?href="\/name\/(.*?)\/".*?>(.*?)<\/a>/ms', self::match('/<h3>Cast<\/h3>(.*?)<\/table>/ms', $html, 1));
        $arr['cast'] = array_slice($arr['cast'], 0, 30);
        $arr['stars'] = array_slice($arr['cast'], 0, 5);
        $arr['producers'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Produced by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['musicians'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Original Music by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['cinematographers'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Cinematography by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['editors'] = self::match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', self::match('/Film Editing by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
        $arr['mpaa_rating'] = self::match('/MPAA<\/a>:<\/h5><div class="info-content">Rated (G|PG|PG-13|PG-14|R|NC-17|X) /ms', $html, 1);
        $arr['release_date'] = self::match('/Release Date:<\/h5>.*?<div class="info-content">.*?([0-9][0-9]? (January|February|March|April|May|June|July|August|September|October|November|December) (19|20)[0-9][0-9])/ms', $html, 1);
        $arr['tagline'] = trim(strip_tags(self::match('/Tagline:<\/h5>.*?<div class="info-content">(.*?)(<a|<\/div)/ms', $html, 1)));
        $arr['plot'] = trim(strip_tags(self::match('/Plot:<\/h5>.*?<div class="info-content">(.*?)(<a|<\/div|\|)/ms', $html, 1)));
        $arr['plot_keywords'] = self::match_all('/<a.*?>(.*?)<\/a>/ms', self::match('/Plot Keywords:<\/h5>.*?<div class="info-content">(.*?)<\/div/ms', $html, 1), 1);
        $arr['poster'] = self::match('/<div class="photo">.*?<a name="poster".*?><img.*?src="(.*?)".*?<\/div>/ms', $html, 1);
        $arr['poster_large'] = "";
        $arr['poster_full'] = "";
        if ($arr['poster'] != '' && strpos($arr['poster'], "media-imdb.com") > 0) { //Get large and small posters
            $arr['poster'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY200.jpg", $arr['poster']);
            $arr['poster_large'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY500.jpg", $arr['poster']);
            $arr['poster_full'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY0.jpg", $arr['poster']);
        } else {
            $arr['poster'] = "";
        }
        $arr['runtime'] = trim(self::match('/Runtime:<\/h5><div class="info-content">.*?(\d+) min.*?<\/div>/ms', $html, 1));
        $arr['top_250'] = trim(self::match('/Top 250: #(\d+)</ms', $html, 1));
        $arr['oscars'] = trim(self::match('/Won (\d+) Oscars?\./ms', $html, 1));
        if(empty($arr['oscars']) && preg_match("/Won Oscar\./i", $html)) $arr['oscars'] = "1";
        $arr['awards'] = trim(self::match('/(\d+) wins/ms',$html, 1));
        $arr['nominations'] = trim(self::match('/(\d+) nominations/ms',$html, 1));
        $arr['votes'] = self::match('/>([0-9,]*) votes</ms', $html, 1);
        $arr['language'] = self::match_all('/<a.*?>(.*?)<\/a>/ms', self::match('/Language.?:(.*?)(<\/div>|>.?and )/ms', $html, 1), 1);
        $arr['country'] = self::match_all('/<a.*?>(.*?)<\/a>/ms', self::match('/Country:(.*?)(<\/div>|>.?and )/ms', $html, 1), 1);
         
        if($getExtraInfo == true) {
            
            /** @var string $plotPageHtml */
            $plotPageHtml = self::geturl("${imdbUrl}plotsummary");
            
            $arr['storyline'] = trim(strip_tags(self::match('/<li class="odd">.*?<p>(.*?)(<|<\/p>)/ms', $plotPageHtml, 1)));
            $releaseinfoHtml = self::geturl("http://www.imdb.com/title/" . $arr['title_id'] . "/releaseinfo");
            $arr['also_known_as'] = self::getAkaTitles($releaseinfoHtml);
            $arr['release_dates'] = self::getReleaseDates($releaseinfoHtml);
            $arr['recommended_titles'] = self::getRecommendedTitles($arr['title_id']);
            $arr['media_images'] = self::getMediaImages($arr['title_id']);
            $arr['videos'] = self::getVideos($arr['title_id']);
        }
         
        return $arr;
    }
    
     /**
      * Scan all Release Dates.
      * @param string $html
      * @return array
      */
    private static function getReleaseDates($html){
        
        /** @var array $releaseDates */
        $releaseDates = array();
        
        foreach(self::match_all('/<tr.*?>(.*?)<\/tr>/ms', self::match('/<table id="release_dates".*?>(.*?)<\/table>/ms', $html, 1), 1) as $r) {
            /** @var string $country */
            $country = trim(strip_tags(self::match('/<td>(.*?)<\/td>/ms', $r, 1)));
            
            /** @var string $date */
            $date = trim(strip_tags(self::match('/<td class="release_date">(.*?)<\/td>/ms', $r, 1)));
            array_push($releaseDates, $country . " = " . $date);
        }
        return array_filter($releaseDates);
    }
 
    /**
     * Scan all AKA Titles.
     * @param string $html
     * @return array
     */
    private static function getAkaTitles($html){
        
        /** @var array $akaTitles */
        $akaTitles = array();
        
        foreach(self::match_all('/<tr.*?>(.*?)<\/tr>/msi', self::match('/<table id="akas".*?>(.*?)<\/table>/ms', $html, 1), 1) as $m) {
            $akaTitleMatch = self::match_all('/<td>(.*?)<\/td>/ms', $m, 1);
            $akaCountry = trim($akaTitleMatch[0]);
            $akaTitle = trim($akaTitleMatch[1]);
            array_push($akaTitles, $akaTitle . " = " . $akaCountry);
        }
        return array_filter($akaTitles);
    }
 
    /**
     * Collect all Media Images.
     * @param string $titleId
     * @return array
     */
    private static function getMediaImages($titleId){
        $url  = "http://www.imdb.com/title/" . $titleId . "/mediaindex";
        $html = self::geturl($url);
        $media = array();
        $media = array_merge($media, self::scanMediaImages($html));
        foreach(self::match_all('/<a.*?>(\d*)<\/a>/ms', self::match('/<span class="page_list">(.*?)<\/span>/ms', $html, 1), 1) as $p) {
            $html = self::geturl($url . "?page=" . $p);
            $media = array_merge($media, self::scanMediaImages($html));
        }
        return $media;
    }
 
    /**
     * Scan all media images.
     * @param string $html
     * @return array
     */
    private static function scanMediaImages($html){
        $pics = array();
        foreach(self::match_all('/src="(.*?)"/msi', self::match('/<div class="media_index_thumb_list".*?>(.*?)<\/div>/msi', $html, 1), 1) as $i) {
            array_push($pics, preg_replace('/_V1\..*?.jpg/ms', "_V1._SY0.jpg", $i));
        }
        return array_filter($pics);
    }
     
    /**
     * Get recommended titles by IMDb title id.
     * @param string $titleId
     * @return array
     */
    public static function getRecommendedTitles($titleId){
        $json = self::geturl("http://www.imdb.com/widget/recommendations/_ajax/get_more_recs?specs=p13nsims%3A${titleId}");
        $resp = json_decode($json, true);
        $arr = array();
        if(isset($resp["recommendations"])) {
            foreach($resp["recommendations"] as $val) {
                $name = self::match('/title="(.*?)"/msi', $val['content'], 1);
                $arr[$val['tconst']] = $name;
            }
        }
        return array_filter($arr);
    }
     
    /**
     * Get all Videos and Trailers
     * @param string $titleId
     * @return array
     */
    public static function getVideos($titleId){
        $html = self::geturl("http://www.imdb.com/title/${titleId}/videogallery");
        $videos = array();
        foreach (self::match_all('/<a.*?href="(\/video\/imdb\/.*?)".*?>.*?<\/a>/ms', $html, 1) as $v) {
            $videos[] = "http://www.imdb.com${v}";
        }
        return array_filter($videos);
    }
 
    /**
     * Movie title search on Google, Bing or Ask. If search fails, return FALSE.
     * @var string $title
     * @var string $engine
     * @return array
     */
    private static function getIMDbIdFromSearch($title, $engine = "google"){
        switch ($engine) {
            case "google":  $nextEngine = "bing";  break;
            case "bing":    $nextEngine = "ask";   break;
            case "ask":     $nextEngine = FALSE;   break;
            case FALSE:     return NULL;
            default:        return NULL;
        }
        $url = "http://www.${engine}.com/search?q=imdb+" . rawurlencode($title);
        $ids = self::match_all('/<a.*?href="http:\/\/www.imdb.com\/title\/(tt\d+).*?".*?>.*?<\/a>/ms', self::geturl($url), 1);
        if (!isset($ids[0]) || empty($ids[0])) //if search failed
            return self::getIMDbIdFromSearch($title, $nextEngine); //move to next search engine
        else
            return $ids[0]; //return first IMDb result
    }
     
    public static function geturl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }
 
    private static function match_all_key_value($regex, $str, $keyIndex = 1, $valueIndex = 2){
        $arr = array();
        preg_match_all($regex, $str, $matches, PREG_SET_ORDER);
        foreach($matches as $m){
            $arr[$m[$keyIndex]] = $m[$valueIndex];
        }
        return $arr;
    }
     
    public static function match_all($regex, $str, $i = 0){
        if(preg_match_all($regex, $str, $matches) === false)
            return false;
        else
            return $matches[$i];
    }
 
    private static function match($regex, $str, $i = 0){
        if(preg_match($regex, $str, $match) == 1)
            return $match[$i];
        else
            return false;
    }
    
}