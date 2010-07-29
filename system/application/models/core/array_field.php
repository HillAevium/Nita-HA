<?php

require_once APPPATH.'/models/core/field.php';

final class Array_Field extends Field {
    
    private $field;
    private $fields = array();
    private $min;
    private $max;
    
    public function Array_Field(Field $field, $min = 0, $max = PHP_INT_MAX) {
        // Mimick the underlying type
        parent::Field($field->name(), $field->type());
        $this->field = $field;
        $this->min = $min;
        $this->max = $max;
    }

    public function equals(Field $that) {
        if(! $that instanceof Array_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        if(! $this->field->equals($that->field)) {
            return false;
        }
        return true;
    }
    
    protected function doValidate($list) {
        if(! is_array($list)) {
            $this->error = "Not an array.";
            return false;
        }
        $size = count($list);
        if($size < $this->min) {
            $this->error = "Not enough elements found. " . $size . "/" . $this->min;
            return false;
        }
        if($size > $this->max) {
            $this->error = "Too many elements found. " . $size . "/" . $this->max;
            return false;
        }
        foreach($list as $item) {
            // Don't call validate() as it can only be called once.
            if(! $this->field->doValidate($item)) {
                $this->error = $this->field->error;
                return false;
            }
        }
        return true;
    }
    
    protected function doProcess($list) {
        $result = array();
        // Don't call process() as it can only be called once.
        foreach($list as $item) {
            $result[] = $this->field->doProcess($item);
        }
        return $result;
    }
}