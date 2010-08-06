<?php
require_once APPPATH.'/controllers/AbstractController.php';

class Cart extends AbstractController {
    
    public function Cart() {
        parent::AbstractController();
    }
    
    public function _remap($method) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        try {
            switch($requestMethod) {
                case 'POST' :
                    $this->load->helper('post');
                    $this->handlePost($method);
                break;
                case 'GET' :
                    $this->handleGet($method);
                break;
                default :
                    // we really shouldn't end up here...
                    // FIXME but we still should handle it better
                    // Send back a INVALID METHOD HTTP code
                    throw new RuntimeException("Invalid HTTP_REQUEST_METHOD");
                break;
            }
        } catch(Exception $e) {
            throw $e;
            // TODO Setup AuthenticationException trap
        }
    }

    public function showBilling() {
        $views = array(
            array('name' => 'cart/billing', 'args' => array('title' => 'Billing Information'))
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Billing Information');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showCart() {
        $views = array(
            array('name' => 'cart/cart', 'args' => array('title' => 'Your Cart'))
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Your Cart');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showEnrollment() {
        $views = array(
            array('name' => 'cart/enrollment', 'args' => array('title' => 'Enroll Profiles in Programs'))
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Enroll Profiles in Programs');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showEnrollmentReview() {
        $views = array(
            array('name' => 'cart/enrollment_review', 'args' => array('title' => 'Enrollment Review'))
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Enrollment Review');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();

    }
    
    public function showThankYou() {
        $views = array(
            array('name' => 'cart/thankyou', 'args' => array('title' => 'Thank you!'))
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Thank you!');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    private function handleGet($method) {
        switch($method) {
            case 'mycart' :
                $this->showCart();
            break;
            case 'enrollment' :
                $this->showEnrollment();
            break;
            case 'enrollment-review' :
                $this->showEnrollmentReview();
            break;
            case 'billing' :
                $this->showBilling();
            break;
            case 'thankyou' :
                $this->showThankYou();
            break;
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
}