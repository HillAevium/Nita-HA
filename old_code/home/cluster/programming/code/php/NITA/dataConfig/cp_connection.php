<?
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
define("DBCONN", "");
define("DBDATA", "");
define("DBUSER", "");
define("DBPASS", "");
define("DBGATE", ""); // MySQL, MsSQL are support

// legacy vars from cart, chnge when time permits
$hostname_cp_connection = DBCONN;
$database_cp_connection = DBDATA;
$username_cp_connection = DBUSER;
$password_cp_connection = DBPASS;
$gateways_cp_connection = DBGATE; // MySQL, MsSQL are support

if($gateways_cp_connection == "MySQL"){
	//$cp_connection = mysql_pconnect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or trigger_error(mysql_error(),E_USER_ERROR);
} else {
	//$cp_connection = mssql_connect($hostname_cp_connection, $username_cp_connection, $password_cp_connection) or die(mssql_get_last_message()); 
}

define('ENCRYPTKEY', "NITA(dot)ORG");

$ftp_cp_diffhost = false;
$ftp_cp_connection = "";
$ftp_cp_passive = true;
$ftp_cp_username = "";
$ftp_cp_password = "";
$ftp_cp_root = "";

$ftp2_cp_diffhost = false;
$ftp2_cp_connection = "";
$ftp2_cp_passive = "";
$ftp2_cp_username = "";
$ftp2_cp_password = "";
$ftp2_cp_root = "";

// Credit Card Gateway
$ini_gateway = "authorize_net";
$ini_live = false;
$ini_type = ""; //auth, charge
$ini_method = ""; //process. void, return
$ini_login = "";
$ini_password = "";
$ini_key = "";
$ini_reciever = "";

$UPS_Gateway = false;
$UPS_Access_Number = "";
$UPS_UserName = "";
$UPS_Password = "";
$UPS_Account_Number = ""; ?>