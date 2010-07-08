<?php

require_once '../libraries/filter/filterset.php';

/**
 * A model for a Publication
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Publication extends Model {
    
    private $authors         = array();
    private $description     = '';
    private $edition         = '';
    private $id              = 0;
    private $image           = '';
    private $name            = '';
    private $numberOfPages   = 0;
    private $price           = 0.0;
    private $relatedProducts = array();
    private $tableOfContents = array(); // maybe need something more elaborate for this?
    private $type            = '';
    private $year            = 0;
    
    public function Publication() {
        parent::Model();
    }
    
    /**
     * Get a list of all publications.
     *
     * @return an array of Publications
     */
    public function getAllPublications() {
        $publications = $this->soap->getAllPublications;
        
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

/* End of file publication.php */