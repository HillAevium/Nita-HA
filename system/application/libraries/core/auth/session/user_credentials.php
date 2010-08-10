<?php

define('USER_SUPER', 1);
define('USER_NORMAL', 2);
define('USER_CHILD', 3);
define('USER_ANON', 4);
define('USER_DEBUG', USER_NORMAL);

require_once APPPATH.'/libraries/core/auth/credentials.php';

class User_Credentials implements Credentials {
    
    public $auth = array();
    public $user = array();
    
    public function User_Credentials() {
        
    }
}