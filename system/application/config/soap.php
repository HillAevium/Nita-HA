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
$config['soap']['server'] = 'http://72.54.98.142:6652/';

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
$config['soap']['services']['webPage'] = 'WebPageService.svc?wsdl';
$config['soap']['services']['program'] = 'ProgramService.svc?wsdl';
$config['soap']['services']['account'] = 'AccountService.svc?wsdl';
$config['soap']['services']['contact'] = 'ContactService.svc?wsdl';


/*
|--------------------------------------------------------------------------
| Soap Page IDs
|--------------------------------------------------------------------------
|
| Page ID numbers and colors for content coming from CRM.
*/
$config['soap']['page']['home']           = array(  'guid' => 'f4d317f9-f37e-df11-8d9f-000c2916a1cb',
                                                    'color' => false);
$config['soap']['page']['about']          = array(  'guid' => 'CD1BC3CF-F67E-DF11-8D9F-000C2916A1CB',
                                                    'color' => 'green');
$config['soap']['page']['donate']         = array(  'guid' => 'E7E9F222-F57E-DF11-8D9F-000C2916A1CB',
                                                    'color' => 'yellowgreen');
$config['soap']['page']['contact']        = array(  'guid' => '656930E6-F67E-DF11-8D9F-000C2916A1CB',
                                                    'color' => 'blue');
$config['soap']['page']['customize']      = array(  'guid' => 'B647AFA2-F67E-DF11-8D9F-000C2916A1CB',
                                                    'color' => 'purple');
$config['soap']['page']['footer']         = array(  'giud' => '582cd7a9-8f80-df11-8d9f-000c2916a1cb',
                                                    'color' => false);
$config['soap']['page']['law_school']     = array(  'guid' => '356FF3B6-F67E-DF11-8D9F-000C2916A1CB',
                                                    'color' => 'grayblue');
$config['soap']['page']['links']          = array(  'guid' => '1847B830-9080-DF11-8D9F-000C2916A1CB',
                                                    'color' => false);
$config['soap']['page']['privacy_policy'] = array(  'guid' => 'E8CA8E6A-9080-DF11-8D9F-000C2916A1CB',
                                                    'color' => false);
$config['soap']['page']['publications']   = array(  'guid' => '86617168-7AA5-DF11-85D6-000C2916A1CB',
                                                    'color' => 'red');
$config['soap']['page']['sitemap']        = array(  'guid' => 'F80624BD-9080-DF11-8D9F-000C2916A1CB',
                                                    'color' => false);

/* End of file soap.php */