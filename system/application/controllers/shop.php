<?php

require_once APPPATH.'/controllers/AbstractController.php';

class Shop extends AbstractController {
    
    private $titles;
    private $breadcrumbs;
    
    public function Shop() {
        parent::AbstractController();
        // FIXME remove for production
        //MockSoap::$numberOfItems = 65;
        
        // All entry points here use the main
        // navigation
        $this->setViewOption('mainNav', true);
        
        // Create the page titles
        $this->titles['publication'] = 'Our Publications';
        $this->titles['program'] = 'Our Programs';
        
        // Create the breadcrumbs
        $this->breadcrumbs = array(
            'home'        => array('name' => 'Home', 'id' => '/main/index/'),
            'program'     => array('name' => 'Programs',     'id' => '/shop/programs/'),
            'publication' => array('name' => 'Publications', 'id' => '/shop/publications')
        );
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
        $model = $this->initDetail('program', 'orange');
        
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
        
        $this->setViewOption('color', 'orange');
        $this->setViewOption('pageTitle', 'My Profile');
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    /**
     * Generate the page for displaying a list of programs.
     *
     * @param int $offset the current offset within the list of items to show
     */
    public function programs() {
        $this->renderList('program', 'orange');
    }
    
    /**
     * Generate the page for displaying a specific publication.
     *
     * @param string $id the id of the publication to display
     */
    public function publication() {
        $model = $this->initDetail('publication', 'red');
        
        $args['publication'] = $model;
        
        // Setup the views
        $views = array(
            array('name' => 'publications/detail', 'args' => $args)
        );
        
        $this->setViewOption('color', 'red');
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    /**
     * Generate the page for displaying a list of publications.
     */
    public function publications() {
        $this->renderList('publication', 'red');
    }

    private function initDetail($type, $color) {
        $id = $this->getArgument('id');
        
        // No id was supplied?
        if($id === false) {
            // FIXME What do we do here.
            show_404('/shop/'.$type.'/');
        }
        
        // Load the selected model
        $this->load->model($type);
        
        // Get the model for the details
        $model = $this->$type->getSingle($id);
        
        // We need to check if we got a program as the ID could
        // have been entered by hand and been invalid
        if (is_null($model)) {
            show_404('/shop/'.$type.'/id/'.$id);
        }
        
        // Setup the topbox content
        $topbox = array(
            'image'   => 'topbox_test.jpg',
            'title'   => $model->title,
            'content' => $this->getRandomText(1)
        );
        
        // Setup the breadcrumb
        $breadcrumb = array(
            $this->breadcrumbs['home'],
            $this->breadcrumbs[$type],
            array('name' => $model->title)
        );
        
        // Load the view options
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('pageTitle', $model->title);
        $this->setViewOption('color', $color);
        
        return array('model' => $model);
    }
    
    private function renderList($type, $color) {
        // Load the model
        $this->load->model($type);
        
        // Grab some models
        $models = $this->$type->getAll();
        
        // TODO - We should do something here if no items are returned
        
        // Setup the data for the view
        $views = array(
            array('name' => $type.'s/list', 'args' => array('models' => $models))
        );
        
        // Setup the breadcrumb
        $breadcrumb = array(
            $this->breadcrumbs['home'],
            $this->breadcrumbs[$type]
        );
        
        // Setup the topbox content
        $topbox = array(
            'image'   => 'topbox_test.jpg',
            'title'   => strtoupper($this->titles[$type]),
            'content' => $this->getRandomText(1)
        );
        
        // Load the view options
        $this->setViewOption('searchbox', true);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('views', $views);
        $this->setViewOption('pageTitle', $this->titles[$type]);
        $this->setViewOption('color', $color);
        
        // ... and go
        $this->loadViews();
    }
}

/* End of file Shop.php */