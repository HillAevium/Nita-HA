<?php

abstract class Definition {
    
    private $fields = array();
    
    public function addField(Field $field) {
        $this->fields[] = $field;
    }
    
    public function fields() {
        return $this->fields;
    }
}

class Field {
    
    public $name;
    public $type;
    public $required;
    
    // FIXME maxLength
    public function Field($name, $type, $required) {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
    }
}

class ArrayField extends Field {
    
    //Field[]
    public $fields = array();
    public $min;
    public $max;
    
    public function ArrayField($name, $min, $max) {
        $this->name = $name;
        $this->type = 'array';
        $this->required = $min > 0;
        $this->min = $min;
        $this->max = $max;
    }
    
    public function addField(Field $field) {
        $this->fields[$field->name] = $field;
    }
    
    public function fields() {
        return $this->fields;
    }
}