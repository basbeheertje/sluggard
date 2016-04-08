<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\DeviceType;
    use \common\models\Device;

    /**
     * DeviceController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     */
    class DeviceController extends BaseController{

        public $defaultAction = 'index';
        public $modelClass = '\common\models\Device';

        /**
        * @inheritdoc
        */
       public function behaviors(){

            /** @var array $behaviors */
            $behaviors = parent::behaviors();
            $behaviors['access']['rules'][] = [
                'actions' => [
                    'index',
                    'create',
                    'view',
                    'update',
                    'delete',
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
            
            parent::init();

        }

        public function actionIndex(){

            \Yii::$app->params['sidebaritems'] = [
                [
                    'label' => \Yii::t('app', 'Add'),
                    'icon'  => 'fa-plus',
                    'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Add'),
                    'value' => Url::to(['/device/create']),
                ],
            ];
            
            $user = User::findOne(Yii::$app->user->identity->id);
            
            $devices = $user->devices;

            return $this->render('index', [
                'devices' => $devices,
            ]);

        }
        
        public function actionCreate(){
            
            /** @var GoogleUser $model */
            $model = new $this->modelClass;
            
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['Device'])){
                
                
                
            }else{
            
                $device_types = DeviceType::find()->all();
                
                if(Yii::$app->request->isAjax){
                    return $this->renderAjax('create', [
                        'model' => $model,
                        'device_types' => $device_types,
                    ]);
                }
                
                return $this->render('create', [
                    'model' => $model,
                    'device_types' => $device_types,
                ]);
            }
            
        }
        
        public function actionUpdate(){
            
            
            
        }

        public function actionView(){
            
            
            
        }
        
        public function actionDelete(){
            
            
            
        }
        
    }