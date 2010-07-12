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
    
    protected function loadViews(array $views) {
        $this->load->view('http_header', array('title' => $this->title));
        $this->load->view('header');
        foreach($views as $view) {
            $this->load->view($view['name'], $view['args']);
        }
        $this->load->view('footer');
    }
}