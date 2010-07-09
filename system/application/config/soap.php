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
$soap['server'] = 'http://10.137.1.49:6652/';

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
$soap['services']['webPages'] = 'WebPageService.svc?wsdl';
$soap['services']['programs'] = 'ProgramService.svc?wsdl';
$soap['services']['users']    = 'AccountService.svc?wsdl';
$soap['services']['contacts'] = 'ContactService.svc?wsdl';


/*
|--------------------------------------------------------------------------
| Soap Page IDs
|--------------------------------------------------------------------------
|
| Page ID numbers for contentent coming from CRM.
*/
$soap['page']['home']   = 'f4d317f9-f37e-df11-8d9f-000c2916a1cb';
$soap['page']['footer'] = '582cd7a9-8f80-df11-8d9f-000c2916a1cb';

/* End of file soap.php */