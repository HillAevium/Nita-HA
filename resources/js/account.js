// takes a jQuery object as a param
function prepareFormForAjax(form) {
    form.submit(function(event) {
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    });
}

function addAjaxHandler() {
    prepareFormForAjax($("#registration_form"));
    prepareFormForAjax($("#verification_form"));
    prepareFormForAjax($("#login_form"));
    $("#continue").click(function(event) {
//        if(ajaxHandler.state == 'form') {
        $('#registration_form').submit();
//        }
//        else if(ajaxHandler.state == 'verify'){
//            $('#verification_form').submit();
//        }
    });
    ajaxHandler();
}

function ajaxHandler() {
    /**
     * Login
     */
    $("#login_form").ajaxComplete(function(e, xhr, setting) {
        switch(xhr.status) {
            case 201 : // ACCEPTED
                ajaxHandler.state = 'done';
                // Display a message to the user
                // Redirect them to referrer or profile page
                window.location = '/account/user';
                break;
            case 400 : // BAD REQUEST
                // The verify ID was not found, redirect to home page
                $("#error_container").html(xhr.responseText);
                break;
        }
    });
    
    /**
     * Registration / Verification
     */
    // Remember the state that our forms are in
    // and handle the AJAX response.
    // There are 2 states: form, verify
    $('#forms_container').ajaxComplete(function(e, xhr, settings) {
//        if(typeof ajaxHandler.state == undefined) {
//            ajaxHandler.state = 'form';
//        }
        // get the http status code from the response headers
//        if(ajaxHandler.state == 'form') {
        switch(xhr.status) {
            case 201 : // CREATED
                // FIXME 2nd param is for https, needs to be true for
                // login page
                doPageLoad('/account/login', false, true);
                    // Valid data
//                    ajaxHandler.state = 'verify';
//                    $("#registration_form").hide();
                
                    // FIXME Remove
//                    $("#error_container").html(xhr.responseText);
                
//                    $("#verification_form").show();
                break;
            case 400 : // BAD REQUEST
                // Invalid data
                $("#error_container").html(xhr.responseText);
                break;
        }
//        }
//        else if(ajaxHandler.state == 'verify') {
//            switch(xhr.status) {
//                case 201 : // ACCEPTED
//                    ajaxHandler.state = 'done';
//                    // Display a message to the user
//                    // Redirect them to referrer or profile page
//                    $("#verification_form").hide();
//                    $("#error_container").html(xhr.responseText);
//                    $("#response_message").html('Your account is now verified. Go forth and have loads of fun. And always...ALWAYS...be safe.');
//                    window.location = '/account/login';
//                    break;
//                case 400 : // BAD REQUEST
//                    // The verify ID was not found, redirect to home page
//                    $("#error_container").html(xhr.responseText);
//                    $("#response_message").html('The verification code is invalid.');
//                    break;
//                case 408 : // REQUEST TIMEOUT
//                    // The form data will be removed and no user will be created
//                    $("#error_container").html(xhr.responseText);
//                    $("#response_message").html('The verification code you entered is no longer valid. You will need to fill out the registration form again in order to receive a new verification code.');
//                    break;
//            }
//        }
    });
}

// Initialize
$(addAjaxHandler);