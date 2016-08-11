<?php

namespace frontend\modules\imdb\models;

use frontend\modules\imdb\models\video;
use frontend\modules\imdb\components\TorrentHelper;
use frontend\modules\imdb\components\IMDBHelper;
use frontend\modules\imdb\components\SubtitleHelper;

class movie{
    
    public $id;
    public $title;
    public $year;
    public $rating;
    public $poster;
    public $url;
    public $rank;
    public $language;
    public $country;
    public $release_date;
    public $plot;
    public $tags;
    public $awards;
    public $nominations;
    
    public $torrents = [];
    
    public $trailers = [];
    
    public $subtitles = [];
    
    public function getImage(){
        
        $imagePath = realpath(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'imdb'.DIRECTORY_SEPARATOR;
        
        $imageName = $imagePath.$this->id.'.jpg';
        
        if(!file_exists($imageName) and !empty($this->poster)){
            copy($this->poster, $imageName);
            return '/images/imdb/'.$this->id.'.jpg';
        }else if(empty($this->poster)){
            return '/images/imdb/empty.jpg';
        }
        
        return '/images/imdb/'.$this->id.'.jpg';
        
    }
    
    public function getTorrents(){
        
        $this->torrents = TorrentHelper::find($this->title);
        
    }
    
    public function getTrailers(){
        
        $html = IMDBHelper::geturl("http://www.imdb.com/title/".$this->id."/videogallery");
        $videos = array();
        foreach (IMDBHelper::match_all('/<a.*?href="(\/video\/imdb\/.*?)".*?>.*?<\/a>/ms', $html, 1) as $v) {
            $video = new video();
            $video->url = "http://www.imdb.com${v}";
            $videos[] = $video;
        }
        
        $this->trailers = $videos;
        
        return $videos;
        
    }
    
    // Get recommended titles by IMDb title id.
    public function getRecommendedTitles($titleId){
        $json = IMDBHelper::geturl("http://www.imdb.com/widget/recommendations/_ajax/get_more_recs?specs=p13nsims%3A".$this->id."");
        $resp = json_decode($json, true);
        $arr = array();
        if(isset($resp["recommendations"])) {
            foreach($resp["recommendations"] as $val) {
                $name = IMDBHelper::match('/title="(.*?)"/msi', $val['content'], 1);
                $arr[$val['tconst']] = $name;
            }
        }
        return array_filter($arr);
    }
    
    public function getBestTorrent(){
        
        if(!empty($this->torrents)){
            
            $this->mergeTorrents();
            
            foreach($this->torrents as $torrent){

                if($torrent->isGoodEnough() and $torrent->hasSubtitle()){
                    return $torrent;
                }

            }
            
            foreach($this->torrents as $torrent){

                if($torrent->isGoodEnough()){
                    return $torrent;
                }

            }
        }
        
        return false;
        
    }
    
    public function getSubtitles(){
        
        $this->subtitles = SubtitleHelper::find($this->title);
        
    }
    
    public function hasSubtitles(){
        
        if(!empty($this->subtitles)){
            return true;
        }
        
        return false;
        
    }
    
    public function hasTorrents(){
        
        if(!empty($this->torrents)){
            return true;
        }
        
        return false;
        
    }
    
    public function hasTrailers(){
        
        if(!empty($this->trailers)){
            return true;
        }
        
        return false;
        
    }
    
    public function mergeTorrents(){
        
        foreach($this->torrents as $torrentkey => $torrent){
            
            foreach($this->subtitles as $subtitlekey => $subtitle){
                
                $torrentname = strtolower(str_replace(' ','',str_replace('.','',$torrent->title)));
                $subtitlename = strtolower(str_replace(' ','',str_replace('.','',$subtitle->title)));
                
                if($torrentname === $subtitlename){
                    
                    $this->torrents[$torrentkey]->subtitle = $subtitle;
                    
                }
                
            }
            
        }
        
    }
    
}