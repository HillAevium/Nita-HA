<?php

require_once BASEPATH.'/libraries/Model.php';

class UserProfile extends Model {
    
    // FIXME - Unknown models parameters
    // Nita_isln
    // nita_web_ip
    // nita_web_level
    // nita_web_log_count
    // nita_web_login_try
    // nita_web_type
    // nita_web_uncode
    // primarycustomerid
    // suffix
    
    public $address          = ''; // Street Address, Could have upto 3 lines
    public $city             = '';
    public $country          = '';
    public $creationTime     = '';
    public $description      = '';
    public $email            = '';
    public $ethnicity        = '';
    public $fax              = '';
    public $firstName        = '';
    public $gender           = '';
    public $id               = '';
    public $lastLoginTime    = '';
    public $lastName         = '';
    public $middleName       = '';
    public $modificationTime = '';
    public $password         = '';
    public $phoneMobile      = '';
    public $phoneOne         = '';
    public $phoneTwo         = '';
    public $state            = '';
    public $username         = '';
    public $zip              = '';
    
    private $errors = array();
    
    public function UserProfile() {
        parent::Model();
    }
    
    /**
     * Does an authentication check against the AccountService
     *
     * @param string $username the account username
     * @param string $password the account password
     * @return UserProfile model for the account
     * @throws AuthenticationException if there was a problem
     */
    public function authenticate($username, $password) {
        $this->soap->userAuthenticate();
    }
    
    /**
     * Get the profile for a user.
     *
     * @param int $id the user's id
     * @return UserProfile model for the account
     */
    public function get($id) {
        $this->soap->userGet($id);
    }
    
    public function insert(UserProfile $model) {
        $this->soap->userInsert($model);
    }
    
    public function loadForm(AbstractController $controller) {
        // First we check that required fields are present.
        if(!$controller->haveArgument('firstName')) {
            $this->error[] = "No first name supplied.";
        }
        if(!$controller->haveArgument('lastName')) {
            $this->error[] = "No last name supplied.";
        }
        if(!$controller->haveArgument('address')) {
            $this->error[] = "No address supplied.";
        }
        if(!$controller->haveArgument('city')) {
            $this->error[] = "No city supplied.";
        }
        if(!$controller->haveArgument('state')) {
            $this->error[] = "No state supplied.";
        }
        if(!$controller->haveArgument('phone')) {
            $this->error[] = "No phone number supplied.";
        }
        if(!$controller->haveArgument('email')) {
            $this->error[] = "No email supplied.";
        }
        if(!$controller->haveArgument('username')) {
            $this->error[] = "No username supplied.";
        }
        if(!$controller->haveArgument('password')) {
            $this->error[] = "No password supplied.";
        }
        
        // If there are errors dont bother continuing
        if(sizeof($this->errors) > 0) {
            return false;
        }
        
        // Next check that the information is sane.
        // TODO Look into using CI Form Validator class for this
        
        $this->firstName = $controller->getArgument('firstName');
        $this->lastName  = $controller->getArgument('lastName');
        $this->address   = $controller->getArgument('address');
        $this->city      = $controller->getArgument('city');
        $this->state     = $controller->getArgument('state');
        $this->phoneOne  = $controller->getArgument('phone');
        $this->email     = $controller->getArgument('email');
        $this->username  = $controller->getArgument('username');
        $this->password  = $controller->getArgument('password');
        
        return true;
    }
    
    /**
     * Update the user's profile.
     *
     * @param UserProfile $model model for the account
     */
    public function update(UserProfile $model) {
        $this->soap->userUpdate($model);
    }
}

/* End of file UserProfile.php */