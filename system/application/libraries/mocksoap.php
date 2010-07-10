<?php

require_once 'Soap.php';
require_once APPPATH.'models/Program.php';
require_once APPPATH.'models/Publication.php';

class MockSoap extends Soap {
    
    public static $numberOfItems = 5;
    
    public function getAllPrograms() {
        $programs = array();
        for($i = 0; $i < self::$numberOfItems; $i++) {
            $programs[] = $this->mockProgram();
        }
        return $programs;
    }
    
    public function getAllPublications() {
        $publications = array();
        for($i = 0; $i < self::$numberOfItems; $i++) {
            $publications[] = $this->mockPublication();
        }
        return $publications;
    }
    
    public function getProgram($id) {
        return $this->mockProgram($id);
    }
    
    public function getPrograms(FilterSet $filters) {
        throw new RuntimeException("Not Implemented");
    }
    
    public function getPublication($id) {
        return $this->mockPublication($id);
    }
    
    public function getPublications(FilterSet $filters) {
        throw new RuntimeException("Not Implemented");
    }
    
    private function mockProgram($id = '') {
        return ProgramProducer::getProgram($id);
    }
    
    private function mockPublication($id = '') {
        return PublicationProducer::getPublictaion($id);
    }
}

class ProgramProducer {
    private static $choice = 0;
    private static $idMap = array(
        'BLDA610' => 0,
        'NATL710' => 1,
        'NCTR910' => 2,
        'BODP910' => 3,
        'NYTT610' => 4
    );
    
    public static function getProgram($id = '') {
        $choice = 0;
        if($id !== '') {
            $choice = self::$idMap[$id];
        } else {
            $choice = self::$choice++;
        }
        
        $program = new Program();
        
        switch($choice % 5) {
            case 0 :
                $program->city = 'Louisville, CO';
                $program->cleCredits = 36;
                $program->date = '6/27/2010';
                $program->description = '';
                $program->director[] = array('name' => 'Baker, John', 'bio' => '');
                $program->director[] = array('name' => 'Sher, Beth', 'bio' => '');
                $program->discounts = '';
                $program->id = 'BLDA610';
                $program->location = 'Nita Education Center';
                $program->name = 'Robert F. Hanley Advanced Advocacy Skills';
                $program->price = '2495';
                $program->programDates = '6/27/2010:7/1/2010';
                $program->type = 'Trial Advocacy';
            break;
            case 1 :
                $program->city = 'Louisville, CO';
                $program->cleCredits = 87;
                $program->date = '7/10/2010';
                $program->description = '';
                $program->discounts = '';
                $program->director[] = array('name' => 'Johnson, Michael', 'bio' => '');
                $program->id = 'NATL710';
                $program->location = 'Nita Education Center';
                $program->name = 'Building Trial Skills: National Session';
                $program->price = '3495';
                $program->programDates = '7/10/2010:7/24/2010';
                $program->type = 'Trial Advocacy';
            break;
            case 2 :
                $program->city = 'St. Paul, MN';
                $program->cleCredits = 0;
                $program->date = '9/11/2010';
                $program->description = '';
                $program->discounts = '';
                $program->director[] = array('name' => 'Sonsteng, John O.', 'bio' => '');
                $program->id = 'NCTR910';
                $program->location = 'William Mitchell College of Law';
                $program->name = 'The Complete Advocate';
                $program->price = '2495';
                $program->programDates = '9/11/2010:9/17/2010';
                $program->type = 'Trial Advocacy';
            break;
            case 3 :
                $program->city = 'Boston, MA';
                $program->cleCredits = 17;
                $program->date = '9/30/2010';
                $program->description = '';
                $program->discounts = '';
                $program->director[] = array('name' => 'Hunt, William J.', 'bio' => '');
                $program->id = 'BODP910';
                $program->location = 'Batterymarch Conference Center';
                $program->name = 'Deposition Skills: New England';
                $program->price = '1495';
                $program->programDates = '9/30/2010:10/2/2010';
                $program->type = 'Deposition and Pretrial Skills';
            break;
            case 4 :
                $program->city = 'New York, NY';
                $program->cleCredits = 0;
                $program->date = '6/10/2010';
                $program->description = '';
                $program->discounts = '';
                $program->id = 'NYTT610';
                $program->location = 'New York Law School';
                $program->name = 'Advocacy Teacher Training: New York';
                $program->price = '1495';
                $program->programDates = '6/10/2010:6/12/2010';
                $program->type = 'Teacher Training';
            break;
        }
        return $program;
    }
}

