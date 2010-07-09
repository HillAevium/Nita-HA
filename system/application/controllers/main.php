<?php

class Main extends Controller {
    
    /* TODO
     * > Should we move the generic page titles to a config file?
     */
    
    // the page title
    private $title;
    
    public function __construct() {
        parent::Controller();
        MockSoap::$numberOfItems = 65;
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
    
    /**
     * Generates the page for displaying a specific program.
     *
     * @param string $id the id of the program to display
     */
    public function program($id = '') {
        if($id === '') {
            // What do we do here?
            show_404('/main/programs/');
        }
        
        // Load the model and get some data
        $this->load->model('program');
        
        // Returns a single Program object
        $args['program'] = $this->program->getProgram($id);
        
        // We need to check if we got a program as the ID could
        // have been entered by hand and been invalid
        if (is_null($args['program'])) {
            // Return a 404?
            show_404('/main/program/'.$id);
        }
        
        // Set the title to the Program being viewed
        $this->title = $args['program']->name;
        
        // Setup the views
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'programs/detail', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views);
    }
    
    /**
     * Generate the page for displaying a list of programs.
     *
     * @param int $offset the current offset within the list of items to show
     */
    public function programs($offset = 0) {
        $this->title = 'Nita - Our Programs';
        
        // Load the model and get some data
        $this->load->model('program');
        
        // Gives an array of Program objects
        $args['programs']   = $this->program->getAllPrograms();
        $args['pagination'] = $this->createPaginationLinks(__FUNCTION__, count($args['programs']));
        
        // TODO - We should do something here if no items are returned
        
        // Slice the programs array to the range
        // called for by the pagination
        $args['programs'] = array_slice($args['programs'], $offset, $this->pagination->per_page);
        
        // Setup the views
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'programs/list', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views);
    }
    
    /**
     * Generate the page for displaying a specific publication.
     *
     * @param string $id the id of the publication to display
     */
    public function publication($id = '') {
        if($id === '') {
            // What do we do here?
            show_404('/main/publication/');
        }
        
        // Load the model and get some data
        $this->load->model('publication');
        
        // Returns a single Publication object
        $args['publication'] = $this->publication->getPublication($id);
        
        // We need to check if we got a publication as the ID could
        // have been entered by hand and been invalid
        if (is_null($args['publication'])) {
            // Return a 404?
            show_404('/main/program/'.$id);
        }
        
        // Set the title to the publication being viewed
        $this->title = $args['publication']->title;
        
        // Setup the views
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'publications/detail', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views);
    }
    
    /**
     * Generate the page for displaying a list of publications.
     *
     * @param int $offset the current offset within the list of items to show
     */
    public function publications($offset = 0) {
        $this->title = 'NITA - Our Publications';
        
        // Load the model
        $this->load->model('publication');
        
        // Gives an array of Publication objects
        $args['publications'] = $this->publication->getAllPublications();
        
        // Create the pagination links
        $args['pagination'] = $this->createPaginationLinks(__FUNCTION__, count($args['publications']));
        
        // TODO - We should do something here if no items are returned
        
        // Slice the publications array to the range
        // called for by the pagination
        $args['publications'] = array_slice($args['publications'], $offset, $this->pagination->per_page);
        
        // Setup the views
        $views = array(
            array('name' => 'main_nav', 'args' => null),
            array('name' => 'publications/list', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views);
    }
    
    private function createPaginationLinks($function, $totalRows) {
        $this->load->library('pagination');
        
        // Move this to config/pagination.php ?
        $config = array();
        $config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/main/' . $function;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = 10;
        
        $this->pagination->initialize($config);
        
        return $this->pagination->create_links();
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