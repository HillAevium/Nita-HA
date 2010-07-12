<?php

define('DEBUG_NONE', 0);
define('DEBUG_SESSION', 1);

define('DEBUG_ALL', DEBUG_SESSION);

define('DEBUG', DEBUG_NONE);

abstract class AbstractController extends Controller {
    
    /* TODO
     * > Should we move the generic page titles to a config file?
     */
    
    // the uri arguments
    protected $arguments;
    // the page title
    protected $title;
    // ajax request
    protected $ajax = false;
    // show the main navigation container
    protected $showMainNav = false;
    
    private $debugOptions = DEBUG_NONE;
    
    protected function AbstractController() {
        parent::Controller();
        
        $this->arguments = $this->uri->uri_to_assoc();
        
        // Turn on debug if its enabled.
        if(DEBUG != DEBUG_NONE) {
            $this->setDebug(true, DEBUG);
        }
        
        // and allow uri to override
        if($this->haveArgument('debug')) {
            $this->setDebug(true, $this->getArgument('debug'));
        }
        
        // Check for ajax request
        if($this->haveArgument('request')) {
            if($this->getArgument('request') === 'ajax') {
                $this->ajax = true;
            }
        }
    }
    
    protected function isDebugEnabled($option = -1) {
        if ($option == -1) {
            return $this->debugOptions > DEBUG_NONE;
        }
        return $this->debugOptions & $option == $option;
    }
    
    protected function getArgument($name) {
        return $this->haveArgument($name) ? $this->arguments[$name] : false;
    }
    
    protected function haveArgument($name) {
        return isset($this->arguments[$name]);
    }
    
    protected function createPaginationLinks($function, $totalRows) {
        $this->load->library('pagination');
        
        // Move this to config/pagination.php ?
        $config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/shop/' . $function . '/offset/';
        $config['total_rows'] = $totalRows;
        $config['per_page'] = 8;
        
        $this->pagination->initialize($config);
        
        return $this->pagination->create_links();
    }
    
    /**
     * Loads persistent header and footer and any
     * optional views.
     *
     * @param array $views array of views to be loaded
     *                     into the main content area
     * @param string $bodyClass optional css class to be applied to the <body>
     */
    protected function loadViews(array $views, $bodyClass = '') {
        $this->load->view('http_header', array('title' => $this->title));
        if($bodyClass != '') {
            $this->load->view('header', array('bodyClass' => $bodyClass));
        } else {
            $this->load->view('header');
        }
        
	// Only show the nav bar if its requested
        if($this->showMainNav) {
            $this->load->view('main_nav');
        }
        
        // Load the content container and inject the
        // requested view into it.
        $args['content'] = '';
        foreach($views as $view) {
            $args['content'] .= $this->load->view($view['name'], $view['args'], true);
        }
        $this->load->view('content_container', $args);
        
        // If debugging is turned on display the Debug Console
        if($this->isDebugEnabled()) {
            $args = array();
            
            // Session Debug
            $session['userData'] = $this->session->all_userdata();
            
            if($session['userData'] !== false) {
                $args['categories']['session'] = $session;
                $args['categories']['session']['_title'] = 'Session Info';
            }
            
            if(count($args['categories']) > 0) {
                $this->load->view('debug', $args);
            }
        }
        
        // And voila!
        $this->load->view('footer');
    }
    
    private function setDebug($on, $args) {
        if(!$on) {
            $this->debugOptions = DEBUG_NONE;
            return;
        }
        
        if(is_int($args)) {
            $this->debugOptions = $args;
        } else if(is_string($args)) {
            $args = explode(',', $args);

            foreach($args as $arg) {
                switch($arg) {
                    case 'session' :
                        $this->debugOptions |= DEBUG_SESSION;
                    break;
                    default :
                        log_message('debug', 'Unknown debug option: ' . $arg);
                    break;
                }
            }
        }
    }
}
