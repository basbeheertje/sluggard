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
use \filter_input;
use \header;
use \filter_var;

class RegisterController extends BaseController {

    /** @var string $modelClass */
    public $modelClass = 'common\models\GoogleUser';
    
    /** @const oauthredirectpath  default url for the oauthcallback */
    const oauthredirectpath = '/google/register/oauth2callback';
    
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
                'oauth2callback',
            ],
            'allow'     => true,
            'roles'     => ['@'],
        ];
        
        return $behaviors;
        
    }
    
    public function actions() {
        return array_merge(parent::actions(), [
            'view' => [
                'class' => 'api\modules\google\views\register\ViewAction',
		'modelClass' => $this->modelClass,
            ],
            'update' => [
		'class' => 'api\modules\google\views\register\UpdateAction',
		'modelClass' => $this->modelClass,
            ],
            'delete' => [
		'class' => 'api\modules\google\views\register\DeleteAction',
		'modelClass' => $this->modelClass,
            ],
        ]);
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
    
    public function actionCreate($id = null){
        
        /** @var User $currentUser */
        $currentUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        
        /** @var GoogleUser $model */
        $model = new $this->modelClass;
        
        if(!is_null($id)){
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => (string) $model->_id]);
            }
        }else if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model
            ]);
        }else{
            
            $postvalues = Yii::$app->request->post();
            
            if(isset($postvalues['GoogleUser'])){
                
                if($postvalues['GoogleUser']['email'] && $postvalues['GoogleUser']['password']){
                    
                    $_SESSION['create']['GoogleUser'] = $postvalues['GoogleUser'];
                    
                    return $this->redirect(['oauth2callback']);
                    
                }
                
            }
            
        }
        
        return $this->redirect(['index']);
        
    }
    
    /**
     * The Oauth2 Callback for Google Redirection
     */
    public function actionOauth2callback(){
        
        if(!GoogleAPIHelper::isEnabled()){
            
            $redirect_uri = 'http://' . \filter_input(\INPUT_SERVER, 'HTTP_HOST',\FILTER_FLAG_NO_ENCODE_QUOTES) . '/';
            \header('Location: ' . $redirect_uri);
            
        }
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . self::oauthredirectpath);
        $client = GoogleAPIHelper::addScopes($client);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        /** @var string $code */
        $code = \filter_input(\INPUT_GET,'code');
        
        if (empty($code) || is_null($code)) {
            /** @var string $auth_url */
            $auth_url = $client->createAuthUrl();
            
            \header('Location: ' . \filter_var($auth_url, \FILTER_SANITIZE_URL));
            exit;
            
        } else {
            $client->authenticate($code);
            
            /** @var string $_SESSION['access_token'] */
            $_SESSION['access_token'] = $client->getAccessToken();
            
            /** @var string $access_token */
            $access_token = $client->getAccessToken();//json_decode($client->getAccessToken());//json_decode($client->getAccessToken())->access_token;
            
            /** @var object $GoogleUserInfo */
            $GoogleUserInfo = GoogleAPIHelper::getUserInfo($client);
            
            /** @var GoogleUser $GoogleUser */
            $GoogleUser = GoogleUser::find()->where( [ 'google_id' => $GoogleUserInfo->id ] )->one();
            
            /** @var array $originalGoogleUser */
            $originalGoogleUser = $_SESSION['create']['GoogleUser'];
            
            if($originalGoogleUser['email'] !== $GoogleUserInfo->email){
                throw new UserException('Email addresses do not match!');
            }
            
            if(!$GoogleUser){
                $GoogleUser = new GoogleUser();
                $GoogleUser->google_id = $GoogleUserInfo->id;
                $GoogleUser->email = $GoogleUserInfo->email;
                $GoogleUser->name = $GoogleUserInfo->name;
                $GoogleUser->given_name = $GoogleUserInfo->given_name;
                $GoogleUser->family_name = $GoogleUserInfo->family_name;
                $GoogleUser->auth_code = $code;
                $GoogleUser->access_token = $access_token;
                $GoogleUser->picture = $GoogleUserInfo->picture;
                $GoogleUser->locale = $GoogleUserInfo->locale;
                $GoogleUser->creator = \Yii::$app->user->identity->id;
                if(GoogleContactsHelper::isEnabled()){
                    $GoogleUser->contacts = true;
                }else{
                    $GoogleUser->contacts = false;
                }
                if(GoogleCalendarHelper::isEnabled()){
                    $GoogleUser->calendar = true;
                }else{
                    $GoogleUser->calendar = false;
                }
                if(GoogleDriveHelper::isEnabled()){
                    $GoogleUser->drive = true;
                }else{
                    $GoogleUser->drive = false;
                }
                if(GoogleMailHelper::isEnabled()){
                    $GoogleUser->mail = true;
                }else{
                    $GoogleUser->mail = false;
                }
                if(GooglePlusHelper::isEnabled()){
                    $GoogleUser->plus = true;
                }else{
                    $GoogleUser->plus = true;
                }
                if(GoogleHistoryAPIHelper::isEnabled()){
                    $GoogleUser->location = true;
                }else{
                    $GoogleUser->location = true;
                }
                $GoogleUser->created_at = date('Y-m-d H:i:s');
                $GoogleUser->updated_at = date('Y-m-d H:i:s');
                    
                $GoogleUser->password = $originalGoogleUser['password'];
                
                if(!$GoogleUser->save()){
                    throw new UserException('Could not save the GoogleUser!'.print_r($GoogleUser));
                }
                \Yii::info('New Google Account['.$GoogleUser->id.']{'.\Yii::$app->user->identity->id.'}', 'frontend');
                
            }
            
            /** @var UserGoogleLink $UserGoogleLink */
            $UserGoogleLink = UserGoogleLink::find()->where( [ 
                'google_user_id' => $GoogleUser->id,
                'user_id' => \Yii::$app->user->identity->id
            ] )->one();
            
            if(!$UserGoogleLink){
                $UserGoogleLink = new UserGoogleLink();
                $UserGoogleLink->google_user_id = $GoogleUser->id;
                $UserGoogleLink->user_id = \Yii::$app->user->identity->id;
                $UserGoogleLink->save();
            }
            
            /** @var string $redirect_uri */
            $redirect_uri = 'http://' . \filter_input(\INPUT_SERVER, 'HTTP_HOST') . '/google/register/index';
            return $this->redirect(['index']);
        }
        
    }
    
}
