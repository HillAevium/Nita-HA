<?php

class Request_Exception extends RuntimeException {
    
    private $requestMethod;
    private $requestUri;
    
    public function Request_Exception($message) {
        parent::RuntimeException($message);
        
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestUri    = $_SERVER['REQUEST_URI'];
    }
}