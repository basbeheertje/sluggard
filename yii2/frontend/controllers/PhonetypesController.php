<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\PhoneTypes;
    use yii\base\UserException;

    /**
     * PhoneTypesController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     */
    class PhonetypesController extends BaseController{

        public $defaultAction = 'index';
        public $modelClass = '\common\models\PhoneTypes';

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
                    'value' => Url::to(['/phonetypes/create']),
                ],
                [
                    'label' => \Yii::t('app', 'Phonetypes'),
                    'icon'  => 'fa-phone',
                    //'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Phonetypes'),
                    'url' => Url::to(['/phonetypes/index']),
                ],
            ];
            
            $phonetypes = PhoneTypes::find()->all();

            return $this->render('index', [
                'devicetypes' => $devicetypes,
            ]);

        }
        
        public function actionCreate(){
            
            /** @var DeviceType $model */
            $model = new $this->modelClass;
            
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['PhoneTypes'])){
                
                /** @var PhoneTypes $phoneType */
                $phoneType = new DeviceType();
                
                $phoneType->creator = \Yii::$app->user->identity->id;
                $phoneType->created_at = date("Y-m-d H:i:s");
                
                $phoneType->attributes = $postvalues['PhoneTypes'];
                if(!$phoneType->save()){
                    throw new UserException('Could not save the PhoneTypes!'.print_r($phoneType));
                }
                \Yii::info('New PhoneTypes['.$phoneType->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
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

            if(!isset($postvalues['PhoneTypes'])){

                return ['saved'=>false,'message'=>'No PhoneTypes defined!'];

            }

            if(!isset($postvalues['PhoneTypes']['id'])){

                return ['saved'=>false,'message'=>'PhoneTypes ID is not defined!'];

            }

            /** @var integer $PhoneTypeId */
            $PhoneTypeId = (int) $postvalues['PhoneTypes']['id'];

            if($PhoneTypeId <= 0){

                return ['saved'=>false,'message'=>'PhoneTypes ID is smaller or equals to zero!'];

            }

            /** @var Device $model */
            $model = new $this->modelClass;

            /** @var Device $Device */
            $PhoneType = $model::findOne($PhoneTypeId);

            foreach($postvalues['PhoneTypes'] as $key => $value){
                $PhoneType->$key = $value;
            }

            $PhoneType->updated_at = date('Y-m-d H:i:s');
            if(!$PhoneType->save()){
                return ['saved'=>true,'message'=>'Could not save!'];
            }    

            return ['saved'=>true,'message'=>'Saved the update!'];
            
        }

        public function actionView(){
            
            
            
        }
        
        public function actionDelete($id = null){
            
            if(!is_null($id)){
                
                /** @var PhoneTypes $model */
                $model = new $this->modelClass;
                
                /** @var Device $Device */
                $PhoneType = $model::findOne($id);
                
                $PhoneType->delete();
                
            }
            
            return $this->redirect(['index']);
            
        }
        
    }