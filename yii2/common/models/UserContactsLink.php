<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Contact;
use common\models\User;

/**
 * Description of GoogleUser
 * @since 31-03-2016
 * @author Bas van Beers
 * 
 * @property int $id
 * @property int $contacts_id
 * @property int $user_id
 * @property Contacts $contact
 * @property User $user
 */
class UserContactsLink extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_contacts_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'contacts_id',
                    'user_id',
                ],
                'required'
            ],
            [
                [
                    'contacts_id',
                    'user_id',
                ],
                'integer'
            ],
            [
                ['contacts_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Contact::className(),
                'targetAttribute' => ['contacts_id' => 'id']
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
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getContact(){
        return $this->hasOne(Contact::className(), ['id' => 'contacts_id']);        
    }
    
    /**
     * @inheritdoc
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);        
    }
}
