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
    
    /* Variable definitions
     * $address  - a colon separated list of 4 possible address fields
     * $director - Contains an array of arrays. Each array has two keys,
     *             one for the director's name and one for a link to their
     *             bio. They are called 'name' and 'bio' respectively.
     */
    
    // TODO - Dealing with facility address
    // It might be easier for the view if we combine the facility location
    // elements into a single string, or at least an array of elements. At
    // the moment i dont like the way it is, but we won't know the best format
    // for the view until the view is actually using this info.
    
    public $address        = '';
    public $capacityMax    = '';
    public $capacityMin    = '';
    public $city           = '';
    public $cleCredits     = '';
    public $description    = '';
    public $descriptor     = '';
    public $dinnerDate     = '';
    public $dinnerLocation = '';
    public $director       = array();
    public $discounts      = array(); // may require an object
    public $duration       = '';
    public $endDate        = '';
    public $id             = '';
    public $location       = '';
    public $name           = '';
    public $price          = '';
    public $registerEnd    = '';
    public $registerStart  = '';
    public $startDate      = '';
    public $state          = '';
    public $zip            = '';
    
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
        $program = $this->soap->getProgram($id);
        
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