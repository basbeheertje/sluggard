<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $name
 * @property integer $device_type
 * @property string $imei
 * @property string $mac
 * @property string $ip
 * @property string $number
 * @property string $brand
 * @property string $version
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property User $creator0
 * @property DeviceWhattsapp[] $deviceWhattsapps
 * @property UserDevices[] $userDevices
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'mac', 'ip', 'brand', 'version', 'creator'], 'required'],
            [['name', 'imei', 'mac', 'ip', 'number', 'brand', 'version'], 'string'],
            [['device_type', 'creator'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
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
            'device_type' => 'Device Type',
            'imei' => 'Imei',
            'mac' => 'Mac',
            'ip' => 'Ip',
            'number' => 'Number',
            'brand' => 'Brand',
            'version' => 'Version',
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
    public function getDeviceWhattsapps()
    {
        return $this->hasMany(DeviceWhattsapp::className(), ['device_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDevices()
    {
        return $this->hasMany(UserDevices::className(), ['device_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceType()
    {
        return $this->hasOne(DeviceType::className(), ['id' => 'device_type']);
    }
    
}
