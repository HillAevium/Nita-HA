<?php

require_once APPPATH.'/models/core/field.php';
// FIXME Needs more work to deal with
// multiples properly
final class Enum_Field extends Field {
    
    private $values;
     
    public function Enum_Field($name, array $values) {
        parent::Field($name, 'enum');
        $this->values = $values;
    }
    
    public function equals(Field $that) {
        if(! $that instanceof Enum_Field) {
            return false;
        }
        if(! parent::equals($that)) {
            return false;
        }
        if(count(array_intersect($this->values, $that->values)) !== count($this->values)) {
            return false;
        }
        return true;
    }
    
    protected function doValidate($data) {
        if(! in_array($data, $this->values)) {
            $this->error = "Not a valid selection: " . $data;
            return false;
        }
    }
    
    protected function doProcess($data) {
        return $data;
    }
}