<?php

require_once APPPATH.'/libraries/core/auth/authenticator.php';

define('AUTH_NOT_AUTHENTICATED', 0);
define('AUTH_AUTHENTICATED', 1);

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
        $authStatus = $this->session->userdata('authStatus');
        
        // New session that has not been initialized
        if($authStatus === false) {
            $this->revoke();
        } else {
            switch($authStatus) {
                case AUTH_NOT_AUTHENTICATED :
                    // TODO need to run the request through a filter
                    // chain. The filter chain will make sure that the
                    // request being made is ok for an unauthenticated
                    // session.
                    log_message('debug', 'Unauthenticated Session');
                    break;
                case AUTH_AUTHENTICATED :
                    // TODO same here only for an authenticated session
                    log_message('debug', 'Authenticated Session');
                    break;
                default :
                    throw new RuntimeException("Unknown Auth Status : " . $authStatus);
            }
        }
    }
    
    public function grant($id) {
        $this->session->set_userdata('authStatus', AUTH_AUTHENTICATED);
        $this->session->set_userdata('id', $id);
    }
    
    public function revoke() {
        $this->session->set_userdata('authStatus', AUTH_NOT_AUTHENTICATED);
        $this->session->unset_userdata('id');
    }
    
    public function isAuthenticated() {
        $authStatus = (int) $this->session->userdata('authStatus');
        
        return $authStatus === AUTH_AUTHENTICATED;
    }
    
    public function credentials() {
        if(!$this->isAuthenticated()) {
            return null;
        }
        
        // FIXME
        $credentials = new stdClass();
        $credentials->id = $this->session->userdata('id');
        
        return $credentials;
    }
    
    public function __toString() {
        log_message('error', $this->isAuthenticated() ? 'true' : 'false');
        if($this->isAuthenticated()) {
            $credentials = $this->getCredentials();
            return "Authenticated: YES\n" .
                   "ID: " . $credentials->id;
        } else {
            return "Authenticated: NO";
        }
    }
}