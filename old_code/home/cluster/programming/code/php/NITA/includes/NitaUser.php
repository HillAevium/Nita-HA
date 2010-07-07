<?
class NitaUser{
	private $rp				= ''; // System tries its best to find the root folder.
	private $eol			= "\n";
	public 	$SesNames		= array("User" => "UserLogIn"); // Session Key for our User
	
	private $HTTP			= "http://10.137.1.49:6652/"; // URL to our SOAP client
	public 	$URLS			= array('user' => 'AccountService.svc?wsdl',
									'contact' => 'ContactService.svc?wsdl'); // WSDL for our programs module
	public 	$Auth 			= false;
	public 	$HTML			= '';
	public	$ERROR			= false;
	public 	$TEMP			= array(); // An array holding our template
	public 	$TAB			= 1; // Our tab index tracker
	public function __construct(){
		
		for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $this->rp .= "../";
		if (strtoupper(substr(PHP_OS,0,3))=='WIN') $this->eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $this->eol="\r"; else $this->eol="\n";
		require_once($this->rp.'includes/fnct_clean_entry.php');
		
		$this->validateUser(); // Lets validate our active user session
		
		//$this->TEMP['logIn']		= "prods_list.tpl"; 		// file path to our log in template
	}
	public function __toString(){ // Check for errors if non, return our HTML buffer.
		if($this->ERROR!==false) return $this->ERROR;
		else return $this->HTML;
	}
	public function __get($Name){
		switch($Name){
			case 'Authenticated': return $this->Auth!==false && is_array($this->Auth) && $this->Auth[0] !== 0; break;
		}
	}
	private function setAuth($User, $Level){
		$SessKey = $this->SesNames["User"];
		if(!isset( $_SESSION[$SessKey] )) session_register($SessKey); // Check to see if we have started a user session already
		$_SESSION[$SessKey] = $this->Auth = array($User, $Level); // Store our Session, our user session should already have been started.
	}
	private function CheckSession(){ // Lets check and see if a session has been started
		if(!defined("SESSIONCHECK")){
			@session_start();
			define("SESSIONCHECK",true);
		}
	}
	private function validateUser(){ // Lets get our user authentication from our session
		$SessKey = $this->SesNames["User"]; $this->CheckSession();
		if(isset($_SESSION[$SessKey])) $this->Auth=$_SESSION[$SessKey];
	}
	public function LogIn(){
		if($this->Authenticated) return true; // Are we already authenticated
		if(isset($_POST['Controller']) && $_POST['Controller'] == "true"){ // Check for Form Controller and form submition
			if(strlen($_POST['Username'])>0 && strlen($_POST['Password'])>0){ // Lets make sure that we have our username and password
				$User = new SoapClient($this->HTTP.$this->URLS['contact']); // Initiate our SOAP client
						
				$wrapper = new stdClass;
				$wrapper->username = new SoapVar( $_POST['Username'] , XSD_STRING, "string"); // Set our Username
				$wrapper->password = new SoapVar( md5($_POST['Password']) , XSD_STRING, "string"); // Set our Password
				
				$res = $User->Authenticate($wrapper); // Retrieve active programs from our SOAP client
				//var_dump($res);
				//echo $res->AuthenticateResult->contactid.' '.$res->AuthenticateResult->nita_web_level;
				//die();
				if( is_null($res->AuthenticateResult) ) $this->ERROR='Wrong Username or Password';
				else { // Need to set the authentication to actual user id and user level
					$this->setAuth($res->AuthenticateResult->contactid, intval($res->AuthenticateResult->nita_web_level)); // Set our Authentication User ID and User Level 
					return true; // If authenticated return true
				}
			} else $this->ERROR='Username and Password are required';
		}
		$this->HTML = (($this->ERROR!==false)?'<p style="color:#C00C00">'.$this->ERROR.'</p>':'').'<form method="post" name="LogIn Form" id="LogIn_Form" action="'.$_SERVER['PHP_SELF'].'">
  <label for="Username">Username</label>
  <input type="text" name="Username" id="Username" tabindex="'.($this->TAB++).'" />
  <br />
  <label for="Password">Password</label>
  <input type="password" name="Password" id="Password" tabindex="'.($this->TAB++).'" /><br />
  <input type="submit" name="submit" id="submit" value="Log In" tabindex="'.($this->TAB++).'" />
  <input type="hidden" name="Controller" id="Controller" value="true" />
</form>';
		return false;
	}
}
?>