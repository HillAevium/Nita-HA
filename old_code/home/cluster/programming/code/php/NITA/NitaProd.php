<?
class FatalError extends Exception{}
class NitaProd{
	private $rp					=	''; // System tries its best to find the root folder.
	public 	$SesNames		= array("Cart"=>"ShoppingCart", "User" => "UserLogIn"); // Session Key for our User
	
	public 	$ERROR			=	false;
	private $HTTP				=	"http://10.137.1.49:6652/"; // URL to our SOAP client
	public 	$URLS				=	array();
	private $TEMPPath		=	"templates/";
	public 	$TEMP				=	array();
	private $ProdSetup	=	array('Pager'=>true, 'Limit'=>15);
	private $HTML				=	''; // Our outputed HTML buffer.
	private $CodeFncts	=	array('Image'=>false, // Processing Functions for our Processing Codes
											 'Attr'=>false);
	
	public	$Files			=	array(
													'prodList'=>'/products/',
													'prodDet'=>'/products/',
													'Cart'=>'/cart/',
													'CartAdd'=>'/cart/add.php',
													'CartChng'=>'/cart/change.php',
													'CartSign'=>'/cart/signup.php',
													'CartMem'=>'/cart/member.php',
													'CartMemBill'=>'/cart/member_billing.php',
													'CartCheck'=>'/cart/checkout.php',
													'CartBill'=>'/cart/billing.php',
													'CartRev'=>'/cart/review.php',
													'CartProc'=>'/cart/process.php',
													'CartInv'=>'/cart/invoice.php',
													'CartThank'=>'/cart/thankyou.php'
												);
	
	// Arrays to hold our session information
	private $Cart 		=		array();
	private $Referred = 	array();
	private $Shipping = 	array(1,7); // Default is UPS Ground according to Aevium Database
	private $Customer = 	array( 			// Will hold three sub-arrays Customers Personal, Billing and Shipping Infomation
																'Pers'=>array(),
																'Ship'=>array(),
																'Bill'=>array(),
															);
	public	$Review		= 	false; // Check to see if we are looking at the review cart
	private $Totals 	=		array(); // Totals of our Shopping Cart
	private $SKey 		=	 	array('Name','ASC');
	public 	$SArray 	= 	array(
																'Name'			=> 'NITA_Title',
																'Date'			=> 'NITA_StartDate',
																'Location'	=> array('NITA_FacilityState','NITA_FacilityCity'),
																'Price'			=> 'NITA_TuitionPriceStandard'
															);
	public 	$Page 			= 0;
	public 	$Limit 			= 10;
	public 	$PageNum 		= 10;
	public 	$TotalRows 	= 0;
	public 	$TotalPages = 0;
	
