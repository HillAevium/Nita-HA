<?php

define('DEBUG_NONE', 0);
define('DEBUG_SESSION', 1);

define('DEBUG_ALL', DEBUG_SESSION);

define('DEBUG', DEBUG_NONE);

abstract class AbstractController extends Controller {
    
    /* TODO(chris)
     * viewOptoins is really starting to become an unwelcome
     * hack. Need to fix this up.
     */
    
    // the uri arguments
    protected $arguments = array();
    // options for the loadViews
    private $viewOptions = array();
    private $titlePrefix = '';
    
    private $lipsum = array();
    
    /* viewOptions
     * breadcrumb - an indexed array of strings to add to the
     *              breadcrumb bar. Each element has an array with
     *              two elements, 'id' and 'name'.
     * color      - string used as prefix for color-dependent css classes
     *              "orange" , "blue" , etc.
     * debug      - boolean switch to turn the debug console on/off
     * mainNav    - boolean switch to turn the mainNav on/off
     * pageTitle  - title for the browser window
     * views      - an array of views consisting of the following structure:
     *             'name': the name of the view to load
     *             'args': the arguments the view uses to render itself
     */
    
    private $debugOptions = DEBUG_NONE;
    
    protected function AbstractController() {
        parent::Controller();
        
        // FIXME - Hack
        // Clean this argument code up.
        
        // URI
        $this->addArguments($this->uri->uri_to_assoc());
        
        // TODO - Session
        //$this->addArguments($this->session->all_userdata());
        
        // Cookies
        $cookieKeys = array_keys($_COOKIE);
        $args = array();
        foreach($cookieKeys as $key) {
            $value= $this->input->cookie($key, true);
        }
        $this->addArguments($args);
        
        // Post
        $postKeys = array_keys($_POST);
        $args = array();
        foreach($postKeys as $key) {
            $value = $this->input->post($key, true);
            $args[$key] = $value;
        }
        $this->addArguments($args);
        
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
        
        $this->lipsum['50'] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. "
                             ."Morbi sit amet neque at nulla pretium mattis quis a enim. "
                             ."Integer ligula felis, sollicitudin sit amet porta ut, "
                             ."fringilla et libero. Mauris sed commodo magna. Maecenas "
                             ."porta, mi ut tincidunt bibendum, ligula orci pretium nibh, "
                             ."vel dapibus odio diam non enim.";
    }
    
    private function addArguments($arguments) {
        foreach($arguments as $key => $value) {
            if(array_key_exists($key, $this->arguments)) {
                // Namespace conflict!
                throw new Exception("Namespace conflict: AbstractController::arguments: Key: " + $key);
            }
            $this->arguments[$key] = $value;
        }
    }
    
    protected function isDebugEnabled($option = -1) {
        if ($option == -1) {
            return $this->debugOptions > DEBUG_NONE;
        }
        return $this->debugOptions & $option == $option;
    }
    
    public function getArgument($name, $default = false) {
        return $this->haveArgument($name) ? $this->arguments[$name] : $default;
    }
    
    public function getRandomText($amount) {
        $output = '';
        for($i = 0; $i < $amount; $i++) {
            $output .= $this->lipsum['50'];
        }
        return $output;
    }
    
    protected function getViewOption($option) {
        return $this->haveViewOption($option) ? $this->viewOptions[$option] : false;
    }
    
    public function haveArgument($name) {
        return isset($this->arguments[$name]);
    }
    
    protected function haveViewOption($option) {
        return isset($this->viewOptions[$option]);
    }
    
    protected function setViewOption($option, $value) {
        $this->viewOptions[$option] = $value;
    }
    
    /**
     * Main view loading function
     *
     */ 
    protected function loadViews() {
        $color     = $this->getViewOption('color');
        $debug     = $this->getViewOption('debug');
        $mainNav   = $this->getViewOption('mainNav');
        $title     = $this->titlePrefix . $this->getViewOption('pageTitle');
        $views     = $this->getViewOption('views');
        $searchbox = $this->getViewOption('searchbox');
        $topbox    = $this->getViewOption('topbox');
        $breadcrumb= $this->getViewOption('breadcrumb');
        
        $this->load->view('http_header', array('title' => $title));
        
        // Check if user is authenticated
        // and set the proper account links
        $isAuth = $this->mod_auth->isAuthenticated();
        if($isAuth === true) {
            $accountLink = '/account/user';
        } else {
            $accountLink = '/account/register';
        }
        
        // Set header view args
        $headerArgs = array();
        if($color !== false) {
            $headerArgs['color'] = $color;
        }
        $headerArgs['accountLink'] = $accountLink;
        
        // Load the header view
        $this->load->view('header', $headerArgs);
        
        // Only show the nav bar if its requested
        if($mainNav) {
            $this->load->view('main_nav');
        }
        
        // Load the top box container for injection
        $args['topbox'] = '';
        if($topbox !== false) {
            $topbox['topbox'] = $topbox;
            $args['topbox'] = $this->load->view('topbox', $topbox, true);
        }
        
        // Load the breadcrumb for injection
        $args['breadcrumb'] = '';
        if($breadcrumb !== false) {
            $breadcrumb['hasSearch'] = $searchbox;
            $breadcrumb['breadcrumb'] = $breadcrumb;
            $breadcrumb['color'] = $color;
            $args['breadcrumb'] = $this->load->view('breadcrumb', $breadcrumb, true);
        }
        
        // Load the search box for injection
        $args['searchbox'] = '';
        if($searchbox !== false) {
            $args['searchbox'] = $this->load->view('searchbox', null, true);
        }
        
        // Load the content pane for injection
        $args['content'] = '';
        foreach($views as $view) {
            $args['content'] .= $this->load->view($view['name'], $view['args'], true);
        }
        
        // Inject the content container
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
        
        // Set footer args
        $footerArgs['accountLink'] = $accountLink;
        // And voila!
        $this->load->view('footer',$footerArgs);
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
