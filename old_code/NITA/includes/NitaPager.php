<?

$HomeID			= 'f4d317f9-f37e-df11-8d9f-000c2916a1cb'; // Our homepage id from the CRM system
$FootID			= '582cd7a9-8f80-df11-8d9f-000c2916a1cb'; // Our footer id for the footer navigation
	
function buildNav($Level, $Depth, $Path=array(), $PID=NULL){ $NavArray=array();
	try{
		$navSearch = new SoapClient($this->HTTP.$this->URLS['website']); // Initiate our SOAP client
				
		$wrapper = new stdClass;
		$wrapper->parentId = new SoapVar( ((is_null($PID))?$this->HomeID:$PID), XSD_STRING, "string"); // Set our Start date
		
		$nav = $navSearch->GetPagesByParentId($wrapper); // Retrieve active programs from our SOAP client
		if(!isset( $nav->GetPagesByParentIdResult ) || !isset( $nav->GetPagesByParentIdResult->WebPageModel ) ) return;
			
		$nav = $nav->GetPagesByParentIdResult->WebPageModel;
		$this->ObjectSorter($nav, array('Nita_nav_order' => 'ASC') );  // Sort our array of objects based on an object property
		
		$NavArray[]='<ul>';
		foreach($nav as $item){ $TPath=$Path; array_push($TPath,$item->Nita_nav_name);
			$Target='';
			if(!empty($item->Nita_nav_url)){
				/*
				array(4) {
					["dirname"]=>
					string(5) "http:"
					["basename"]=>
					string(14) "www.google.com"
					["extension"]=>
					string(3) "com"
					["filename"]=>
					string(10) "www.google"
				}
				array(4) {
					["dirname"]=>
					string(1) "."
					["basename"]=>
					string(14) "www.google.com"
					["extension"]=>
					string(3) "com"
					["filename"]=>
					string(10) "www.google"
				}
				array(3) {
					["dirname"]=>
					string(1) "\"
					["basename"]=>
					string(9) "MyAccount"
					["filename"]=>
					string(9) "MyAccount"
				}

				*/
				$URL = $item->Nita_nav_url; $Parts = pathinfo($URL);
				if(isset($Parts['extension']) && $Parts['extension']=="com" && $Parts['basename'] != $_SERVER['HTTP_HOST']){
					if($Parts['dirname'] != 'http:') $URL='http://'.$URL; $Target=' target="_blank"';
				}
			} else $URL = '/'.((count($Path)>0)?implode('/',$Path).'/':'').$item->Nita_nav_name;
			
			$BUFF  ='<li><a href="'.$URL.'" title="'.$item->Nita_page_name.'"'.$Target.'>'.$item->Nita_page_name.'</a>';
			if($Level<$Depth) $BUFF .= $this->buildNav($Level, ($Depth+1), $TPath, $item->Nita_webpageId);
			$BUFF .='</li>';
			$NavArray[]=$BUFF;
		}
		$NavArray[]='</ul>';
		
		return implode($this->eol,$NavArray);
	} catch (FatalError $e) {
		$this->NitaERROR(102, $e->getMessage()); // Coding fault 102
	} catch (SoapFault $fault) {
		//echo '<pre>';
		//var_dump($fault);
		//echo '</pre>';
		$this->NitaERROR(101, $fault->faultcode.", ".$fault->faultstring); // SOAP fault 101, error to our SOAP connection
	}  catch (Exception $e) {
		$this->NitaERROR(103, $e->getMessage()); // Catch all error 103
	}
}

function getPage(){
	if(isset($_GET['p'])) {
	    $this->Page=array_pop( explode("/",$_GET['p']) );
	}
	
	if(intval( $this->Page == 404 )) {
		$this->e404();
		return;
	}
	
	try{
	    // Initiate our SOAP client
		$soap = new SoapClient($this->HTTP.$this->URLS['website']);
		$args = new stdClass;
		
		// Set our Start date
		$args->name = new SoapVar( $this->Page, XSD_STRING, "string");
		 
		// Set our Start date
		//$wrapper->id	 = new SoapVar('f4d317f9-f37e-df11-8d9f-000c2916a1cb', XSD_STRING, "string");
		
		// Retrieve active programs from our SOAP client
		$page = $soap->GetPagesByNavName($args);
		
		if(!isset( $page->GetPagesByNavNameResult ) || !isset( $page->GetPagesByNavNameResult->WebPageModel ) ){
			$this->e404();
			return;
		}
		
		$page = $page->GetPagesByNavNameResult->WebPageModel;
		
		if(is_array($page)) {
		    $page=$page[0];
		}
		
		// Set the page that we are looking at
		$this->PageID = $page->Nita_webpageId;
		
		if(!is_null($page->Nita_page_image)) {
		    define('MetaImages', '<link rel="image_src" href="'.$page->Nita_page_image.'" />');
		}
		if(!is_null($page->Nita_page_keywords)) {
		    define('MetaKey', $page->Nita_page_keywords);
		}
		if(!is_null($page->Nita_page_desc)) {
		    define('MetaDesc', $page->Nita_page_desc);
		}
		if(!is_null($page->Nita_nav_name) && $page->Nita_page_name!='homepage') {
		    define('MetaTitle', $page->Nita_page_name);
		}
		
		$this->HTML=$page->Nita_page_text;
		
	} catch (FatalError $e) {
		$this->NitaERROR(102, $e->getMessage()); // Coding fault 102
	} catch (SoapFault $fault) {
		//echo '<pre>';
		//var_dump($fault);
		//echo '</pre>';
		$this->NitaERROR(101, $fault->faultcode.", ".$fault->faultstring); // SOAP fault 101, error to our SOAP connection
	}  catch (Exception $e) {
		$this->NitaERROR(103, $e->getMessage()); // Catch all error 103
	}
}