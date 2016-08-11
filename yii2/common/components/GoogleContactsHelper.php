<?php

namespace common\components;

use  \yii\base\Component;
use common\models\Address;
use \common\models\GoogleUser;
use common\models\Company;
use common\models\Contact;
use common\models\ContactAddress;
use common\models\ContactCompany;
use common\models\ContactMail;
use common\models\ContactPhonenumber;
use common\models\GoogleContact;
use common\models\Mailaddress;
use common\models\Phonenumber;
use common\models\Phonetypes;
use common\models\UserGoogleLink;
use common\models\UserContactsLink;
use common\components\GoogleAPIHelper;
use \libphonenumber\PhoneNumberUtil;

/**
 * Description of GoogleContactsHelper
 * Class GoogleContactsHelper
 * @since 2016-03-31
 * @author Bas van Beers
 * 
 */
class GoogleContactsHelper extends Component {
    
    /** @const string SCOPE */
    const SCOPE = 'https://www.google.com/m8/feeds';
    
    /**
     * @access public
     * @author Bas van Beers
     * @since 2016-03-31
     * @return boolean
     */
    public static function isEnabled(){
        
        if(GoogleAPIHelper::isEnabled() && \Yii::$app->params['google']['contacts']){
            return true;
        }
        
        return false;
        
    }
    
    /**
     * @access public
     * @author Bas van Beers
     * @since 2016-03-31
     * @param GoogleUser $GoogleUser
     * @return array $response
     */
    public static function getAllContacts(GoogleUser $GoogleUser){
        
        $GoogleUser->refreshToken();
        
        /** @var array $access_token */
        $access_token = json_decode($GoogleUser->access_token)->access_token;
        
        /** @var string url */
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&oauth_token='.$access_token;
        
        /** @var array $Contacts */
        $Contacts = self::getFromurl($GoogleUser,$url);
        
        return $Contacts;
        
    }
	
    /**
     * @access public
     * @author Bas van Beers
     * @since 2016-03-31
     * @param GoogleUser $GoogleUser
     * @param Timestamp $date
     * @return array $response
     */
    public static function getAllContactsFromDate(GoogleUser $GoogleUser, $date){
		
	$date = substr($date, 0, 19);
		
        $GoogleUser->refreshToken();
        
        /** @var array $access_token */
        $access_token = json_decode($GoogleUser->access_token)->access_token;
        
        /** @var string $url */
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&oauth_token='.$access_token.'&updated-min='.$date;
        
        /** @var array $Contacts */
        $Contacts = self::getFromurl($GoogleUser,$url);
        
        return $Contacts;
        
    }
    
    /**
     * Retrieves contacts for an user
     * @param GoogleUser $GoogleUser
     * @param string $url
     * @return array $Contacts
     */
    protected static function getFromurl(GoogleUser $GoogleUser,$url){
        
        /** @var string $response */
        $response = file_get_contents($url);
        
        if(self::hasNextLink($response)){
            /** @var string $nextlink */
            $nextlink = self::getNextLink($response)."&oauth_token=".json_decode($GoogleUser->access_token)->access_token;
        }
        
        $response = self::cleanupContact($response);
        
	/** @var array $Contacts */
        $Contacts = array();
		
        /** @var array json_decode */
        $array = json_decode($response,TRUE);
		
        if(isset($array['feed']) and !empty($array['feed'])){
			
            if(isset($array['feed']['entry']) and !empty($array['feed']['entry'])){
			
                $array = $array['feed']['entry'];
				
		foreach($array as $key => $value){
					
                    if(isset($value['name']['fullname']['value'])){
                        $Contacts[] = self::convertToContact($value);
                    }
					
		}
				
		if(isset($nextlink)){
				
                    /** @var string $NextContacts */
                    $NextContacts = self::getFromurl($GoogleUser,$nextlink);
                    foreach($NextContacts as $NextContact){
                        $Contacts[] = $NextContact;
                    }
		}
				
            }
			
	}
        
        return $Contacts;
        
    }
    
