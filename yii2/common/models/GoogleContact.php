<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "google_contact".
 *
 * @property integer $id
 * @property integer $google_user_id
 * @property integer $contacts_id
 * @property string $etag
 * @property string $updated
 * @property string $create_at
 * @property integer $creator
 *
 * @property User $creator0
 * @property Contact $contacts
 * @property GoogleUser $googleUser
 */
class GoogleContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'google_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['google_user_id', 'contact_id', 'etag', 'updated', 'creator'], 'required'],
            [['google_user_id', 'contact_id', 'creator'], 'integer'],
            [['etag', 'updated'], 'string'],
            [['create_at'], 'safe'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['google_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoogleUser::className(), 'targetAttribute' => ['google_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'google_user_id' => 'Google User ID',
            'contact_id' => 'Contact ID',
            'etag' => 'Etag',
            'updated' => 'Updated',
            'create_at' => 'Create At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoogleUser()
    {
        return $this->hasOne(GoogleUser::className(), ['id' => 'google_user_id']);
    }
}
