<?php

require_once APPPATH.'/models/core/string_field.php';
require_once APPPATH.'/models/core/field.php';

final class Date_Field extends Field {
    
    public function Date_Field($name) {
        parent::Field($name, 'date');
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Date_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        return true;
    }
    
    protected function doValidate($date) {
        $time = strtotime($date);
        // PHP <5.1 returns -1
        if($time === -1 || $time === false) {
            return false;
        }
        return true;
    }
    
    protected function doProcess($date) {
        return strtotime($date);
    }
}