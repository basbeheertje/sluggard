<?php

namespace frontend\modules\imdb\cron\tasks;

use console\modules\cron\components\core\CronTask;
use frontend\modules\imdb\components\IMDBHelper;
use frontend\modules\imdb\models\movie;
use frontend\modules\imdb\models\torrent;
use frontend\modules\imdb\models\video;

class IMDBtop250 extends CronTask {
    
    /**
     * We only want this task to be executed on a non-cron server.
     * @var bool When true it will only execute when the cron-module is NOT installed as cron-server.
     */
    public $normalServerOnly = true;

    function execute() {
        
        $cacheDir = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache').DIRECTORY_SEPARATOR;
        
        $TMPmovies = IMDBHelper::getTop250();
        
        foreach($TMPmovies as $TMPmovie){
            
            $cachedName = $cacheDir.$TMPmovie['id'].'.txt';
            
            if(!file_exists($cachedName)){
                
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
                
                $movie->getSubtitles();
                
                $movie->getTrailers();
                
                $objData = serialize($movie);
                
                file_put_contents($cachedName, $objData);
            
            }
            
        }
        
    }
    
}