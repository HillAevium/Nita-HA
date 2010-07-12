<?php

require_once APPPATH.'/controllers/AbstractController.php';

class Shop extends AbstractController {
    
    public function Shop() {
        parent::AbstractController();
        // FIXME remove for production
        MockSoap::$numberOfItems = 65;
        
        // All entry points here use the main
        // navigation
        $this->showMainNav = true;
    }
    
    /**
     * Catches accesses to this controller with no function
     * specified and redirects to a default view.
     */
    public function index() {
        // Redirect calls to /Shop/ to /Shop/programs/
        // TODO
        // There may be a way to make this configurable.
        // if not we should make it so.
        $this->arguments['offset'] = 0;
        $this->programs();
    }
    
    /**
     * Generates the page for displaying a specific program.
     *
     * @param string $id the id of the program to display
     */
    public function program() {
        $id = $this->getArgument('id');
        
        if($id === false) {
            // FIXME What do we do here?
            show_404('/shop/program/');
        }
        
        // Load the model and get some data
        $this->load->model('program');
        
        // Returns a single Program object
        $model['model'] = $this->program->getProgram($id);
        
        // We need to check if we got a program as the ID could
        // have been entered by hand and been invalid
        if (is_null($model)) {
            show_404('/shop/program/'.$id);
        }
        
        // Set the title to the Program being viewed
        $this->title = $args['program']->name;
        
        // TODO
        // Some of these will likely get created
        // from templates. However we end up doing
        // that.
        // Load our content panels
        $content[] = $this->load->view('programs/overview',  $model, true);
        $content[] = $this->load->view('programs/schedule',  $model, true);
        $content[] = $this->load->view('programs/logistics', $model, true);
        $content[] = $this->load->view('programs/materials', $model, true);
        $content[] = $this->load->view('programs/faculty',   $model, true);
        $content[] = $this->load->view('programs/credits',   $model, true);
        $content[] = $this->load->view('programs/forum',     $model, true);
        
        // Setup the tab panel
        $tabs = array(
            array('name' => 'Overview',    'id' => 'overview',  'content' => $content[0]),
            array('name' => 'Schedule',    'id' => 'schedule',  'content' => $content[1]),
            array('name' => 'Logistics',   'id' => 'logistics', 'content' => $content[2]),
            array('name' => 'Materials',   'id' => 'materials', 'content' => $content[3]),
            array('name' => 'Faculty',     'id' => 'faculty',   'content' => $content[4]),
            array('name' => 'CLE Credits', 'id' => 'credits',   'content' => $content[5]),
            array('name' => 'Forum',       'id' => 'forum',     'content' => $content[6])
        );
        
        // And the tabs classes
        $class['tabs']   = 'orange_tabs';
        $class['border'] = 'orange_border';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        
        // Setup the views
        $views = array(
            array('name' => 'tab_panel', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views, 'orange');
    }
    
    /**
     * Generate the page for displaying a list of programs.
     *
     * @param int $offset the current offset within the list of items to show
     */
    public function programs() {
        $offset = $this->getArgument('offset');
        
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
            array('name' => 'programs/list', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views, 'orange');
    }
    
    /**
     * Generate the page for displaying a specific publication.
     *
     * @param string $id the id of the publication to display
     */
    public function publication() {
        $id = $this->getArgument('id');
        
        if($id === false) {
            // FIXME What do we do here?
            show_404('/shop/publication/');
        }
        
        // Load the model and get some data
        $this->load->model('publication');
        
        // Returns a single Publication object
        $args['publication'] = $this->publication->getPublication($id);
        
        // We need to check if we got a publication as the ID could
        // have been entered by hand and been invalid
        if (is_null($args['publication'])) {
            // Return a 404?
            show_404('/shop/publication/id/'.$id);
        }
        
        // Set the title to the publication being viewed
        $this->title = $args['publication']->title;
        
        // Setup the views
        $views = array(
            array('name' => 'publications/detail', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views, 'red');
    }
    
    /**
     * Generate the page for displaying a list of publications.
     *
     * @param int $offset the current offset within the list of items to show
     */
    public function publications() {
        $offset = intval($this->getArgument('offset'));
        
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
            array('name' => 'publications/list', 'args' => $args)
        );
        
        // ... and go
        $this->loadViews($views, 'red');
    }
}

/* End of file Shop.php */