<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
    public function getAddresses(){
        
        $Addresses = array();
        
        $ContactAddressList = $this->getContactAddress;
        
        foreach($ContactAddressList as $ContactAddress){
            $Addresses[] = $ContactAddress->address;
        }
        
        return $Addresses;
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies(){
        
        $Companies = array();
        
        $ContactCompanyList = $this->getContactCompany;
        
        foreach($ContactCompanyList as $ContactCompany){
            $Companies[] = $ContactCompany->company;
        }
        
        return $Companies;
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAddresses(){
        
        $MailsAddresses = array();
        
        $ContactMailList = $this->getContactMail;
        
        foreach($ContactMailList as $ContactMail){
            $MailsAddresses[] = $ContactMail->mailaddress;
        }
        
        return $MailsAddresses;
        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhonenumbers(){
        
        $PhoneNumbers = array();
        
        $ContactPhonenumberList = $this->getContactPhonenumber;
        
        foreach($ContactPhonenumberList as $ContactPhonenumber){
            $PhoneNumbers[] = $ContactPhonenumber->phonenumber;
        }
        
        return $PhoneNumbers;
        
    }
    
}
