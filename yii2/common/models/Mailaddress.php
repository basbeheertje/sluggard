<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mailaddress".
 *
 * @property integer $id
 * @property integer $name
 * @property integer $address
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property CompanyMail[] $companyMails
 * @property ContactMail[] $contactMails
 */
class Mailaddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailaddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'creator'], 'required'],
            [['creator'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
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
            'address' => 'Address',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyMails()
    {
        return $this->hasMany(CompanyMail::className(), ['mailaddress_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactMails()
    {
        return $this->hasMany(ContactMail::className(), ['mailaddress_id' => 'id']);
    }
}
