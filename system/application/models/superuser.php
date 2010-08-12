<?php

require_once APPPATH.'/models/user.php';

/**
 * A model for a SuperUser.
 *
 * @author Daniel Zukowski (daniel.zukowski@gmail.com)
 */
class Superuser extends User {
    
    public function Superuser() {
        parent::User();
    }   
}

/* End of file SuperUser.php */