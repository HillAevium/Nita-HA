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
                    $this->load->helper('post');
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
            $uri = "/";
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
        // TODO
        // get the account id of the authenticated user
        // and handle permissions or accessing this area
        $views = array(
            array('name' => 'user/account', 'args' => array('title' => 'My Account'))
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
    
    public function showLogin() {
        $views = array(
            array('name' => 'user/login', 'args' => null)
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
    
    /**
     * User registration pages.
     *
     * Displays an initial page that allows the user to choose whether they
     * want to signup for a group or as an individual. The forms are then
     * presented and validation occurs over ajax post requests.
     *
     * @see Account::doRegistration()
     */
    public function showRegistration() {
        $args['firmForm'] = $this->load->view('user/form_firm', null, true);
        $args['profileForm'] = $this->load->view('user/form_profile', null, true);
        
        $views = array(
            array('name' => 'user/reg_funnel', 'args' => null),
            array('name' => 'user/reg_form', 'args' => $args)
        );
        
        // Set the view options
        $this->setViewOption('color', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    public function showUserProfile() {
        // Get the user ID (from a cookie?)
        $userId = $this->getArgument('userId');
        
        // Make sure the user has access.
        if(!$this->checkUserAuthentication($userId)) {
            // Return a 401 UNAUTHORIZED
            //log_message('error', "Unauthroized UserProfile Access Attempt");
            //show_error("You are not authorized to access this profile.", 401);
            // TODO This may simply be an expired session which is no
            // reason to sound off any alarm bells. We should still do something
            // here to track intrusion attempts.
        }
        
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
            case 'login' :
                $this->showLogin();
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
            case 'register' :
                $this->showRegistration();
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