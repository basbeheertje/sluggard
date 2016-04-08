<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property User $creator0
 */
class DeviceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'creator'], 'required'],
            [['creator'], 'integer'],
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
}
