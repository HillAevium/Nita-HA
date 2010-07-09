<?php
	
function LogIn() {
    /**********************************************************
     * This function contains info necessary for the Soap API *
     **********************************************************/
    
	if($this->Authenticated) {
        // Are we already authenticated
	    return true;
	}
	
	// Check for Form Controller and form submition
	if(isset($_POST['Controller']) && $_POST['Controller'] == "true") {
	    // Lets make sure that we have our username and password
		if(strlen($_POST['Username']) > 0 && strlen($_POST['Password']) > 0){
		    // Initiate our SOAP client
			$soap = new SoapClient($this->HTTP.$this->URLS['contact']);
					
			$args = new stdClass;
			// Set our Username
			$args->username = new SoapVar( $_POST['Username'] , XSD_STRING, "string");
			// Set our Password
			$args->password = new SoapVar( md5($_POST['Password']) , XSD_STRING, "string");
			
			// Retrieve active programs from our SOAP client
			$result = $soap->Authenticate($args);
			
			if(is_null($res->AuthenticateResult)) {
			    $this->ERROR='Wrong Username or Password';
			} else {
			    // Need to set the authentication to actual user id and user level
				$this->setAuth($result->AuthenticateResult->contactid,
				               intval($result->AuthenticateResult->nita_web_level));
				 // If authenticated return true
				return true;
			}
		} else {
		    $this->ERROR='Username and Password are required';
		}
	}
}