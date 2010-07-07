<?php

class Main extends Controller {
    
    public function __construct() {
        parent::Controller();
    }
    
    public function index() {
        $this->load->view('http_header', array('title' => 'NITA - National Institute for Trial Advocacy'));
    }
}