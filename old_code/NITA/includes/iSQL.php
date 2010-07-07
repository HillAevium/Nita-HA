<?
class ERROR extends Exception{}
class iSQL {
	var $KEY=0;
	var $TOTROWS=0;
	var $EmailError=true;
	var $DBFile='dataConfig/cp_connection.php';	
	public $ERROR=false;
	public $Row=array();
	
	private $rp='';
	protected $RESULTS=NULL;
	protected $DATA=array();
	protected $Q='';
	protected $EString='';
	protected $FIELDS=array();
	protected $TIMER=0;
	protected $NewID=0;
	protected $AFFECT=0;
	
	public function __construct(){
		$this->rp=''; for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $this->rp .= "../";
		
		try{
			if(!is_file($this->rp.$this->DBFile)) throw new ERROR("Unable to find connection file");
			require $this->rp.$this->DBFile;
		
		
			if(DBGATE == "MySQL"){
				if(!$this->DATA['CONN'] = mysql_pconnect(DBCONN, DBUSER, DBPASS)) throw new ERROR('Unable to connect to database');
				if(!mysql_select_db(DBDATA, $this->DATA['CONN'])) throw new ERROR('Unable to connect to database');
			} else if(DBGATE == "MsSQL"){
				if(!$this->DATA['CONN'] = mssql_connect(DBCONN, DBUSER, DBPASS)) throw new ERROR('Unable to connect to database');
				if(!mssql_select_db(DBDATA, $this->DATA['CONN'])) throw new ERROR('Unable to connect to database');
			} else throw new ERROR('Please select a database type');
		} catch (ERROR $e) {
			$this->EString = $e->getMessage();
			$this->ERROR=true;
		} catch (Exception $e) {
			$this->myError($e->getMessage());
			$this->EString = "We have experienced an internal error, please try again later. If this error persistes please contact us.";
			$this->ERROR=true;
		}
	}
	public function __destruct(){
		$this->free();
		if($this->TOTROWS > 0){
			if(DBGATE == "MySQL"){ 		mysql_close($this->DATA['CONN']); }
			else if(DBGATE == "MsSQL"){ mssql_close($this->DATA['CONN']); }
		}
	}
	public function __toString(){
		return $this->Q;
	}
	public function __get($VAR){
		switch(strtolower($VAR)){
			case "totalrows":
			case "total": 		return $this->TOTROWS; break;
			case "fields": 		return $this->FIELDS;  break;
			case "lastrow":
			case "last": 			return $this->TOTROWS-1; break;
			case "executiontime":
			case "time": 			return $this->TIMER.' seconds'; break;
			case "newid":
			case "insertedid":
			case "id":				return $this->NewID; break;
			case "touched":
			case "affected":	return $this->AFFECT; break;
			case "errormsg": 	return $this->EString; break;
		}		
	}
	private function free(){
		if($this->TOTROWS > 0){
			if(DBGATE == "MySQL"){ 		mysql_free_result($this->RESULTS); }
			else if(DBGATE == "MsSQL"){ mssql_free_result($this->RESULTS); }
		}
	}
	private function query($ARGS){
		if($this->ERROR) return;
		if(count($ARGS)>1){
			foreach(array_slice($ARGS,1,NULL,true) as $key => $value){
				if(DBGATE == "MySQL") 			$ARGS[$key] = mysql_real_escape_string($value);
				else if(DBGATE == "MsSQL") 	$ARGS[$key] = str_replace(array("'","\0"),array("''","[NULL]"),$value);
			}
			$this->Q = vsprintf($ARGS[0],array_slice($ARGS,1));
		}	else $this->Q = $ARGS[0];
		
		$this->FIELDS = array();
		$this->RESULTS = array();
		$this->TOTROWS = 0;
		
		try{
			$this->TIMER=microtime();
			if(strlen($this->Q) == 0){ throw new ERROR('Please provide a SQL statement'); }
			if(DBGATE == "MySQL"){
				if(!$this->RESULTS = mysql_query($this->Q, $this->DATA['CONN'])) throw new Exception(mysql_error()."\n\r In MySQL statement: ".$this->Q);
				if(strpos(strtolower($this->Q),"insert into")!==false){
					if(!$this->NewID=mysql_insert_id($this->DATA['CONN'])) throw new ERROR("During ID retreaval: ".mysql_error());
				}
				$this->AFFECT=mysql_affected_rows($this->DATA['CONN']);
				@$this->TOTROWS = mysql_num_rows($this->RESULTS);
				if($this->TOTROWS > 0){ $n=0;
					while ($Fields = mysql_fetch_field($this->RESULTS)) {
						array_push($this->FIELDS,array($Fields->name, mysql_field_type($this->RESULTS,$n)));
						$n++;
					}
				}
			} else if(DBGATE == "MsSQL"){
				if(!$this->RESULTS = mssql_query($this->Q, $this->DATA['CONN'])) throw new ERROR(mssql_get_last_message()."\n\r In MySQL statement: ".$this->Q);
				if(strpos(strtolower($this->Q),"insert into")!==false){
					
					$result = mssql_query('SELECT SCOPE_IDENTITY() AS last_insert_id');
          $row = mysql_fetch_assoc($result);
					if($row!==false){
						mssql_free_result($result);
						$this->NewID = $row['last_insert_id'];
						unset($result);
						unset($row);
					} else throw new Exception("During ID retreaval: ".mssql_get_last_message());
				}
				$this->AFFECT=mssql_rows_affected($this->DATA['CONN']);
				@$this->TOTROWS = mssql_num_rows($this->RESULTS);
				if($this->TOTROWS > 0){ $n = 0;
					while ($Fields = mssql_fetch_field($this->RESULTS)) {
						array_push($this->FIELDS,array($Fields->name, mssql_field_type($this->RESULTS,$n)));
						$n++;
					}
				}
			}
			$this->TIMER=microtime()-$this->TIMER;
			$this->Row = &new iSQLROW($this,$this->RESULTS);
		} catch (ERROR $e){
			$this->EString = $e->getMessage();
			$this->ERROR=true;
		} catch (Exception $e) {
			$this->myError($e->getMessage());
			$this->EString = "We have experienced an internal error, please try again later. If this error persistes please contact us.";
			$this->ERROR=true;
		}
	}
	public function mySQL(){
		if(DBGATE == "MySQL") $this->query(func_get_args());
		else return;
	}
	public function msSQL(){
		if(DBGATE == "MsSQL") $this->query(func_get_args());
		else return;
	}
	public function Rows(){
		return new iSQLROWS($this,$this->RESULTS);
	}
	public function setKey($Key){
		if(DBGATE == "MySQL") 			mysql_data_seek($this->RESULTS, $Key);
		else if(DBGATE == "MsSQL")	mssql_data_seek($this->RESULTS, $Key);
	}
	public function getRow(){
		if(DBGATE == "MySQL") 			return mysql_fetch_assoc($this->RESULTS);
		else if(DBGATE == "MsSQL") 	return mssql_fetch_assoc($this->RESULTS);
	}
	protected function myError($MSG){
		if($this->EmailError==true){
			require_once($this->rp.'scripts/fnct_send_email.php');
			
			$MSG = mb_convert_encoding($MSG,"UTF-8","HTML-ENTITIES");
			
			$server = $_SERVER['HTTP_HOST'];
			if(strpos("http:",strtolower($_SERVER['HTTP_HOST'])) !== false) 	$server = substr($_SERVER['HTTP_HOST'],6);
			if(strpos("https:",strtolower($_SERVER['HTTP_HOST'])) !== false) 	$server = substr($_SERVER['HTTP_HOST'],7);
			if(strpos("www",strtolower($_SERVER['HTTP_HOST'])) !== false) 		$server = substr($_SERVER['HTTP_HOST'],4);
			
			$mail = new PHPMailer();
			//$mail -> IsSMTP();
			$mail -> Host = "smtp.".$server;
			$mail -> IsHTML(false);
			$mail -> From = "chad.serpan@aevium.com";
			$mail -> FromName = "chad.serpan@aevium.com";
			$mail -> AddAddress("chad.serpan@aevium.com");
			$mail -> Subject = "PHP Error: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			$mail -> Body = $MSG;
			$mail -> Send();
		}
	}
}
class iSQLROWS implements Iterator{
	private $PRNT=NULL;
	private $RESULTS=NULL;
	public function __construct($prnt, $Results){
		$this->PRNT=$prnt;
		$this->RESULTS=$Results;
		$this->PRNT->KEY=0;
	}
	public function __destruct(){
		$this->PRNT->KEY=0;
	}
	public function rewind() {
		if($this->PRNT->ERROR===false){
			$this->PRNT->setKey(0);
		}
		$this->PRNT->KEY=0;
	}
	public function current() {
		if($this->PRNT->ERROR===false){
			$ROW = $this->PRNT->getRow();
			$this->PRNT->KEY++;
		} else $ROW = $this->PRNT->EString;
		return $ROW;
	}
	public function key() {
		return ($this->PRNT->KEY==0)?0:$this->PRNT->KEY-1;
	}
	public function prev(){
		if($this->PRNT->ERROR===false) return ($this->PRNT->KEY-1);
		else return 0;
	}
	public function next() {
		if($this->PRNT->ERROR===false) return ($this->PRNT->KEY+1);
		else return 0;
	}
	public function end(){
		if($this->PRNT->ERROR===false){
			$this->PRNT->KEY=$this->PRNT->TOTROWS-1;
			
			$this->PRNT->setKey($this->PRNT->KEY);
			$ROW = $this->PRNT->getRow();
			
			if ($ROW!==false) return array();
			$this->PRNT->KEY++;
		} else $ROW = $this->PRNT->EString;
		return $ROW;
	}
	public function valid() {
		if($this->PRNT->ERROR===false) return ($this->PRNT->KEY<$this->PRNT->TOTROWS && $this->PRNT->KEY >= 0)?true:false;
		return false;
	}
}
class iSQLROW implements arrayaccess {
	private $PRNT=NULL;
	private $RESULTS=NULL;
	public function __construct($prnt, $Results){
		$this->PRNT=$prnt;
		$this->RESULTS=$Results;
	}
	public function offsetSet($offset, $value) {
			return true;
	}
	public function offsetExists($offset) {
		return ($offset<$this->PRNT->TOTROWS && $offset >= 0)?true:false;
	}
	public function offsetUnset($offset) {
			return true;
	}
	public function offsetGet($offset) {
		if($this->PRNT->ERROR===false){
			if($offset>=$this->PRNT->TOTROWS) return '';
			$this->PRNT->KEY=$offset;
			
			$this->PRNT->setKey($offset);
			$ROW = $this->PRNT->getRow();
			
			return ($ROW!==false)?$ROW:'';
		} return '';
	}
}
?>
