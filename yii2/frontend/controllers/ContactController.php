<?php

    namespace frontend\controllers;

    use Yii;
    use frontend\controllers\BaseController;
    use common\models\User;
    use yii\helpers\Url;
    use \common\models\Contact;
    use yii\base\UserException;

    /**
     * ContactController
     * @author Bas van Beers
     * @copyright (c) 2016, Bas van Beers
     * 
     * @property string $defaultAction
     * @property string $modelClass
     * 
     */
    class ContactController extends BaseController{

        
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
                    'merge',
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
         * Index of contacts
         * @return type
         */
        public function actionIndex(){

            \Yii::$app->params['sidebaritems'] = [
                [
                    'label' => \Yii::t('app', 'Add'),
                    'icon'  => 'fa-plus',
                    'class' => 'showModalButton',
                    'title' => \Yii::t('app', 'Add'),
                    'value' => Url::to(['/contact/create']),
                ],
            ];
            
            $user = User::findOne(Yii::$app->user->identity->id);
            
            $contacts = $user->contacts;

            return $this->render('index', [
                'contacts' => $contacts,
            ]);

        }
        
        /**
         * Creates devices
         * @return redirect
         * @throws UserException
         */
        public function actionCreate(){
            
            /** @var Contact $model */
            $model = new $this->modelClass;
            
            /** @var array $postvalues */
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['Contact'])){
                
                /** @var Contact $contact */
                $contact = new Contact();
                
                $contact->creator = \Yii::$app->user->identity->id;
                $contact->created_at = date("Y-m-d H:i:s");
                
                $contact->attributes = $postvalues['Contact'];
                if(!$contact->save()){
                    throw new UserException('Could not save the Contact!'.print_r($contact));
                }
                \Yii::info('New Contact['.$contact->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
                /** @var UserContactsLink $UserContactsLink */
                $UserContactsLink = new UserDevices();
                $UserContactsLink->device_id = $contact->id;
                $UserContactsLink->user_id = $contact->creator;
                $UserContactsLink->updated_at = $contact->updated_at;
                $UserContactsLink->created_at = $contact->created_at;
                $UserContactsLink->creator = $contact->creator;
                
                if(!$UserContactsLink->save()){
                    throw new UserException('Could not save the UserContactsLink!'.print_r($UserDevices));
                }
                \Yii::info('New UserContactsLink['.$UserContactsLink->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
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

            /** @var Contact $model */
            $model = new $this->modelClass;

            /** @var Contact $Contact */
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
        public function actionView($id = null){
            
            /** @var Contact $model */
            $model = new $this->modelClass;

            /** @var Contact $Contact */
            $Device = $model::findOne($id);
            
            return $this->render('view', [
                'model' => $model,
            ]);
            
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