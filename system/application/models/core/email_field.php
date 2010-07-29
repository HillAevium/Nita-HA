<?php

require_once APPPATH.'/models/core/string_field.php';
require_once APPPATH.'/models/core/field.php';

final class Email_Field extends String_Field {
    
    private static $regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    
    public function Email_Field($name) {
        parent::String_Field($name, 'email', 6);
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Email_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        return true;
    }
    
    public function doValidate($data) {
        if(! preg_match(self::$regex, $data)) {
            $this->error = "Not a valid email address";
            return false;
        }
        
        return parent::doValidate($data);
    }
}