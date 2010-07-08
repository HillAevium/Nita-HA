<?php

/**
 * API for interacting with the CRM backend.
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Soap {
    
    // Singleton SoapClient
    private static $soapClient;
    
    /**
     * Construct a new Soap API object
     *
     * @param string $url location for the Soap client to connect to.
     */
    public function Soap($url) {
        Soap::$soapClient = new SoapClient($url);
    }
    
    public function getAllPrograms() {
        // TODO
    }
    
    public function getAllPublications() {
        // TODO
    }
    
    public function getProgram($id) {
        // TODO
    }
    
    public function getPrograms(FilterSet $filters) {
        // TODO
    }
    
    public function getPublication($Id) {
        // TODO
    }
    
    public function getPublications(FilterSet $filters) {
        // TODO
    }
}

/* End of file soap.php */