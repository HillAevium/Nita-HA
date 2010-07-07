<?

include '../dataConfig/cp_connection.php';
include '../includes/encrypt.php';

ini_set("soap.wsdl_cache_enabled","0");

$website = new SoapClient('http://10.137.1.49:6652/ContactService.svc?wsdl', array( 'trace' => true ) );

//var_dump($website->__getFunctions()  );
//var_dump($website->__getTypes() );
try {
	$wrapper = new stdClass;
	//$wrapper->id = new SoapVar("582cd7a9-8f80-df11-8d9f-000c2916a1cb", XSD_STRING, "string"); 
	//$wrapper->primarycustomerid = new SoapVar("582cd7a9-8f80-df11-8d9f-000c2916a1cb", XSD_STRING, "string"); 
	//$wrapper->GenderCode = new SoapVar("0", XSD_STRING, "string"); 
	//$wrapper->Nita_Ethnicity = new SoapVar("white", XSD_STRING, "string"); 
	//$wrapper->Nita_isln = new SoapVar("y", XSD_STRING, "string");
	$wrapper->address1_city = new SoapVar("Lakewood", XSD_STRING, "string"); 
	$wrapper->address1_country = new SoapVar("United States", XSD_STRING, "string"); 
	$wrapper->address1_line1 = new SoapVar("225 Union Blvd", XSD_STRING, "string"); 
	$wrapper->address1_line2 = new SoapVar("Suite 375", XSD_STRING, "string"); 
	//$wrapper->address1_line3 = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->address1_postalcode = new SoapVar("80228", XSD_STRING, "string"); 
	$wrapper->address1_stateorprovince = new SoapVar("CO", XSD_STRING, "string"); 
	//$wrapper->contactid = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->createdon = new SoapVar("2010-07-05 19:00:00", XSD_STRING, "string"); 
	$wrapper->description = new SoapVar("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisis rutrum ipsum, vitae cursus augue molestie ac. Pellentesque justo lectus, rutrum quis commodo id, vestibulum in eros. Quisque justo orci, adipiscing condimentum aliquet id, mollis vel turpis. Nulla quam enim, viverra at blandit nec, feugiat id augue. Donec tempor varius massa et tempus. Praesent tempus dapibus enim sit amet bibendum. Mauris hendrerit sapien vel ipsum porta feugiat. Praesent condimentum metus eget enim facilisis iaculis. Fusce dignissim tempus lorem, vel facilisis est aliquet condimentum. Praesent vitae felis erat, in fringilla tellus. Suspendisse laoreet eleifend luctus. Etiam turpis felis, fermentum id dictum eu, adipiscing non elit. Phasellus a convallis erat. Nam pharetra erat at dui ornare eu lobortis ipsum convallis. Nam pellentesque ante eget lorem consequat a venenatis tortor lobortis. Sed elit sapien, tempus nec cursus id, posuere non lacus.", XSD_STRING, "string"); 
	$wrapper->emailaddress1 = new SoapVar("chad@hillaevium.com", XSD_STRING, "string"); 
	$wrapper->fax = new SoapVar("(720) 570-0773", XSD_STRING, "string"); 
	$wrapper->firstname = new SoapVar("Chad", XSD_STRING, "string"); 
	$wrapper->lastname = new SoapVar("Serpan", XSD_STRING, "string"); 
	$wrapper->middlename = new SoapVar("m", XSD_STRING, "string"); 
	//$wrapper->mobilephone = new SoapVar("", XSD_STRING, "string");
	$wrapper->modifiedon = new SoapVar("2010-07-05 19:00:00", XSD_STRING, "string"); 
	$wrapper->nita_web_ip = new SoapVar("72.54.98.142", XSD_STRING, "string"); 
	//$wrapper->nita_web_last_login = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->nita_web_level = new SoapVar("10", XSD_STRING, "string"); // 10 as user 15 for attendee 
	//$wrapper->nita_web_log_count = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->nita_web_login_try = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->nita_web_password = new SoapVar(md5('temp22'), XSD_STRING, "string"); 
	//$wrapper->nita_web_type = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->nita_web_uncode = new SoapVar(urlencode(encrypt_data('temp22')), XSD_STRING, "string"); 
	$wrapper->nita_web_username = new SoapVar("cserpan", XSD_STRING, "string"); 
	//$wrapper->suffix = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->telephone1 = new SoapVar("(720) 570-0773", XSD_STRING, "string"); 
	//$wrapper->telephone2 = new SoapVar("", XSD_STRING, "string"); 
	
	// Insert website page into web service
	$request = new stdClass;
	$request->model = $wrapper;
	
	$rows = $website->Insert( $request );
	//$rows = $website->Update( $request );
	//$rows = $website->Delete( $wrapper );
	
	var_dump($rows);
	
} catch (SoapFault $fault) {
	echo "<pre>";
	//var_dump($fault);
	echo htmlentities($website->__getLastRequest() ) ; 
	echo htmlentities($website->__getLastResponse() ) ; 
	
	//trigger_error("SOAP Fault: faultcode: {$fault->faultcode}
//faultstring: {$fault->faultstring}

//{$fault->detail->ExceptionDetail->StackTrace}

//", E_USER_ERROR); 
	echo "</pre>";   
} 

die();


?>