class PublicationProducer {
    
    private static $choice = 0;
    private static $idMap = array (
            '9781556817793' => 0,
            '9781422479094' => 1,
            '9781556817496' => 2,
            '9781601560384' => 3,
            '9781556819506' => 4
        );
    
    public static function getPublictaion($id = '') {
        $choice = 0;
        if($id !== '') {
            $choice = self::$idMap[$id];
        } else {
            $choice = self::$choice++;
        }
        
        $publication = new Publication();
        
        switch($choice % 5) {
            case 0 :
                $publication->authors[] = "David Ball";
                $publication->description = "This practical step-by-step guide will transform you into a seasoned performer, with guidance for voir dire, openings and closings, testimony, and focus groups. Did law school teach you how to act in a courtroom? This book will.";
                $publication->edition = "1st Edition";
                $publication->id = '9781556817793';
                $publication->pages = 478;
                $publication->image = "?";
                $publication->name = "Theater Tips & Strategies";
                $publication->price = 65;
                $publication->relatedProducts = null;
                $publication->tableOfContents = null;
                $publication->type = "Book";
                $publication->year = 2004;
            break;
            case 1 :
                $publication->authors[] = "Unknown";
                $publication->description = "This DVD set uses recorded trial clips, live demonstrations, audience participation and Frank Rothschild's own remarkable lecture style to create a most enjoyable and memorable learning experience.";
                $publication->edition = "2nd Edition";
                $publication->id = '9781422479094';
                $publication->pages = 5;
                $publication->image = "?";
                $publication->name = "31 Ways to Winning Advocacy";
                $publication->price = 295;
                $publication->relatedProducts = null;
                $publication->tableOfContents = null;
                $publication->type = "DVD";
                $publication->year = 2007;
            break;
            case 2 :
                $publication->authors[] = "Andrea Doneff";
                $publication->authors[] = "Abraham Ordover";
                $publication->description = "Alternatives to Litigation explores key concepts and terms in ADR practice, and addresses practical how-to issues that all attorneys need to recognize and master regardless of their field of expertise.";
                $publication->edition = "2nd Edition";
                $publication->id = '9781556817496';
                $publication->pages = 119;
                $publication->image = "?";
                $publication->name = "Alternatives to Litigation";
                $publication->price = 60;
                $publication->relatedProducts = null;
                $publication->tableOfContents = null;
                $publication->type = "Book";
                $publication->year = 2005;
            break;
            case 3 :
                $publication->authors[] = "Unknown";
                $publication->description = "Now in its Fourth Fdition, A Practical Guide to Texas Evidence: Objections, Responses, Rules, and Practice Commentary, provides information on the appropriate way to offer and oppose evidence during pretrial and trial.";
                $publication->edition = "4th Edition";
                $publication->id = '9781601560384';
                $publication->pages = 317;
                $publication->image = "?";
                $publication->name = "A Practical Guide to Texas Evidence";
                $publication->price = 45;
                $publication->relatedProducts = null;
                $publication->tableOfContents = null;
                $publication->type = "Book";
                $publication->year = 2008;
            break;
            case 4 :
                $publication->authors[] = "Thomas F. Guernsey";
                $publication->authors[] = "Paul J. Zwier";
                $publication->description = "Providing a systematic, strategic and integrated approach to negotiation, authors Thomas F. Guernsey and Paul J. Zwier explain adversarial and problem-solving strategies, how to help clients make better strategic use of negotiation, counseling techniques and organizational tools for successful negotiations.";
                $publication->edition = "1st Edition";
                $publication->id = '9781556819506';
                $publication->pages = 518;
                $publication->image = "?";
                $publication->name = "Advanced Negotiation and Mediation Theory and Practice";
                $publication->price = 65;
                $publication->relatedProducts = null;
                $publication->tableOfContents = null;
                $publication->type = "Book";
                $publication->year = 2002;
            break;
        }
        return $publication;
    }
}
/* End of file mockswap.php */