<?php

require_once APPPATH.'/controllers/abstractcontroller.php';
require_once APPPATH.'/models/core/json_field.php';
require_once APPPATH.'/models/core/string_field.php';

class Cart extends AbstractController {
    
    public function Cart() {
        parent::AbstractController();
        $this->load->library('cart');
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

    // Ajax Entry Points
    
    public function addItem() {
        $definition = Model_Definition::runtimeInstance();
        
        $definition->addField(new String_Field('id'));
        $definition->addField(new String_Field('price'));
        $definition->addField(new String_Field('name'));
        
        $cart = $definition->processPost('array');
        $cart['qty'] = 1;
        
        $this->cart->insert($cart);
    }
    
    public function removeItem() {
        $rowid = $this->getRowId();
        
        $this->cart->update(array('rowid' => $rowid, 'qty' => 0));
    }
    
    public function addToWaitList() {
        // The user has a program in their cart that is full.
        // We need to add them to the wait list and remove the
        // item from their cart.
        die("Not Implemented");
        
        $rowid = $this->getRowId();
        $programId = $this->getProgramId();
        
        // TODO
        // Add them to the waitlist, howeve we do that...
        
        // Remove the program from their shopping cart
        $this->cart->update(array('rowid' => $rowid, 'qty' => 0));
        
        // TODO Send Email ???
        
        // Tell the client-side the waitlist entry is created
        $this->output->set_status_header(HTTP_CREATED);
    }
    
    public function doManage() {
        // TODO
        $jsonField = new Json_Field('selection_model');
    }
    
    public function doBilling() {
        // TODO
    }
    
    // HTML View Entry Points
    
    public function showCart() {
        if($this->mod_auth->isAuthenticated()) {
            switch($this->mod_auth->credentials()->userType) {
                case USER_NORMAL :
                    $this->setViewOption('checkout', '/cart/review');
                    break;
                case USER_SUPER :
                    $this->setViewOption('checkout', '/cart/manage');
                    break;
                case USER_CHILD :
                    // Should this user-type even be capable of having
                    // a cart. If not we should block this from happening
                    // before we even get this far.
                    throw new RuntimeException("Child users cannot purchase items.");
                default :
                    throw new RuntimeException("Unknown user type");
            }
        } else {
            $this->setViewOption('checkout', '/account/login');
        }
        
        $views = array(
            array('name' => 'cart/cart', 'args' => array('title' => 'Your Cart'))
        );
        
        // TODO add checkout to view
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Your Cart');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    public function showCartManager() {
        // This is only reachable by an authenticated
        // super user. The auth filters should pick
        // this up and deny access to anyone else
        if(!$this->mod_auth->isAuthenticated()) {
            throw new RuntimeException("Unauthenticted access to cart manager.");
        }
        if($this->mod_auth->credentials()->userType !== USER_SUPER) {
            throw new RuntimeException("Only super users may access the cart manager.");
        }
        
        // Get the account id for the super user
        $accountId = $this->mod_auth->credentials()->accountId;
        
        // Request the profiles (name/id) that the super-user
        // has access to.
        $this->load->model('accountProvider');
        $args['users'] = $this->accountProvider->getUsersByAccount();
        
        $views = array(
            array('name' => 'cart/enrollment', 'args' => array('title' => 'Enroll Profiles in Programs'))
        );
        
        $this->setViewOption('pageTitle', 'Enroll Profiles in Programs');
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    public function showCartReview() {
        $views = array(
            array('name' => 'cart/enrollment_review', 'args' => array('title' => 'Enrollment Review'))
        );
        
        $this->setViewOption('pageTitle', 'Enrollment Review');
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
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
    
    public function showFinalOrder() {
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
    
    private function getProgramId() {
        $idField = new String_Field('id');
        
        if(!$idField->validate()) {
            // TODO
            return;
        }
        
        return $idField->process();
    }
    
    private function getRowId() {
        $rowidField = new String_Field('rowid');
        
        if(!$rowidField->validate()) {
            // TODO
            return;
        }
        
        $rowid = $rowidField->process();
        
        return $rowid;
    }
    
    
    private function handleGet($method) {
        switch($method) {
            case 'display' :
                $this->showCart();
            break;
            case 'manage' :
                $this->showCartManager();
            break;
            case 'review' :
                $this->showCartReview();
            break;
            case 'billing' :
                $this->showBilling();
            break;
            case 'final' :
                $this->showFinalOrder();
            break;
            default :
                show_404('/account/' . $method);
            return;
        }
    }
    
    private function handlePost($method) {
        switch($method) {
            case 'add' :
                $this->addItem();
            break;
            case 'remove' :
                $this->removeItem();
            break;
            case 'wait' :
                $this->addToWaitList();
            break;
            case 'manage' :
                $this->doManage();
            break;
            case 'billing' :
                $this->doBilling();
            break;
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
}