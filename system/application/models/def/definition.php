<?php

abstract class Definition {
    
    private $fields = array();
    
    public function addField(Field $field) {
        $fields[] = $field;
    }
}

class Field {
    
    private $name;
    private $type;
    private $required;
    
    // FIXME maxLength
    public function Field($name, $type, $required) {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
    }
}