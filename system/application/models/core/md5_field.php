<?php

require_once APPPATH.'/models/core/string_field.php';

class MD5_Field extends String_Field {
    
    public function MD5_Field($name) {
        parent::String_Field($name, 'md5', 32, 32);
    }
    
    public function equals(Field $field) {
        if(! $field instanceof MD5_Field) {
            return false;
        }
        if(! parent::equals($field)) {
            return false;
        }
        return true;
    }
    
    public function doValidate($data) {
        if(! parent::doValidate($data)) {
            return false;
        }
        // TODO check for proper md5 [a-f0-9A-F]
        if(! preg_match("/^[a-fA-F0-9]+$/", $data)) {
            $this->error = "Not a md5 hashcode";
            return false;
        }
        return true;
    }
}