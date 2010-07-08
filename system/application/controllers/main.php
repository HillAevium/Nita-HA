<?php

class Main extends Controller {
    
    public function __construct() {
        parent::Controller();
    }
    
    public function index() {
        $this->load->view('http_header', array('title' => 'NITA - National Institute for Trial Advocacy'));
        $this->load->view('header');
        $this->load->view('home/default');
        $this->load->view('footer');
    }
    
    public function programs() {
        $this->load->view('http_header', array('title' => 'NITA - National Institute for Trial Advocacy'));
        $this->load->view('header');
        $this->load->view('main_nav');
        $this->load->view('programs/list');
        $this->load->view('footer');
    }
    
    public function publications() {
        $this->load->view('http_header', array('title' => 'NITA - National Institute for Trial Advocacy'));
        $this->load->view('header');
        $this->load->view('main_nav');
        $this->load->view('publications/list');
        $this->load->view('footer');
    }
}