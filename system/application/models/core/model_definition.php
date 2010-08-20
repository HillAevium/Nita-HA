<?php

require_once APPPATH.'/models/core/has_fields.php';
require_once APPPATH.'/models/core/field.php';

abstract class Model_Definition implements Has_Fields {
    
    public static function runtimeInstance() {
        return new Runtime_Definition();
    }
    
    // A list of fields
    protected $fields = array();
    
    private $state = 'required';
    private $dependant = null;
    
    // List<String>
    private $requiredFields = array();
    
    // List<String>
    private $optionalFields = array();
    
    // Map<String, List<String>>
    private $dependantFields = array();
    
    private $processed = false;
    private $processErrors = array();
    
    public function Model_Definition() {
        // no-op
    }
    
    // Has_Fields
    public function addField(Field $field) {
        switch($this->state) {
            case 'required' :
                $this->requiredFields[] = $field->name();
                break;
            case 'optional' :
                $this->optionalFields[] = $field->name();
                break;
            case 'dependant' :
                $this->dependantFields[$this->dependant->name()][] = $field->name();
                 break;
        }
        $this->fields[] = $field;
    }
    
    // Has_Fields
    public function addFields(array $fields) {
        foreach($fields as $field) {
            if(! $field instanceof Field) {
                throw new InvalidArgumentException("Only Field objects allowed");
            }
            $this->addField($field);
        }
    }
    
    public function errors() {
        return $this->processErrors;
    }
    
    // Has_Fields
    public function fields() {
        return $this->fields;
    }
    
    // Has_Fields
    public function names() {
        $names = array();
        foreach($this->fields as $field) {
            $names[] = $field->name;
        }
        return $names;
    }
    
    public function isRequired(Field $field) {
        if(in_array($field->name(), $this->requiredFields)) {
            return true;
        }
        if(in_array($field->name(), $this->optionalFields)) {
            return false;
        }
        
        foreach($this->dependantFields as $depName => $fields) {
            if(in_array($field->name(), $fields)) {
                $depField = $this->storedDependants[$depName];
                $depValue = $this->storedValues[$depName];
                if($depField->matches($depValue)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        throw new RuntimeException("Field has not been created " . $field->name());
    }
    
    public function processPost($returnType = 'object') {
        if($this->processed) {
            return $this->processResult;
        }
        
        $returnData = array();
        $errors = array();
        
        foreach($this->fields() as $field) {
            if($field->isEmpty()) {
                if($this->isRequired($field)) {
                    $errors[] = "Missing required field: " . $field->name();
                }
            } else if(! $field->validate()) {
                echo "<pre>".print_r($field,true)."</pre>";die();
                $errors[] = $field->error();
            } else {
                $returnData[$field->name()] = $field->process();
            }
        }
        
        $this->processed = true;
        if(count($errors) > 0) {
            log_message('error', 'There be errors!' . print_r($errors, true));
            $this->processErrors = $errors;
            $this->processResult = null;
            return null;
        } else {
            // FIXME check for 'object' return type
            $this->processResult = $returnData;
        }
        
        return $this->processResult;
    }
    
    protected function startRequiredBlock() {
        $this->state = 'required';
        $this->dependant = null;
    }
    
    protected function startDependantBlock(Field $field, $value) {
        $this->state = 'dependant';
        $this->dependant = $field;
        $this->storedDependants[$field->name()] = $field;
        $this->storedValues[$field->name()] = $value;
    }
    
    protected function startOptionalBlock() {
        $this->state = 'optional';
        $this->depedant = null;
    }
}

class Runtime_Definition extends Model_Definition {
    
}