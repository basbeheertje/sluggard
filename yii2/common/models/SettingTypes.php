<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
 *
 * @property UserSettings[] $userSettings
 */
class SettingTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'comment'], 'required'],
            [['name', 'comment'], 'string'],
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
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasMany(UserSettings::className(), ['setting_types_id' => 'id']);
    }
}
