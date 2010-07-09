<?php

function products(){
    
    /**********************************************************
     * This function contains info necessary for the Soap API *
     **********************************************************/
    
	if(isset($_POST['Controller'])
	      && $_POST['Controller'] == "AddToCart"
	      && isset($_POST['qty'])
	      && isset($_POST['id'])
	      && isset($_POST['price']) ) {
	    
		$this->addToCart();
		return;
	}
	
	// Buffer holding HTML from our templates
	$myBuff = '';
	// Empty string to hold our pagination
	$PString = '';
	
	try {
		if(isset($_GET['id'])) {
		    
			if($contBuff = $this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pLarge'])) {
			    // Initiate our SOAP client
			    $soap = new SoapClient($this->HTTP.$this->URLS['programs']);
				$args = new stdClass;
				
				// Set our Start date
				$args->id = new SoapVar( $_GET['id'] , XSD_STRING, "string");
				 
				// Retrieve active programs from our SOAP client
				$result = $programs->Get($wrapper);
				
				$info =& $result->GetResult;
				
				// format our program start date
				$startDate = date("M j, Y",strtotime($info->NITA_StartDate));
				// format our program end date
				$endDate = date("M j, Y",strtotime($info->NITA_EndDate));
				
				$Facility=array();
				
				if(!is_null($info->NITA_FacilityName))  $Facility[] = $info->NITA_FacilityName;
				if(!is_null($info->NITA_FacilityAddr1)) $Facility[] = $info->NITA_FacilityAddr1;
				if(!is_null($info->NITA_FacilityAddr2)) $Facility[] = $info->NITA_FacilityAddr2;
				if(!is_null($info->NITA_FacilityAddr3)) $Facility[] = $info->NITA_FacilityAddr3;
				if(!is_null($info->NITA_FacilityAddr4)) $Facility[] = $info->NITA_FacilityAddr4;
				if(!is_null($info->NITA_FacilityCity)
				        && !is_null($info->NITA_FacilityState)
				        && !is_null($info->NITA_FacilityZip)) {
				       
				    $Facility[] = (!is_null($info->NITA_FacilityCity)  ? $info->NITA_FacilityCity.', ' : '').
				                  (!is_null($info->NITA_FacilityState) ? $info->NITA_FacilityState.' ' : '').
					              (!is_null($info->NITA_FacilityZip)   ? $info->NITA_FacilityZip       : '');
				}
				
				$CLE = array();
				if(!is_null($info->Nita_50MinuteCredits))
				   $CLE[] = "50 Minute Credits: ".$info->Nita_50MinuteCredits;
				if(!is_null($info->Nita_60MinuteCredits))
				   $CLE[] = "60 Minute Credits: ".$info->Nita_60MinuteCredits;
				   
				if(count($CLE)==0)
				   $CLE[]="No CLE Credits";
				
				
				$this->HTML.=$this->processCodes($contBuff,array('ID'			=> $this->ckNULL($info->NITA_ProgramId),
																 'Name'			=> $this->ckNULL($info->NITA_Title),
																 'Desc'			=> $this->ckNULL($info->NITA_Description),
																 'SKU'			=> $info->NITA_name,
																 'SDate'		=> $SDate,
																 'EDate'		=> $EDate,
																 'Avail'		=> 'Open to Registration',
																 'Fac'			=> '<p>'.implode("<br />",$Facility).'</p>',
																 'CLE'			=> '<p>'.implode("<br />",$CLE).'</p>',
																 'Price'		=> '$'.number_format($this->ckNULL($info->NITA_TuitionPriceStandard),2,'.',','),
																 'PriceInt'		=> $this->ckNULL($info->NITA_TuitionPriceStandard),
																 'Attr'			=> '',
																 'AddCart'		=> $_SERVER['PHP_SELF'],
																 'URL'			=> $this->Files['prodDet'].'?id='.$info->NITA_ProgramId,
																 ));
			} else {
			    throw new FatalError('Cannot open template '.$this->TEMP['pLarge']);
			}
		} else {
		    // If we have not selected a program to look at provide a list of programs.
		    // Initiate our SOAP client
			$soap = new SoapClient($this->HTTP.$this->URLS['programs']);
			$args = new stdClass;
			
			// Set our Start date
			$args->startDate = new SoapVar(date("c"), XSD_DATETIME, "dateTime");
			
			// Drop an end date a year out
			$args->endDate   = new SoapVar(date("c", mktime(0,0,0,date("m"),date("d"),date("Y")+5 ) ), XSD_DATETIME, "dateTime");

			// Retrieve active programs from our SOAP client
			$rows = $soap->GetActivePrograms($args);
			 
			if(isset($_GET['s'])) {
			    $this->SKey = explode(',', $_GET['s']);
			}
			
			// Get our product list template from the server
			if($contBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pListCont'])) {
			    $prefix = $_SERVER['PHP_SELF'].'?s=';
			    // Drop our sorting links into the product list
				$contBuff = $this->processCodes($contBuff,
                    array(
                        // Fucking hack!
                       'SortTitle' => $prefix.'Name,'    .(($this->SKey[0] == 'Name'     && $this->SKey[1] == 'ASC') ? 'DESC' : 'ASC'),
                       'SortDate'  => $prefix.'Date,'    .(($this->SKey[0] == 'Date'     && $this->SKey[1] == 'ASC') ? 'DESC' : 'ASC'),
                       'SortLoc'   => $prefix.'Location,'.(($this->SKey[0] == 'Location' && $this->SKey[1] == 'ASC') ? 'DESC' : 'ASC'),
                       'SortPrice' => $prefix.'Price,'   .(($this->SKey[0] == 'Price'    && $this->SKey[1] == 'ASC') ? 'DESC' : 'ASC')
                    ));
                
                // Set our list of programs to an array for easier use
				$programs =& $rows->GetActiveProgramsResult->ProgramModel;
				// Our Sorting header
				$Sorting = array();
				
				if(is_array($this->SArray[$this->SKey[0]])) {
				    foreach($this->SArray[$this->SKey[0]] as $Sort) {
				        $Sorting[$Sort]=$this->SKey[1];
				    }
				} else {
				    $Sorting[$this->SArray[$this->SKey[0]]]=$this->SKey[1];
				}
				
				// Sort our array of objects based on an object property
				$this->ObjectSorter($programs, $Sorting);
				
				// Get Item templace from the server
				if($itemBuff=$this->LoadTemplate($this->rp.$this->TEMPPath.$this->TEMP['pList'])) {
					if(intval($this->Limit)>0) {
						if(isset($_GET['pg'])) {
						    $this->Page = intval($_GET['pg']);
						}
						
						// Total number of products in the database
						$this->TotalRows = count($programs);
						$this->TotalPages = ceil($this->TotalRows/$this->Limit)-1;
						
						// Get our pagination string from our class function
						$PString=$this->Pagination();
                        
						$programs = array_slice($programs, ($this->Page*$this->Limit), $this->Limit);
					}

					// For each program set the appropriate data to our hooks
					foreach ($programs as $row){
					    // format our program start date
						$SDate = date("M j, Y",strtotime($row->NITA_StartDate));
						// format our program end date
						$EDate = date("M j, Y",strtotime($row->NITA_EndDate));
						$myBuff.=$this->processCodes($itemBuff,
                            array(
                                'ID'      => $this->ckNULL($row->NITA_ProgramId),
                                'Name'    => $this->ckNULL($row->NITA_Title					),
                                'SDate'   => $SDate,
                                'EDate'   => $EDate,
                                'City'    => $this->ckNULL($row->NITA_FacilityCity		),
                                'State'   => $this->ckNULL($row->NITA_FacilityState	),
                                'Price'   => '$'.number_format($this->ckNULL($row->NITA_TuitionPriceStandard),2,'.',','),
                                'PriceInt'=> $this->ckNULL($row->NITA_TuitionPriceStandard),
                                'AddCart' => $_SERVER['PHP_SELF'],
                                'URL'     => $this->Files['prodDet'].'?id='.$this->ckNULL($row->NITA_ProgramId)
                                ));
					}
					unset($programs);
					
				} else {
				    throw new FatalError('Cannot open template '.$this->TEMP['pList']);
				}
				// Place our products into our template
				$this->HTML .= str_replace("[List]", $myBuff, $contBuff);
				 
			} else {
			    throw new FatalError('Cannot open template '.$this->TEMP['pListCont']);
			}
		}
		$this->HTML = $PString.$this->HTML.$PString;
	} catch (FatalError $e) {
	    // Coding fault 102
		$this->NitaERROR(102, $e->getMessage());
	} catch (SoapFault $fault) {
		// SOAP fault 101, error to our SOAP connection
		$this->NitaERROR(101, $fault->faultcode.", ".$fault->faultstring);
	}  catch (Exception $e) {
	    // Catch all error 103
		$this->NitaERROR(103, $e->getMessage());
	}
}