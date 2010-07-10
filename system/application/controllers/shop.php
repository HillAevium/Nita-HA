<?php

require_once 'AbstractController.php';

class Shop extends AbstractController {
    
    public function Shop() {
        parent::AbstractController();
        
        // FIXME remove for production
        MockSoap::$numberOfItems = 65;
    }
    
    /**
     * Catches accesses to this controller with no function
     * specified and redirects to a default view.
     */
    public function index() {
        // Redirect calls to /shop/ to /shop/programs/
        // TODO
        // There may be a way to make this configurable.
        // if not we should make it so.
        $this->programs();
    }
    
    /**
     * Generates the page for displaying a specific program.
     *
     * @param string $id the id of the program to display
     */
    public function program($id = '') {
        if($id === '') {
            // FIXME What do we do here?
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
            // FIXME What do we do here?
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
}