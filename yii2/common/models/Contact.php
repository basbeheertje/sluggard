<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\components\GravatarHelper;

/**
 * Description of contacts
 *
 * @author Bas van Beers
 */
class Contact extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'main_name'
                ],
                'required'
            ],
            [
                [
                    'main_name'
                ],
                'string'
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactAddress(){
        return $this->hasMany(ContactAddress::className(), ['contact_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactCompany(){
        return $this->hasMany(ContactCompany::className(), ['contact_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactMail(){
        return $this->hasMany(ContactMail::className(), ['contact_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactPhonenumber(){
        return $this->hasMany(ContactPhonenumber::className(), ['contact_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoogleContact(){
        
        return $this->hasMany(GoogleContact::className(), ['contact_id' => 'id']);
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses(){
        
        $Addresses = array();
        
        $ContactAddressList = $this->contactAddress;
        
        foreach($ContactAddressList as $ContactAddress){
            $Addresses[] = $ContactAddress->address;
        }
        
        return $Addresses;
        
    }
    
    public function getPlace(){
        
        $Addresses = $this->addresses;
        
        if(!empty($Addresses) && is_array($Addresses)){
            
            return $Addresses[0]->place;
            
        }
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies(){
        
        $Companies = array();
        
        $ContactCompanyList = $this->contactCompany;
        
        foreach($ContactCompanyList as $ContactCompany){
            $Companies[] = $ContactCompany->company;
        }
        
        return $Companies;
        
    }
    
    public function getCompany(){
        
        $Companies = $this->companies;
        
        if(!empty($Companies) && is_array($Companies)){
            
            return $Companies[0]->name;
            
        }
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAddresses(){
        
        $MailsAddresses = array();
        
        $ContactMailList = $this->contactMail;
        
        foreach($ContactMailList as $ContactMail){
            $MailsAddresses[] = $ContactMail->mailaddress;
        }
        
        return $MailsAddresses;
        
    }
    
    public function getMailaddress(){
        
        $mailaddresses = $this->mailAddresses;
        
        if(!empty($mailaddresses) && is_array($mailaddresses)){
            
            return $mailaddresses[0]->address;
            
        }
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhonenumbers(){
        
        $PhoneNumbers = array();
        
        $ContactPhonenumberList = $this->contactPhonenumber;
        
        foreach($ContactPhonenumberList as $ContactPhonenumber){
            $PhoneNumbers[] = $ContactPhonenumber->phonenumber;
        }
        
        return $PhoneNumbers;
        
    }
    
    public function getPhonenumber(){
        
        $Phonenumbers = $this->phonenumbers;
        
        if(!empty($Phonenumbers) && is_array($Phonenumbers)){
            
            return $Phonenumbers[0]->number;
            
        }
        
    }
    
    public function getImage(){
        
        if(!empty($this->mailAddress)){
            
            $gravatarImage = GravatarHelper::getImageLink($this->mailaddress);
       
            if($gravatarImage){
            
                return $gravatarImage;
                
            }            
            
        }
        
        return '';
        
    }
    
    public function getIsGoogle(){
        
        if(!empty($this->googleContact)){
            
            return true;
            
        }
        
        return false;
        
    }
    
}
