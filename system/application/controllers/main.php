<?php

class Main extends Controller {

    // the page title
    private $title;
    
    public function __construct() {
        parent::Controller();
    }
    
    public function index() {
        $this->title = 'NITA - National Institue for Trial Advocacy';
        $views = array(
            array('name' => 'home/default', 'args' => null)
        );
        
        $this->loadViews($views);
    }
    
    public function programs() {
        $this->title = 'Nita - Our Programs';
        
        // Load the model and get some data
        $this->load->model('program');
        
        // Gives an array of Program objects
        // MockSoap not setup for this yet...
        //$programs = $this->program->getAllPrograms();
        $programs = array();
        
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'programs/list', 'args' => $programs)
        );
        
        $this->loadViews($views);
    }
    
    public function publications() {
        $this->title = 'NITA - Our Publications';
        
        // Load the model and get some data
        $this->load->model('publication');
        
        // Gives an array of Publication objects
        $publications = $this->publication->getAllPublications();
        
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'publications/list', 'args' => $publications)
        );
        
        $this->loadViews($views);
    }
    
    private function loadViews(array $views) {
        $this->load->view('http_header', array('title' => $this->title));
        $this->load->view('header');
        foreach($views as $view) {
            $this->load->view($view['name'], $view['args']);
        }
        $this->load->view('footer');
    }
}

/* End of file main.php */