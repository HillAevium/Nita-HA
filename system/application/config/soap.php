<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Soap Server Address
|--------------------------------------------------------------------------
|
| URL to the Soap server root. This is the base URL WITH a trailing slash.
|
|   With a hostname
|       http://soap.example.com/
|   Or a static IP
|       http://10.42.122.19/
|
*/
$soap['server'] = '';

/*
|--------------------------------------------------------------------------
| WSDL URI name
|--------------------------------------------------------------------------
|
| The URI name for the Soap client to query the web service.
| This will be the name that follows the server configuration above.
|
|       SomeWsdlService.svc?wsdl
*/
$soap['wsdl']   = '';

/* End of file soap.php */