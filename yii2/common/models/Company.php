<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property CompanyAddress[] $companyAddresses
 * @property CompanyMail[] $companyMails
 * @property CompanyPhonenumber[] $companyPhonenumbers
 * @property ContactCompany[] $contactCompanies
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'creator'], 'required'],
            [['name'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['creator'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyAddresses()
    {
        return $this->hasMany(CompanyAddress::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyMails()
    {
        return $this->hasMany(CompanyMail::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyPhonenumbers()
    {
        return $this->hasMany(CompanyPhonenumber::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactCompanies()
    {
        return $this->hasMany(ContactCompany::className(), ['company_id' => 'id']);
    }
}
