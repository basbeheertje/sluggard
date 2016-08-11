<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\DeviceType;
    use yii\base\UserException;

    /**
     * DeviceTypesController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     */
    class DevicetypesController extends BaseController{

        public $defaultAction = 'index';
        public $modelClass = '\common\models\DeviceType';

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
                    'value' => Url::to(['/devicetypes/create']),
                ],
                [
                    'label' => \Yii::t('app', 'Devices'),
                    'icon'  => 'fa-device',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Devices'),
                    'url' => Url::to(['/device/index']),
                ],
            ];
            
            $devicetypes = DeviceType::find()->all();

            return $this->render('index', [
                'devicetypes' => $devicetypes,
            ]);

        }
        
        public function actionCreate(){
            
            /** @var DeviceType $model */
            $model = new $this->modelClass;
            
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['DeviceType'])){
                
                /** @var DeviceType $deviceType */
                $deviceType = new DeviceType();
                
                $deviceType->creator = \Yii::$app->user->identity->id;
                $deviceType->created_at = date("Y-m-d H:i:s");
                
                $deviceType->attributes = $postvalues['DeviceType'];
                if(!$deviceType->save()){
                    throw new UserException('Could not save the DeviceType!'.print_r($deviceType));
                }
                \Yii::info('New DeviceType['.$deviceType->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
                return $this->redirect(['index']);
                
            }else{
                
                if(Yii::$app->request->isAjax){
                    return $this->renderAjax('create', [
                        'model' => $model,
                    ]);
                }
                
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            
        }
        
        public function actionUpdate(){
            
            \Yii::$app->response->format = 'json';
        
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();

            if(!isset($postvalues['DeviceType'])){

                return ['saved'=>false,'message'=>'No DeviceType defined!'];

            }

            if(!isset($postvalues['DeviceType']['id'])){

                return ['saved'=>false,'message'=>'DeviceType ID is not defined!'];

            }

            /** @var integer $DeviceTypeId */
            $DeviceTypeId = (int) $postvalues['DeviceType']['id'];

            if($DeviceTypeId <= 0){

                return ['saved'=>false,'message'=>'DeviceType ID is smaller or equals to zero!'];

            }

            /** @var Device $model */
            $model = new $this->modelClass;

            /** @var Device $Device */
            $DeviceType = $model::findOne($DeviceTypeId);

            foreach($postvalues['DeviceType'] as $key => $value){
                $DeviceType->$key = $value;
            }

            $DeviceType->updated_at = date('Y-m-d H:i:s');
            if(!$DeviceType->save()){
                return ['saved'=>true,'message'=>'Could not save!'];
            }    

            return ['saved'=>true,'message'=>'Saved the update!'];
            
        }

        public function actionView(){
            
            
            
        }
        
        public function actionDelete($id = null){
            
            if(!is_null($id)){
                
                /** @var Device $model */
                $model = new $this->modelClass;
                
                /** @var DeviceType $DeviceType */
                $DeviceType = $model::findOne($id);
                
                $DeviceType->delete();
                
            }
            
            return $this->redirect(['index']);
            
        }
        
    }