<?php

require_once APPPATH.'/controllers/AbstractController.php';
require_once APPPATH.'/models/accountprovider.php';
require_once APPPATH.'/models/core/md5_field.php';
require_once APPPATH.'/models/def/userprofile.php';
require_once APPPATH.'/models/def/firmprofile.php';

define('HTTP_OK',          200);
define('HTTP_CREATED',     201);
define('HTTP_ACCEPTED',    202);

define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED',401);
define('HTTP_FORBIDDEN',   403);
define('HTTP_NOT_FOUND',   404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_TIMEOUT',     408);

define('HTTP_INTERNAL_ERROR', 500);

define('AUTH_OK',         0);
define('AUTH_NO_ACCOUNT', 1);
define('AUTH_BAD_PASS',   2);

class Account extends AbstractController {
    
    public function Account() {
        parent::AbstractController();
    }
    
    public function _remap($method) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        try {
            switch($requestMethod) {
                case 'POST' :
                    $this->handlePost($method);
                break;
                case 'GET' :
                    $this->handleGet($method);
                break;
                default :
                    $this->output->set_status_header(HTTP_METHOD_NOT_ALLOWED);
                    throw new Request_Exception("The request method is not supported.");
                break;
            }
        } catch(Exception $e) {
            throw $e;
            // TODO Setup AuthenticationException trap
        }
    }
    
    public function doFirmUpdate() {
        // Validate form data
        
        // Create a model from the data
        
        // Push the data to the AccountService
        
        // Display view showing update success
        // and bounce back to the firm's profile page
    }
    
    public function doLogin() {
        // Validate form data
        $email = new Email_Field('email');
        $password = new Password_Field('password');
        
        // Check for errors
        if(!$email->validate()) {
            $errors[] = $email->error();
        }
        
        if(!$password->validate()) {
            $errors[] = $password->error();
        }
        
        if(isset($errors)) {
            // Invalid ID - 400 BAD_REQUEST
            $this->output->set_status_header(HTTP_BAD_REQUEST);
            return $this->sendErrors($errors);
        }
        
        // Send authentication request to AccountService
        $email = $email->process();
        $password = $password->process();
        $this->load->model('accountProvider');
        $status = $this->accountProvider->authenticate($email, $password);
        
        if($status !== AUTH_OK) {
            $this->output->set_status_header(HTTP_UNAUTHORIZED);
            switch($status) {
                case AUTH_NO_ACCOUNT :
                    $errors[] = 'No account for this email exists.';
                    break;
                case AUTH_BAD_PASS :
                    $errors[] = 'The password is not valid for this account.';
                    break;
                default :
                    throw new RuntimeException('Invalid AUTH status');
            }
            return $this->sendErrors($errors);
        }
        
        $user = $this->accountProvider->getProfileByEmail($email);
        
        if(!$user) {
            throw new RuntimeException("no user");
        }
        
        // If successful set the user to authenticated
        $creds['type'] = $user->userType;
        $creds['accountId'] = $user->accountId;
        $this->mod_auth->grant($user->id, $creds);
        
        // Send 202 ACCEPTED
        $this->output->set_status_header(HTTP_ACCEPTED);
        
        $uri = $this->session->userdata('login.href');
        if($uri === false) {
            $uri = "/MyAccount";
        }
        
        // Send the referrer uri back to the client
        // so it can load the appropiate page.
        echo $uri;
        
        // Cleanup the session
        $this->session->unset_userdata('login.href');
    }
    
    public function doLogout() {
        $this->mod_auth->revoke();
        
        // Send a redirect to the home page
        // TODO
    }
    
    public function doRegistration() {
        $form = $this->getArgument('form');
        
        switch($form) {
            case 'firm':
                // Validate the firm information and stash
                // it in the session.
                $firmDef = new FirmProfileDefinition();
                $firm = $firmDef->processPost('array');
                
                if($firm === null) {
                    $this->output->set_status_header(HTTP_BAD_REQUEST);
                    $this->sendErrors($firmDef->errors());
                    return;
                }
                
                $this->session->set_userdata('registration_firm_info', serialize($firm));
                $this->output->set_status_header(HTTP_ACCEPTED);
            return;
            case 'profile':
                // Validate the profile information and push it
                // along with the firm info to persistant storage
                
                $profileDef = new UserProfileDefinition();
                $profile = $profileDef->processPost('array');
                
                if($profile === null) {
                    $this->output->set_status_header(HTTP_BAD_REQUEST);
                    $this->sendErrors($profileDef->errors());
                    return;
                }
                
                $firm = unserialize($this->session->userdata('registration_firm_info'));
                
                // We pulled in the user type in the firm form to use as a
                // dependant field, but it needs to be submitted to the
                // contact table with the user info.
                $profile['userType'] = $firm['userType'];
                unset($firm['userType']);
                
                $this->load->model('accountProvider');
                try {
                    $this->accountProvider->storeAccount($firm);
                    $this->accountProvider->storeProfile($profile);
                } catch(Exception $e) {
                    $this->output->set_status_header(HTTP_INTERAL_ERROR);
                    // FIXME
                    echo $e->getMessage();
                    echo $e->getTraceAsString();
                    return;
                }
                
                // Client side will load the login page when
                // recieving this code
                $this->output->set_status_header(HTTP_CREATED);
                
                // Cleanup the session
                $this->session->unset_userdata('registration_firm_info');
            return;
        }
    }
    
    public function doUserUpdate() {
        // Validate form data
        
        // Create a model from the data
        
        // Push the data to the AccountService
        
        // Display view showing update success
        // and bounce back to the users's profile page
    }
    
    public function showAccount() {
        $credentials = $this->mod_auth->credentials();
        
        $args['title'] = 'My Account';
        
        //$profile = $this->accountProvider->getProfile($credentials->auth['id']);
        //$account = $this->accountProvider->getAccount($credentials->user['accountId']);
        //$orders  = $this->accountProvider->getOrdersForProfile($profile);
        
        //$profiles = $this->accountProvider->getProfilesForAccount($credentials->user['accountId']);
        //$orders = $this->accountProvider->getOrdersForProfiles($profiles);
        $account = array(
            'name' => "Jen's Law Firm",
            'address' => '1667 W. Alimosa Ave.',
            'city' => 'Denver',
            'state' => 'CO',
            'zip' => '80219',
            'phone' => '303-824-2789',
            'fax' => '303-824-2291'
        );
        $superProfile = array(
            'name' => "Jen Newstead",
            'address' => '1667 W. Alimosa Ave.',
            'city' => 'Denver',
            'state' => 'CO',
            'zip' => '80219',
            'phone' => '303-824-2789',
            'fax' => '303-824-2291',
            'type' => '(Super User)'
        );
        $profile = array(
            'name' => "Mike Cogline",
            'address' => '1667 W. Alimosa Ave.',
            'city' => 'Denver',
            'state' => 'CO',
            'zip' => '80219',
            'phone' => '303-824-2789',
            'fax' => '303-824-2291',
            'type' => '',
            'bar' => array(
                array(
                    'state' => 'Florida',
                    'barId' => '1384187418',
                    'cle' => '8.0'
                ),
                array(
                    'state' => 'New York',
                    'barId' => '4343872834',
                    'cle' => '27.0'
                )
            )
        );
        $orders = array(
            array(
                'date' => 'Jan 5, 2009',
                'programs' => array('Program A', 'Program B'),
                'price' => '5000'
            ),
            array(
                'date' => 'Sep 27, 2009',
                'programs' => array('Program C', 'Program D', 'Program E'),
                'price' => '6000'
            ),
            array(
                'date' => 'Feb 4, 2010',
                'programs' => array('Program F'),
                'price' => '12000'
            )
        );
        
        $userType = $credentials->user['type'];
        
        switch($userType) {
            case USER_SUPER :
                $display = 'multi';
                // Load the account profile
                $args['profile'] = $account;
                // Load the users profiles
                $args['userProfiles'] = array($superProfile, $profile);
                // Load the account order history
                $args['orders'] = $orders;
                // Load the users order history
                $args['userOrders'] = array($orders, $orders);
                break;
            case USER_NORMAL :
            case USER_CHILD :
                $display = 'single';
                // Load the user profile
                $args['profile'] = $profile;
                // Load the user order history
                $args['orders'] = $orders;
                break;
        }
        
        $args['display'] = $display;
        
        $views = array(
            array('name' => 'user/account', 'args' => $args)
        );
        
        // Set the view options
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('pageTitle', 'My Account');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showFirmProfile() {
        // Get the firm ID (from a cookie?)
        
        // Do authentication check.
        
        // If auth passed request the firm information
        // from AccountService
        
        // It should be noted that although the interface
        // will not provide a means for a single user
        // account to end up here, that does not stop
        // an outside attempt to do so. The authentication
        // should not only verify a valid session, but that
        // the user is a firm super-user and is the firm
        // super-user for the requested account.
        
        // If auth failed send them to the login screen
        // with the firm profile page as the referal page.
    }
    
    public function showLoginAndRegister() {
        $args['firmForm'] = $this->load->view('user/form_firm', null, true);
        $args['profileForm'] = $this->load->view('user/form_profile', null, true);
        
        $views = array(
            array('name' => 'user/forms', 'args' => null),
            array('name' => 'user/reg_form', 'args' => $args)
        );
        
        // Set the view options
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showOrders() {
        // TODO
        // get the account id of the authenticated user
        // and handle permissions or accessing this area
        $views = array(
            array('name' => 'user/orders', 'args' => array('title' => 'Previous Orders'))
        );
        
        // Set the view options
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('pageTitle', 'Pervious Orders');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showUserProfile() {
        
        // Load the profile model and request the account.
        //$this->load->model('userprofile');
        //$model = $this->userprofile->get($userId);
        
        //if(is_null($model)) {
            // Apparently the user does not exist.
            // FIXME What to do here ?
            //show_404('/account/user/');
        //}

        $content[] = $this->load->view('user/profile/home',  null, true);
        $content[] = $this->load->view('user/profile/cle_credits',  null, true);
        $content[] = $this->load->view('user/profile/enrollment_history', null, true);
               
        // Setup the tab panel
        $tabs = array(
            array('name' => 'Home',               'id' => 'home',               'content' => $content[0]),
            array('name' => 'CLE Credits',        'id' => 'cle_credits',        'content' => $content[1]),
            array('name' => 'Enrollment History', 'id' => 'enrollment_history', 'content' => $content[2])
        );
        
        // And the tabs classes
        $class['tabs']   = 'orange_tabs';
        $class['border'] = 'orange_border';
        
        // Populate args for the view
        $args['tabs']  = $tabs;
        $args['class'] = $class;
        
        // Setup the views
        $title = "My Profile";
        $views = array(
            array('name' => 'user/profile/top', 'args' => array('title' => $title)),
            array('name' => 'tab_panel', 'args' => $args),
            array('name' => 'user/profile/bottom', 'args' => null)
        );
        
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('pageTitle', 'My Profile');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();

    }
    
    private function handleGet($method) {
        switch($method) {
            case 'main' :
                $credentials = $this->mod_auth->credentials();
                switch($credentials->user['type']) {
                    case USER_SUPER :
//                        break;
                    case USER_NORMAL :
//                        break;
                    case USER_CHILD :
                        $this->showAccount();
                        break;
                    case USER_ANON :
                        $this->showLoginAndRegister();
                        break;
                }
            break;
            case 'logout' :
                $this->doLogout();
            break;
            case 'user' :
                $this->showUserProfile();
            break;
            case 'company' :
                $this->showAccount();
            break;
            case 'orders' :
                $this->showOrders();
            break;
            case 'group' :
                $this->showGroupProfile();
            break;
            case 'forms' :
                $this->showLoginAndRegister();
            break;
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
    
    private function handlePost($method) {
        switch($method) {
            case 'login' :
                $this->doLogin();
            break;
            case 'user' :
                $this->doUserUpdate();
            break;
            case 'firm' :
                $this->doFirmUpdate();
            break;
            case 'register' :
                $this->doRegistration();
            break;
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
    
    private function sendErrors(array $errors) {
        $this->load->view('errors', array('errors' => $errors));
    }
}

/* End of file Account.php */