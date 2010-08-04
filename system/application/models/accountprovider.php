<?php

require_once BASEPATH.'/libraries/Model.php';

class AccountProvider extends Model {
    
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
    
    private $errors = array();
    
//    private $expireWindow = 3600; // 1 Hour
    
    public function AccountProvider() {
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
    public function authenticate($email, $password) {
        //$this->soap->userAuthenticate($email, $password);
        return $this->doAuthenticate($email, $password);
    }
    
    public function getUserByEmail($email) {
        return $this->selectUserByEmail($email);
    }
    
    /**
     * Get the profile for a user.
     *
     * @param int $id the user's id
     * @return UserProfile model for the account
     */
    public function getUserById($id) {
        //$this->soap->userGet($id);
        return $this->selectUserById($id);
    }
    
    public function getUsersByFirm($id) {
        return $this->selectUsersByFirm($id);
    }
    
    public function getFirm($id) {
        return $this->selectFirm($id);
    }
    
    public function storeUser(array $data) {
        $this->insertUser($data);
    }
    
    /* Scrapped by NITA
    public function storeUser(array $data) {
        // Store the current time in the data
        // so we can check if the data is still
        // valid
        $data['__time__'] = time() + $this->expireWindow;
        
        // Serialize the data structure
        $serial = serialize($data);
        
        log_message('error', 'strlen('. strlen($serial) .')');
        
        // MD5 for verify code and store the data in the session
        $code = md5($serial);
        $this->session->set_userdata(array($code => $serial));
        
        return $code;
    }
    
    public function verifyUser($code) {
        // Lookup the session for this code
        $serial = $this->session->userdata($code);
        
        // If the code does not exist, abort
        if(!$serial) {
            return HTTP_BAD_REQUEST;
        }
        
        $data = unserialize($serial);
        
        // Check the time, if we're past the expire
        // window, abort
        $expiryTime = $data['__time__'];
        $timeNow    = time();
        
        
        if($timeNow > $expiryTime) {
            return HTTP_TIMEOUT;
        }
        unset($data['__time__']);
        
        $id = $this->insertUser($data);
        
        return HTTP_CREATED;
    }
    */
    
    public function createFirm(array $data) {
        $this->insertFirm($data);
    }
    
    /**
     * Update the user's profile.
     *
     * @param UserProfile $model model for the account
     */
    public function update($model) {
        //$this->soap->userUpdate($model);
    }
    
    ///////////////////////////////////////////////////////////////////////
    
    private function doAuthenticate($email, $password) {
        $result = $this->db  ->  select('password')
                             ->  from('contact')
                             ->  where(array('email' => $email))
                             ->  get();
        
        if($result->num_rows() != 1) {
            return AUTH_NO_ACCOUNT;
        }
        
        if($password !== $result->row()->password) {
            return AUTH_BAD_PASS;
        }
        
        return AUTH_OK;
    }
    
    private function selectFirm($id) {
        $result = $this->db->from('account')
                           ->where(array('id' => $id))
                           ->get();
        if($result->num_rows() != 1) {
            throw new RuntimeException("Account does not exist");
        }
        
        return $result->row();
    }
    
    private function selectUserByEmail($email) {
        $result = $this->db->from('contact')
                           ->where(array('email' => $email))
                           ->get();
        
        if($result->num_rows() != 1) {
            throw new RuntimeException("User does not exist Email=".$email);
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
        
        return $user;
    }
    
    private function selectUsersByFirm($firmId) {
        $result = array();
        $ids = $this->getUserIdsForFirm($firmId);
        foreach($ids as $id) {
            $result[] = $this->selectUserById($id);
        }
        
        return $result;
    }
    
    private function getUserIdsForFirm($accountId) {
        $result = $this->db->select('id')
                           ->from('contact')
                           ->where(array('accountId' => $accountId))
                           ->get();
        if($result->num_rows() === 0) {
            throw new RuntimeException("No users found for account id. ID=".$accountId);
        }
    }
    
    private function insertFirm(array $data) {
        return $this->insertInto('account', $data);
    }
    
    private function insertUser(array $data) {
        $barId = $data['barId']; unset($data['barId']);
        $state = $data['state']; unset($data['state']);
        $date  = $data['date'];  unset($data['date']);
        
        $userId = $this->insertInto('contact', $data);
        
        // Create the bar entries
        for($i = 0; $i < count($barId); $i++) {
            $bar = array(
                'userId'  => $userId,
                'barId'   => $barId[$i],
                'state'   => $state[$i],
                'date'    => $date[$i]
            );
            $this->insertInto('contactbarinfo', $bar);
        }
        
        return $userId;
    }
    
    private function insertInto($table, array $data) {
        $this->db->insert($table, $data);
        
        if($this->db->affected_rows() !== 1) {
            log_message('error', print_r($data, true));
            throw new RuntimeException("INSERT INTO `".$table."` Failed. Data: ".print_r($data, true));
        }
        
        return $this->db->insert_id();
    }
    
    private function updateFirm($data, $id=0) {
        // TODO
    }
    
    private function updateUser($data, $id=0) {
        // TODO
    }
}

/* End of file UserProfile.php */