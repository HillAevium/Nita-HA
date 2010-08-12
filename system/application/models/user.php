<?php

/**
 * A model for a User.
 *
 */
class User extends Model {
    
    public $firstName = '';
    public $lastName  = '';
    public $address   = '';
    public $city      = '';
    public $state     = '';
    public $phone     = '';
    public $email     = '';
    public $username  = '';
    
    public function User() {
        parent::Model();
    }
    
}

/* End of file User.php */