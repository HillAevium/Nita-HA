const HTTP_OK = 200;
const HTTP_CREATED = 201;
const HTTP_ACCEPTED = 202;

const HTTP_NOT_MODIFIED = 304;

const HTTP_BAD_REQUEST = 400;
const HTTP_UNAUTHORIZED = 401;
const HTTP_FORBIDDEN = 403;
const HTTP_NOT_FOUND = 404;
const HTTP_TIMEOUT = 408;

const HTTP_INTERNAL_ERROR = 500;

// FIXME
// Remove for production
function addTestValues() {
    $('#firm_form input[name="name"]').val('ABC Law Firm');
    $('#firm_form input[name="phone1"]').val('408-555-6666');
    $('#firm_form input[name="fax"]').val('408-777-8888');
    $('#firm_form input[name="billingAddress1"]').val('123 Main St');
    $('#firm_form input[name="billingCity"]').val('New York');
    $('#firm_form input[name="billingState"]').val('NY');
    $('#firm_form input[name="billingZip"]').val('12345');
    $('#firm_form input[name="shippingAddress1"]').val('345 Broadway');
    $('#firm_form input[name="shippingCity"]').val('Walla Walla');
    $('#firm_form input[name="shippingState"]').val('WA');
    $('#firm_form input[name="shippingZip"]').val('97450');
    $('#firm_form input[name="firmSize"]').val('10-19');
    $('#firm_form input[name="practiceType"]').val('Type 4');
    $('#firm_form input[name="trainingDirector"]').val('Bruce Willis');
    
    $('#profile_form input[name="shippingAddress1"]').val('345 Broadway');
    $('#profile_form input[name="shippingCity"]').val('Walla Walla');
    $('#profile_form input[name="shippingState"]').val('WA');
    $('#profile_form input[name="shippingZip"]').val('97450');
    $('#profile_form input[name="firstName"]').val('Jogn');
    $('#profile_form input[name="middleInitial"]').val('B');
    $('#profile_form input[name="lastName"]').val('Doe');
    $('#profile_form input[name="suffix"]').val('Jr.');
    $('#profile_form input[name="title"]').val('Director of Direction');
    $('#profile_form input[name="email"]').val('test@test.com');
    $('#profile_form input[name="password"]').val('abc123');
    $('#profile_form input[name="password2"]').val('abc123');
    $('#profile_form input[name="phone"]').val('+1(303)413-0551');
    $('#profile_form input[name="phone2"]').val('7075556666');
    $('#profile_form input[name="isAttendingClasses"]').val('Yes');
    $('#profile_form input[name="role"]').val('Doe');
    $('#profile_form input[name="badgeName"]').val('Doe');
    $('#profile_form input[name="requireAccessibility"]').val("0");
    $('#profile_form input[name="etnicity"]').val('Doe');
}

function addBarRow() {
    var rowCells = $("#bar_row").html();
    var newRow = $("<tr></tr>").html(rowCells); 
    clearFormElements(newRow);
    var _newRow = $("#bar_info").append(newRow);
}

function toggleIsAttendingDependentFields() {
    if($("#isAttendingClasses").val() == '1') {
        $(".isAttendingDependent").show();
    } else {
        clearFormElements($(".isAttendingDependent").hide());
    }
}

function initForm(form) {
    $('#error_container').html('');
    $('#response_message').html('');
    $("#continue").unbind('click');
    $("#continue").click(
        function(event) {
            form.ajaxSubmit();
        }
    );
    $('form').hide();
    form.fadeIn();
}

function ajaxHandler() {
    /**
     * Login
     */
    $("#login_form").ajaxComplete(function(e, xhr, setting) {
        alert('ajaxComplete');
        switch(xhr.status) {
            case HTTP_ACCEPTED : // ACCEPTED
                alert('HTTP_ACCEPTED');
                ajaxHandler.state = 'done';
                // Display a message to the user
                // Redirect them to referrer or profile page
                doPageLoad(xhr.responseText, false, true);
                break;
            case HTTP_BAD_REQUEST : // BAD REQUEST
                // The form info was invalid
                $("#error_container").html(xhr.responseText);
                break;
            case HTTP_UNAUTHORIZED : // UNAUTHORIZED
                // The authorization failed
                $("#error_container").html(xhr.responseText);
                break;
            default :
                // Unhandled code
                $("#error_container").html("Unhandled HTTP status code : " + xhr.status);
        }
    });
    
    /**
     * Registration / Verification
     */
    // Remember the state that our forms are in
    // and handle the AJAX response.
    // There are 2 states: form, verify
    $('#forms_container').ajaxComplete(function(e, xhr, settings) {
        alert('ajaxComplete2');
//        if(typeof ajaxHandler.state == undefined) {
//            ajaxHandler.state = 'form';
//        }
        // get the http status code from the response headers
//        if(ajaxHandler.state == 'form') {
        switch(xhr.status) {
            case HTTP_CREATED : // CREATED
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
            case HTTP_BAD_REQUEST : // BAD REQUEST
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
$(document).ready(
    function() {
        initForm($('#firm_form'));
        $("form").submit(function(event) { return false; });
        //addAjaxHandler();
        // FIXME
        // Remove for production
        addTestValues();
        
        $('#firm_form').ajaxComplete(
            function(e, xhr, setting) {
                switch(xhr.status) {
                    case 202 : // ACCEPTED
                        // FIXME
                        $("#response_message").html(xhr.responseText);
                        initForm($('#profile_form'));
                        break;
                }
            }
        );
        
        $('#profile_form').ajaxComplete(
            function(e, xhr, setting) {
                switch(xhr.status) {
                    case 202 : // ACCEPTED
                        // FIXME
                        $("#response_message").html(xhr.responseText);
                        break;
                }
            }
        );
    }
);