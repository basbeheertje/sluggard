<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\Movie;
    use yii\base\UserException;

    /**
     * MovieController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     */
    class MovieController extends BaseController{

        public $defaultAction = 'index';
        public $modelClass = '\common\models\Movie';

        /**
        * @inheritdoc
        */
       public function behaviors(){

            /** @var array $behaviors */
            $behaviors = parent::behaviors();
            $behaviors['access']['rules'][] = [
                'actions' => [
                    'index',
                    'genre',
                    'actor',
                    'director',
                    'search',
                ],
                'allow'     => true,
                'roles'     => ['@'],
            ];

            return $behaviors;

        }
        
        /**
        * @inheritdoc
        */
        public function init(){
            
            \Yii::$app->params['sidebaritems'] = [
                [
                    'label' => \Yii::t('app', 'Genres'),
                    'icon'  => 'fa-device',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Genres'),
                    'url' => Url::to(['/movie/genre']),
                ],
                [
                    'label' => \Yii::t('app', 'IMDB'),
                    'icon'  => 'fa-device',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'IMDB'),
                    'url' => Url::to(['/imdb/index/index']),
                ],
                [
                    'label' => \Yii::t('app', 'Torrents'),
                    'icon'  => 'fa-device',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Torrents'),
                    'url' => Url::to(['/imdb/torrents/index']),
                ],
            ];
            
            parent::init();

        }

        public function actionIndex(){
            
            $model = Movie::find()->all();

            return $this->render('index', [
                'model' => $model,
            ]);

        }
        
        public function actionSearch(){}
        
        public function actionGenre(){
            
            
            
        }
        
        public function actionActor(){
            
            
            
        }
        
        public function actionDirector(){
            
            
            
        }
        
    }
    
?>