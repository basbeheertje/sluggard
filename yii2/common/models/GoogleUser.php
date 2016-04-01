<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Description of GoogleUser
 * @since 31-03-2016
 * @author Bas van Beers
 */
class GoogleUser extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%google_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
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
    public function getId()
    {
        return $this->getPrimaryKey();
    }
}
