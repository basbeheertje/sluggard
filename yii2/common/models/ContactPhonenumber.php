<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contact_phonenumber".
 *
 * @property integer $id
 * @property integer $contact_id
 * @property integer $phonenumber_id
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property Contact $contact
 * @property User $creator0
 * @property Phonenumber $phonenumber
 */
class ContactPhonenumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_phonenumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'phonenumber_id', 'creator'], 'required'],
            [['contact_id', 'phonenumber_id', 'creator'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
            [['phonenumber_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phonenumber::className(), 'targetAttribute' => ['phonenumber_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contact_id' => 'Contact ID',
            'phonenumber_id' => 'Phonenumber ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
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
    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhonenumber()
    {
        return $this->hasOne(Phonenumber::className(), ['id' => 'phonenumber_id']);
    }
}
