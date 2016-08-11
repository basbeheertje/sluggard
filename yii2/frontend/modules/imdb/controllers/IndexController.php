<?php

namespace frontend\modules\imdb\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\controllers\BaseController;
use frontend\modules\imdb\components\IMDBHelper;
use frontend\modules\imdb\components\MovieMeterHelper;
use yii\helpers\Url;

class IndexController extends BaseController {

    /** @var string $modelClass */
    public $modelClass = 'common\models\GoogleUser';
    
    /**
     * @inheritdoc
     */
    public function behaviors(){
        
        /** @var array $behaviors */
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][] = [
            'actions' => [
                'index',
            ],
            'allow'     => true,
            'roles'     => ['@'],
        ];
        
        return $behaviors;
        
    }
        
    public function actionIndex(){
        
        set_time_limit(300);
                
        /** @var array $movieMeterRecentMovies */
        //$movieMeterCinemaMovies = MovieMeterHelper::scrape('http://www.moviemeter.nl/cinema/now/');
        
        /** @var array $IMDBmovies */
        //$IMDBmovies = IMDBHelper::getMovies();
        
        //$movies = array_merge($movieMeterCinemaMovies, $IMDBmovies);
        
        $cacheDir = realpath(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        
        $moviefiles = scandir($cacheDir);
        
        $movies = [];
        
        foreach($moviefiles as $movie){
            
            if(!in_array($movie,array(".",".."))){            
                $objData = file_get_contents($cacheDir.$movie);
                $movie = unserialize($objData);
                $movies[] = $movie;
            }
            
        }
        
        return $this->render('index', [
            'movies' => $movies,
        ]);
        
    }
    
}