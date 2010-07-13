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
    // Date Format -> March 23rd, 2009
    private $dateFormat = 'F jS, Y';
    
    /**
     * Construct a new Soap API object
     */
    public function Soap() {
        global $soap;
        $this->soapUrl    = $soap['server'];
        $this->programUri = $soap['services']['programs'];
    }
    
    public function getContent($id) {
        // TODO
    }
    
    public function getContentByNavName($navName) {
        // TODO
    }
    
    public function getContentTree($id) {
        // TODO
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
    
    public function getPrograms(FilterSet $filters) {
        // TODO
    }
    
    public function getPublication($id) {
        // TODO
    }
    
    public function getPublications(FilterSet $filters) {
        // TODO
    }
    
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
     * @return Program the Program model for this resource, or null if there
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
        foreach($soapResult as $program) {
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
        $programModel->address          = $soapModel->NITA_FacilityAddress1 . ":" .
                                          $soapModel->NITA_FacilityAddress2 . ":" .
                                          $soapModel->NITA_FacilityAddress3 . ":" .
                                          $soapModel->NITA_FacilityAddress4;
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
        $programModel->name             = $soapModel->NITA_Title;
        $programModel->price            = $soapModel->NITA_TuitionPriceStandard;
        $programModel->registerEnd      = $soapModel->NITA_RegistrationEndDate;
        $programModel->registerStart    = $soapModel->NITA_RegistrationBeginDate;
        $programModel->startDate        = $soapModel->NITA_StartDate;
        $programModel->state            = $soapModel->NITA_FacilityState;
        $programModel->zip              = $soapModel->NITA_FacilityZip;
        
        // Do some date formatting
        $programModel->endDate       = $this->formatDate($programModel->endDate);
        $programModel->startDate     = $this->formatDate($programModel->startDate);
        $programModel->registerEnd   = $this->formatDate($programModel->registerEnd);
        $programModel->registerStart = $this->formatDate($programModel->registerStart);
        
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
}
/* End of file Soap.php */