<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_phonenumber".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $phonenumber_id
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property User $creator0
 * @property Company $company
 * @property Phonenumber $phonenumber
 */
class CompanyPhonenumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_phonenumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'phonenumber_id', 'creator'], 'required'],
            [['company_id', 'phonenumber_id', 'creator'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'company_id' => 'Company ID',
            'phonenumber_id' => 'Phonenumber ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhonenumber()
    {
        return $this->hasOne(Phonenumber::className(), ['id' => 'phonenumber_id']);
    }
}
