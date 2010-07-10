<?php

/**
 * Represents a physical address
 *
 * @author Chris Boertien (chris.boertien@gmail.com)
 */
class Location {
    
    private $state   = '';
    private $city    = '';
    private $street  = '';
    private $zipCode = 0;
    
    /**
     * Creates a new Location
     *
     * @param string $state
     * @param string $city
     * @param string $street
     * @param string $zipCode
     */
    public function Location($state, $city, $street, $zipCode) {
        $this->state   = $state;
        $this->city    = $city;
        $this->street  = $street;
        $this->zipCode = $zipCode;
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function getCity() {
        return $this->city;
    }
    
    public function getStreet() {
        return $this->street;
    }
    
    public function getZipCode() {
        return $this->street;
    }
}

/* End of file Location.php */