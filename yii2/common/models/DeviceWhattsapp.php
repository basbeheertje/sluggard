<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device_whattsapp".
 *
 * @property integer $id
 * @property integer $device_id
 * @property string $username
 * @property string $nickname
 * @property string $coderequest
 * @property string $code
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property Device $device
 * @property User $creator0
 */
class DeviceWhattsapp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_whattsapp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'username', 'nickname', 'coderequest', 'code', 'creator'], 'required'],
            [['device_id', 'creator'], 'integer'],
            [['username', 'nickname', 'coderequest', 'code'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
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
            'device_id' => 'Device ID',
            'username' => 'Username',
            'nickname' => 'Nickname',
            'coderequest' => 'Coderequest',
            'code' => 'Code',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }
}
