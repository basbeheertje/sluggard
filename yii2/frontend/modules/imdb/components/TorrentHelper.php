<?php

namespace frontend\modules\imdb\components;

use  \yii\base\Component;
use frontend\modules\imdb\models\movie;
use frontend\modules\imdb\models\torrent;

/**
 * Description of TorrentHelper
 * Class TorrentHelper
 * @since 2016-03-31
 * @author Bas van Beers
 */
class TorrentHelper extends Component {
    
    const KICKASSTORRENTS = 'https://kat.cr';
    
    public static function find($title){
        
        return self::getFromKickAssTorrents($title);
        
    }
    
    public static function getFromKickAssTorrents($title){
        
        $url = self::KICKASSTORRENTS.'/usearch/'.urlencode($title).'%20category%3Amovies/?field=seeders&sorder=desc&rss=1';
        
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

        $data = curl_exec($curl);
        curl_close($curl);
        
        $feed = str_replace('torrent:','',$data);
        
        libxml_use_internal_errors(true);
        try{
            $rss = simplexml_load_string($feed);
        } catch (Exception $e) {
            echo 'could not load:'.$url."<br/>\r\n";
            exit;
        }
        
        $torrents = [];

        if(!empty($rss)){
        
            foreach ($rss->channel->item as $item) {

                $torrent = new torrent();
                $torrent->url = (string) $item->link;
                $torrent->title = (string) $item->title;
                $torrent->site = (string) 'KickAss Torrents';
                $torrent->rating = 0;
                $torrent->peers = (string) $item->peers;
                $torrent->seeds = (string) $item->seeds;
                $torrent->size = (string) $item->contentLength;
                $torrent->age = date('Y-m-d H:i:s',strtotime((string) $item->pubDate));
                $torrent->magnet = (string) $item->magnetURI;
                $torrent->torrentfile = (string) $item->enclosure['url'];
                $torrents[] = $torrent;

            }
            
        }
        
        return $torrents;
        
    }
    
}