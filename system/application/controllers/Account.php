<?php

require_once APPPATH.'/controllers/AbstractController.php';
require_once APPPATH.'/models/accountprovider.php';
require_once APPPATH.'/models/def/definition.php';
require_once APPPATH.'/models/def/userprofile.php';
require_once APPPATH.'/models/def/firmprofile.php';

class Account extends AbstractController {
    
    /* Notes:
     *
     * This controller is our main source of authentiation
     * negotiation and we need to plan the interaction here
     * carefully. Ideally, almost everything that happens
     * through this controller should be performed over SSL
     * as there is alot of data being transmitted that is
     * sensitive. User information, firm information, passwords
     * and other information needs to be properly protected.
     *
     * CI has Encryption support in their custom session
     * implementation and maybe that will help eliviate some
     * of the concern. Also the Encryption library itself
     * could be used directly to some effect. But really none
     * of this is a substitue for SSL especially when the
     * user is submitting information.
     *
     * Handling referal pages.
     *
     * When an unauthenticated session attempts to perform
     * an action that requires authentication we need to
     * route them off to the login page, and potentially
     * the registration page.
     *
     * If the user has an account then they can simply login
     * and we can bounce them back to where they came from.
     * If the user needs to register first, then we can store
     * the referal page along with the registration model.
     *
     * Once they come back after verifying their registration
     * we can pull the referal page out of the model and send
     * it with them on their way to the login screen. When
     * they login sucessfully, they get bounced to where they
     * originally came from allowing them to clean.
     *
     * Another way to handle the referal page would be to stash
     * it in a cookie var. After the login has been authenticated
     * we can use the cookie var to send the user back to where they
     * came from. If the user requires registration then this becomes
     * even more helpful as we dont need to stash the ref page in
     * the model cache to bounce them after verification and login.
     *
     * Handling user/firm page views.
     *
     * The user interface will only provide means for the currently
     * logged in user to access their own account profile pages.
     * This however does not prevent someone from reverse engineering
     * the protocol used here and attempting to access those pages
     * in a more forceful manner.
     *
     * If the session is not one that is authenticated then we can
     * simply bounce them off to the login page. If the session is
     * authenticated and the user is trying to access pages not belonging
     * to them we should probably display a 401 Unauthorized page.
     *
     * If such requests are repeated in sucession than we can block the IP.
     * When an IP is blocked in this fashion it would be a good idea to have
     * a configuration setup where an admin is informed of the
     * intrustion detection being tripped off. Although this is probably
     * a topic that is better explored in its own discussion and
     * implemented in a more global manner (via hooks).
     *
     * There is probably more here that needs discussion but thats all
     * i have for now...
     */
    
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
                    // we really shouldn't end up here...
                    // FIXME but we still should handle it better
                    throw new RuntimeException("Invalid HTTP_REQUEST_METHOD");
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
        
        // Send authentication request to AccountService
        
        // If successful set the user to authenticated
        
