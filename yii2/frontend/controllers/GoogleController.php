<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
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
use \Google_Client;
use \filter_input;
use \header;
use \filter_var;

/**
 * Google controller
 * @author Bas van Beers
 * @copyright (c) 2016, Bas van Beers
 * @const string oauthredirectpath
 */
class GoogleController extends BaseController{

    /** @const oauthredirectpath  default url for the oauthcallback */
    const oauthredirectpath = '/google/oauth2callback';
    
    /**
     * Adds the right scopes to the Request
     * @param \Google_Client $client
     * @return \Google_Client
     */
    protected function addScopes(\Google_Client $client){
        
        if(GoogleAPIHelper::isEnabled()){
            if(GoogleContactsHelper::isEnabled()){
                $client->addScope(GoogleContactsHelper::SCOPE);
            }
            if(GoogleCalendarHelper::isEnabled()){
                $client->addScope(GoogleCalendarHelper::SCOPE);
            }
            if(GoogleDriveHelper::isEnabled()){
                $client->addScope(GoogleDriveHelper::SCOPE);
            }
            if(GoogleMailHelper::isEnabled()){
                $client->addScope(GoogleMailHelper::SCOPE);
            }
            $client->addScope('https://www.googleapis.com/auth/userinfo.email');
            $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
            if(GooglePlusHelper::isEnabled()){
                $client->addScope(GooglePlusHelper::SCOPE);
            }
        }
        return $client;
        
    }
    
    /**
     * Does an request for the google OAuth2 settings
     */
    public function actionAuthorize(){
        
        session_start();
		
        /** @var \Google_Client $client */
	$client = new \Google_Client();
	$client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
	$client = $this->addScopes($client);
        $client->setAccessType('offline');

        /** @var string $redirect_uri */
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . self::oauthredirectpath;
        \header('Location: ' . \filter_var($redirect_uri, \FILTER_SANITIZE_URL));
        
        exit;  
        
    }

    /**
     * The Oauth2 Callback for Google Redirection
     */
    public function actionOauth2callback(){
        
        if(!GoogleAPIHelper::isEnabled()){
            
            $redirect_uri = 'http://' . \filter_input(\INPUT_SERVER, 'HTTP_HOST',\FILTER_FLAG_NO_ENCODE_QUOTES) . '/';
            \header('Location: ' . $redirect_uri);
            
        }
        
        session_start();

        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . self::oauthredirectpath);
        $client = $this->addScopes($client);
        $client->setAccessType('offline');

        /** @var string $code */
        $code = \filter_input(\INPUT_GET,'code');
        
        if (empty($code) || is_null($code)) {
            /** @var string $auth_url */
            $auth_url = $client->createAuthUrl();
            
            echo 'I did not get any code!';
            var_dump($code);
            
            exit;
            \header('Location: ' . \filter_var($auth_url, \FILTER_SANITIZE_URL));
        } else {
            $client->authenticate($code);
            /** @var string $_SESSION['access_token'] */
            $_SESSION['access_token'] = $client->getAccessToken();
            
            /** @var string $access_token */
            $access_token = json_decode($client->getAccessToken())->access_token;
            
            /** @var object $GoogleUserInfo */
            $GoogleUserInfo = $this->getUserInfo($client);
            
            /** @var GoogleUser $GoogleUser */
            $GoogleUser = GoogleUser::find()->where( [ 'google_id' => $GoogleUserInfo->id ] )->one();
            
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
                if(!GoogleContactsHelper::isEnabled()){
                    $GoogleUser->contacts = true;
                }else{
                    $GoogleUser->contacts = false;
                }
                if(!GoogleCalendarHelper::isEnabled()){
                    $GoogleUser->calendar = true;
                }else{
                    $GoogleUser->calendar = false;
                }
                if(!GoogleDriveHelper::isEnabled()){
                    $GoogleUser->drive = true;
                }else{
                    $GoogleUser->drive = false;
                }
                if(!GoogleMailHelper::isEnabled()){
                    $GoogleUser->mail = true;
                }else{
                    $GoogleUser->mail = false;
                }
                if(!GooglePlusHelper::isEnabled()){
                    $GoogleUser->plus = true;
                }else{
                    $GoogleUser->plus = true;
                }
                $GoogleUser->save();
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
            $redirect_uri = 'http://' . \filter_input(\INPUT_SERVER, 'HTTP_HOST') . '/google/index';
            \header('Location: ' . $redirect_uri);
        }
        
        exit;
        
    }
    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex(){
		
        return $this->render('index');
    }
    
    public function actionContacts(){
        
        if(!GoogleAPIHelper::isEnabled()){
            
            $redirect_uri = 'http://' . \filter_input(\INPUT_SERVER, 'HTTP_HOST',\FILTER_FLAG_NO_ENCODE_QUOTES) . '/';
            \header('Location: ' . \filter_var($redirect_uri, \FILTER_SANITIZE_URL));
            
        }
        
        /** @var \Google_Client $client */
        $client = new \Google_Client();
	$client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setAccessType('offline');
        
        /** @var User $currentUser */
        $currentUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        
        /** @var GoogleUser $GoogleUser */
        $GoogleUser = $currentUser->googleUsers[0];
        
        $client->refreshToken($GoogleUser->access_token);
        
        $GoogleUser->access_token = json_decode($client->getAccessToken())->access_token;;
        
        $contacts = GoogleContactsHelper::getAllContacts($GoogleUser);
                
    }
    
    /**
     * Retrieves info about the Google User
     * @param \Google_Client $client
     * @return type
     */
    protected function getUserInfo(\Google_Client $client){
        
        $access_token = json_decode($client->getAccessToken())->access_token;
        
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?oauth_token='.$access_token;

        $response =  file_get_contents($url);
            
        $j = json_decode($response);
        
        return $j;
        
    }
    
}
