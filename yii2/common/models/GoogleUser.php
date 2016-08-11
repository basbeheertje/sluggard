<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\GoogleAPIHelper;
use common\models\UserGoogleLink;
use \yii\web\ForbiddenHttpException;

/**
 * Description of GoogleUser
 * @since 31-03-2016
 * @author Bas van Beers
 */
class GoogleUser extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%google_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [
                [
                    'google_id',
                    'email',
                    'name',
                    'given_name',
                    'family_name',
                    'auth_code',
                    'access_token',
                    'contacts',
                    'calendar',
                    'drive',
                    'mail',
                    'plus',
                    'password',
                    'creator',
                    'created_at',
                    'updated_at'
                ],
                'required'
            ],
            [
                [
                    'name',
                    'given_name',
                    'family_name',
                ],
                'safe'
            ],
            [
                [
                    'contacts',
                    'calendar',
                    'drive',
                    'mail',
                    'plus',
                ],
                'boolean'
            ],
            [
                [
                    'google_id',
                    'email',
                    'auth_code',
                    'access_token'
                ],
                'unique'
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getId(){
        return $this->getPrimaryKey();
    }
    
    /**
     * This function encrypts the fields password, auth_code and access_token
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert){
        
        $secretKey = \Yii::$app->params['security']['basic'];
        $this->password = base64_encode(Yii::$app->getSecurity()->encryptByPassword($this->password, $secretKey));
        $this->auth_code = base64_encode(Yii::$app->getSecurity()->encryptByPassword($this->auth_code, $secretKey));
        $this->access_token = base64_encode(Yii::$app->getSecurity()->encryptByPassword($this->access_token, $secretKey));
        
        parent::beforeSave($insert);
        
        return true;
        
    }
    
    /**
     * This function decrypts the fields password, auth_code, access_token
     */
    public function afterFind() {
        
        $secretKey = \Yii::$app->params['security']['basic'];
        
        $this->password = \Yii::$app->getSecurity()->decryptByPassword(base64_decode($this->password), $secretKey);
        $this->auth_code = \Yii::$app->getSecurity()->decryptByPassword(base64_decode($this->auth_code), $secretKey);
        $this->access_token = \Yii::$app->getSecurity()->decryptByPassword(base64_decode($this->access_token), $secretKey);
        
        parent::afterFind();
    }

    public function refreshToken(){
        
        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client = GoogleAPIHelper::addScopes($client);
        $client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setAccessType('offline');
        
        $client->setAccessToken($this->access_token);
        
        if($client->isAccessTokenExpired()) {
            //$client->authenticate();
            $NewAccessToken = json_decode($client->getAccessToken());
            //$NewAccessToken = json_decode($client->getAccessToken());
            $client->refreshToken($NewAccessToken->refresh_token);
            //$client->refreshToken($client->getAccessToken());            
            $this->access_token = $client->getAccessToken();
            $this->save();
        }
        
    }
    
    public function getGoogleClientObject(){
        
        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client = GoogleAPIHelper::addScopes($client);
        $client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setAccessType('offline');
        
        $client->setAccessToken($this->access_token);
        
        $this->refreshToken();
        
        return $client;
        
    }
   
    public function refreshProfileInfo(){
        
        /** @var \Google_Client $client */
        $client = new \Google_Client();
        $client = GoogleAPIHelper::addScopes($client);
        $client->setAuthConfigFile(\Yii::$app->params['google']['AuthConfigFile']);
        $client->setAccessType('offline');
        
        $client->setAccessToken($this->access_token);
        
        $this->refreshToken();
        
        $GoogleUserInfo = GoogleAPIHelper::getUserInfo($client);
        
        $this->name         = $GoogleUserInfo->name;
        $this->given_name   = $GoogleUserInfo->given_name;
        $this->family_name  = $GoogleUserInfo->family_name;
        $this->picture      = $GoogleUserInfo->picture;
        $this->locale       = $GoogleUserInfo->locale;
        $this->save();
        
    }
    
    public function hasRights($user_id = null,$google_user_id = null){
        
        if(is_null($user_id)){
            $user_id = Yii::$app->user->identity->id;
        }
        
        if(is_null($google_user_id)){
            $google_user_id = $this->id;
        }
        
        $allowed = UserGoogleLink::find()
            ->where([
                'user_id' => $user_id,
                'google_user_id' => $google_user_id
            ])
            ->one();
        
        if($allowed){
            return true;
        }
        
        return false;
        
    }
    
    /**
     * Retrieves all users for an Google user
     * @return type
     */
    public function getUsers(){
        
        /** @var array $Users */
        $Users = array();
        
        /** @var UserGoogleLink[] $GoogleUserList */
        $GoogleUserList = $this->userGoogleLink;
        
        if(!empty($GoogleUserList)){
            foreach($GoogleUserList as $UserGoogleLink){
                $Users[] = $UserGoogleLink->user;
            }
        }
        
        return $Users;
        
    }
    
    /**
     * @return UserGoogleLink[]
     */
    public function getUserGoogleLink(){
        return $this->hasMany(UserGoogleLink::className(), ['google_user_id' => 'id']);
    }
    
}
