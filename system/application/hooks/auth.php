<?php

class Auth {
    public function init() {
        // Load the authenication module
        
        $CI =& get_instance();
        if(!isset($CI->mod_auth)) {
            $CI->load->library('core/auth/session/Session_Authenticator', null, 'mod_auth');
        }
        
        $CI->mod_auth->init();
    }
}