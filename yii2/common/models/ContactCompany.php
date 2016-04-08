<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contact_company".
 *
 * @property integer $id
 * @property integer $contact_id
 * @property integer $company_id
 * @property string $title
 * @property string $updated_at
 * @property string $created_at
 * @property integer $creator
 *
 * @property Contact $contact
 * @property Company $company
 * @property User $creator0
 */
class ContactCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'company_id', 'title', 'creator'], 'required'],
            [['contact_id', 'company_id', 'creator'], 'integer'],
            [['title'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'contact_id' => 'Contact ID',
            'company_id' => 'Company ID',
            'title' => 'Title',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
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
    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }
}
