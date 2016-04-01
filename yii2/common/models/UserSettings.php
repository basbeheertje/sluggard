<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $setting_types_id
 * @property string $value
 *
 * @property SettingTypes $settingTypes
 * @property User $user
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'setting_types_id', 'value'], 'required'],
            [['user_id', 'setting_types_id'], 'integer'],
            [['value'], 'string'],
            [['setting_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => SettingTypes::className(), 'targetAttribute' => ['setting_types_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'setting_types_id' => 'Setting Types ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingTypes()
    {
        return $this->hasOne(SettingTypes::className(), ['id' => 'setting_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
