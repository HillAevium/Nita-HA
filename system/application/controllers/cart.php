<?php

require_once APPPATH.'/controllers/abstractcontroller.php';
require_once APPPATH.'/models/core/json_field.php';
require_once APPPATH.'/models/core/string_field.php';
require_once APPPATH.'/libraries/core/auth/session/user_credentials.php';

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
    
    // Called from shop controller list/detail pages
    public function addItem() {
        $definition = Model_Definition::runtimeInstance();
        
        $definition->addField(new String_Field('id'));
        $definition->addField(new String_Field('price'));
        $definition->addField(new String_Field('name'));
        
        $cart = $definition->processPost('array');
        $cart['qty'] = 1;
        
        $this->cart->insert($cart);
    }
    
    // Called from cart display pages
    public function removeItem() {
        $rowid = $this->getRowId();
        
        $this->cart->update(array('rowid' => $rowid, 'qty' => 0));
    }
    
    // Called from cart display pages when a program
    // is full and the person wants to be on the wait list
    // instead of just removing the item
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
    
    // Process the cart display pages before moving on
    // to review
    public function doCart() {
        // TODO
        $jsonField = new Json_Field('selection_model');
    }
    
    public function doBilling() {
        // TODO
    }
    
    // HTML View Entry Points
    
    // Main entry point for cart display. This method
    // splits depending on authentication credentials
    public function showCart() {
        // FIXME Remove for production
        $this->stageDebugCart();
        
        // Use 3 states to determine the page to show
        // normal - Normal and Anonymous users
        // super - Super users
        // none - Child users
        
        $display = 'normal';
        $auth = false;
        
        // We change the display to super for a super user
        // and none for a child user
        if($this->mod_auth->isAuthenticated()) {
            $auth = true;
            switch($this->mod_auth->credentials()->user['type']) {
                case USER_SUPER :
                    $display = 'super';
                break;
                case USER_CHILD :
                    $display = 'none';
                break;
            }
        }
        
        switch($display) {
            case 'normal' :
                $this->showNormalCart($auth);
            break;
            case 'super' :
                $this->showSuperCart();
            break;
            case 'none' :
                // FIXME - What to do for child users ?
                die("Cart support for child user not implemented.");
        }
    }
    
    // Page 2
    // Reachable by unauthenticated users
    //   Show Login/Register buttons
    // Reachable by authenticated normal users
    //   Show Checkout button
    private function showNormalCart($auth) {
        // We display different buttons depending on the user type
        // Anonymous users see Login/Register
        // Normal users see Checkout
        if($auth) {
            $args['buttons'] = 'checkout';
        } else {
            $this->session->set_userdata('referrer', '/cart/display');
            $args['buttons'] = 'login';
        }
        
        $args['title'] = 'Your Cart';
        
        $views = array(
            array('name' => 'cart/normal', 'args' => $args)
        );
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('pageTitle', 'Your Cart');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    // Page 8
    // Reachable by authenticated super users
    private function showSuperCart() {
        $this->load->model('accountProvider');
        $args['users'] = $this->accountProvider->getUsersByAccount();
        
        $views = array(
            array('name' => 'cart/super', 'args' => array('title' => 'Enroll Profiles in Programs'))
        );
        
        $this->setViewOption('pageTitle', 'Enroll Profiles in Programs');
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    // Page 9
    // Shown after cart information has been set
    public function showCartReview() {
        $views = array(
            array('name' => 'cart/review', 'args' => array('title' => 'Enrollment Review'))
        );
        
        $this->setViewOption('pageTitle', 'Enrollment Review');
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    // Page 10
    // Shown after cart review
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
    
    // Page 11
    // Final output for user to print
    public function showFinalOrder() {
        $views = array(
            array('name' => 'cart/final', 'args' => array('title' => 'Thank you!'))
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
            case 'process' :
                $this->doCart();
            break;
            case 'billing' :
                $this->doBilling();
            break;
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
    
    private function stageDebugCart() {
        if(count($this->cart->contents()) > 0) {
            return;
        }
        $cart = array(
            array(
                'id' => 'a',
                'qty' => '1',
                'price' => '2000',
                'name' => 'Program A'
            ),
            array(
                'id' => 'b',
                'qty' => '1',
                'price' => '3000',
                'name' => 'Program B'
            ),
            array(
                'id' => 'c',
                'qty' => '1',
                'price' => '1500',
                'name' => 'Program C'
            )
        );
        
        $this->cart->insert($cart);
    }
}