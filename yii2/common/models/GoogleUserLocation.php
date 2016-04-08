<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "GoogleUserLocation".
 */
class GoogleUserLocation extends \yii\db\ActiveRecord{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'google_user_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'time', 'longitude', 'latitude', 'height', 'google_user_id', 'creator'], 'required'],
            [['google_user_id', 'creator'], 'integer'],
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
            'date' => 'Date',
            'time' => 'Time',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'height' => 'Height',
            'google_user_id' => 'Google User Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'creator' => 'Creator',
        ];
    }
    
    public function duplicate(){
        
        $GoogleUserLocation = GoogleUserLocation::find()
            ->where(
                [
                    'date' => $this->date,
                    'time' => $this->time,
                    'longitude' => $this->longitude,
                    'latitude' => $this->latitude,
                    'height' => $this->height,
                    'google_user_id' => $this->google_user_id,
                ])
            ->one();
        
        if($GoogleUserLocation){
            return true;
        }
        
        return false;
        
    }
    
}