    /**
     * Converts an array from Google into an contact
     * @param $importContact
     * @return stdClass
     */
    public static function convertToContact($importContact){
        
        $Contact = new \stdClass();
        if(!isset($importContact['name']['fullname']['value'])){
            var_dump($importContact);
            exit;
        }
        $Contact->main_name = $importContact['name']['fullname']['value'];
        if(isset($importContact['birthday']) and isset($importContact['birthday']['when'])){
            $Contact->birthday = date('Y-m-d',strtotime($importContact['birthday']['when']));
        }else{
            $Contact->birthday = "";
        }
        $Contact->updated_at = date('Y-m-d H:i:s');
        $Contact->created_at = date('Y-m-d H:i:s');
        $Contact->creator = 1;
        
        $Contact->GoogleContact = new \stdClass();
        $Contact->GoogleContact->google_user_id = NULL;
        $Contact->GoogleContact->contacts_id = NULL;
        $Contact->GoogleContact->etag = $importContact['etag'];
        $Contact->GoogleContact->updated = $importContact['updated']['value'];
        $Contact->GoogleContact->create_at = $importContact['updated']['value'];
        $Contact->GoogleContact->creator = 1;
        
        $Contact->Mailaddresses = self::extractEmailAddresses($importContact);
        
        $Contact->Phonenumbers = self::extractPhonenumbers($importContact);
        
        $Contact->Addresses = self::extractAddresses($importContact);
        
        $Contact->Companies = self::extractCompanies($importContact);
        
        return $Contact;
        
    }
    
    protected static function cleanupContact($importContact){
        
        /** Replace all bad values */
        $importContact = str_replace('$t','value',$importContact);
        $importContact = str_replace('gd$etag','etag',$importContact);
        $importContact = str_replace('gd$fullName','fullname',$importContact);
        $importContact = str_replace('gd$givenName','givenname',$importContact);
        $importContact = str_replace('gd$familyName','familyname',$importContact);
        $importContact = str_replace('gd$phoneNumber','phonenumber',$importContact);
        $importContact = str_replace('gd$email','email',$importContact);
        $importContact = str_replace('gd$structuredPostalAddress','address',$importContact);
        $importContact = str_replace('gd$city','city',$importContact);
        $importContact = str_replace('gd$street','street',$importContact);
        $importContact = str_replace('gd$region','region',$importContact);
        $importContact = str_replace('gd$postcode','postcode',$importContact);
        $importContact = str_replace('gd$country','country',$importContact);
        $importContact = str_replace('gd$formattedAddress','address',$importContact);
        $importContact = str_replace('gContact$birthday','birthday',$importContact);
        $importContact = str_replace('gd$','',$importContact);
        
        return $importContact;        
        
    }
    
    /**
     * Extracts an address from an GoogleContact
     * @param type $importContact
     * @return \stdClass
     */
    protected static function extractAddresses($importContact){
        
        /** @var array $list */
        $list = [];
        
        if(isset($importContact['address'])){
            foreach($importContact['address'] as $key => $value){

                /** @var string $street */
                $street = "";
                
                /** @var string $housenumber */
                $housenumber = "";

                /** @var array $matches */
                $matches = array();
                
                if(preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $value['street']['value'], $matches)){
                    $street = $matches['address'];
                    $housenumber = $matches['number'];
                } else { // no number found, it is only address
                    $street = $input_string;
                    $housenumber = '';
                }
                
                /** @var \stdClass $address*/
                $address = new \stdClass();
                $address->street = (string) $street;
                $address->number = (string) $housenumber;
                if(isset($value['postcode'])){
                    $address->zipcode = (string) $value['postcode']['value'];
                }else{
                    $address->zipcode = \Yii::t('app','Unknown');
                }
                $address->place = (string) $value['city']['value'];
                if(isset($value['region']['value'])){
                    $address->province = (string) $value['region']['value'];
                }else{
                    $address->province = \Yii::t('app','Unknown');
                }
                if(isset($value['country']['value'])){
                    $address->country = (string) $value['country']['value'];
                }else{
                    $address->country = \Yii::$app->params['defaults']['countrycode'];
                }
                $address->updated_at = $importContact['updated']['value'];
                $address->created_at = $importContact['updated']['value'];
                $address->creator = 1;
                $list[] = $address;

            }
        }
        
