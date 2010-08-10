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
    $("#submit_form").unbind('click');
    $("#submit_form").click(
        function(event) {
            form.ajaxSubmit();
        }
    );
    $('form').hide();
    form.fadeIn();
}

if(window.location.pathname == '/account/login') {
    // Initialize Login
    $(document).ready(
        function() {
            initForm($('#login_form'));
            $("form").submit(function(event) { return false; });
            
            $('#login_form').ajaxComplete(
                function(e, xhr, setting) {
                    switch(xhr.status) {
                        case HTTP_ACCEPTED :
                            // Display a message to the user
                            // Redirect them to referrer or profile page
                            doPageLoad(xhr.responseText, false, true);
                            break;
                        case HTTP_BAD_REQUEST :
                            // The form info was invalid
                            $("#error_container").html(xhr.responseText);
                            break;
                        case HTTP_UNAUTHORIZED :
                            // The authorization failed
                            $("#error_container").html(xhr.responseText);
                            break;
                        default :
                            // Unhandled code
                            $("#error_container").html("Unhandled HTTP status code : " + xhr.status);
                    }
                }
            );
        }
    );
}

if (window.location.pathname == '/account/register/regtype/individual' || window.location.pathname == '/account/register/regtype/group') {
    // Initialize Registration forms
    $(document).ready(
        function() {
            initForm($('#firm_form'));
            $("form").submit(function(event) { return false; });
            // FIXME
            // Remove for production
            addTestValues();
            
            $('#firm_form').ajaxComplete(
                function(e, xhr, setting) {
                    switch(xhr.status) {
                        case HTTP_ACCEPTED :
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
                        case HTTP_CREATED :
                            // FIXME - Needs to be https (2nd param true)
                            doPageLoad(xhr.responseText, false, true);
                            break;
                    }
                }
            );
        }
    );
}