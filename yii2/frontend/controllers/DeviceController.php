<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\DeviceType;
    use \common\models\Device;
    use \common\models\UserDevice;
    use yii\base\UserException;

    /**
     * DeviceController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     * 
     * @property string $defaultAction
     * @property string $modelClass
     * 
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

        /**
         * Index of devices
         * @return type
         */
        public function actionIndex(){

            \Yii::$app->params['sidebaritems'] = [
                [
                    'label' => \Yii::t('app', 'Add'),
                    'icon'  => 'fa-plus',
                    'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Add'),
                    'value' => Url::to(['/device/create']),
                ],
                [
                    'label' => \Yii::t('app', 'Device Types'),
                    'icon'  => 'fa-device',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Device Types'),
                    'url' => Url::to(['/devicetypes/index']),
                ],
            ];
            
            $user = User::findOne(Yii::$app->user->identity->id);
            
            $devices = $user->devices;

            return $this->render('index', [
                'devices' => $devices,
            ]);

        }
        
        /**
         * Creates devices
         * @return redirect
         * @throws UserException
         */
        public function actionCreate(){
            
            /** @var Device $model */
            $model = new $this->modelClass;
            
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['Device'])){
                
                /** @var Device $device */
                $device = new Device();
                
                $device->creator = \Yii::$app->user->identity->id;
                $device->created_at = date("Y-m-d H:i:s");
                
                $device->attributes = $postvalues['Device'];
                if(!$device->save()){
                    throw new UserException('Could not save the Device!'.print_r($device));
                }
                \Yii::info('New Device['.$device->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
                /** @var UserDevices $UserDevices */
                $UserDevices = new UserDevices();
                $UserDevices->device_id = $device->id;
                $UserDevices->user_id = $device->creator;
                $UserDevices->updated_at = $device->updated_at;
                $userDevices->created_at = $device->created_at;
                $UserDevices->creator = $device->creator;
                
                if(!$UserDevices->save()){
                    throw new UserException('Could not save the UserDevice!'.print_r($UserDevices));
                }
                \Yii::info('New UserDevice['.$UserDevices->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
                return $this->redirect(['index']);
                
            }else{
            
                /** @var DeviceType[] $device_types */
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
        
        /**
         * Updates devices
         * @return redirect
         */
        public function actionUpdate(){
            
            \Yii::$app->response->format = 'json';
        
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();

            if(!isset($postvalues['Device'])){

                return ['saved'=>false,'message'=>'No Device defined!'];

            }

            if(!isset($postvalues['Device']['id'])){

                return ['saved'=>false,'message'=>'Device ID is not defined!'];

            }

            /** @var integer $DeviceId */
            $DeviceId = (int) $postvalues['Device']['id'];

            if($DeviceId <= 0){

                return ['saved'=>false,'message'=>'Device ID is smaller or equals to zero!'];

            }

            /** @var Device $model */
            $model = new $this->modelClass;

            /** @var Device $Device */
            $Device = $model::findOne($DeviceId);

            if(!$Device->hasRights()){
                return ['saved'=>false,'message'=>'You are not allowed to update this Device!'];
            }

            foreach($postvalues['Device'] as $key => $value){
                $Device->$key = $value;
            }

            $Device->updated_at = date('Y-m-d H:i:s');
            if(!$Device->save()){
                return ['saved'=>true,'message'=>'Could not save!'];
            }    

            return ['saved'=>true,'message'=>'Saved the update!'];
            
        }

        /**
         * Show information about an device
         */
        public function actionView(){
            
            
            
        }
        
        /**
         * Deletes devices
         * @param int $id
         * @return type
         */
        public function actionDelete($id = null){
            
            if(!is_null($id)){
                
                /** @var Device $model */
                $model = new $this->modelClass;
                
                /** @var Device $Device */
                $Device = $model::findOne($id);
                
                /** @var UserDevices[] $UserDevices */
                $UserDevices = $Device->userDevices;
                
                foreach($UserDevices as $UserDevice){
                    
                    $UserDevice->delete();
                    
                }
                
                $Device->delete();
                
            }
            
            return $this->redirect(['index']);
            
        }
        
    }