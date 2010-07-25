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
    
    private static $requiredContactFields = array (
        'firstname'             => 'ERROR_FIRST_NAME',
        'firstName'             => 'ERROR_FIRST_NAME',
        'lastName'              => 'ERROR_LAST_NAME',
        'email'                 => 'ERROR_EMAIL',
        'password'              => 'ERROR_PASSWORD',
        'phone'                 => 'ERROR_PHONE',
        'role'                  => 'ERROR_ROLE',
        'billingAddress1'       => 'ERROR_BILL_ADDRESS',
        'billingCity'           => 'ERROR_BILL_ADDRESS',
        'billingState'          => 'ERROR_BILL_STATE',
        'billingZip'            => 'ERROR_BILL_ZIP',
        'billingCountry'        => 'ERROR_BILL_COUNTRY',
        'shippingAddress1'      => 'ERROR_SHIP_ADDRESS',
        'shippingCity'          => 'ERROR_SHIP_CITY',
        'shippingState'         => 'ERROR_SHIP_STATE',
        'shippingZip'           => 'ERROR_SHIP_ZIP',
        'shippingCountry'       => 'ERROR_SHIP_COUNTRY',
        'requireAccessibility'  => 'ERROR_REQ_ACCESS',
        'haveScolarship'        => 'ERROR_SCOLARSHIP'
    );
    
    private static $optionalContactFields = array(
        'accountId', 'salutation', 'middleInitial', 'suffix', 'title',
        'phone2', 'fax', 'companyName', 'typeOfPractice', 'lawSchoolAttended',
        'firmSize', 'ethnicity', 'lawInterests', 'trainingDirector'
    );
    
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
    
    public function insert(array $data) {
        $this->soap->userInsert($data);
    }
    
    public function getRequiredFields() {
        return self::$requiredContactFields;
    }
    
    public function getOptionalFields() {
        return self::$optionalContactFields;
    }
    
    /**
     * Update the user's profile.
     *
     * @param UserProfile $model model for the account
     */
    public function update(UserProfile $model) {
        $this->soap->userUpdate($model);
    }
    
    private function selectAccount($id) {
        $result = $this->db->from('account')
                           ->where(array('id' => $id))
                           ->get();
        if($result->num_rows() != 1) {
            throw new RuntimeException("Account does not exist");
        }
        
        return $result->row();
    }
    
    private function selectUserById($id) {
        // Get the user info
        $result = $this->db->from('contact')
                           ->where(array('id' => $id))
                           ->get();
        if($result->num_rows() != 1) {
            throw new RuntimeException("User does not exist ID=".$id);
        }
        $user = $result->row();
        
        // And the bar info
        $result = $this->db->from('contactbarinfo')
                           ->where(array('id' => $id))
                           ->get();
        if($result->num_rows() === 0) {
            throw new RuntimeException("No bar IDs found for this user. ID=".$id);
        }
        $user->bar = $result->result_array();
        
        //TODO return (UserProfile) $user;
        return $user;
    }
    
    private function selectUsersByAccount($accountId) {
        $result = array();
        $ids = $this->getUserIdsForAccount($accountId);
        foreach($ids as $id) {
            $result[] = $this->selectUserById($id);
        }
        
        return $result;
    }
    
    private function getUserIdsForAccount($accountId) {
        $result = $this->db->select('id')
                           ->from('contact')
                           ->where(array('accountId' => $accountId))
                           ->get();
        if($result->num_rows() === 0) {
            throw new RuntimeException("No users found for account id. ID=".$accountId);
        }
    }
    
    private function insertAccount($data) {
        return $this->insertInto('account', $data);
    }
    
    private function insertUser($data) {
        $bars = $data['bar'];
        unset($data['bar']);
        
        $userId = $this->insertInto('contact', $data);
        
        foreach($bars as $bar) {
            $bar['userId'] = $userId;
            $this->insertInto('contactbarinfo', $bar);
        }
    }
    
    private function insertInto($table, array $data) {
        $this->db->insert($table, $data);
        
        if($this->db->affected_rows() !== 1) {
            log_message('error', print_r($data, true));
            throw new RuntimeException("INSERT INTO `".$table."` Failed. Data: ".print_r($data, true));
        }
        
        return $this->db->insert_id();
    }
    
    private function updateAccount($data, $id=0) {
        // TODO
    }
    
    private function updateUser($data, $id=0) {
        // TODO
    }
}

/* End of file UserProfile.php */