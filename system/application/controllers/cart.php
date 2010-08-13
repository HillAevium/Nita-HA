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
        $jsonField = new Json_Field('json');
        
        if(!$jsonField->validate()) {
            $error = $jsonField->error();
            log_message('error', $error);
            $this->output->set_status_header(400);
            return;
        }
        
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
        
        // FIXME - Try using $this->output->set_output();
        $this->output->set_status_header(202);
        echo json_encode(array('details' => $details, 'billing' => $billing));
    }
    
    public function doFinish() {
        $this->output->set_status_header(201);
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
        $userType = $this->mod_auth->credentials()->user['type'];
        switch($userType) {
            case USER_SUPER :
                $this->showSuperCart();
            break;
            case USER_ANON :
            case USER_NORMAL :
                $this->showNormalCart($userType);
            break;
            case USER_CHILD :
                // FIXME - What to do for child users ?
                die("Cart support for child user not implemented.");
            break;
        }
    }
    
    // Page 2
    // Reachable by unauthenticated users
    //   Show Login/Register buttons
    // Reachable by authenticated normal users
    //   Show Checkout button
    private function showNormalCart($userType) {
        // We display different buttons depending on the user type
        // Anonymous users see Login/Register
        // Normal users see Checkout
        switch($userType) {
            case USER_NORMAL :
                $args['buttons'] = 'checkout';
            break;
            case USER_ANON :
                $args['buttons'] = 'login';
                $this->session->set_userdata('login.href', '/cart/display');
            break;
        }
        
        $args['title'] = 'Your Cart';
        
        $views = array(
            array('name' => 'cart/normal', 'args' => $args)
        );
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('pageTitle', 'Your Cart');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        $this->loadViews();
    }
    
    // Page 8
    // Reachable by authenticated super users
    private function showSuperCart() {
        $accountId = $this->mod_auth->credentials->user['accountId'];
        $this->load->model('accountProvider');
        $args['users'] = $this->accountProvider->getProfilesByAccount($accountId);
        log_message('error', print_r($args, true));
        
        $views = array(
            array('name' => 'cart/super', 'args' => array('title' => 'Enroll Profiles in Programs'))
        );
        
        $this->setViewOption('pageTitle', 'Enroll Profiles in Programs');
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