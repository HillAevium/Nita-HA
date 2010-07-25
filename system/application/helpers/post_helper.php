<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Post Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Daniel Zukowski
 */

// ------------------------------------------------------------------------

/**
 * Process Post
 *
 * Processes post data, cleaning it and testing for required fields
 *
 * @access	public
 * @param	array     $required	the required fields
 * @param	array     $optional	the optional fields
 * @return	array
 */	
if ( ! function_exists('process_post'))
{
	function process_post($required, $optional)
	{
	   
		$CI =& get_instance();
		$post = $_POST;
        $returnData = array();
        $errors = array();
        
                /**
         * If the form contains multiple fields for 
         * bar info, we need to handle those fields 
         * as a special case.
         * 
         * $barFields is a list of array input field names
         * in the POST that together constitute one of the
         * user's bar licenses.
         * 
         * For the $returnData, we format
         * bar info like this:
         * $returnData['bar'] = array(
         *                          [0] => array(
         *                              'barId' => 123,
         *                              'state' => 'CA',
         *                              'data'  => '2010-07-20')
         *                          [1] => array(
         *                              'barId' => 345,
         *                              'state' => 'AZ',
         *                              'data'  => '2005-01-08')
         *                      );
         */
        $barFields = array("barId","state","date");
        foreach($barFields as $key) {
            if($value = $CI->input->post($key)) {
                for($i=0;$i<count($value);$i++) {
                    $returnData['bar'][$i][$key] = $value[$i];
                }
            }
        }
        
        foreach($required as $key=>$error) {
            if($value = $CI->input->post($key)) {
                $returnData[$key] = $value;
            } else {
                $errors[] = $error;
            }
        }
        
        foreach($optional as $key) {
            if($value = $CI->input->post($key)) {
                $returnData[$key] = $value;
            }
        }
        
        if(count($errors)) {
            $errorsOut = print_r($errors,true);
            log_message('debug', $errorsOut);
            throw new RuntimeException($errorsOut);
        }
        
        return $returnData;

	}
}

/* End of file post_helper.php */
/* Location: ./system/helpers/post_helper.php */