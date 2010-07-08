<?php

require_once 'soap.php';
require_once APPPATH.'models/program.php';
require_once APPPATH.'models/publication.php';

class MockSoap extends Soap {
    
    public function getAllPrograms() {
        $programs = array();
        for($i = 0; $i < 5; $i++) {
            $programs[] = $this->mockProgram(0);
        }
        return $programs;
    }
    
    public function getAllPublications() {
        $publications = array();
        for($i = 0; $i < 5; $i++) {
            $programs[] = $this->mockPublication(0);
        }
        return $publications;
    }
    
    public function getProgram($id) {
        return $this->mockProgram($id);
    }
    
    public function getPrograms(FilterSet $filters) {
        throw new RuntimeException("Not Implemented");
    }
    
    public function getPublication($Id) {
        return $this->mockPublication($id);
    }
    
    public function getPublications(FilterSet $filters) {
        throw new RuntimeException("Not Implemented");
    }
    
    private function mockProgram($id) {
        // TODO
        return null;
    }
    
    public static $pubId = 1;
    
    private function mockPublication($id) {
        if($id == 0) {
            $id = self::$pubId++;
        }
        $publication = new Publication();
        $publication->authors[] = "Charles Severance";
        $publication->description = "Building web applications with Google App Engine";
        $publication->edition = "1st Edition";
        $publication->id = $id;
        $publication->image = "?";
        $publication->name = "Using Google App Engine";
        $publication->pages = 272;
        $publication->price = 19.79;
        $publication->relatedProducts = null;
        $publication->tableOfContents = null;
        $publication->type = "Computer and Internet";
        $publication->year = 2009;
        
        return $publication;
    }
}

/* End of file mockswap.php */