<?php

require_once APPPATH.'/models/core/field.php';

if(! defined('PHP_INT_MIN')) {
    define('PHP_INT_MIN', ~PHP_INT_MAX);
}

class Int_Field extends Field {
    
    private $min;
    private $max;
    
    public function Int_Field($name, $min = PHP_INT_MIN, $max = PHP_INT_MAX) {
        parent::Field($name, 'int');
        $this->min = $min;
        $this->max = $max;
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Int_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        if($this->min !== $that->min) {
            return false;
        }
        if($this->max !== $that->max) {
            return false;
        }
        return true;
    }
    
    protected function doValidate($data) {
        if(! is_int($data)) {
            $this->error = "Not an integer.";
            return false;
        }
        $value = (int) $data;
        if($value > $this->max) {
            $this->error = "Number cannot be larger than " . $this->max;
            return false;
        }
        if($value < $this->min) {
            $this->error = "Number cannot be smaller than " . $this->min;
            return false;
        }
        
        return true;
    }
    
    protected function doProcess($data) {
        return (int) $data;
    }
}