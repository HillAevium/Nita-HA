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
        // TODO Make sure the user does not add an item twice
        $this->load->model('program');
        $program = $this->program->getSingle($_POST['id']);
        
        $cart = array(
            'id' => $_POST['id'],
            'price' => $program->price,
            'name' => $program->title
        );
        $cart['qty'] = 1;
        
        $this->cart->insert($cart);
        
        $this->output->set_status_header(202);
    }
    
    // Called from cart display pages
    public function removeItem() {
        $rowid = $_POST['rowid'];
        
        $this->cart->update(array('rowid' => $rowid, 'qty' => 0));
        
        $this->output->set_status_header(202);
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
        $jsonField = new Json_Field('json');
        
        if(!$jsonField->validate()) {
            $error = $jsonField->error();
            log_message('error', $error);
            $this->output->set_status_header(400);
            return;
        }
        
        $userType = $this->mod_auth->credentials()->user['type'];
        $programs = $jsonField->process();
        
        // Remap the profiles to an assoc array
        $profileMap = array();
        foreach($programs as $program) {
            $profileMap[$program->programId] = $program->profiles;
        }
        
        $this->load->model('accountProvider');
        
        // Add the profiles to the cart options
        // and load details for review
        $details = array();
        $billing = array();
        foreach($this->cart->contents() as $item) {
            if($userType == USER_NORMAL) {
                $billing[] = array(
                    'programTitle' => $item['name'],
                    'numAttendees' => '1 Attendee',
                    'price'        => $item['price'],
                    'subTotal'     => $item['subtotal']
                );
            } else {
                if(!isset($profileMap[$item['id']])) {
                    // TODO What do we do if the user has a program
                    // in the cart but does not add attendees?
                    $msg = new stdClass();
                    $msg->msg = "No Attendees Selected";
                    $details[] = array($msg);
                    continue;
                }
                $profiles = $profileMap[$item['id']];
                
                // Persist the choices in the cart options
                $this->cart->update(
                    array(
                        'rowid' => $item['rowid'],
                        'qty' => count($profiles),
                        'options' => $profiles
                    )
                );
                // Reload the item
                $cart = $this->cart->contents();
                $item = $cart[$item['rowid']];
                
                // Loop through the profiles to send back details
                // for the review widget
                foreach($profiles as $profile) {
                    //$user = $this->accountProvider->getProfile($profile->id);
                    //FIXME
                    $user = new stdClass();
                    $user->name = $profile->name;
                    $user->id   = $profile->id;
                    $user->address = "123 2nd Ave SE";
                    $user->city = "Calgary";
                    $user->state = "AB";
                    $user->zip = "T1A 4B7";
                    $user->country = "Canada";
                    $users[] = $user;
                }
                $details[] = $users;
                unset($users);
                
                // FIXME For some reason the cart info is stale that
                // we have. The cart update above works but it doesn't
                // show up in $item
                $billing[] = array(
                    'programTitle' => $item['name'],
                    'numAttendees' => $item['qty'] . ' Attendees',
                    'price'        => $item['price'],
                    'subTotal'     => $item['subtotal']
                );
            }
        }
        
        // FIXME - Try using $this->output->set_output();
        $this->output->set_status_header(202);
        echo json_encode(array('details' => $details, 'billing' => $billing));
    }
    
    public function doFinish() {
        // TODO - Tie into order processing
        // TODO - Protect this against multiple-submissions
        echo "Order Completed.";
        $this->output->set_status_header(201);
    }
    
    // HTML View Entry Points
    
    // Main entry point for cart display. This method
    // splits depending on authentication credentials
    public function showCart() {
        $this->load->model('accountProvider');
        
        // FIXME Remove for production
        $this->stageDebugCart();
        
        $profiles = array();
        $info = array();
        $display = 'single';
        $button = 'review';
        $titles = array(
            'cart' => 'Your Cart',
            'review' => 'Enrollment Review',
            'billing' => 'Billing Information',
            'finish' => 'Thank You!'
        );
        
        $accountId = $this->mod_auth->credentials()->user['accountId'];
        $userType = $this->mod_auth->credentials()->user['type'];
        
        // Super users see a multi-cart and are able to add
        // attendees. Single users see a simple cart with their
        // items. Anonymous users see the same simple cart, but
        // do not have the rest of the cart interfaces loaded as
        // they must go through login/register before coming
        // back to the cart.
        
        switch($userType) {
            case USER_SUPER :
                // TODO - Pull this from the account
                //$profiles = $this->accountProvider->getProfilesByAccount($accountId);
                $profiles = array (
                    array('id' => 1, 'name' => 'A'),
                    array('id' => 2, 'name' => 'B'),
                    array('id' => 3, 'name' => 'C'),
                    array('id' => 4, 'name' => 'D'),
                    array('id' => 5, 'name' => 'E'),
                    array('id' => 6, 'name' => 'F')
                );
                $info = array(
                    'name' => "Jen's Law Firm",
                    'address' => "1667 W. Alimosa Ave.",
                    'city' => 'Denver',
                    'state' => 'CO',
                    'country' => 'USA',
                    'zip' => '80219',
                    'phone' => '303-824-2789',
                    'fax' => '303-824-2291'
                );
                $display = 'multi';
                $titles['cart'] = 'Enroll Profiles In Programs';
            break;
            case USER_NORMAL :
                // TODO
                $info = array(
                    'name' => "Jen's Law Firm",
                    'address' => "1667 W. Alimosa Ave.",
                    'city' => 'Denver',
                    'state' => 'CO',
                    'country' => 'USA',
                    'zip' => '80219',
                    'phone' => '303-824-2789',
                    'fax' => '303-824-2291'
                );
            break;
            case USER_ANON :
                $button = 'login';
                // FIXME URL
                $this->session->set_userdata('login.href', '/cart/display');
            break;
            case USER_CHILD :
                // FIXME - What to do for child users ?
                die("Cart support for child user not implemented.");
            break;
        }
        
        $args = array(
            'profiles' => $profiles,
            'titles' => $titles,
            'display' => $display,
            'button' => $button,
            'info' => $info
        );
        
        $views = array(
            array('name' => 'cart/display', 'args' => $args)
        );
        
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
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
            case 'finish' :
                $this->doFinish();
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
        $result[0] = $this->soap->getProgram("8c98e179-c38e-df11-8d9f-000c2916a1cb");
        $result[1] = $this->soap->getProgram("8998e179-c38e-df11-8d9f-000c2916a1cb");
        
        $cart = array();
        foreach($result as $item) {
            $cart[] = array(
                'id' => $item->id,
                'qty' => 1,
                'price' => $item->price,
                'name' => str_replace("|", " ", $item->name),
            );
        }
        
        $this->cart->insert($cart);
    }
}