        // If there is a referral page they came from
        // send them back there. Otherwise send them
        // to the home page
    }
    
    public function doRegistration() {
        // Validate form data
        
        // There's a config setting that we can set which will
        // automatically scrub all POST data for XSS and such
        
        // If this is a single user registration
        // create a single user model.
        $regType = $this->getArgument('regtype');
        
        if($regType == 'individual') {
            $this->load->model('accountProvider');
            $profile = process_post(new UserProfileDefinition());
            
            $this->accountProvider->createUser($profile);
            
            echo "User Created";
            echo "<pre>" . print_r($profile, true) . "</pre>";
            
            // FIXME Add in the cache for this so
            // we can do verification via email.
            // huh?
        }
        
        // If this is a group registration
        // create a firm model and a user model
        // with this user being the firm's superuser.
        else if ($regType == 'group') {
            // TODO
        }
        
        // If this is a user being added to a firm
        // create a user model and associate them
        // with the firm.
        
        // If there was a referal page that the user
        // was sent from then embed the referal into
        // the model so we can reload it after validation.
        
        // Memcache the model awaiting the user to
        // check their email for verification.
        
        /* NOTE:
         * The model is only valid for a limited amount
         * of time. We could use a pre-controller hook
         * to check the cache for registration models
         * that have expired and invalidate them. It
         * potentially means a registration could live in
         * the cache for an indeterminate amount of time, but
         * if the user clicks the validate link after the
         * expired amount of time, they will essentially be
         * invalidating the model before this controller
         * even gets to load.
         */
        
        // Generate a unique link and send the link
        // to the email provided in the registration.
    }
    
    private function doVerify() {
        // Get the unique identifier from the URI
        
        // Find the serialized model corresponding to
        // the users account details.
        
        // If the model has been invalidated we need them
        // to re-register. We cannot use the model to
        // repopulate the form for them. As much of a convenience
        // as this may be for the user, its also a security risk
        // and a breach of user privacy if a 3rd party has gained
        // access to the link.
        
        // If the model is still valid then we can push the user
        // information to AccountService
        
        // Since we cannot be 100% sure that the user that clicked
        // the validate link is the user that registered we
        // bounce them to the login screen for authentication.
    }
    
    public function doUserUpdate() {
        // Validate form data
        
        // Create a model from the data
        
        // Push the data to the AccountService
        
        // Display view showing update success
        // and bounce back to the users's profile page
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
        // If the user was directed here from some
        // page that required login before continuing
        // e.g. clicking checkout, then we need to
        // know where they came from.
        
        // Get the referal page, make the default the
        // home page if there is no referal
        $refPage = $this->getArgument('refPage', '/');
        
        // TODO - Session
        // Add the referal page to the session
        
        $args['refPage'] = $refPage;
        $views = array(
            array('name' => 'user/login', 'args' => $args)
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    /**
     * User registration pages
     *
     * @param string $step the step in the registration process
     * @param string $accountType the user's account type, either 'group' or 'individual'
     */
    public function showRegistration() {
        // TODO
        // Set referral page in session, or pass through
        // to next page.
        
        // Check for registration type in uri,
        // otherwise display funnel if not set
        if(!$this->getArgument('type')) {
            $this->showRegistrationFunnel();
            return;
        } else {
            $accountType = $this->getArgument('type');
            $this->showRegistrationForm($accountType);
        }
        
    }
    
    /**
     * Loads the funnel page which asks the user to
     * choose individual or group registration.
     */
    private function showRegistrationFunnel() {
        $views = array(
            array('name' => 'user/reg_funnel', 'args' => null)
        );
        
        // Set the view options
        $this->setViewOption('bodyClass', 'blue_short');
        $this->setViewOption('mainNav', true);
        $this->setViewOption('views', $views);
        
        // ... and go
        $this->loadViews();
    }
    
    /**
     * Display the user registration form
     *
     * @param string $accountType the user's account type, either 'group' or 'individual'
     */
    private function showRegistrationForm($accountType) {
        switch($accountType) {
            case 'group':
                // get the form template for groups
                $args['form'] = $this->load->view('user/form_group', '', true);
                
                $views = array(
                    array('name' => 'user/reg_group', 'args' => $args)
                );
                
                // Set up the view options
                $this->setViewOption('pageTitle', 'Create A New Group Account');
                $this->setViewOption('bodyClass', 'blue_short');
                $this->setViewOption('mainNav', true);
                $this->setViewOption('views', $views);
                
                // ... and go
                $this->loadViews();
                break;
            case 'individual':
                // get the form template for individuals
                $args['form'] = $this->load->view('user/form_individual', '', true);
                
                $views = array(
                    array('name' => 'user/reg_individual', 'args' => $args)
                );
                
                // Set up the view options
                $this->setViewOption('pageTitle', 'Create A New Individual Account');
                $this->setViewOption('bodyClass', 'blue_short');
                $this->setViewOption('mainNav', true);
                $this->setViewOption('views', $views);
                
                // ... and go
                $this->loadViews($views, 'blue_short');
                break;
            default:
                // the uri is invalid, so send the user back to the funnel
                $this->showRegistrationFunnel();
                break;
        }
    }
    
    public function showUserProfile() {
        // Get the user ID (from a cookie?)
        $userId = $this->getArgument('userId');
        
        // Make sure the user has access.
        if(!$this->checkUserAuthentication($userId)) {
            // Return a 401 UNAUTHORIZED
            log_message('error', "Unauthroized UserProfile Access Attempt");
            show_error("You are not authorized to access this profile.", 401);
            // TODO This may simply be an expired session which is no
            // reason to sound off any alarm bells. We should still do something
            // here to track intrusion attempts.
        }
        
        // Load the profile model and request the account.
        $this->load->model('userprofile');
        $model = $this->userprofile->get($userId);
        
        if(is_null($model)) {
            // Apparently the user does not exist.
            // FIXME What to do here ?
            show_404('/account/user/');
        }
        
        $views = array(
            array('name' => 'user/profile', 'args' => $model)
        );
        
        $this->viewOption('bodyClass', 'blue_short');
        $this->viewOption('mainNav', true);
        $this->viewOption('views', $views);
        $this->loadViews();
    }
    
    private function checkUserId($userId) {
        // Covers false, 0, '' and null
        if($userId == false) {
            return false;
        }
        // TODO What more checking should be done here?
        return true;
    }
    
    private function checkUserAuthentication($userId) {
        $this->checkUserId($userId);
        // TODO
        // This is just a session check, not a check
        // that goes to the ContactService.Authenticate()
        // We mostly need to know that the requested user
        // page is that of the session user
        return true;
    }
    
    private function handleGet($method) {
        switch($method) {
            case 'login' :
                $this->showLogin();
            break;
            case 'user' :
                $this->showUserProfile();
            break;
            case 'firm' :
                $this->showFirmProfile();
            break;
            case 'register' :
                $this->showRegistration();
            break;
            case 'verify' :
                $this->doVerify();
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
                // Since we're using remapping we have to handle the 404
                show_404('/account/' . $method . '/');
            return;
        }
    }
}

/* End of file Account.php */