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
    
    public function program() {
        // Load the model and get some data
        $this->load->model('program');
        
        // program id
        $id = $this->uri->segment(3);
        
        $program = $this->program->getProgram($id);
        
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'programs/detail', 'args' => array(
              'program'  => $program)
            )
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
            array('name' => 'programs/list', 'args' => array('programs' => $programs))
        );
        
        $this->loadViews($views);
    }

    public function publication() {
        // Load the model and get some data
        $this->load->model('publication');
        
        // publication id
        $id = $this->uri->segment(3);
        
        $publication = $this->publication->getPublication($id);
        
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'publications/detail', 'args' => array(
              'publication'  => $publication)
            )
        );
        
        $this->loadViews($views);
        
    }
    
    public function publications() {
        $this->title = 'NITA - Our Publications';
        
        // Load the model and get some data
        $this->load->model('publication');
        
        // Gives an array of Publication objects
        $publications = $this->publication->getAllPublications();
        
        $this->load->library('pagination');

        // Set the pagination configuration
        // NOTE: These pagination routines should probably
        //       be moved elsewhere, into a helper or something.
        $paginationConfig = array();
        $paginationConfig['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/main/' . __FUNCTION__ . '/';
        $paginationConfig['total_rows'] = count($publications);

        $this->pagination->initialize($paginationConfig);

        $pagination = $this->pagination->create_links();
        
        // Slice the publications array to the range
        // called for by the pagination
        $offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $publications = array_slice($publications, $offset, $this->pagination->per_page);
        
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'publications/list', 'args' => array(
              'pagination'    => $pagination,
              'publications'  => $publications)
            )
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