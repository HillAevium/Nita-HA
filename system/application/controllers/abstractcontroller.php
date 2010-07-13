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
    private $arguments;
    // options for the loadViews
    private $viewOptions = array();
    
    /* viewOptions
     * bodyClass - sets the class of the body tag for the page
     * debug     - boolean switch to turn the debug console on/off
     * mainNav   - boolean switch to turn the mainNav on/off
     * pageTitle - title for the browser window
     * views     - an array of views consisting of the following structure:
     *             'name': the name of the view to load
     *             'args': the arguments the view uses to render itself
     */
    
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
    
    protected function getViewOption($option) {
        return $this->haveViewOption($option) ? $this->viewOptions[$option] : false;
    }
    
    protected function haveArgument($name) {
        return isset($this->arguments[$name]);
    }
    
    protected function haveViewOption($option) {
        return isset($this->viewOptions[$option]);
    }
    
    protected function setViewOption($option, $value) {
        $this->viewOptions[$option] = $value;
    }
    
    /**
     * Loads persistent header and footer and any
     * optional views.
     *
     * @param array $views array of views to be loaded
     *                     into the main content area
     * @param string $bodyClass optional css class to be applied to the <body>
     */
    protected function loadViews() {
        $bodyClass = $this->getViewOption('bodyClass');
        $debug     = $this->getViewOption('debug');
        $mainNav   = $this->getViewOption('mainNav');
        $title     = $this->getViewOption('pageTitle');
        $views     = $this->getViewOption('views');
        
        $this->load->view('http_header', array('title' => $title));
        if($bodyClass !== false) {
            $this->load->view('header', array('bodyClass' => $bodyClass));
        } else {
            $this->load->view('header');
        }
        
        // Only show the nav bar if its requested
        if($mainNav) {
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
