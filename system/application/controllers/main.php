<?php

require_once APPPATH.'/controllers/AbstractController.php';

class Main extends AbstractController {
    
    public function __construct() {
        parent::AbstractController();
    }
    
    /**
     * The site home page.
     *
     * This entry point will be used when there is not
     * a complete URI path to map to any other entry point.
     */
    public function index() {
        $this->title = 'NITA - National Institue for Trial Advocacy';
        
        // Setup the views
        $views = array(
            array('name' => 'home/default', 'args' => null)
        );
        
        // ... and go
        
        // FIXME This broke the main page which does not want
        // the other parts of the content pane
        //$this->loadViews($views);
        $this->load->view('http_header', array('title' => $this->title));
        $this->load->view('header');
        $this->load->view('home/default');
        $this->load->view('footer');
    }
}

/* End of file Main.php */