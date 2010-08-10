<?php

require_once APPPATH.'/libraries/core/auth/authenticator.php';
require_once APPPATH.'/libraries/core/auth/session/user_credentials.php';

class Session_Authenticator implements Authenticator {
    
    private $session;
    
    public function Session_Authenticator() {
        $CI =& get_instance();
        if(!isset($CI->session)) {
            $CI->load->library('session');
        }
        $this->session = $CI->session;
    }
    
    public function init() {
        // Loading makes sure that we have valid credentials
        // before continuing. On a new session anon credentials
        // are generated.
        $this->loadCredentials();
        
        $authStatus = $this->credentials->auth['status'];
        
        switch($authStatus) {
            case AUTH_NOT_AUTHENTICATED :
                // TODO need to run the request through a filter
                // chain. The filter chain will make sure that the
                // request being made is ok for an unauthenticated
                // session.
                break;
            case AUTH_AUTHENTICATED :
                // TODO same here only for an authenticated session
                break;
            default :
                throw new RuntimeException("Unknown Auth Status : " . $authStatus);
        }
    }
    
    public function grant($id, array $user) {
        $this->credentials->auth['status'] = AUTH_AUTHENTICATED;
        $this->credentials->auth['id'] = $id;
        $this->credentials->user['type'] = $user['type'];
        $this->storeCredentials($this->credentials);
    }
    
    public function revoke() {
        $this->credentials->auth['status'] = AUTH_NOT_AUTHENTICATED;
        $this->credentials->auth['id'] = -1;
        $this->credentials->user['type'] = USER_ANON;
        $this->storeCredentials($this->credentials);
    }
    
    public function isAuthenticated() {
        $authStatus = (int) $this->credentials->auth['status'];
        return $authStatus === AUTH_AUTHENTICATED;
    }
    
    public function credentials() {
        return $this->credentials;
    }
    
    public function __toString() {
        $auth = $this->credentials->auth['status'] === AUTH_AUTHENTICATED;
        $id   = $this->credentials->auth['id'];
        $type = $this->credentials->user['type'];
        
        $string = 'Authenticated: ' . ($auth ? 'Yes' : 'No') . '<br />';
        $string.= 'ID: ' . $id . '<br />';
        $string.= 'Type: ' . $type;
        return $string;
    }
    
    private function createCredentials() {
        // TODO
        // Might want to destroy the session when credentials are created
        // to ensure that there is no stale session information kicking around.
        // Will have to test the consequences of a complete session wipe, might
        // be just as well to simply make sure all authentication data has
        // been cleared.
        $credentials = new User_Credentials();
        $credentials->auth['status'] = AUTH_NOT_AUTHENTICATED;
        $credentials->auth['id'] = -1;
        $credentials->user['type'] = USER_ANON;
        
        $this->credentials = $credentials;
        $this->storeCredentials($credentials);
    }
    
    private function loadCredentials() {
        $credentials = $this->session->userdata('auth.credentials');
        if($credentials === false) {
            $this->createCredentials();
            return;
        }
        
        $credentials = unserialize($credentials);
        if($this->verifyCredentials($credentials)) {
            $this->credentials = $credentials;
        } else {
            // This shouldn't occur unless the session data is tampered
            // with or the data has been corrupted. Log the error
            // and gracefully reset authentication.
            log_message('error', __METHOD__);
            log_message('error', 'Invalid Credentials in Session');
            log_message('error', print_r($credentials, true));
            $this->createCredentials();
        }
    }
    
    private function storeCredentials(Credentials $credentials) {
        // Credential corruption is more likely to happen here
        // instead of load. Same deal, log the error and reset
        // authentication.
        if(!$this->verifyCredentials($credentials)) {
            log_message('error', __METHOD__);
            log_message('error', 'Invalid Credentials');
            log_message('error', print_r($credentials, true));
            $this->createCredentials();
        }
        
        $this->session->set_userdata('auth.credentials', serialize($credentials));
    }
    
    private function verifyCredentials(Credentials $credentials) {
        // TODO
        return true;
    }
}