<?php

require_once APPPATH.'/controllers/AbstractController.php';

class Main extends AbstractController {
    
    private $breadcrumbs;
    
    public function __construct() {
        parent::AbstractController();
        $this->breadcrumbs['home'] = array('name' => 'Home', 'id' => '/Home');
        $this->breadcrumbs['careers'] = array('name' => '', 'id' => '/Careers/');
        $this->breadcrumbs['enewsletter'] = array('name' => 'E-Newsletter', 'id' => '/ENewsLetter');
        $this->breadcrumbs['news_room'] = array('name' => 'News Room', 'id' => '/NewsRoom/');
    }
    
    /**
     * The site home page.
     *
     * This entry point will be used when there is not
     * a complete URI path to map to any other entry point.
     */
    public function index() {
        $this->title = 'NITA - National Institue for Trial Advocacy';
        
        $this->load->view('http_header', array('title' => $this->title));
        
        $accountLink = '/MyAccount';
        
        $headerArgs['accountLink'] = $accountLink;
        
        // Load the header view
        $this->load->view('header', $headerArgs);
        
        $this->load->view('home/default');
        
        // Set footer args
        $footerArgs['accountLink'] = $accountLink;
        $this->load->view('footer', $footerArgs);
    }
    
    public function careers() {
        // Load our content panels
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container', null, true);
        
        // Setup the tab panel
        $tabs = array(
            array('name' => 'Careers',    'id' => 'careers',  'content' => $content[0]),
            array('name' => 'Something Else?',    'id' => 'careers2', 'content' => $content[1]),
            array('name' => 'Something Else?',   'id' => 'careers3', 'content' => $content[2])
        );
        
        // And the tabs classes
        $class['tabs']   = 'blue_tabs';
        $class['border'] = 'blue_border';
        $class['tab_panel_class'] = 'tab_panel_wide';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        $args['tabPageTitle'] = 'Careers';
        
        // Setup the topbox content
        // FIXME
        // hack to make css work properly, it needs the topbox to be there
        $topbox = array(
            'image'   => '',
            'title'   => '',
            'content' => ''
        );
        
        // Setup the views
        $views = array(
            array('name' => 'tab_panel', 'args' => $args)
        );
        
        // Breadcrumbs
        $breadcrumb = array();
        $breadcrumb[] = $this->breadcrumbs['home'];
        $breadcrumb[] = $this->breadcrumbs['careers'];
        
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('pageTitle', 'Careers');
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function enewsletter() {
        // Load our content panels
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container', null, true);
        
        // Setup the tab panel
        $tabs = array(
            array('name' => 'E-Newsletter',    'id' => 'enewsletter',  'content' => $content[0]),
            array('name' => 'Something Else?',    'id' => 'enewsletter2', 'content' => $content[1]),
            array('name' => 'Something Else?',   'id' => 'enewsletter3', 'content' => $content[2])
        );
        
        // And the tabs classes
        $class['tabs']   = 'blue_tabs';
        $class['border'] = 'blue_border';
        $class['tab_panel_class'] = 'tab_panel_wide';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        $args['tabPageTitle'] = 'E-Newsletter';
        
        // Setup the topbox content
        // FIXME
        // hack to make css work properly, it needs the topbox to be there
        $topbox = array(
            'image'   => '',
            'title'   => '',
            'content' => ''
        );
        
        // Setup the views
        $views = array(
            array('name' => 'tab_panel', 'args' => $args)
        );
        
        // Breadcrumbs
        $breadcrumb = array();
        $breadcrumb[] = $this->breadcrumbs['home'];
        $breadcrumb[] = $this->breadcrumbs['enewsletter'];
        
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('pageTitle', 'E-Newsletter');
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function newsroom() {
        // Load our content panels
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container',  null, true);
        $content[] = $this->load->view('webpage/tab_content_container', null, true);
        
        // Setup the tab panel
        $tabs = array(
            array('name' => 'Press Releases',    'id' => 'press_releases',  'content' => $content[0]),
            array('name' => 'Media Kit',    'id' => 'media_kit', 'content' => $content[1]),
            array('name' => 'Archives',   'id' => 'archives','content' => $content[2])
        );
        
        // And the tabs classes
        $class['tabs']   = 'blue_tabs';
        $class['border'] = 'blue_border';
        $class['tab_panel_class'] = 'tab_panel_wide';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        $args['tabPageTitle'] = 'News Room';
        
        // Setup the topbox content
        // FIXME
        // hack to make css work properly, it needs the topbox to be there
        $topbox = array(
            'image'   => '',
            'title'   => '',
            'content' => ''
        );
        
        // Setup the views
        $views = array(
            array('name' => 'tab_panel', 'args' => $args)
        );
        
        // Breadcrumbs
        $breadcrumb = array();
        $breadcrumb[] = $this->breadcrumbs['home'];
        $breadcrumb[] = $this->breadcrumbs['news_room'];
        
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('topbox', $topbox);
        $this->setViewOption('breadcrumb', $breadcrumb);
        $this->setViewOption('pageTitle', 'News Room');
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function webpage() {
        $alias = $this->getArgument('alias');
        $guid  = $this->_guid($alias);
        $color = $this->_color($alias);
        
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
            $topbox['content'] = preg_replace('#(<h1>[^<]*</h1>)|(style="[^"]*")#',"",$pageContent->nita_page_text);
            $this->breadcrumbs[$alias] = array('name' => $pageContent->nita_page_name, 'id' => '/main/webpage/alias/' . $alias);
            $breadcrumb[] = $this->breadcrumbs[$alias];
        }
        $this->setViewOption('topbox', $topbox);
        // setup tab panel layout if page has child pages
        if($childContent !== null) {
            // Load our content panels
            foreach($childContent as $model) {
                // Are there granchild content items?
                if($grandchildContent = $this->soap->getPagesByParentId($model->nita_webpageId)) {
                    $model->childPages = $grandchildContent;
                }
                $content[] = $this->load->view('webpage/tab_content',  array('model' => $model), true);
            }
            
            // Setup the tab panel
            $tabs = array();
            for($i=0;$i<count($childContent);$i++) {
                $href = "#". str_replace(" ", "", $childContent[$i]->nita_page_name);
                $tabs[] = array('name' => $childContent[$i]->nita_page_name, 'id' => $childContent[$i]->nita_webpageId, 'href' => $href, 'content' => $content[$i]);
            }
                    
            // And the tabs classes
            $class['color'] = $color;
            $class['tab_panel_class'] = 'tab_panel_wide';
            
            // Populate args for the tab panel view
            $tab_panel['tabs']  = $tabs;
            $tab_panel['class'] = $class;
            
            // Setup the views
            $views[] = array('name' => 'tab_panel', 'args' => $tab_panel);
        } else {
            $views[] = array('name' => 'webpage/webpage', 'args' => array('model' => $pageContent));
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