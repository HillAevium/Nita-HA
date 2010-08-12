<?php

require_once APPPATH.'/config/soap.php';

/**
 * API for interacting with the CRM backend.
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Soap {
    
    private $soapUrl;
    private $programUri;
    private $accountUri;
    
    // Date Format -> March 23rd, 2009
    private $dateFormat = 'n/j/Y';
    
    private $programTypeMap = array();
    
    /**
     * Construct a new Soap API object
     */
    public function Soap() {
        // Load the soap config
        $CI =& get_instance();
        $soap = $CI->config->item('soap');
        
        // Save some of the soap configs locally
        $this->soapUrl    = $soap['server'];
        $this->programUri = $soap['services']['program'];
        $this->accountUri = $soap['services']['account'];
        $this->contactUri = $soap['services']['contact'];
        $this->webPageUri = $soap['services']['webPage'];
        
        // FIXME - Hack for displaying cleaner names in list output
        $this->programTypeMap = array(
            '47c4b583-eca9-dc11-b373-00304832346b' => 'Trial Advocacy',
            '50c4b583-eca9-dc11-b373-00304832346b' => 'Deposition and Pretrial Skills'
        );
    }
    
    public function getPage($guid) {
        return $this->doWebPageServiceGet($guid);
    }
    
    public function getPagesByNavName($navName) {
        return $this->doWebPageServiceGetPagesByNavName($navName);
    }
    
    public function getPagesByParentId($guid) {
        return $this->doWebPageServiceGetPagesByParentId($guid);
    }
    
    public function getAllPrograms() {
        return $this->doProgramServiceGetActivePrograms();
    }
    
    public function getAllPublications() {
        // TODO
    }
    
    public function getProgram($id) {
        return $this->doProgramServiceGet($id);
    }
    
    public function getPublication($id) {
        // TODO
    }
    
    public function userInsert(UserProfile $model) {
        return $this->doContactServiceInsert($model);
    }
    
    public function userAuthenticate($username, $password) {
        log_message('error','test');
        die();
        return $this->doContactServiceAuthenticate($username, $password);
    }
    
    public function userGet($id) {
        return $this->doContactServiceGet($id);
    }
    
    public function userUpdate(UserProfile $model) {
        return $this->doContactServiceUpdate($model);
    }
    
    //----------------------------------------------------------------------------//
    //                               WebPageService                               //
    //----------------------------------------------------------------------------//
    
    private function doWebPageServiceGet($guid) {
        // Create the soap client for the WebPage service
        $soapClient = new SoapClient($this->soapUrl.$this->webPageUri);
        
        // Create the arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('id', $guid);

        // Send the get request
        $soapResult = $soapClient->Get($soapArgs)->GetResult;

        // FIXME - It is not clear what is returned if the navName is invalid
        if(!isset($soapResult) OR is_null($soapResult) OR ($soapResult == false)) {
            // The caller is expecting null on failure
            return null;
        } else {
            return $soapResult; 
        }
        
        // Convert the model
        //$webPage = $this->convertWebPage($soapResult);
        
        // Return the profile
        //return $webPage;
    }
    
    private function doWebPageServiceGetPagesByNavName($navName) {
        // Create the soap client for the WebPage service
        $soapClient = new SoapClient($this->soapUrl.$this->webPageUri);
        
        // Create the arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('name', $navName);
        
        // Send the get request
        $soapResult = $soapClient->GetPagesByNavName($soapArgs)->GetPagesByNavNameResult;
        print_r($soapResult);
        die();
        // FIXME - It is not clear what is returned if the navName is invalid
        if(!isset($soapResult) OR is_null($soapResult) OR ($soapResult == false)) {
            // The caller is expecting null on failure
            return null;
        } else {
            return $soapResult->WebPageModel; 
        }
        
        // Convert the model
        //$webPage = $this->convertWebPage($soapResult);
        
        // Return the profile
        //return $webPage;
    }

    private function doWebPageServiceGetPagesByParentId($guid) {
        // Create the soap client for the WebPage service
        $soapClient = new SoapClient($this->soapUrl.$this->webPageUri);
        
        // Create the arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('parentId', $guid);
        
        // Send the get request
        $soapResult = $soapClient->GetPagesByParentId($soapArgs)->GetPagesByParentIdResult;
        
        // FIXME - It is not clear what is returned if the navName is invalid
        if(!isset($soapResult->WebPageModel) OR is_null($soapResult) OR ($soapResult == false)) {
            // The caller is expecting null on failure
            return null;
        } else {
            return $soapResult->WebPageModel; 
        }
        
        // Convert the model
        //$webPage = $this->convertWebPage($soapResult);
        
        // Return the profile
        //return $webPage;
    }


    //----------------------------------------------------------------------------//
    //                               ContactService                               //
    //----------------------------------------------------------------------------//
    
    /**
     * Perform the Authenticate request on the ContactService.
     *
     * If authentication fails a value of <code>false</code> will
     * be returned.
     *
     * If authentication suceeds a <code>UserProfile</code> will
     * be returned.
     *
     * @param string $username the username
     * @param string $password the password
     * @return boolean|UserProfile false or the UserProfile for the user
     */
    private function doContactServiceAuthenticate($username, $password) {
        // We assume input is clean at this point.
        
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the username and password arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('username', $username);
        $soapArgs->addStringArgument('password', $password);
        
        // Send the auth request
        $soapResult = $soapClient->Authenticate($soapArgs)->AuthenticateResult;
        
        // FIXME - It is not clear what is returned when authentication fails
        if(!isset($soapResult) OR is_null($soapResult) OR ($soapResult == false)) {
            // The caller is expecting false on failure
            return false;
        }
        
        // Conver the soap mess.. er model to a UserProfile
        $userProfile = $this->convertUserProfile($soapResult);
        
        return $userProfile;
    }
    
    /**
     * Perform the Delete request on the ContactService.
     *
     * TODO Is this something we are responsible for?
     *
     * @param int $id the user id
     * @return boolean true if sucessful, otherwise false
     */
    private function doContactServiceDelete($id) {
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the id arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('id', $id);
        
        // Send the delete request
        $soapResult = $soapClient->Delete($soapArgs);
        
        // FIXME - The spec does not specify any fields in DeleteResponse
        
        return true;
    }
    
    /**
     * Perform the Get request on the ContactService.
     *
     * Will return the UserProfile for the specified user. If there was
     * a problem a null value will be returned.
     *
     * @param int $id the user id
     * @return UserProfile
     */
    private function doContactServiceGet($id) {
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the id arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('id', $id);
        
        // Send the get request
        $soapResult = $soapClient->Get($soapArgs)->GetResult;
        
        // FIXME - It is not clear what is returned if the id is invalid
        if(!isset($soapResult) OR is_null($soapResult) OR ($soapResult == false)) {
            // The caller is expecting null on failure
            return null;
        }
        
        // Convert the model
        $userProfile = $this->convertUserProfile($soapResult);
        
        // Return the profile
        return $userProfile;
    }
    
    public function doContactServiceGetDropDownOptionsForField($field) {
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the id arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('fieldName', $field);
        
        // Send the get request
        // XXX This is a prime example of long name abuse!
        $soapResult = $soapClient->GetDropDownOptionsForField($soapArgs)->GetDropDownOptionsForFieldResult;
        
        // soapResult contains ArrayOfDropDownOption
    }
    
    /**
     * Perform the Insert request on the ContctService.
     *
     * Returns true if the insert was successful, false otherwise.
     *
     * @param UserProfile $model the model to insert
     * @return boolean true on success, otherwise false
     */
    private function doContactServiceInsert(UserProfile $model) {
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the id arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addContactModelArgument('model', $this->convertUserProfile($model));
        
        // Send the insert
        // FIXME Fails
        $soapResult = $soapClient->Insert($soapArgs);
        
        // FIXME - The definition says InsertResult should be a string, but of what?
        echo "<pre>";
        print_r($soapResult);
        echo "</pre>";
        return true;
    }
    
    /**
     * Perform the Update request on the ContactService.
     *
     * Returns true if the update was sucessful, false otherwise.
     *
     * @param UserProfile $model the model to update
     * @return boolean true on sucess, otherwise false
     */
    private function doContactServiceUpdate(UserProfile $model) {
        // Create the soap client for the contact service
        $soapClient = new SoapClient($this->soapUrl.$this->contactUri);
        
        // Create the id arguments for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addContactModelArgument('model', $this->convertUserProfile($model));
        
        // Send the update
        $soapResult = $soapClient->Update($soapArgs);
        
        // FIXME - The spec does not specify any fields for UpdateResponse
        
        return true;
    }
    
    /**
     * Convert from a Nita ContactModel to a UserProfile or vice versa.
     *
     * This method handles the dual conversion to and from UserProfile.
     *
     * @param ContactModel|UserProfile $model either a Nita ContactModel or a UserProfile
     * @return UserProfile|ContactModel the converted model
     */
    private function convertUserProfile($model) {
        if($model instanceof UserProfile) {
            // Convert a UserProfile to a Nita ContactModel
            $soapModel = new SoapArguments();
            $soapModel->addStringArgument('address1_line1',           $model->address);
            // FIXME
            //$soapModel->addStringArgument('address1_line2', ???);
            //$soapModel->addStringArgument('address1_line3', ???);
            $soapModel->addStringArgument('address1_city',            $model->city);
            $soapModel->addStringArgument('address1_country',         $model->country);
            // FIXME This might need to be converted to some date format
            $soapModel->addStringArgument('createdon',                $model->creationTime);
            $soapModel->addStringArgument('description',              $model->description);
            $soapModel->addStringArgument('emailaddress1',            $model->email);
            $soapModel->addStringArgument('Nita_ethnicity',           $model->ethnicity);
            $soapModel->addStringArgument('fax',                      $model->fax);
            $soapModel->addStringArgument('firstname',                $model->firstName);
            // FIXME - No idea if this GenderCode is just male/female or something obscure
            $soapModel->addStringArgument('GenderCode',               $model->gender);
            $soapModel->addStringArgument('contactid',                $model->id);
            // FIXME - Do we provide this? or is it simply provided to us?
            $soapModel->addStringArgument('nita_web_last_login',      $model->lastLoginTime);
            $soapModel->addStringArgument('lastname',                 $model->lastName);
            $soapModel->addStringArgument('middlename',               $model->middleName);
            $soapModel->addStringArgument('modifiedon',               $model->modificationTime);
            $soapModel->addStringArgument('nita_web_password',        $model->password);
            $soapModel->addStringArgument('mobilephone',              $model->phoneMobile);
            $soapModel->addStringArgument('telephone1',               $model->phoneOne);
            $soapModel->addStringArgument('telephone2',               $model->phoneTwo);
            $soapModel->addStringArgument('address1_stateorprovince', $model->state);
            $soapModel->addStringArgument('nita_web_username',        $model->username);
            $soapModel->addStringArgument('address1_postalcode',      $model->zip);
            
            return $soapModel;
        } else {
            $userProfile = new UserProfile();
            $userProfile->address       = $model->address1_line1;
            // FIXME
            //$userProfile->??? = $model->address1_line2;
            //$userProfile->??? = $model->address1_line3;
            $userProfile->city              = $model->address1_city;
            $userProfile->country           = $model->address1_country;
            $userProfile->creationTime      = $model->createdon;
            $userProfile->description       = $model->description;
            $userProfile->email             = $model->emailaddress1;
            $userProfile->ethnicity         = $model->Nita_ethnicity;
            $userProfile->fax               = $model->fax;
            $userProfile->firstName         = $model->firstname;
            $userProfile->gender            = $model->GenderCode;
            $userProfile->id                = $model->contactid;
            $userProfile->lastLoginTime     = $model->nita_web_last_login;
            $userProfile->lastName          = $model->lastname;
            $userProfile->middleName        = $model->middlename;
            $userProfile->modificationTime  = $model->modifiedon;
            $userProfile->password          = $model->nita_web_password;
            $userProfile->phoneMobile       = $model->mobilephone;
            $userProfile->phoneOne          = $model->telephone1;
            $userProfile->phoneTwo          = $model->telephone2;
            $userProfile->state             = $model->address1_stateorprovince;
            $userProfile->username          = $model->nita_web_username;
            $userProfile->zip               = $model->address1_postalcode;
            
            // FIXME - Unimplemented fields are documented in UserProfile
        }
    }
    
    //----------------------------------------------------------------------------//
    //                               ProgramService                               //
    //----------------------------------------------------------------------------//
    
    /**
     * Perform the Get request on the ProgramService.
     *
     * If a result is returned then the model will be converted
     * into a CI model and returned.
     *
     * If there was no result returned, possibly because the id
     * was invalid, then a null value will be returned.
     *
     * @param string $id the unique identifier of the resource
     * @return ProgramModel the Program model for this resource, or null if there
     *         was no model for the id.
     */
    private function doProgramServiceGet($id) {
        // Create the soap client for the program service
        $soapClient = new SoapClient($this->soapUrl.$this->programUri);
        
        // Create the id argument for the soap client
        $soapArgs = new SoapArguments();
        $soapArgs->addStringArgument('id', $id);
        
        // Query the server and capture the result
        $soapResult = $soapClient->Get($soapArgs)->GetResult;
        
        // Only process the result if there actually was one.
        // The receiver should expect a null value if there
        // was no result.
        $programModel = null;
        if(!is_null($soapResult)) {
            $programModel = $this->convertProgramModel($soapResult);
        }
        
        return $programModel;
    }
    
    /**
     * Perform the GetActivePrograms request on the ProgramService.
     *
     * Results are returned as an unsorted, unfiltered array of Program
     * model objects that are active from the current date until some
     * time in the future.
     *
     * The default end search time is 1 year.
     *
     * @return array an array of Program model objects.
     */
    private function doProgramServiceGetActivePrograms() {
        $yearInSeconds = 365 * 24 * 60 * 60;
        
        // Create the soap client for the program service
        $soapClient = new SoapClient($this->soapUrl.$this->programUri);
        
        // Create arguments to set a start and end date
        $soapArgs = new SoapArguments();
        // Set the start date to the current time using ISO 8601 format
        $soapArgs->addDateArgument('startDate', date('c'));
        // Set the end date to the current time + 1 year in ISO 8601 format
        $soapArgs->addDateArgument('endDate', date('c', time() + $yearInSeconds));
        
        // Query the server and capture the result
        $soapResult = $soapClient->GetActivePrograms($soapArgs)->GetActiveProgramsResult;
        
        // Process the results into an array of Program objects
        $programs = array();
        foreach($soapResult->ProgramModel as $program) {
            $programs[] = $this->convertProgramModel($program);
        }
        
        return $programs;
    }
    
    /**
     * Converts the model returned by the soap service into a
     * CI model for Program objects.
     *
     * @param ProgramModel(ProgramService) $soapModel the model to convert
     */
    private function convertProgramModel($soapModel) {
        $programModel = new Program();
        $programModel->address          = $soapModel->NITA_FacilityAddr1 . ":" .
                                          $soapModel->NITA_FacilityAddr2 . ":" .
                                          $soapModel->NITA_FacilityAddr3 . ":" .
                                          $soapModel->NITA_FacilityAddr4;
        $programModel->capacityMax      = $soapModel->NITA_MaxCapacity;
        $programModel->capacityMin      = $soapModel->NITA_MinCapacity;
        $programModel->city             = $soapModel->NITA_FacilityCity;
        $programModel->description      = $soapModel->NITA_Description;
        $programModel->descriptor       = $soapModel->NITA_Descriptor;
        $programModel->dinnerDate       = $soapModel->NITA_FacultyDinnerDate;
        $programModel->dinnerLocation   = $soapModel->NITA_FacultyDinnerLocation;
        $programModel->director;    // Not in ProgramModel
        $programModel->discounts;   // Not in ProgramModel
        $programModel->duration         = $soapModel->NITA_Duration;
        $programModel->endDate          = $soapModel->NITA_EndDate;
        $programModel->id               = $soapModel->NITA_ProgramId;
        $programModel->location         = $soapModel->NITA_FacilityName;
        $programModel->name             = $soapModel->NITA_name;
        $programModel->price            = $soapModel->NITA_TuitionPriceStandard;
        $programModel->registerEnd      = $soapModel->NITA_RegistrationEndDate;
        $programModel->registerStart    = $soapModel->NITA_RegistrationBeginDate;
        $programModel->startDate        = $soapModel->NITA_StartDate;
        $programModel->state            = $soapModel->NITA_FacilityState;
        $programModel->title            = $soapModel->NITA_Title;
        $programModel->typeId           = $soapModel->nita_programtypeid;
        $programModel->zip              = $soapModel->NITA_FacilityZip;
        
        // Format dates
        $programModel->endDate       = $this->formatDate($programModel->endDate);
        $programModel->startDate     = $this->formatDate($programModel->startDate);
        $programModel->registerEnd   = $this->formatDate($programModel->registerEnd);
        $programModel->registerStart = $this->formatDate($programModel->registerStart);
        $programModel->dates         = $programModel->startDate . ' - ' . $programModel->endDate;
        
        // Format numbers
        $programModel->price = number_format($programModel->price,2,'.','');
        
        // Format text
        $programModel->description = nl2br($programModel->description);
        
        // Format program types
        if(key_exists($programModel->typeId, $this->programTypeMap)) {
            $programModel->typeId = $this->programTypeMap[$programModel->typeId];
        }
        return $programModel;
    }
    
    /**
     * Converts the model returned by the soap service into a
     * CI model for Content objects.
     *
     * @param WebPageModel(WebPageService) $soapModel the model to convert
     */
    private function convertContentModel($soapModel) {
        $contentModel = new Content();
        
        $contentModel->id       = $soapModel->Nita_webpageId;
        $contentModel->parentId = $soapModel->Nita_ParentPageId;
        $contentModel->navIsNav = $soapModel->Nita_nav_is_nav;
        $contentModel->navLog   = $soapModel->Nita_nav_log;
        $contentModel->navName  = $soapModel->Nita_nav_name;
        $contentModel->navOrder = $soapModel->Nita_nav_order;
        $contentModel->navUrl   = $soapModel->Nita_nav_url;
        $contentModel->navUse   = $soapModel->Nita_nav_use;
        $contentModel->date     = $soapModel->Nita_page_date;
        $contentModel->desc     = $soapModel->Nita_page_desc;
        $contentModel->header1  = $soapModel->Nita_page_header_1;
        $contentModel->header2  = $soapModel->Nita_page_header_2;
        $contentModel->image    = $soapModel->Nita_page_image;
        $contentModel->keywords = $soapModel->Nita_page_keywords;
        $contentModel->name     = $soapModel->Nita_page_name;
        $contentModel->style    = $soapModel->Nita_page_style;
        $contentModel->tag1     = $soapModel->Nita_page_tag_1;
        $contentModel->tag2     = $soapModel->Nita_page_tag_2;
        $contentModel->text     = $soapModel->Nita_page_text;
        
        // Do some date formatting
        $contentModel->date       = $this->formatDate($contentModel->date);
        
        return $contentModel;

    }
    
    private function formatDate($date) {
        return date($this->dateFormat, strtotime($date));
    }
}

class SoapArguments {
    
    public function SoapArguments() {
        
    }
    
    public function addArgument($name, $value, $soapType, $type) {
        $this->$name = new SoapVar($value, $soapType, $type);
    }
    
    public function addStringArgument($name, $value) {
        $this->addArgument($name, $value, XSD_STRING, "string");
    }
    
    public function addDateArgument($name, $value) {
        $this->addArgument($name, $value, XSD_DATETIME, "dateTime");
    }
    
    public function addContactModelArgument($name, SoapArguments $value) {
        // FIXME - Are these the proper soap types?
        $this->addArgument($name, $value, XSD_ANYTYPE, "ContactModel");
    }
}
/* End of file Soap.php */