	private $AttnList	= array(1=>"Jimmy", 2=>"Jonny", 3=>"Adam", 4=>"Jason", 5=>"Luck"); // Tempary array holding attendee information for our logged in user
	function __construct(){
		
		$this->rp=''; for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $this->rp .= "../";
		require_once($this->rp.'includes/fnct_clean_entry.php');
		
		$this->URLS['programs']="ProgramService.svc?wsdl"; // WSDL for our programs module
		
		$this->TEMP['pListCont']		="prods_list.tpl"; 		// file path to our products list template
		$this->TEMP['pList']				="prod_list.tpl"; 		// file path to our product list template
		$this->TEMP['pLarge']				="prod_lrg.tpl"; 			// file path to our product view template
		$this->TEMP['cList']				="cart.tpl"; 					// file path to our cart template
		$this->TEMP['cItem']				="cart_item.tpl"; 			// file path to our product view template
	}
	function __toString(){ // Check for errors if non, return our HTML buffer.
		if($this->ERROR!==false) return $this->ERROR;
		else return $this->HTML;
	}
	function CheckSession(){ $FndSess=false; // Lets check to see if our sessions have been started
		foreach($this->SesNames as $v) if(isset($_SESSION[$v])){ $FndSess=true; break; }
		if($FndSess===false) @session_start();
	}
	function NitaERROR($Code, $String){ // Set our error with code and string
		$this->ERROR=" Error ".$Code.": ".$String;
	}
	function LoadTemplate($myFile){ $myBuff=''; // Function to load a template and return a buffer
		if($hndl=fopen($myFile, 'r')){
			while (!feof($hndl)) { $myBuff.=fgets($hndl, 4096); } fclose($hndl); return $myBuff;
		} else return false;
	}
	function Code($Code,$Vars){ // Function to process our templates hooks based on the variables sent by our processing function
		$Code=substr($Code,1,-1);
		return (isset($Vars[$Code]) )?$Vars[$Code]:"[".$Code."]"; // If no variable exists return the code back to the template
	}
	function processCodes($Item, $Vars){ // Find all hooks with the format [HOOK] and send to our processer to find our variables
		$Item = preg_replace("/[[](.*)[]]/eiU", "\$this->Code('$0',\$Vars)", $Item );
		return $Item; // Return our formated item.
	}	
	function ProdImage($Str,$Image){ // Product Image
		list($width, $height) = getimagesize($this->rp.$File);
		$NWidth = $width;
		$NHeight = $height;
		if(($this->Width != 0 && $width > $this->Width) || ($this->Height != 0 && $height > $this->Height)){
			if($width > $height){
				$NWidth = $this->Width;
				$Perc = $this->Width/$width;
				$NHeight = round($height*$Perc);
			} else {
				$NHeight = $this->Height;
				$Perc = $this->Height/$height;
				$NWidth = round($width*$Perc);
			}
		} 
		$Padding = floor(($this->Height-$NHeight)/2);
		// return '<img src="/'.$Image.'" width="'.$NWidth.'" height="'.$NHeight.'" alt="'.$Alt.'" vspace="'.$Padding.'" border="0" />';
		//return '<img src="/'.$Image.'"  alt="'.$Alt.'"  border="0" />';
		return '';
	}
	function ObjectSorter(&$array, $props){ // Sort our object from the SOAP Object 
    if(!is_array($props)) $props = array($props => true); 
		$function = '$props=unserialize(\''.trim(serialize($props)).'\');
				foreach($props as $prop => $ascending) { 
					if($a->$prop != $b->$prop) { 
						if($ascending=="ASC") return ($a->$prop > $b->$prop) ? 1 : -1; 
						else return ($b->$prop > $a->$prop) ? 1 : -1; 
					} 
				} 
				return -1;';
    usort($array, create_function('$a,$b', $function) );
	}
	function ckNULL($VAL){
		return is_null($VAL)?'':$VAL;
	}
	function QueryReplace($VAR, $VAL, $STR=false){ // Replace Query String Variables
		$QueryString = array();
		if($STR !== false) $String = $STR;
		else $String = $_SERVER['QUERY_STRING'];
		parse_str($String, $Keys);
		foreach($Keys as $k => $v){
			if(trim($k) == trim($VAR)){ if($VAL != NULL) $QueryString[] = $k."=".$VAL;
			} else $QueryString[] = $k."=".$v;
		}
		return implode("&",$QueryString);
	}
	function products(){ // Get a list of active programs from our SOAP client
		if(isset($_POST['Controller']) && $_POST['Controller'] == "AddToCart" && isset($_POST['qty']) && isset($_POST['id']) && isset($_POST['price']) ){
			$this->addToCart();
			return;
		}
		$myBuff = ''; // Buffer holding HTML from our templates 
		$PString = ''; // Empty string to hold our pagination
		try {
			if(isset($_GET['id'])){
				if($contBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pLarge'])){
					$programs = new SoapClient($this->HTTP.$this->URLS['programs']); // Initiate our SOAP client
					
					$wrapper = new stdClass;
					$wrapper->id = new SoapVar( $_GET['id'] , XSD_STRING, "string"); // Set our Start date
					
					$res = $programs->Get($wrapper); // Retrieve active programs from our SOAP client
					//var_dump($rows);
					$info=&$res->GetResult;
					
					$SDate=date("M j, Y",strtotime($info->NITA_StartDate)); // format our program start date
					$EDate=date("M j, Y",strtotime($info->NITA_EndDate)); // format our program end date
					$Facility=array();
					if(!is_null($info->NITA_FacilityName)) $Facility[]=$info->NITA_FacilityName;
					if(!is_null($info->NITA_FacilityAddr1)) $Facility[]=$info->NITA_FacilityAddr1;
					if(!is_null($info->NITA_FacilityAddr2)) $Facility[]=$info->NITA_FacilityAddr2;
					if(!is_null($info->NITA_FacilityAddr3)) $Facility[]=$info->NITA_FacilityAddr3;
					if(!is_null($info->NITA_FacilityAddr4)) $Facility[]=$info->NITA_FacilityAddr4;
					if(!is_null($info->NITA_FacilityCity) && !is_null($info->NITA_FacilityState) && !is_null($info->NITA_FacilityZip) )
							$Facility[]= (!is_null($info->NITA_FacilityCity)?$info->NITA_FacilityCity.', ':'').
													 (!is_null($info->NITA_FacilityState)?$info->NITA_FacilityState.' ':'').
													 (!is_null($info->NITA_FacilityZip)?$info->NITA_FacilityZip:'');
					
					$CLE=array();
					if(!is_null($info->Nita_50MinuteCredits)) $CLE[]="50 Minute Credits: ".$info->Nita_50MinuteCredits;
					if(!is_null($info->Nita_60MinuteCredits)) $CLE[]="60 Minute Credits: ".$info->Nita_60MinuteCredits;
					if(count($CLE)==0) $CLE[]="No CLE Credits";
					
					
					$this->HTML.=$this->processCodes($contBuff,array('ID'				=> $this->ckNULL($info->NITA_ProgramId),
																													 'Name'			=> $this->ckNULL($info->NITA_Title),
																													 'Desc'			=> $this->ckNULL($info->NITA_Description),
																													 'SKU'			=> $info->NITA_name,
																													 'SDate'		=> $SDate,
																													 'EDate'		=> $EDate,
																													 'Avail'		=> 'Open to Registration',
																													 'Fac'			=> '<p>'.implode("<br />",$Facility).'</p>',
																													 'CLE'			=> '<p>'.implode("<br />",$CLE).'</p>',
																													 'Price'		=> '$'.number_format($this->ckNULL($info->NITA_TuitionPriceStandard),2,'.',','),
																													 'PriceInt'	=> $this->ckNULL($info->NITA_TuitionPriceStandard),
																													 'Attr'			=> '',
																													 'AddCart'	=> $_SERVER['PHP_SELF'],
																													 'URL'			=> $this->Files['prodDet'].'?id='.$info->NITA_ProgramId,
																													 ));
				} else throw new FatalError('Cannot open template '.$this->TEMP['pLarge']);
			} else { // If we have not selected a program to look at provide a list of programs.
				$programs = new SoapClient($this->HTTP.$this->URLS['programs']); // Initiate our SOAP client
				// var_dump($programs->__getFunctions()  );  // Dumps the active function from our client
				$wrapper = new stdClass;
				$wrapper->startDate = new SoapVar( date("c") , XSD_DATETIME, "dateTime"); // Set our Start date
				$wrapper->endDate = new SoapVar( date("c",mktime(0,0,0,date("m"),date("d"),date("Y")+5 ) ), XSD_DATETIME, "dateTime"); // Drop an end date a year out
	
				$rows = $programs->GetActivePrograms($wrapper); // Retrieve active programs from our SOAP client
				if(isset($_GET['s'])) $this->SKey=explode(',',$_GET['s']);
											
				if($contBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pListCont'])){ // Get our product list template from the server
					$contBuff=$this->processCodes($contBuff, array(	'SortTitle'		=>$_SERVER['PHP_SELF'].'?s=Name,'.(($this->SKey[0]=='Name' && $this->SKey[1]=='ASC')?'DESC':'ASC'),
																													'SortDate'		=>$_SERVER['PHP_SELF'].'?s=Date,'.(($this->SKey[0]=='Date' && $this->SKey[1]=='ASC')?'DESC':'ASC'),
																													'SortLoc'			=>$_SERVER['PHP_SELF'].'?s=Location,'.(($this->SKey[0]=='Location' && $this->SKey[1]=='ASC')?'DESC':'ASC'),
																													'SortPrice'		=>$_SERVER['PHP_SELF'].'?s=Price,'.(($this->SKey[0]=='Price' && $this->SKey[1]=='ASC')?'DESC':'ASC')
																												)); // Drop our sorting links into the product list
					//var_dump($rows);
					$programs=&$rows->GetActiveProgramsResult->ProgramModel;// Set our list of programs to an array for easier use				
					$Sorting=array();// Our Sorting header
					if( is_array($this->SArray[$this->SKey[0]]) ) foreach($this->SArray[$this->SKey[0]] as $Sort) $Sorting[$Sort]=$this->SKey[1];
					else $Sorting[$this->SArray[$this->SKey[0]]]=$this->SKey[1];
					
					$this->ObjectSorter($programs, $Sorting);  // Sort our array of objects based on an object property
					
					if($itemBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pList'])){// Get Item templace from the server
						if(intval($this->Limit)>0){ 
							if(isset($_GET['p'])) $this->Page=intval($_GET['p']);
									
							$this->TotalRows = count($programs); // Total number of products in the database
							$this->TotalPages = ceil($this->TotalRows/$this->Limit)-1;
							
							$PString=$this->Pagination(); // Get our pagination string from our class function
						
							$programs = array_slice($programs, ($this->Page*$this->Limit), $this->Limit); // Get the programs on our selected page
						}

						foreach ($programs as $row){ // For each program set the appropriate data to our hooks
							$SDate=date("M j, Y",strtotime($row->NITA_StartDate)); // format our program start date
							$EDate=date("M j, Y",strtotime($row->NITA_EndDate)); // format our program end date
							$myBuff.=$this->processCodes($itemBuff,array('ID'=>				$this->ckNULL($row->NITA_ProgramId			),
																													 'Name'=>			$this->ckNULL($row->NITA_Title					),
																													 'SDate'=>		$SDate,
																													 'EDate'=>		$EDate,
																													 'City'=>			$this->ckNULL($row->NITA_FacilityCity		),
																													 'State'=>		$this->ckNULL($row->NITA_FacilityState	),
																													 'Price'=>		'$'.number_format($this->ckNULL($row->NITA_TuitionPriceStandard),2,'.',','),
																													 'PriceInt'=>	$this->ckNULL($row->NITA_TuitionPriceStandard),
																													 'AddCart'=>	$_SERVER['PHP_SELF'],
																													 'URL'=>			$this->Files['prodDet'].'?id='.$this->ckNULL($row->NITA_ProgramId)
																													 ));
						}
						unset($programs);
					} else throw new FatalError('Cannot open template '.$this->TEMP['pList']);
					$this->HTML.=str_replace("[List]", $myBuff, $contBuff); // Place our products into our template
				} else throw new FatalError('Cannot open template '.$this->TEMP['pListCont']);
			}
			$this->HTML=$PString.$this->HTML.$PString;
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
	// ************************************************************
	// ***                Shopping Cart Functions               ***
	// ************************************************************
	function getCart(){ // Get the cart from our session information
		$this->CheckSession();
		$SessKey = $this->SesNames["Cart"];
		
		if(!isset($_SESSION[$SessKey] ) ){ // Check to see if we have started a shopping cart session already
			session_register($SessKey);
			$this->Cart = array();
		} else if(isset($_SESSION[$SessKey]['Cart'])) $this->Cart = 			$_SESSION[$SessKey]['Cart']; 				// Shopping Cart
		if(isset($_SESSION[$SessKey]['Referred'])) 		$this->Referred = 	$_SESSION[$SessKey]['Referred'];		// Referring Information
		if(isset($_SESSION[$SessKey]['Shipping'])) 		$this->Shipping = 	$_SESSION[$SessKey]['Shipping'];		// Shipping Information		
		if(isset($_SESSION[$SessKey]['Cust'])) 				$this->Customer = 	$_SESSION[$SessKey]['Cust'];				// Customer Billing Information
		
		$this->Totals['Total'] = 0; 			// Cart Total
		$this->Totals['Ship'] = 0;				// Shipping Total
		$this->Totals['Ship2'] = 0;				// Shipping Total
		$this->Totals['Taxable'] = 0;			// Taxable Total amount
		$this->Totals['Tax'] = 0;					// Tax
		$this->Totals['Disc'] = 0;				// Discounts
		$this->Totals['Ext'] = 0;					// Extra Shipping or Processing Charges
		$this->Totals['Freight'] = 0;			// Freight Charges
		$this->Totals['Weight'] = 0;			// Weight of all Items
		$this->Totals['Grand'] = 0;				// Grand Total
		$this->Totals['ItmCnt'] = 0; 			// Item Count
	}
	function checkCart($TmpCart){ // Check to see if an item is already in the cart, if not add to quantity
		$result = array();
		foreach ($this->Cart as $k => $v){ if($v["id"] == $TmpCart["id"]) $result[] = $k; } // Find Elements with the same product Id
		$unigue = true;
		if(count($result)>0){
			foreach ($result as $v){ // Check to see if all the data is the same.  Messages and Engravins will always add a new item
				if($this->Cart[$v]["spec"] == $TmpCart["spec"] && 
					 $this->Cart[$v]["special"] == $TmpCart["special"] && 
					 $this->Cart[$v]["selections"] == $TmpCart["selections"] && 
					 intval($this->Cart[$v]["msgs"]) == 0){ $newattnd=true;
						// Lets check our attendee list and make sure we have any new Attendees: this check is for NITA
						if(isset($this->Cart[$v]["attnd"]) && count($this->Cart[$v]['attnd']) > 0 && count($TmpCart['attnd']) > 0){
							foreach($this->Cart[$v]["attnd"] as $attnd){
								if($TmpCart['attnd'][0]==$attnd) { $newattnd=false; $unigue = false; break; }
							}
						}
						if($newattnd && count($TmpCart['attnd'])>0) $this->Cart[$v]["attnd"][]=$TmpCart['attnd'][0]; // Add our new Attendee
						
						if(isset($this->Cart[$v]['attnd']) && count($this->Cart[$v]['attnd'])>0 && $this->Cart[$v]['attnd']!==0){
							$FoundNull=true;
							while($FoundNull){ $FoundNull=false;
								foreach($this->Cart[$v]['attnd'] as $KeyAttnd => $ValAttnd){
									if( (is_int($ValAttnd) && intval($ValAttnd)==0) || is_null($ValAttnd) ){
										unset( $this->Cart[$v]['attnd'][$KeyAttnd] ); $FoundNull=true; break;
									}
								}
							}
						}
						if(!isset($this->Cart[$v]['attnd']) || $this->Cart[$v]['attnd']===0) $this->Cart[$v]['attnd']=array();
						$this->Cart[$v]['name'] = $TmpCart['name']; // Going to store the name here so we don't have to query the web service for every item in the cart
						$this->Cart[$v]["type"] = $TmpCart['type']; // Type 1 = program, Type 2 = product
						//if($TmpCart['type']==1 && count($this->Cart[$v]['attnd'])>0) $this->Cart[$v]["qty"]= count($this->Cart[$v]['attnd']); // If product is program quantity equals the number of attendees have a check for zero to allow for item to placed in cart without any attendee's selected
						//else
						$this->Cart[$v]["qty"] += $TmpCart["qty"]; $unigue = false; break; // Updates our cart quantitee
				}
			}
		}
		if($unigue) $this->Cart[] = $TmpCart; // If we have a unigue item add it to the cart.
	}
	function updateCart($TmpCart){ // Quick call to update the shopping cart
		$this->getCart();
		$this->checkCart($TmpCart);
		$this->setCart();
	}
	function killCart(){ // Kill the Shopping cart and reset all the values
		$this->Cart = $this->Referred = $this->Shipping = $this->Customer = array(); // Set the session to an empty array
		$this->setCart();
		unset($_SESSION[ $this->SesName["Cart"] ]); // Unset the session array
	}
	function setCart(){ // Get the shopping cart from our session data
		$SessKey = $this->SesNames["Cart"];
		$_SESSION[$SessKey]['Cart'] 			= $this->Cart;
		$_SESSION[$SessKey]['Referred'] 	= $this->Referred;
		$_SESSION[$SessKey]['Shipping'] 	= $this->Shipping;
		$_SESSION[$SessKey]['Cust'] 			= $this->Customer;
	}
	
	function addToCart(){ // Add an item to the cart
		if(!isset($_POST['id'])) header(sprintf("Location: %s", $this->Files['Cart']));
		// ---------- These are our posted variables --------------		
		$TmpCart=array();
		$TmpCart['type'] 	= 1;
		$TmpCart['name'] 	= clean_variable($_POST['name'],true);
		$TmpCart['id']	 	= clean_variable($_POST['id'],true);
		$TmpCart['qty'] 	= (is_int($_POST['qty']) && isset($_POST['qty']))?clean_variable($_POST['qty'],true):'1';
		$TmpCart['price'] = clean_variable($_POST['price'],true);
		
		// Process Products Specs and Attributes
		$SPC = 			(isset($_POST['spec'])) ? 
									((!is_array($_POST['spec'])) ? array( $_POST['spec'] ) : $_POST['spec']):
								array();
		if(is_array($SPC)) foreach($SPC as &$v) clean_variable($v,true);
		
		$TmpStrg = '';
		if(count($SPC) > 0){ /* // Check the Specs of the product and get the price for that spec. If differant use that price
			foreach($SPC as $k => $v){ 
				$getSpecs = new sql_processor($this->DB,$this->Conn,$this->Gateway);
				$getSpecs->mysql("SELECT `att_price`, `att_sale`, `att_sale_exp` FROM `prod_link_prod_att` WHERE `att_id` = '$v' AND `prod_id` = '".$TmpCart['id']."';");
				$getSpecs->mssql("SELECT att_price, att_sale, att_sale_exp FROM prod_link_prod_att  WHERE att_id = '$v' AND prod_id = '".$TmpCart['id']."';");
				$getSpecs = $getSpecs->Rows();
				if($getSpecs[0]['att_price'] > 0){
					$attSaleExp = ereg('[^A-Za-z0-9]', $getSpecs[0]['att_sale_exp']);
					if($attSaleExp == "00000000000000") $attprices += $getSpecs[0]['att_price'];
					else if ($attSaleExp > date("YmdHis")) $attprices += $getSpecs[0]['att_sale'];
					else $attprices += $getSpecs[0]['att_price'];
				} else { $attprices = 0; }
				$TmpStrg .= ($TmpStrg == "") ? ($v.".".$attprices) : (":".$v.".".$attprices);
			} */
		}
		$TmpCart['spec'] = $TmpStrg;
		
		// Process Products Special Options
		$SPCL =  		(isset($_POST['special'])) ?
									((!is_array($_POST['special'])) ? array( $_POST['special'] ) : $_POST['special']):
								array();
		if(is_array($SPCL)) foreach($SPCL as &$v) clean_variable($v,true);
		$TmpCart['special'] = (is_array($SPCL) && count($SPCL) > 0) ? implode(":",$SPCL) : $SPCL;
		
		
		$SLCT =  		(isset($_POST['selections'])) ?
									((!is_array($_POST['selections'])) ? array( $_POST['selections'] ) : $_POST['selections']):
								array();
		if(is_array($SLCT)) foreach($SLCT as &$v) clean_variable($v,true);
		$TmpCart['selections'] = (is_array($SLCT) && count($SLCT) > 0) ? implode(":",$SLCT) : $SLCT;
		
		// Process Products Messages and Comments, mainly used for gift cards
		$MSG = 			(isset($_POST['message'])) 			? clean_variable($_POST['message'],true):'';
		$MSGTo = 		(isset($_POST['message_to'])) 	? clean_variable($_POST['message_to'],true):'';
		$MSGFrm = 	(isset($_POST['message_from'])) ? clean_variable($_POST['message_from'],true):'';
		
		$MSGEng = 	(isset($_POST['message']))?
									((!is_array($_POST['engraving']))? array( $_POST['engraving'] ) : $_POST['engraving'] ):
								'';
		if(is_array($MSGEng)) foreach($MSGEng as &$v) clean_variable($v,true);
		/*
		$getMsgs = new sql_processor($this->DB,$this->Conn,$this->Gateway);
		$getMsgs->mysql("SELECT `prod_msg_type` FROM `prod_products` WHERE `prod_id` = '".$TmpCart['id']."';");
		$getMsgs->mssql("SELECT prod_msg_type FROM prod_products WHERE prod_id = '".$TmpCart['id']."';");
		$getMsgs = $getMsgs->Rows();
		$Msgs = $getMsgs[0]['prod_msg_type'];
		*/
		$Msgs='';
		if(strlen(trim($Msgs)) > 0) $Msgs = unserialize(urldecode($Msgs));
		else $Msgs = array(); $Tarray = array();
		if(count($Msgs) > 0){
			foreach($Msgs as $k => $v){
				switch($k){
					case "Cmnts": if(strlen(trim($MSG)) > 0) $Tarray['Cmnts'][0] = $MSG; break; // Straight Comment for the Product
					case "ToFrm":																																// To From comments for Gift Cards Ect.
						if(strlen(trim($MSGTo)) > 0){
							$Tarray['ToFrm'][0] = $MSGTo;
							$Tarray['ToFrm'][1] = $MSGFrm;
						} break;
					case "Engv":																																// Engravings for the product allows for multiple lines
						if(intval($v)>1){ if(strlen(trim($MSGEng)) > 0) $Tarray['Engv'][0] = $MSGEng;
						} else { $n=0; foreach($MSGEng as $v){
								if(strlen(trim($v)) > 0) $Tarray['Engv'][$n] = $v; $n++;
							} 
						} break;
				}
			} $TmpCart['msgs'] = $Tarray;
		} else { $TmpCart['msgs'] = 0; }
		
		$TmpCart['attnd']	= 	(isset($_POST['attendee']))? array(clean_variable($_POST['attendee'],true)) : array(0);
		
		$this->updateCart($TmpCart); // Update our cart with our new information
		
		header(sprintf("Location: %s", $this->Files['Cart'] )); // Go to page to dispaly Cart -- Do this to prevent double insertion of product via refresh button
	}
	function getRefered(){ // Get Referred Information
		if(isset($_POST['ReferredCompany'])) $this->Referred[0] = clean_variable($_POST['ReferredCompany'],true);
		if(isset($_POST['ReferredName'])) 	 $this->Referred[1] = clean_variable($_POST['ReferredName'],true);
	}
	function CalcProdTotal($v,$r){						
		$Extra = 0; $FShip = 0; $Ship = 0; $Weight = 0;
		if($this->Review == true){
			if($v['type']!=1){ /*
				if($r['prod_use_freight'] == "y"){
					$FShip = $r['prod_freight'];
					$Weight = $r['prod_weight'];
				} else {
					switch($this->Shipping[0]){
						case 1:
							if($this->sini_live == false) $Ship = calculate_ship_UPS($r['prod_width'],$r['prod_height'],$r['prod_length'],$r['prod_weight'],	$this->Shipping[1] );
							else { $Data = $this->sini_gateway['UPS'];
								$Ship = connect_ship_UPS($r['prod_width'],$r['prod_height'],$r['prod_length'],$r['prod_weight'], $this->Shipping[1],
																				 $Data['Access'],$Data['User'],$Data['Pass'],$Data['AcntNum'],
																				 $Data['AcntName'],$Data['AcntCon'],$Data['AcntPhone'],$Data['AcntFax'],$Data['AcntZip'],$Data['AcntCount'],$Data['TaxId'],
																				 $Data['FromZip'],$Data['FromCount'],$this->CustShip['Zip'],$this->sini_cntrycode);	
																						
							}break;
						case 2: $Ship = calculate_ship_FEDEX($r['prod_width'],$r['prod_height'],$r['prod_length'],$r['prod_weight'],$this->Shipping[1] ); 		break;
						case 3: $Ship = calculate_ship_DHL($r['prod_width'],$r['prod_height'],$r['prod_length'],$r['prod_weight'],	$this->Shipping[1] ); 		break;
						case 4: $Ship = calculate_ship_USPS($r['prod_width'],$r['prod_height'],$r['prod_length'],$r['prod_weight'],	$this->Shipping[1] ); 		break;
					}
					$this->Totals['Ship2'] += floatval($v['price'])*intval($v['qty']);
					$Weight = $getProd[0]['prod_weight'];
				} */
				//if($r['prod_tax'] == "n") $this->Totals['Taxable'] 	+= floatval($v['price'])*intval($v['qty']);
			}
		}
					
		$this->Totals['Weight'] 		+= $Weight*intval($v['qty']);
		$this->Totals['Ship'] 			+= $Ship*intval($v['qty']);
		$this->Totals['Ext'] 				+= $Extra*intval($v['qty']);
		$this->Totals['Freight'] 		+= $FShip*intval($v['qty']);
		$this->Totals['Total'] 			+= floatval($v['price'])*intval($v['qty']); // Update the total of our products
	}
	function calcTotals(){ // Calculate the totals of our shopping cart
		
		if($this->Review == true){/*
			$getRate = new sql_processor($this->DB,$this->Conn,$this->Gateway);
			$getRate->mysql("SELECT `tax_percent` FROM `billship_tax_states` WHERE `tax_state` = '".$this->CustBill['State']."';");
			$getRate->mssql("SELECT tax_percent FROM billship_tax_states WHERE tax_state = '".$this->CustBill['State']."';");
			if($getRate->TotalRows() != 0){ $getRate = $getRate->Rows();
				$this->Totals['Tax'] += $this->Totals['Taxable'] * ($getRate[0]['tax_percent']/100);
			}
			$getRate = new sql_processor($this->DB,$this->Conn,$this->Gateway);
			$getRate->mysql("SELECT `tax_count_percent` FROM `billship_tax_county` WHERE `tax_count_zip` = '".$this->CustBill['Zip']."';");
			$getRate->mssql("SELECT tax_count_percent FROM billship_tax_county WHERE tax_count_zip = '".$this->CustBill['Zip']."';");
			if($getRate->TotalRows() != 0){ $getRate = $getRate->Rows();
				$this->Totals['Tax'] += $this->Totals['Taxable'] * ($getRate[0]['tax_count_percent']/100);
			}
			
			if($this->Totals['Ship2'] > 0){
				$getRate = new sql_processor($this->DB,$this->Conn,$this->Gateway);
				$getRate->mysql("SELECT `ship_limit_price`, `ship_limit_percent` FROM `billship_shipping_limits` WHERE `ship_limit_start_price` >= '".$this->Totals['Total']."';");
				$getRate->mssql("SELECT ship_limit_price, ship_limit_percent FROM billship_shipping_limits WHERE ship_limit_start_price >= '".$this->Totals['Total']."';");
							
				if($getRate->TotalRows() == 0){
					$getRate->mysql("SELECT `ship_limit_price`, `ship_limit_percent` FROM `billship_shipping_limits` WHERE `ship_limit_upper` = 'y';");
					$getRate->mssql("SELECT ship_limit_price, ship_limit_percent FROM billship_shipping_limits WHERE ship_limit_upper = 'y';");
				}
				$getRate = $getRate->Rows();
				$test_1 = $getRate[0]['ship_limit_price'];
				$test_2 = $this->Totals['Total']*($getRate[0]['ship_limit_percent']/100);
				
				$getRate = new sql_processor($this->DB,$this->Conn,$this->Gateway);
				$getRate->mysql("SELECT `ship_limit_price`, `ship_limit_percent` FROM `billship_shipping_limits` WHERE `ship_limit_start_weight` >= '$weight';");
				$getRate->mssql("SELECT ship_limit_price, ship_limit_percent FROM billship_shipping_limits WHERE ship_limit_start_weight >= '$weight';");
				$getRate = $getRate->Rows();
				
				$test_3 = $getRate[0]['ship_limit_price'];
				$test_4 = $total*($getRate[0]['ship_limit_percent']/100);
				
				$this->Totals['Ship2'] = ($test_1 < $test_2) ? $test_2 : $test_1;
				$this->Totals['Ship2'] = ($this->Totals['Ship2'] < $test_3) ? $test_3 : $this->Totals['Ship2'];
				$this->Totals['Ship2'] = ($this->Totals['Ship2'] < $test_4) ? $test_4 : $this->Totals['Ship2'];
				if(strlen(trim($this->Totals['Ship2'])) > 0) $this->Totals['Ship'] = $this->Totals['Ship2'];
			}
		*/
		}
		$this->Totals['Grand'] = $this->Totals['Total']+$this->Totals['Ship']+$this->Totals['Tax']-$this->Totals['Disc']+$this->Totals['Ext']+$this->Totals['Freight'];
	}
	function getCartCount(){ $Cnt = 0;
		if(count($this->Cart) > 0){	foreach($this->Cart as $v) $Cnt += intval($v["qty"]); }
		return $Cnt;
	}
	function changeCart(){ // Changes the quantities of items in the cart.		
		$this->getCart();
		
		$keys = array(); $keys = $_POST['Key']; // Cart Keys
		$qtys = array(); $qtys = $_POST['Qty']; // Cart Quantities
		if(count($this->Cart) == 0) header(sprintf("Location: %s", $this->Files['Cart'] ));

		foreach($keys as $k => $v){ // For Each Item go through the system and update the quatities
			$this->Cart[$v]["qty"] = $qtys[$k];
			$this->Cart[$v]["attnd"] = (isset($_POST['attnd_'.$k])) ? $_POST['attnd_'.$k] : array() ; // Cart Attendies
			/*
			$getProdMsgs = new sql_processor($this->DB,$this->Conn,$this->Gateway);
			$getProdMsgs->mysql("SELECT `prod_msg_type` FROM `prod_products` WHERE `prod_id` = '".$cart[$v]["id"]."';");
			$getProdMsgs->mssql("SELECT prod_msg_type FROM prod_products WHERE prod_id = '".$cart[$v]["id"]."';");
			$getProdMsgs = $getProdMsgs->Rows();
			$Msgs = $getProdMsgs[0]['prod_msg_type'];
			*/
			$Msgs='';
			if(strlen(trim($Msgs)) > 0)$Msgs = unserialize(urldecode($Msgs));
			else $Msgs = array(); $Tarray = array();
			
			if(count($Msgs) > 0){ // Now we need to update any message that the products may have.
				foreach($Msgs as $msgk => $msgv){
					switch($msgk){
						case "Cmnts":
							if(strlen(trim($_POST['message_'.$v])) > 0) $Tarray['Cmnts'][0] = clean_variable($_POST['message_'.$v],true);
							break;
						case "ToFrm":
							if(strlen(trim($_POST['message_to_'.$v])) > 0){
								$Tarray['ToFrm'][0] = clean_variable($_POST['message_to_'.$v],true);
								$Tarray['ToFrm'][1] = clean_variable($_POST['message_from_'.$v],true);
							}
							break;
						case "Engv":
							if(intval($msgv)>1){
								if(strlen(trim($_POST['engraving_'.$k])) > 0) $Tarray['Engv'][0] = clean_variable($_POST['engraving_'.$v],true);
							} else { $n=0;
								foreach($_POST['engraving_'.$v] as $msgv2){
									if(strlen(trim($v)) > 0) $Tarray['Engv'][$n] = clean_variable($msgv2,true);
									$n++;
								}
							} break;
					}
				}
				$this->Cart[$v]['msgs'] = $Tarray;
				if($qtys[$k] > 1){ $this->Cart[$v]['qty'] = 1; $Tarray = $this->Cart[$v]; array_push($this->Cart,$Tarray); }
			}
		}
		//echo '<pre>';
		//var_dump($this->Cart);
		//echo '</pre>';
		//die();
		$this->getRefered();
		$this->setCart();
		
		switch($_POST['Controller']){
			case "CtnShp": $GoTo = $this->Files['prodList']; break;
			default: $GoTo = $this->Files['Cart']; breal;
		}
		header(sprintf("Location: %s", $GoTo));
	}
	
	// Added this for special occations  Edit the code inside to fit what ever you need
	
	function changeCartSpecial(){
		$this->getCart();
		
		$key = 			clean_variable($_POST['key'],true);
		$PRC = 			clean_variable($_POST['price'],true);
		$SLCT =  		(isset($_POST['selections']))?
									((!is_array($_POST['selections'])) ? array ($_POST['selections'] ) : $_POST['selections']):
								array();
		if(is_array($SLCT)) foreach($SLCT as &$v) clean_variable($v,true);
		
		if(count($this->Cart) == 0) header(sprintf("Location: %s", $this->Files['Cart'] ));
		
		$this->Cart[$key]['price'] = $PRC;
		if(is_array($SLCT) && count($SLCT) > 0) $TmpStrg = implode(":",$SLCT); else $TmpStrg = $SLCT;
		$this->Cart[$key]["selections"] = $TmpStrg;
		
		$this->setCart();
		
		header(sprintf("Location: %s", $this->Files['Cart']));
	}
	function AttenDropList($Key, $Cnt, $Default){
		$AttnBuff='<select name="attnd_'.$Key.'[]" id="attnd_'.$Key.'_'.($Cnt+1).'">';
		$AttnBuff.='<option value="0" title="Select Attendee">Select Attendee</option>';
		foreach($this->AttnList as $AttndID => $AttndList)
			$AttnBuff.='<option value="'.$AttndID.'" title="'.$AttndList.'"'.((intval($Default)===$AttndID)?' selected="selected"':'').'>'.$AttndList.'</option>';
		$AttnBuff.='</select>';
		return $AttnBuff;
	}
	function displayCart(){
		if(isset($_POST['Controller']) && $_POST['Controller'] == "CtnShp"){ $this->changeCart(); return; }
		$this->getCart();
		
		//echo '<pre>';
		//var_dump($this->Cart);
		//echo '</pre>';
		
		$myBuff='';
		
		$CartButton = '<div class="BtnContinue" id="CstmBtn"><a href="#" onclick="$(\'#Controller\').val(\'CtnShp\'); $(\'#cartForm\').submit(); return false;" title="Continue Shopping">Continue Shopping</a></div>
<div class="BtnCheckOut" id="CstmBtn"><a href="#" title="Check Out Now">Check Out Now</a></div>';
		try{
			if($contBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['cList'])){
				if($itemBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['cItem'])){
					foreach($this->Cart as $Key => $Item){
						if($Item['qty']>0){
							
							if($Item['type']==1){ // If we are looking at a program add our attendee list
								$Attn=array();
								foreach($Item['attnd'] as $Attnd) $Attn[]=$this->AttenDropList($Key, count($Attn), $Attnd); // For each of our registered attendees in our cart add a select field
								if(count($Attn)<$Item['qty']){ // If our selected attendees is less than our program quanity add another selection box
									while(count($Attn)<$Item['qty']) $Attn[]=$this->AttenDropList($Key, count($Attn), NULL); // Add another selection box based on the number we are short
								} else if($Item['attnd'][count($Item['attnd'])-1] != 0) $Attn[]=$this->AttenDropList($Key, count($Attn), NULL); // If the last attendee is not 0 then add another selection for more selection.  Will use jQuery to add another selection box and update the quantity
								$Attn=(count($Attn)>0?'<br />':'').implode("<br />",$Attn);
							} else $Attn='';
							$myBuff.=$this->processCodes($itemBuff,array('ID'				=> $Item['id'],
																													 'Qty'			=> ($Item['type']==1)?
																													 									$Item['qty'].'<input type="hidden" name="Qty[]" id="Qty_'.$Key.'" value="'.$Item['qty'].'">' :
																																						'<input type="text" name="Qty[]" id="Qty_'.$Key.'" value="'.$Item['qty'].'">',
																													 'Name'			=> $Item['name'],
																													 'Price'		=> '$'.number_format($Item['price'],2,'.',','),
																													 'Attn'			=> $Attn,
																													 'Attr'			=> '',
																													 'URL'			=> $this->Files['prodDet'].'?id='.$Item['id'],
																													 'Key'			=> '<input type="hidden" name="Key[]" id="Key_'.$Key.'" value="'.$Key.'" />',
																													 'ItemBtnRemove' => '<div class="BtnRemove" id="CstmBtn"><a href="#">Remove</a></div>'
																													)); // Format our cart item template with the appropriate variables
						}
						$this->CalcProdTotal( $Item,array() ); // Calculate item totals
					}
				} else throw new FatalError('Cannot open template '.$this->TEMP['cItem']);
				
				$this->calcTotals(); // Calculate our cart totals
				$this->HTML.=$this->processCodes($contBuff, array('CartUrl'			=> $_SERVER['PHP_SELF'],
																													'CARTITEMS'		=> $myBuff,
																													'CARTBUTTONS'	=> $CartButton,
																													'Total'				=> '$'.number_format($this->Totals['Total'],2,'.',','),
																													'Tax'					=> '$'.number_format($this->Totals['Tax'],2,'.',','),
																													'Ship'				=> '$'.number_format($this->Totals['Ship'],2,'.',','),
																													'Grand'				=> '$'.number_format($this->Totals['Grand'],2,'.',',')
																													)); // Place our cart informaiton into our template
				$this->HTML.='<script type="text/javascript">$(document).ready(function (){
												var Attns = $("#cartForm select[id^=\'attnd_\']"); var Key=null;
												for(var n=0; n<Attns.length; n++){ var id=Attns[n].id.split("_");
													if(Key!=id[1]){ Key=id[1]; var Attn = $("#cartForm select[id^=\'attnd_"+Key+"_\']:last");
														$(Attn[0]).change(function (){ $(this).parent().append(\'<br />\');
															$(this).parent().append( $(this).clone() );
															$(this).unbind(\'change\');														
														});
													}																										
												}
																																			 });</script>';
			} else throw new FatalError('Cannot open template '.$this->TEMP['cList']);
		} catch (FatalError $e) {
			$this->NitaERROR(102, $e->getMessage()); // Coding fault 102
		} catch (Exception $e) {
			$this->NitaERROR(103, $e->getMessage()); // Catch all error 103
		}
	}
	// ************************************************************
	// ***                      Pagination                      ***
	// ************************************************************
	function Pagination(){ // return a string for pagination
		$BUFF='';
		$QString='';
		if (!empty($_SERVER['QUERY_STRING'])) $QString = $this->QueryReplace('p','');
		$BaseString = $_SERVER['PHP_SELF']."?".$QString.((strlen(trim($QString))>0)?"&":"")."p=";
							
		ob_start(); ?>

<div id="Pager">
  <? if ($this->Page > 0) { // Show if not first page ?>
  <a href="<? echo $BaseString."0"; ?>" title="First Page">First . </a> <a href="<? echo $BaseString.max(0,$this->Page-1); ?>" title="Previous Page">Prev . </a>
  <? 	} if($this->TotalRows>$this->Limit){
			if(($this->TotalPages+1) < $this->PageNum) $AddPage = $this->TotalPages+1;
			else $AddPage = $this->PageNum;
			
			if(($this->Page+1)<($this->PageNum/2)) $Start = 1;
			else if(abs(($this->Page+1)-($this->TotalPages+1))<=(($this->PageNum/2)-1)) $Start = ($this->TotalPages+1)-$AddPage+1;
			else $Start = ($this->Page+1)-(($this->PageNum/2)-1);
					
			for($n=$Start;$n<($Start+$AddPage);$n++){ ?>
  <a href="<? echo $BaseString.($n-1); ?>" title="Page Number <? echo $n; ?>"<? if($n==($this->Page+1)) echo ' class="NavSel"'; ?>><? echo $n; ?> . </a>
  <? 		}
		} if ($this->Page < $this->TotalPages) { // Show if not last page ?>
  <a href="<? echo $BaseString.min($this->TotalPages, $this->Page + 1); ?>" title="Next Page">Next</a> <a href="<? echo $BaseString.$this->TotalPages; ?>" title="Last Page"> . Last</a>
  <? 	} ?>
</div>
<? 
		$BUFF=ob_get_contents(); ob_end_clean();
		return $BUFF;
	}
}
?>
