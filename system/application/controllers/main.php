<?php

require_once APPPATH.'/controllers/AbstractController.php';

class Main extends AbstractController {
    
    private $breadcrumbs;
    
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
        
        // Check if user is authenticated
        // and set the proper account links
        $isAuth = $this->mod_auth->isAuthenticated();
        if($isAuth === true) {
            $accountLink = '/account/user';
        } else {
            $accountLink = '/account/register';
        }
        $headerArgs['accountLink'] = $accountLink;
        
        // Load the header view
        $this->load->view('header', $headerArgs);
        
        $this->load->view('home/default');
        
        // Set footer args
        $footerArgs['accountLink'] = $accountLink;
        $this->load->view('footer');
    }
    
    public function webpage() {
        $alias = $this->getArgument('alias');
        $guid  = $this->_guid($alias);
        $color = $this->_color($alias);
        
        // FIXME
        // The breadcrubms are hacked in, need to find a more elegant solution
        // Create the breadcrumbs
        $this->breadcrumbs = array();
        $this->breadcrumbs['home'] = array('name' => 'Home', 'id' => '/main/index/');
        
        // Setup the breadcrumb
        $breadcrumb = array();
        $breadcrumb[] = $this->breadcrumbs['home'];
        
        // FIXME
        // Need to utilize a native Webpage model in this controller
        // instead of straight WebPageModels from soap
        
        $pageContent = $this->soap->getPage($guid);
        $childContent = $this->soap->getPagesByParentId($guid);
        //$pageContent = $this->soap->getPagesByNavName($alias);
        
        $views = array();
        // set the args for the topbox view
        $topbox['image'] = "";
        $topbox['title'] = "";
        $topbox['content'] = "";
        if ($pageContent !== false) { 
            $topbox['image'] = $pageContent->nita_page_image;
            $topbox['title'] = $pageContent->nita_page_name;
            $topbox['content'] = $pageContent->nita_page_text;
            
            $this->breadcrumbs[$alias] = array('name' => $pageContent->nita_page_name, 'id' => '/main/webpage/alias/' . $alias);
            $breadcrumb[] = $this->breadcrumbs[$alias];
        }
        $this->setViewOption('topbox', $topbox);
        // setup tab panel layout if page has child pages       
        if($childContent !== null) {
            // Load our content panels
            foreach($childContent as $model) {
                $content[] = $this->load->view('webpage/tab_content',  array('model' => $model), true);
            }
            
            // Setup the tab panel
            $tabs = array();
            for($i=0;$i<count($childContent);$i++) {
                $tabs[] = array('name' => $childContent[$i]->nita_page_name, 'id' => $childContent[$i]->nita_nav_name, 'content' => $content[$i]);
            }
                    
            // And the tabs classes
            $class['color'] = $color;
            $class['tab_panel_class'] = 'tab_panel_wide';
            
            // Populate args for the tab panel view
            $tab_panel['tabs']  = $tabs;
            $tab_panel['class'] = $class;
            
            // Setup the views
            $views[] = array('name' => 'tab_panel', 'args' => $tab_panel);
        }
        
        // default to short box if topbox has no title
        //if($topbox['title'] != '') {
            $this->setViewOption('color', $this->_color($alias));
        //} else {
            //$this->setViewOption('color', 'blue_short');
        //}
        $this->setViewOption('mainNav', true);
        $this->setViewOption('pageTitle', 'My Profile');
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }

    private function _guid($alias) {
        $pages = $this->config->item('page','soap');
        return $pages[$alias]['guid'];
    }
    
    private function _color($alias) {
        $pages = $this->config->item('page','soap');
        return $pages[$alias]['color'];
    }
}

/* End of file Main.php */