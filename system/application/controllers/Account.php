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
    
    public function doBar() {
        // TODO
    }
    
    public function doFirmUpdate() {
        $creds = $this->mod_auth->credentials();
        // Validate form data
        $_POST['userType'] = $creds->user['type'] == USER_SUPER ? 'group' : 'individual';
        $firmDef = new FirmProfileDefinition();
        $firm = $firmDef->processPost('array');
        
        if($firm === null) {
            $this->output->set_status_header(HTTP_BAD_REQUEST);
            $this->sendErrors($firmDef->errors());
            return;
        }
        
        $firm->id = $creds->user['accountId'];
        
        // Create a model from the data
        $this->load->model('accountProvider');
        
        // Push the data to the AccountService
        $this->accountProvider->updateAccount($firm);
        
        $this->output->set_status_header(HTTP_ACCEPTED);
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
        $creds['name'] = $user->firstName.' '.$user->lastName;
        $this->mod_auth->grant($user->id, $creds);
        
        // Send 202 ACCEPTED
        $this->output->set_status_header(HTTP_ACCEPTED);
        
        $uri = $this->session->userdata('login.href');
        if($uri !== false) {
            echo "/MyCart";
        }
        
        // Cleanup the session
        $this->session->unset_userdata('login.href');
    }
    
    public function doLogout() {
        $this->mod_auth->revoke();
        $this->output->set_header("Location: /");
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
                    $profile['accountId'] = $this->accountProvider->storeAccount($firm);
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
    
    public function doProfileAdd() {
        $creds = $this->mod_auth->credentials();
        
        $profileDef = new UserProfileDefinition();
        $profile = $profileDef->processPost('array');
        
        if($profile === null) {
            $this->output->set_status_header(HTTP_BAD_REQUEST);
            $this->sendErrors($profileDef->errors());
            return;
        }
        
        $profile['userType'] = USER_CHILD;
        $profile['accountId'] = $creds->user['accountId'];
        
        $this->load->model('accountProvider');
        $this->accountProvider->storeProfile($profile);
        
        $this->output->set_status_header(HTTP_CREATED);
    }
    
    public function doProfileUpdate() {
        $creds = $this->mod_auth->credentials();
        // FIXME Fudging the password to make the definition validate
        $_POST['password'] = '111111';
        $_POST['password2'] = '111111';
        $profileDef = new UserProfileDefinition();
        $profile = $profileDef->processPost('array');
        unset($profile['password']);
        
        if($profile === null) {
            $this->output->set_status_header(HTTP_BAD_REQUEST);
            $this->sendErrors($profileDef->errors());
            return;
        }
        
        switch($creds->user['type']) {
            case USER_SUPER :
                $profile->id = $_POST['id'];
                break;
            case USER_NORMAL :
            case USER_CHILD :
                $profile->id = $creds->auth['id'];
                break;
            default :
                $this->output->set_status_header(HTTP_FORBIDDEN);
                return;
        }
        
        $this->load->model('accountProvider');
        $this->accountProvider->updateProfile($profile);
        $this->output->set_status_header(HTTP_ACCEPTED);
    }
    
    public function showAccount() {
        $credentials = $this->mod_auth->credentials();
        
        $args['title'] = 'My Account';
        
        $this->load->model('accountProvider');
        
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
        $account = $this->accountProvider->getAccount($credentials->user['accountId']);
        $args['account'] = $account;
        
        switch($userType) {
            case USER_SUPER :
                $display = 'multi';
                $profiles = $this->accountProvider->getProfilesByAccount($credentials->user['accountId']);
                $args['userProfiles'] = $profiles;
                //$orders  = $this->accountProvider->getOrdersForProfile($profile);
                
                // Load the account order history
                $args['orders'] = $orders;
                // Load the users order history
                $args['userOrders'] = array($orders, $orders);
                break;
            case USER_NORMAL :
            case USER_CHILD :
                $display = 'single';
                $profile = $this->accountProvider->getProfileById($credentials->auth['id']);
                $args['profile'] = $profile;
                //$orders = $this->accountProvider->getOrdersForAccount($profiles);
                
                // Load the user order history
                $args['orders'] = $orders;
                break;
        }
        
        $args['display'] = $display;
        
        $args['firmForm'] = $this->load->view('user/forms/firm', $args, true);
        $args['profileForm'] = $this->load->view('user/forms/profile', $args, true);
        
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
    
    public function showLoginAndRegister() {
        $args['firmForm'] = $this->load->view('user/forms/firm', null, true);
        $args['profileForm'] = $this->load->view('user/forms/profile', null, true);
        
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
    
    private function handleGet($method) {
        switch($method) {
            case 'main' :
                $credentials = $this->mod_auth->credentials();
                switch($credentials->user['type']) {
                    case USER_SUPER :
                    case USER_NORMAL :
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
            default :
                show_404('/account/' . $method . '/');
            return;
        }
    }
    
    private function handlePost($method) {
        $auth = $this->mod_auth->isAuthenticated();
        switch($method) {
            case 'login' :
                $this->doLogin();
            break;
            case 'profile' :
                $this->doProfileUpdate();
            break;
            case 'firm' :
                $this->doFirmUpdate();
            break;
            case 'profile_add' :
                $this->doProfileAdd();
            break;
            case 'register' :
                $this->doRegistration();
            break;
            case 'bar' :
                $this->doBar();
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