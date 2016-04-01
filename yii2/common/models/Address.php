<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $street
 * @property string $number
 * @property string $zipcode
 * @property string $place
 * @property string $province
 * @property string $country
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property CompanyAddress[] $companyAddresses
 * @property ContactAddress[] $contactAddresses
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['street', 'number', 'zipcode', 'place', 'province', 'country', 'creator'], 'required'],
            [['street', 'number', 'zipcode', 'place', 'province', 'country'], 'string'],
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
            'street' => 'Street',
            'number' => 'Number',
            'zipcode' => 'Zipcode',
            'place' => 'Place',
            'province' => 'Province',
            'country' => 'Country',
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
        return $this->hasMany(CompanyAddress::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactAddresses()
    {
        return $this->hasMany(ContactAddress::className(), ['address_id' => 'id']);
    }
}
