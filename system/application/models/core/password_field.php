<?php

require_once APPPATH.'/models/core/string_field.php';
require_once APPPATH.'/models/core/field.php';

class Password_Field extends String_Field {
    
    private $matchName;
    
    public function Password_Field($name, $matchName, $min, $max) {
        parent::String_Field($name, 'password', $min, $max);
        $this->matchName = $matchName;
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Password_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        if($this->matchName !== $that->matchName) {
            return false;
        }
        return true;
    }
    
    public function doValidate($password) {
        // If there is a match name supplied check that
        // they are equal
        if($this->matchName !== '') {
            $password2 = get_instance()->input->post($this->matchName);
            if($password !== $password2) {
                $this->error = "Passwords do not match.";
                return false;
            }
        }
        
        return parent::doValidate($password);
    }
}