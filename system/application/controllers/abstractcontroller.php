<?php

abstract class AbstractController extends Controller {
    
    /* TODO
     * > Should we move the generic page titles to a config file?
     */
    
    // the page title
    protected $title;
    
    protected function AbstractController() {
        parent::Controller();
    }
    
    protected function createPaginationLinks($function, $totalRows) {
        $this->load->library('pagination');
        
        // Move this to config/pagination.php ?
        $config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/shop/' . $function;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = 8;
        
        $this->pagination->initialize($config);
        
        return $this->pagination->create_links();
    }
    
    protected function loadViews(array $views) {
        $this->load->view('http_header', array('title' => $this->title));
        $this->load->view('header');
        foreach($views as $view) {
            $this->load->view($view['name'], $view['args']);
        }
        $this->load->view('footer');
    }
}