<?php

require_once 'AbstractController.php';

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
        $this->loadViews($views);
    }
}

/* End of file Main.php */