<?php

require_once APPPATH.'/models/core/field.php';

class String_Field extends Field {
    
    private $maxLength;
    private $minLength;
    
    public function String_Field($name, $type = 'string', $minLength = 0, $maxLength = 255) {
        parent::Field($name, $type);
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
    }
    
    public function equals(Field $that) {
        if(! $that instanceof String_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        if($this->maxLength !== $that->maxLength) {
            return false;
        }
        if($this->minLength !== $that->maxLength) {
            return false;
        }
    }
    
    public function doValidate($data) {
        if(strlen($data) > $this->maxLength) {
            $this->error = $this->type() . ' must be less than ' . $this->maxLength . ' characters.';
            return false;
        }
        if(strlen($data) < $this->minLength) {
            $this->error = $this->type() . ' must be more than ' . $this->minLength . ' characters.';
            return false;
        }
        
        return true;
    }
    
    public function doProcess($data) {
        return $data;
    }
}