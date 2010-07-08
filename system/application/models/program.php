<?php

require_once BASEPATH.'libraries/Model.php';
require_once APPPATH.'libraries/location.php';
require_once APPPATH.'libraries/filter/filterset.php';

/**
 * A model for a Program.
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Program extends Model {
    
    public $date         = null;    // Date
    public $description  = "";
    public $discounts    = array(); // may require an object
    public $id           = 0;
    public $location     = null;    // Location
    public $name         = "";
    public $price        = 0.0;
    public $programDates = array();
    
    /*TODO - Parameters
     * Director(s) / speaker(s) - provide links to bios
     * Schedule? (see PLI)
     * CLE credit information
     * Travel/Logistics (Notes?)
     * General Program Info (Notes?)
     * Program Materials (available only to registered attendees)
     * Related Materials [will probably require a more web service API]
     */
    
    public function Program() {
        parent::Model();
        // TODO - Remove this once the soap API goes live
        $this->soap =& $this->mocksoap;
    }
    
    /**
     * Get a list of all Programs
     *
     * @return an array of Programs
     */
    public function getAllPrograms() {
        $programs = $this->soap->getAllPrograms();
        
        return $programs;
    }
    
    /**
     * Get a specific program based on the item id
     *
     * @param int $id the id of a Program
     * @return a Program matching the id
     */
    public function getProgram($id) {
        $program = $this->soap->getProgram();
        
        return $program;
    }
    
    /**
     * Get a list of Programs based on a set of search filters
     *
     * @param FilterSet $filters a set of search parameters to apply to the query
     * @return an array of Programs
     */
    public function getPrograms(FilterSet $filters) {
        $programs = $this->soap->getPrograms($filters);
        
        return $programs;
    }
}

/* End of file program.php */