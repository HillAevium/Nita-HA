<?php

require_once BASEPATH.'/libraries/Model.php';
require_once APPPATH.'/libraries/filter/FilterSet.php';

/**
 * A model for a Publication
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Publication extends Model {
    
    public $authors         = array();
    public $description     = '';
    public $edition         = '';
    public $id              = '';
    public $image           = '';
    public $name            = '';
    public $pages           = 0;
    public $price           = 0.0;
    public $relatedProducts = array();
    public $tableOfContents = array(); // maybe need something more elaborate for this?
    public $type            = '';
    public $year            = 0;
    
    public function Publication() {
        parent::Model();
        // TODO - Remove this once the soap API goes live
        $this->soap =& $this->mocksoap;
    }
    
    /**
     * Get a list of all publications.
     *
     * @return an array of Publications
     */
    public function getAllPublications() {
        $publications = $this->soap->getAllPublications();
        
        return $publications;
    }
    
    /**
     * Get a specific Publication based on the items id
     *
     * @param int $id the id of a Publication
     * @return a Publication matching the id
     */
    public function getPublication($id) {
        $publication = $this->soap->getPublication($id);
        
        return $publication;
    }
    
    /**
     * Get a list of publications based on a set of search filters.
     *
     * @param FilterSet $filters a set of search parameters to apply to the query
     * @return an array of Publications
     */
    public function getPublications(FilterSet $filters) {
        $publications = $this->soap->getPublications($filters);
        
        return $publications;
    }
}

/* End of file Publication.php */