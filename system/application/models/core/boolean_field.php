<?php

require_once APPPATH.'/models/core/field.php';

final class Boolean_Field extends Field {
    
    public function Boolean_Field($name) {
        parent::Field($name, 'boolean');
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Boolean_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        return true;
    }
    
    protected function doValidate($data) {
        return $data === '0' || $data === '1';
    }
    
    protected function doProcess($data) {
        if($data === '0') {
            return false;
        }
        if($data === '1') {
            return true;
        }
    }
}