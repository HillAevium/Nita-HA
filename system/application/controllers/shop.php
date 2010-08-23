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
            'home'        => array('name' => 'Home', 'id' => '/Home'),
            'program'     => array('name' => 'Programs',     'id' => '/Shop')
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
        $id = $this->getArgument('id');
        
        // No id was supplied?
        if($id === false) {
            $this->output->set_header('Location: /Shop');
        }
        
        // Load the selected model
        $this->load->model('program');
        
        // Get the model for the details
        $model = $this->program->getSingle($id);
        
        // We need to check if we got a program as the ID could
        // have been entered by hand and been invalid
        if (is_null($model)) {
            // FIXME URL
            show_404('/Program/'.$id);
        }
        
        // Setup the topbox content
        $topboxContent = $this->load->view('programs/content_top_detail', array('model'=>$model), true);
        $topbox = array(
            'image'   => 'topbox_test.jpg',
            'title'   => $model->title,
            'content' => $topboxContent
        );
        
        // Setup the breadcrumb
        $breadcrumb = array(
            $this->breadcrumbs['home'],
            $this->breadcrumbs['program'],
            array('name' => $model->title)
        );
        
        $this->setViewOption('pageTitle', $model->title);
        
        $model = array('model' => $model);
        
        // TODO
        // Some of these will likely get created
        // from templates. However we end up doing
        // that.
        // Load our content panels
        $content[] = $this->load->view('programs/overview',  $model, true);
        $content[] = $this->load->view('programs/schedule',  $model, true);
        $content[] = $this->load->view('programs/faculty',   $model, true);
        $content[] = $this->load->view('programs/credits',   $model, true);
        $content[] = $this->load->view('programs/logistics', $model, true);
        $content[] = $this->load->view('programs/materials', $model, true);
        // Forum is not in current spec, so comment out for now
        //$content[] = $this->load->view('programs/forum',     $model, true);
        
        // Setup the tab panel
        $tabs = array(
            array('name' => 'Overview',    'id' => 'overview',  'href' => 'Overview', 'content' => $content[0]),
            array('name' => 'Schedule',    'id' => 'schedule',  'href' => 'Schedule', 'content' => $content[1]),
            array('name' => 'Faculty',     'id' => 'faculty',   'href' => 'Faculty', 'content' => $content[2]),
            array('name' => 'CLE Credits', 'id' => 'credits',   'href' => 'CLE', 'content' => $content[3]),
            array('name' => 'Travel',   'id' => 'logistics', 'href' => 'Logistics', 'content' => $content[4]),
            array('name' => 'Books',   'id' => 'materials', 'href' => 'Materials', 'content' => $content[5])
        );
        
        // And the tabs classes
        $class['tabs']   = 'orange_tabs';
        $class['border'] = 'orange_border';
        $class['tab_panel_class'] = 'tab_panel';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        
        // Setup the views
        $views = array(
            array('name' => 'programs/sidebar', 'args' => null),
            array('name' => 'tab_panel', 'args' => $args)
        );
        
        // Load the view options
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('color', 'orange');
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
        // Load the model
        $this->load->model('program');
        
        // Grab some models
        $models = $this->program->getAll();
        
        // Sort if neccessary
        usort()
        
        // Setup the data for the view
        $views = array(
            array('name' => 'programs/list', 'args' => array('models' => $models))
        );
        
        // Setup the breadcrumb
        $breadcrumb = array(
            $this->breadcrumbs['home'],
            $this->breadcrumbs['program']
        );
        
        // Setup the topbox content
        $pages = $this->config->item('page','soap');
        $guid = $pages['programs']['guid'];
        $pageContent = $this->soap->getPage($guid);
        $topbox['image'] = $pageContent->nita_page_image;
        $topbox['title'] = $pageContent->nita_page_name;
        $topbox['content'] = preg_replace('#<h1>[^<]*<\/h1>#',"",$pageContent->nita_page_text);
        
        // Load the view options
        $this->setViewOption('searchbox', true);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('views', $views);
        $this->setViewOption('pageTitle', $this->titles['program']);
        $this->setViewOption('color', 'orange');
        
        // ... and go
        $this->loadViews();
    }
    
    private function sort() {
        
    }
}

/* End of file Shop.php */