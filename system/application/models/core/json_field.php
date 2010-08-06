<?php

class Json_Field extends String_Field {
    
    private $jsonResult;
    
    public function Json_Field($name) {
        parent::String_Field($name, 'json');
    }
    
    public function doValidate($data) {
        if(!parent::doValidate()) {
            return false;
        }
        
        $this->jsonResult = json_decode($data);
        if($this->jsonResult === null) {
            $error = json_last_error();
            switch($error) {
                case JSON_ERROR_DEPTH :
                    $this->error = "JSON_ERROR_DEPTH";
                break;
                case JSON_ERROR_CTRL_CHAR :
                    $this->error = "JSON_ERROR_CTRL_CHAR";
                break;
                case JSON_ERROR_STATE_MISMATCH :
                    $this->error = "JSON_ERROR_STATE_MISMATCH";
                break;
                case JSON_ERROR_SYNTAX :
                    $this->error = "JSON_ERROR_SYNTAX";
                break;
            }
            log_message('error', $this->error);
            return false;
        }
        
        return true;
    }
    
    public function doProcess($data) {
        return $this->jsonResult;
    }
}