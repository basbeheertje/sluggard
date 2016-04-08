<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phonenumber".
 *
 * @property integer $id
 * @property string $number
 * @property integer $phonetypes_id
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property CompanyPhonenumber[] $companyPhonenumbers
 * @property ContactPhonenumber[] $contactPhonenumbers
 * @property User $creator0
 * @property Phonetypes $phonetypes
 */
class Phonenumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phonenumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'creator'], 'required'],
            [['phonetypes_id', 'creator'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
            [['phonetypes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phonetypes::className(), 'targetAttribute' => ['phonetypes_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'phonetypes_id' => 'Phonetypes ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyPhonenumbers()
    {
        return $this->hasMany(CompanyPhonenumber::className(), ['phonenumber_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactPhonenumbers()
    {
        return $this->hasMany(ContactPhonenumber::className(), ['phonenumber_id' => 'id']);
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
    public function getPhonetypes()
    {
        return $this->hasOne(Phonetypes::className(), ['id' => 'phonetypes_id']);
    }
}
