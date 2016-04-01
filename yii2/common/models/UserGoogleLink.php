<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Description of GoogleUserLink
 * Class UserGoogleLink
 * @since 31-03-2016
 * @author Bas van Beers
 * 
 * @property int $id Identifier of the row
 * @property int $google_user_id Relation with GoogleUser
 * @property int $user_id Relation with User
 * @property GoogleUser $googleUser
 * @property User $user
 */
class UserGoogleLink extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_google_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'google_user_id',
                    'user_id',
                ],
                'required'
            ],
            [
                [
                    'google_user_id',
                    'user_id',
                ],
                'integer'
            ],
            [
                ['google_user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => GoogleUser::className(),
                'targetAttribute' => ['google_user_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'                         => 'ID',
            'google_user_id'             => 'google_user_id ID',
            'user_id'                    => 'Visitor user_id ID',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getGoogleUser(){
        return $this->hasOne(GoogleUser::className(), ['id' => 'google_user_id']);        
    }
    
    /**
     * @inheritdoc
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']); 
    }
}
