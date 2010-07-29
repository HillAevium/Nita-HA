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
    function process_post(Model_Definition $definition) {
        $returnData = array();
        $errors = array();
        
        foreach($definition->fields() as $field) {
            if($field->isEmpty()) {
                if($definition->isRequired($field)) {
                    $errors[] = "Missing required field: " . $field->name;
                }
            } else if(! $field->validate()) {
                $errors[] = $field->error;
            } else {
                $returnData[] = $field->process();
            }
        }

        if(count($errors)) {
            $returnData['errors'] = $errors;
        }
        
        return $returnData;
    }
}

/* End of file post_helper.php */
/* Location: ./system/helpers/post_helper.php */