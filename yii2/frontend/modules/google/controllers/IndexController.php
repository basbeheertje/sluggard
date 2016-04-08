<?php

namespace frontend\modules\google\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\controllers\BaseController;
use common\models\GoogleUser;
use common\models\UserGoogleLink;
use common\models\User;
use common\components\GoogleAPIHelper;
use common\components\GoogleCalendarHelper;
use common\components\GoogleContactsHelper;
use common\components\GoogleDriveHelper;
use common\components\GoogleMailHelper;
use common\components\GooglePlusHelper;
use common\components\GoogleHistoryAPIHelper;
use \Google_Client;
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
                'update',
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
        \Yii::$app->params['sidebaritems'] = [
            [
                'label' => \Yii::t('app', 'Add'),
                'icon'  => 'fa-plus',
                'class' => 'showModalButton',
                'title' => \Yii::t('app', 'Add'),
                'value' => Url::to(['/google/register/create']),
            ],
        ];
    }
    
    public function actionIndex(){
        
        /** @var User $currentUser */
        $currentUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        
        /** @var GoogleUser $GoogleUser */
        $GoogleUsers = $currentUser->googleUsers;
        
        return $this->render('index', [
            'GoogleUsers' => $GoogleUsers,
        ]);
        
    }
    
    public function actionUpdate(){
        
        \Yii::$app->response->format = 'json';
        
        /** @var array $postvalues */
        $postvalues = Yii::$app->request->post();
            
        if(!isset($postvalues['GoogleUser'])){
            
            return ['saved'=>false,'message'=>'No GoogleUser defined!'];
            
        }
        
        if(!isset($postvalues['GoogleUser']['id'])){
            
            return ['saved'=>false,'message'=>'GoogleUser ID is not defined!'];
            
        }
        
        /** @var integer $GoogleUserId */
        $GoogleUserId = (int) $postvalues['GoogleUser']['id'];
                
        if($GoogleUserId <= 0){
                    
            return ['saved'=>false,'message'=>'GoogleUser ID is smaller or equals to zero!'];
                    
        }
        
        /** @var GoogleUser $model */
        $model = new $this->modelClass;
        
        /** @var GoogleUser $GoogleUser */
        $GoogleUser = $model::findOne($GoogleUserId);
        
        if(!$GoogleUser->hasRights()){
            return ['saved'=>false,'message'=>'You are not allowed to update this GoogleUser!'];
        }
        
        foreach($postvalues['GoogleUser'] as $key => $value){
            $GoogleUser->$key = $value;
        }
        
        $GoogleUser->updated_at = date('Y-m-d H:i:s');
        if(!$GoogleUser->save()){
            return ['saved'=>true,'message'=>'Could not save!'];
        }    
        
        return ['saved'=>true,'message'=>'Saved the update!'];
        
    }
    
}