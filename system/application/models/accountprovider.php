<?php

require_once BASEPATH.'/libraries/Model.php';

class AccountProvider extends Model {
    
    private $errors = array();
    
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
        return $this->doAuthenticate($email, $password);
    }
    
    public function getProfileByEmail($email) {
        return $this->selectProfileByEmail($email);
    }
    
    /**
     * Get the profile for a user.
     *
     * @param int $id the user's id
     * @return UserProfile model for the account
     */
    public function getProfileById($id) {
        return $this->selectProfileById($id);
    }
    
    public function getProfilesByAccount($id) {
        return $this->selectProfilesByAccount($id);
    }
    
    public function getAccount($id) {
        return $this->selectAccount($id);
    }
    
    public function storeProfile(array $data) {
        return $this->insertProfile($data);
    }
    
    public function storeAccount(array $data) {
        return $this->insertAccount($data);
    }
    
    public function updateProfile(array $data) {
        // TODO
    }
    
    public function updateAccount(array $data) {
        // TODO
    }
    
    // TEMPORARY SANDBOX CODE ///////////////////////////////////////////////////////////////////////
    
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
    
    private function selectAccount($id) {
        $result = $this->db->from('account')
                           ->where(array('id' => $id))
                           ->get();
        if($result->num_rows() != 1) {
            throw new RuntimeException("Account does not exist");
        }
        
        return $result->row();
    }
    
    private function selectProfileByEmail($email) {
        $result = $this->db->from('contact')
                           ->where(array('email' => $email))
                           ->get();
        
        if($result->num_rows() != 1) {
            throw new RuntimeException("User does not exist Email=".$email);
        }
        
        return $result->row();
    }
    
    private function selectProfileById($id) {
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
                           ->where(array('userId' => $id))
                           ->get();
        if($result->num_rows() === 0) {
            throw new RuntimeException("No bar IDs found for this user. ID=".$id);
        }
        $user->bar = $result->result_array();
        
        return $user;
    }
    
    private function selectProfilesByAccount($accountId) {
        $result = $this->db->select('id')
                           ->from('contact')
                           ->where(array('accountId' => $accountId))
                           ->get();
       
        foreach($result->result() as $profile) {
            $profiles[] = $this->selectProfileById($profile->id);
        }
        
        return $profiles;
    }
    
    private function insertAccount(array $data) {
        return $this->insertInto('account', $data);
    }
    
    private function insertProfile(array $data) {
        $barId = $data['barId']; unset($data['barId']);
        $state = $data['state']; unset($data['state']);
        $month = $data['month']; unset($data['month']);
        $year  = $data['year'];  unset($data['year']);
        
        $userId = $this->insertInto('contact', $data);
        
        // Create the bar entries
        for($i = 0; $i < count($barId); $i++) {
            $bar = array(
                'userId'  => $userId,
                'barId'   => $barId[$i],
                'state'   => $state[$i],
                'month'   => $month[$i],
                'year'    => $year[$i]
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
}

/* End of file UserProfile.php */