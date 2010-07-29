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
    public function authenticate($username, $password) {
        $this->soap->userAuthenticate();
    }
    
    /**
     * Get the profile for a user.
     *
     * @param int $id the user's id
     * @return UserProfile model for the account
     */
    public function getUserById($id) {
        //$this->soap->userGet($id);
        $this->selectUserById($id);
    }
    
    public function getUserByFirm($id) {
        $this->selectUsersByFirm($id);
    }
    
    public function getFirm($id) {
        $this->selectFirm($id);
    }
    
    public function storeUser(array $data) {
        // return the insert query
        return $this->insertForUser($data);
    }
    
    public function verifyUser($code) {
        return $this->doInsert($code);
    }
    
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
    
    private function selectFirm($id) {
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
    
    private function doInsert($insert) {
        $this->db->query($insert);
        return $this->db->insert_id();
    }
    
    private function insertFirm(array $data) {
        return $this->insertInto('account', $data);
    }
    
    private function insertForUser(array $data) {
        unset($data['barId']);
        unset($data['state']);
        unset($data['date']);
        
        $insert = $this->db->insert_string('contact', $data);
        
        return $insert;
    }
    
    private function insertUser(array $data) {
        $bars = $data['bar'];
        unset($data['bar']);
        
        log_message('debug', 'before insert');
        log_message('debug', print_r($data, true));
        $userId = $this->insertInto('contact', $data);
        log_message('debug', 'after insert');
        
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
    
    private function updateFirm($data, $id=0) {
        // TODO
    }
    
    private function updateUser($data, $id=0) {
        // TODO
    }
}

/* End of file UserProfile.php */