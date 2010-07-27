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
    function process_post(Definition $definition) {
        $CI =& get_instance();
        $returnData = array();
        $errors = array();
        
        foreach($definition->fields() as $field) {
            log_message('debug', $field->name);
            
            if($field->type === 'array') {
                // Loop through the sub fields for this array
                foreach($field->fields() as $subField) {
                    log_message('debug', ' - '.$subField->name);
                    $value = $CI->input->post($subField->name);
                    if($value === false) {
                        if($field->required) {
                            $errors[] = "Field Required: " . $subField->name;
                        }
                    }
                    
                    // Here we finally go through the form array
                    for($i = 0; $i < count($value); $i++) {
                        if(!__verify($subField, $value[$i])) {
                            $errors[] = "Field Invalid: " . $subField->name;
                        }
                        $returnData[$field->name][$i][$subField->name] = $value[$i];
                    }
                }
            } else {
                $value = $CI->input->post($field->name);
                if($value === false) {
                    if($field->required) {
                        $errors[] = "Field Required: " . $field->name;
                    }
                    // Optional unset values get ignored
                } else {
                    if(!__verify($field, $value)) {
                        $error[] = "Field Invalid: " . $field->name;
                    }
                    // TODO Verification
                    $returnData[$field->name] = $value;
                }
            }
        }

        if(count($errors)) {
            $returnData['errors'] = $errors;
            $errorsOut = print_r($errors,true);
            log_message('debug', 'Errors: ' . $errorsOut);
            //throw new RuntimeException($errorsOut);
        }
        
        return $returnData;
    }
    
    function __verify(Field $field, $data) {
        switch($field->type) {
            case 'email' :
                break;
            case 'state' :
                break;
            case 'date' :
                break;
            case 'bar' :
                break;
            case 'password' :
                break;
            case 'boolean' :
                break;
            case 'phone' :
                return true;
            default :
                return true;
        }
        // TODO
        return true;
    }
}

/* End of file post_helper.php */
/* Location: ./system/helpers/post_helper.php */