        return $list;
        
    }
    
    /**
     * Extracts an phonenumber from an GoogleContact
     * @param type $importContact
     * @return \stdClass
     */
    protected static function extractPhonenumbers($importContact){
        
        /** @var array $list */
        $list = [];
        
        if(isset($importContact['phonenumber'])){
            foreach($importContact['phonenumber'] as $key => $value){

                /** @var \stdClass $phonenumber */
                $phonenumber = new \stdClass();
                $phonenumber->number = (string) $value['value'];
                $phonenumber->updated_at = $importContact['updated']['value'];
                $phonenumber->created_at = $importContact['updated']['value'];
                $phonenumber->creator = 1;
                $list[] = $phonenumber;

            }
        }
        
        return $list;
        
    }
    
    /**
     * Extracts Email Addresses from an GoogleContact
     * @param type $importContact
     * @return \stdClass
     */
    protected static function extractEmailAddresses($importContact){
        
        /** @var array $list */
        $list = [];
        
        if(isset($importContact['email'])){
            foreach($importContact['email'] as $key => $value){

                /** @var \stdClass $mailaddress */
                $mailaddress = new \stdClass();
                if(isset($mailaddress->name)){
                    $mailaddress->name = (string) $value['label'];
                }else{
                    $mailaddress->name = \Yii::t('app', 'Default');
                }
                $mailaddress->address = (string) $value['address'];
                $mailaddress->updated_at = $importContact['updated']['value'];
                $mailaddress->created_at = $importContact['updated']['value'];
                $mailaddress->creator = 1;
                $list[] = $mailaddress;

            }
        }
        
        return $list;
        
    }
    
    /**
     * Extracts companies from an GoogleContact
     * @param array $importContact
     * @return \stdClass
     */
    protected static function extractCompanies($importContact){
        
        /** @var array $list */
        $list = [];
        
        if(isset($importContact['organization'])){
            
            /* @var $importContact array */
            foreach($importContact['organization'] as $value){

                /** @var \stdClass $Company */
                $Company = new \stdClass();
                if(isset($value['orgName']) and isset($value['orgName']['value'])){
                    $Company->name = (string) $value['orgName']['value'];
                    if(isset($value['orgTitle']) and isset($value['orgTitle']['value'])){
                        $Company->title = (string) $value['orgTitle']['value'];
                    }else{
                        $Company->title = \Yii::t('app', 'Unknown');
                    }
                    $Company->updated_at = $importContact['updated']['value'];
                    $Company->created_at = $importContact['updated']['value'];
                    $Company->creator = 1;
                    $list[] = $Company;
                }

            }
        }
        
        return $list;
        
    }
    
    /**
     * Retrieves the next link from an GoogleContact feed
     * @param type $result
     * @return boolean
     */
    protected static function getNextLink($result){
        
        /** @var array $result */
        $result = json_decode($result);
        
        if(isset($result->feed) and isset($result->feed->link)){
            foreach($result->feed->link as $linkrow){
                if(isset($linkrow->rel) and $linkrow->rel === "next"){
                    return $linkrow->href;
                }
            }
        }
        
        return false;
        
    }
    
    /**
     * Checks if there is an nextlink in an GoogleContact feed
     * @param type $result
     * @return boolean
     */
    protected static function hasNextLink($result){
        
        /** @var array $result */
        $result = json_decode($result);
        
        if(isset($result->feed) and isset($result->feed->link)){
            foreach($result->feed->link as $linkrow){
                if(isset($linkrow->rel) and $linkrow->rel === "next"){
                    return true;
                }
            }
        }
        
        return false;
        
    }
    
    public static function addContact($googleContact, $GoogleUser){
        
        /** @var GoogleContact $GoogleContact */
        $GoogleContact = GoogleContact::find()->where(['etag'=>$googleContact->GoogleContact->etag])->one();
        
        if(!$GoogleContact){
            
            /** @var Contact $Contact */
            $Contact = new Contact();
            $Contact->main_name = $googleContact->main_name;
            $Contact->birthday = $googleContact->birthday;
            $Contact->updated_at = $googleContact->updated_at;
            $Contact->created_at = $googleContact->created_at;
            $Contact->creator = $googleContact->creator;
            $Contact->save();
            
            $GoogleContact = new GoogleContact();
            $GoogleContact->google_user_id = $GoogleUser->id;
            $GoogleContact->contact_id = $Contact->id;
            $GoogleContact->etag = $googleContact->GoogleContact->etag;
            $GoogleContact->updated = date("Y-m-d",strtotime($googleContact->GoogleContact->updated))."T".date("H:i:s",strtotime($googleContact->GoogleContact->updated));
            $GoogleContact->create_at = $googleContact->GoogleContact->create_at;
            $GoogleContact->creator = $googleContact->GoogleContact->creator;
            $GoogleContact->save();
            
        }else if($GoogleContact->updated < $googleContact->updated_at){
            
            /** @var Contact $Contact */
            $Contact = $GoogleContact->contact;
            $Contact->main_name = $googleContact->main_name;
            $Contact->birthday = $googleContact->birthday;
            $Contact->updated_at = $googleContact->updated_at;
            $Contact->save();
            
            $GoogleContact->updated = $googleContact->updated_at;
            $GoogleContact->save();
            
        }else{
            $Contact = $GoogleContact->contact;
        }
        
        /** @var UserGoogleLink $UserGoogleLinks */
        $UserGoogleLinks = UserGoogleLink::find()
            ->where(
                [
                    'google_user_id'=>$GoogleUser->id
                ])
            ->all();
        
        if(!empty($UserGoogleLinks)){
            
            foreach($UserGoogleLinks as $UserGoogleLink){
            
                /** @var UserGoogleLink $UserGoogleLinks */
                $UserContactLinks = UserContactsLink::find()
                    ->where(
                        [
                            'contacts_id'=>$Contact->id,
                            'user_id'=>$UserGoogleLink->user_id
                        ])
                    ->one();
                
                if(!$UserContactLinks){
                    $UserContactLinks = new UserContactsLink();
                    $UserContactLinks->contacts_id = $Contact->id;
                    $UserContactLinks->user_id = $UserGoogleLink->user_id;
                    $UserContactLinks->save();
                }
            
            }
            
        }
        
        if(!empty($googleContact->Addresses)){
            
            foreach($googleContact->Addresses as $TMPAddress){
                
                /** @var Address $Address */
                $Address = Address::find()
                    ->where(
                        [
                            'street'=>$TMPAddress->street,
                            'number'=>$TMPAddress->number,
                            'zipcode'=>$TMPAddress->zipcode,
                            'place'=>$TMPAddress->place,
                            'country'=>$TMPAddress->country
                        ])->one();
                
                if(!$Address){
                    
                    $Address = new Address();
                    $Address->street = $TMPAddress->street;
                    $Address->number = $TMPAddress->number;
                    $Address->zipcode = $TMPAddress->zipcode;
                    $Address->place = $TMPAddress->place;
                    $Address->province = $TMPAddress->province;
                    $Address->country = $TMPAddress->country;
                    $Address->updated_at = $TMPAddress->updated_at;
                    $Address->created_at = $TMPAddress->created_at;
                    $Address->creator = $TMPAddress->creator;
                    $Address->save();
                    
                }else if($Address->updated_at < $TMPAddress->updated_at){
                    
                    $Address->street = $TMPAddress->street;
                    $Address->number = $TMPAddress->number;
                    $Address->zipcode = $TMPAddress->zipcode;
                    $Address->place = $TMPAddress->place;
                    $Address->province = $TMPAddress->province;
                    $Address->country = $TMPAddress->country;
                    $Address->updated_at = $TMPAddress->updated_at;
                    $Address->save();
                    
                }
                
                /** @var ContactAddress $ContactAddress */
                $ContactAddress = ContactAddress::find()->where(['address_id'=>$Address->id,'contact_id'=>$Contact->id])->one();
                
                if(!$ContactAddress){
                    $ContactAddress = new ContactAddress();
                    $ContactAddress->contact_id = $Contact->id;
                    $ContactAddress->address_id = $Address->id;
                    $ContactAddress->updated_at = $TMPAddress->updated_at;
                    $ContactAddress->created_at = $TMPAddress->created_at;
                    $ContactAddress->creator = $TMPAddress->creator;
                    $ContactAddress->save();
                }else{
                    $ContactAddress->updated_at = $TMPAddress->updated_at;
                    $ContactAddress->save();
                }
                
            }
            
        }
        
        if(!empty($googleContact->Phonenumbers)){
            
            /** @var \libphonenumber\PhoneNumberUtil $phoneUtil */
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            
            foreach($googleContact->Phonenumbers as $TMPphonenumber){
                               
                /** @var string $number */
                $number = $phoneUtil->parse($TMPphonenumber->number,\Yii::$app->params['defaults']['countrycode']);
                $TMPphonenumber->number = $phoneUtil->format($number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
                
                /** @var Phonenumber $Phonenumber */
                $Phonenumber = Phonenumber::find()->where(['number'=>$TMPphonenumber->number])->one();
                if(!$Phonenumber){
                    
                    $Phonenumber = new Phonenumber();
                    $Phonenumber->number = $TMPphonenumber->number;
                    $Phonenumber->phonetypes_id = 1;
                    $Phonenumber->updated_at = $TMPphonenumber->updated_at;
                    $Phonenumber->created_at = $TMPphonenumber->created_at;
                    $Phonenumber->creator = $TMPphonenumber->creator;
                    
                    $Phonenumber->save();
                    
                }else if($Phonenumber->updated_at < $TMPphonenumber->updated_at){
                    
                    $Phonenumber->number = $TMPphonenumber->number;
                    $Phonenumber->updated_at = $TMPphonenumber->updated_at;
                    $Phonenumber->save();
                    
                }
                
                /** @var ContactPhonenumber $ContactPhonenumber */
                $ContactPhonenumber = ContactPhonenumber::find()->where(['phonenumber_id'=>$Phonenumber->id,'contact_id'=>$Contact->id])->one();
                
                if(!$ContactPhonenumber){
                    $ContactPhonenumber = new ContactPhonenumber();
                    $ContactPhonenumber->contact_id = $Contact->id;
                    $ContactPhonenumber->phonenumber_id = $Phonenumber->id;
                    $ContactPhonenumber->updated_at = $TMPphonenumber->updated_at;
                    $ContactPhonenumber->created_at = $TMPphonenumber->created_at;
                    $ContactPhonenumber->creator = $TMPphonenumber->creator;
                    $ContactPhonenumber->save();
                }else{
                    $ContactPhonenumber->updated_at = $TMPphonenumber->updated_at;
                    $ContactPhonenumber->save();
                }
                
                unset($number);
                unset($Phonenumber);
                unset($ContactPhonenumber);
                unset($TMPphonenumber);
                
            }
            
        }
        
        if(!empty($googleContact->Mailaddresses)){
            
            foreach($googleContact->Mailaddresses as $TMPmailaddress){
                
                /** @var MailAddress $Mailaddress */
                $Mailaddress = MailAddress::find()->where(['address'=>$TMPmailaddress->address])->one();
                
                if(!$Mailaddress){
                    
                    /** @var MailAddress $Mailaddress */
                    $Mailaddress = new MailAddress();
                    $Mailaddress->name = $TMPmailaddress->name;
                    $Mailaddress->address = $TMPmailaddress->address;
                    $Mailaddress->updated_at = $TMPmailaddress->updated_at;
                    $Mailaddress->created_at = $TMPmailaddress->created_at;
                    $Mailaddress->creator = $TMPmailaddress->creator;
                    
                    $Mailaddress->save();
                    
                }else if($Mailaddress->updated_at < $TMPmailaddress->updated_at){
                    
                    $Mailaddress->name = $TMPmailaddress->name;
                    $Mailaddress->address = $TMPmailaddress->address;
                    $Mailaddress->updated_at = $TMPmailaddress->updated_at;
                    $Mailaddress->save();
                    
                }
                
                /** @var ContactMail $ContactMail */
                $ContactMail = ContactMail::find()->where(['mailaddress_id'=>$Mailaddress->id,'contact_id'=>$Contact->id])->one();
                
                if(!$ContactMail){
                    $ContactMail = new ContactMail();
                    $ContactMail->contact_id = $Contact->id;
                    $ContactMail->mailaddress_id = $Mailaddress->id;
                    $ContactMail->updated_at = $TMPmailaddress->updated_at;
                    $ContactMail->created_at = $TMPmailaddress->created_at;
                    $ContactMail->creator = $TMPmailaddress->creator;
                    $ContactMail->save();
                }else{
                    $ContactMail->updated_at = $TMPmailaddress->updated_at;
                    $ContactMail->save();
                }
                
            }
            
        }
        
        if(!empty($googleContact->Companies)){
            
            foreach($googleContact->Companies as $TMPCompany){
                
                /** @var Company $Company */
                $Company = Company::find()->where(['name'=>$TMPCompany->name])->one();
                
                if(!$Company){
                    
                    $Company = new Company();
                    $Company->name = $TMPCompany->name;
                    $Company->updated_at = $TMPCompany->updated_at;
                    $Company->created_at = $TMPCompany->created_at;
                    $Company->creator = $TMPCompany->creator;
                    $Company->save();
                    
                }else if($Company->updated_at < $TMPCompany->updated_at){
                    
                    $Company->name = $TMPCompany->name;
                    $Company->updated_at = $TMPCompany->updated_at;
                    $Company->save();
                    
                }
                
                /** @var ContactCompany $ContactCompany */
                $ContactCompany = ContactCompany::find()->where(['company_id'=>$Company->id,'contact_id'=>$Contact->id])->one();
                
                if(!$ContactCompany){
                    $ContactCompany = new ContactCompany();
                    $ContactCompany->contact_id = $Contact->id;
                    $ContactCompany->company_id = $Company->id;
                    $ContactCompany->title = $TMPCompany->title;
                    $ContactCompany->updated_at = $TMPCompany->updated_at;
                    $ContactCompany->created_at = $TMPCompany->created_at;
                    $ContactCompany->creator = $TMPCompany->creator;
                    $ContactCompany->save();
                }else{
                    $ContactCompany->updated_at = $TMPCompany->updated_at;
                    $ContactCompany->save();
                }
                
            }
            
        }
        
    }
    
}