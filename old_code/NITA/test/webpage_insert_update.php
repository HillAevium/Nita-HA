<?

ini_set("soap.wsdl_cache_enabled","0");

$website = new SoapClient('http://10.137.1.49:6652/WebPageService.svc?wsdl', array( 'trace' => true ) );

//var_dump($website->__getFunctions()  );
//var_dump($website->__getTypes() );
try {
	$wrapper = new stdClass;
	//$wrapper->id = new SoapVar("582cd7a9-8f80-df11-8d9f-000c2916a1cb", XSD_STRING, "string"); 
	$wrapper->Nita_webpageId = new SoapVar("f80624bd-9080-df11-8d9f-000c2916a1cb", XSD_STRING, "string"); 
	//$wrapper->Nita_ParentPageId = new SoapVar("582cd7a9-8f80-df11-8d9f-000c2916a1cb", XSD_STRING, "string"); 
	//$wrapper->Nita_nav_is_nav = new SoapVar("y", XSD_STRING, "string");
	//$wrapper->Nita_nav_log = new SoapVar("n", XSD_STRING, "string"); 
	//$wrapper->Nita_nav_name = new SoapVar("sitemap", XSD_STRING, "string"); 
	//$wrapper->Nita_page_name = new SoapVar("Sitemap", XSD_STRING, "string"); 
	//$wrapper->Nita_nav_order = new SoapVar("8", XSD_STRING, "string"); 
	//$wrapper->Nita_nav_url = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_nav_use = new SoapVar("y", XSD_STRING, "string"); 
	//$wrapper->Nita_page_date = new SoapVar("2010-06-14", XSD_STRING, "string"); 
	//$wrapper->Nita_page_desc = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_header_1 = new SoapVar("", XSD_STRING, "string"); 
	$wrapper->Nita_page_header_2 = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_image = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_keywords = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_style = new SoapVar("0", XSD_STRING, "string"); 
	//$wrapper->Nita_page_tag_1 = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_tag_2 = new SoapVar("", XSD_STRING, "string"); 
	//$wrapper->Nita_page_text = new SoapVar("<h1>Sitemap</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisis rutrum ipsum, vitae cursus augue molestie ac. Pellentesque justo lectus, rutrum quis commodo id, vestibulum in eros. Quisque justo orci, adipiscing condimentum aliquet id, mollis vel turpis. Nulla quam enim, viverra at blandit nec, feugiat id augue. Donec tempor varius massa et tempus. Praesent tempus dapibus enim sit amet bibendum. Mauris hendrerit sapien vel ipsum porta feugiat. Praesent condimentum metus eget enim facilisis iaculis. Fusce dignissim tempus lorem, vel facilisis est aliquet condimentum. Praesent vitae felis erat, in fringilla tellus. Suspendisse laoreet eleifend luctus. Etiam turpis felis, fermentum id dictum eu, adipiscing non elit. Phasellus a convallis erat. Nam pharetra erat at dui ornare eu lobortis ipsum convallis. Nam pellentesque ante eget lorem consequat a venenatis tortor lobortis. Sed elit sapien, tempus nec cursus id, posuere non lacus.</p><p>Sed nisl mi, adipiscing vitae molestie eget, fringilla sit amet neque. Suspendisse euismod gravida ornare. Etiam ac mauris nunc, ultricies mattis nibh. Praesent id sodales leo. Mauris pellentesque, leo sed dignissim euismod, sem nisl ultricies est, id tempus nisl risus nec diam. Curabitur ultricies nulla eget lacus imperdiet a imperdiet enim molestie. Nam sodales ornare turpis, sit amet eleifend risus elementum quis. Sed ut risus elit, eget auctor quam. Cras justo enim, porta vitae auctor sit amet, bibendum ac urna. Morbi ultrices fringilla mi nec condimentum. Nam felis orci, iaculis vitae luctus adipiscing, viverra a justo. Suspendisse enim arcu, cursus eget mollis sed, accumsan et orci. Vivamus tristique velit ut velit feugiat sit amet vulputate enim ultricies. Vivamus non quam purus, sit amet tempus quam. Curabitur id felis odio. Nullam aliquam varius pretium. Integer id velit tortor, in vestibulum libero. Ut et purus a turpis molestie semper vitae sit amet risus.</p><p>Nulla facilisi. Donec non libero eget eros ornare gravida. Donec at felis eu lorem varius mattis. Fusce in felis nisi. Fusce lobortis condimentum augue, et sagittis velit viverra convallis. Nulla a dapibus magna. Nullam et quam urna. Aenean feugiat elit sed erat pretium volutpat. Nam tristique, velit sit amet dignissim molestie, ligula dui lacinia nibh, nec pellentesque metus massa in sem. Donec sed venenatis leo. Morbi nisl libero, aliquet non rhoncus sit amet, pharetra a lorem. Maecenas quis augue nec nisi vulputate condimentum et non lectus. Etiam accumsan facilisis urna eu fermentum. Morbi vehicula tristique nulla. Duis non sodales tortor. In sed augue ligula, at blandit erat. Donec non velit in velit bibendum placerat ac non libero. Aenean neque nibh, accumsan non tempus hendrerit, luctus quis libero.</p><p>Aenean ullamcorper metus et ipsum fermentum sit amet cursus neque imperdiet. Pellentesque elementum, nunc accumsan sodales lobortis, nunc lorem facilisis sem, ac laoreet velit nulla eu lacus. Aliquam a metus ligula. Aenean quis sollicitudin felis. Mauris viverra ornare volutpat. Mauris tempor, purus ultrices suscipit faucibus, turpis nibh auctor enim, et tincidunt nunc massa aliquam dui. Nunc commodo pellentesque placerat. Morbi ultrices felis non eros laoreet eget feugiat mauris imperdiet. Proin iaculis scelerisque mattis. Etiam convallis dignissim dictum. Vestibulum convallis dolor nulla. Vestibulum at ligula diam, sit amet lacinia tortor. Aliquam erat volutpat. Vestibulum dapibus bibendum enim, quis mollis dui lacinia eu. Quisque euismod tortor ac ipsum mollis feugiat. Proin vulputate urna quis tellus dapibus venenatis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla turpis neque, fermentum sit amet iaculis vel, faucibus eget erat. Morbi ut enim mauris. Aliquam erat volutpat.</p><p>Suspendisse varius, est a varius ultricies, leo purus hendrerit risus, non euismod ligula turpis nec sapien. Aenean ac cursus sem. Sed feugiat ipsum in arcu ultrices sagittis pharetra risus eleifend. Mauris sit amet est nisl, vitae mollis arcu. Pellentesque eu viverra diam. Maecenas eu mauris vitae nibh scelerisque lacinia. Aenean sit amet ipsum ac augue cursus viverra in quis sem. Aenean feugiat varius ligula et consequat. Phasellus et orci velit, vitae tincidunt sem. Etiam eget dolor ipsum.</p>", XSD_STRING, "string");
	
	// Insert website page into web service
	$request = new stdClass;
	$request->model = $wrapper;
	
	//$rows = $website->Insert( $request );
	$rows = $website->Update( $request );
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