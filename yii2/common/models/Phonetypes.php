<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phonetypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property Phonenumber[] $phonenumbers
 * @property User $creator0
 */
class Phonetypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phonetypes';
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
    public function getPhonenumbers()
    {
        return $this->hasMany(Phonenumber::className(), ['phonetypes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }
}
