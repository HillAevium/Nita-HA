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
    
    $('#profile_form input[name="billingAddress1"]').val('345 Broadway');
    $('#profile_form input[name="billingCity"]').val('Walla Walla');
    $('#profile_form input[name="billingState"]').val('WA');
    $('#profile_form input[name="billingZip"]').val('97450');
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
    $('#profile_form input[name="haveScholarship"]').val("0");
    $('#profile_form input[name="barId[]"]').val('1111111');
    $('#profile_form input[name="state[]"]').val('NY');
    $('#profile_form input[name="month[]"]').val('7');
    $('#profile_form input[name="year[]"]').val('1341');
}

function addBarRow() {
    var rowCells = $("#bar_row").html();
    var newRow = $('<div class="row"></div>').html(rowCells);
    newRow.insertBefore("#bar_add");
}

function toggleIsAttendingDependentFields() {
    $(".isAttendingDependent").slideToggle();
}

function tearDown() {
    $("#back").unbind('click');
    $("#continue").unbind('click');
    $("#login_submit").unbind('click');
    $(document).unbind('ajaxComplete');
    $("#content_reg_funnel").hide();
    $("#content_reg_form").hide();
    $("#firm_form").hide();
    $("#profile_form").hide();
    $('#error_container').html('');
    $('#response_message').html('');
}

function renderLogin() {
    tearDown();
    $(document).ajaxComplete(handleLoginComplete);
    $("#login_submit").click(function() {
        $("#login_form").ajaxSubmit();
        return false;
    });
    $("#content_reg_funnel").show();
}

function renderFirmForm() {
    tearDown();
    $(document).ajaxComplete(handleFormComplete);
    $("#back").click(function() {
        renderLogin();
        return false;
    });
    $("#continue").click(function() {
        $("#firm_form").ajaxSubmit();
        return false;
    });
    $("#firm_form").show();
    $("#content_reg_form").show();
}

function renderProfileForm() {
    tearDown();
    $(document).ajaxComplete(handleFormComplete);
    $("#back").click(function() {
        renderFirmForm();
        return false;
    });
    $("#continue").click(function() {
        $("#profile_form").ajaxSubmit();
        return false;
    });
    $("#profile_form").show();
    $("#content_reg_form").show();
}

function selectRegType(event) {
    // FIXME
    // Remove for production
    addTestValues();
    
    var type = event.currentTarget.id;
    switch(type) {
        case 'group' :
            $("#page_title").html('Create A New Group Account');
            $("#instructions").html("<p>To enroll others, you'll need to create an account. You can then create profiles for each attendee.</p>");
        break;
        case 'individual' :
            $("#page_title").html('Create A New Individual Account');
        break;
    }
    $('input[name="userType"]').val(type);

    renderFirmForm();
    return false;
}

// Ajax Handlers

function handleLoginComplete(e, xhr, setting) {
    switch(xhr.status) {
        case HTTP_ACCEPTED :
            // Display a message to the user
            // Redirect them to referrer or profile page
            if(xhr.responseText == "/MyCart") {
                doPageLoad('/MyCart', false, true);
            } else {
                window.location.reload();
            }
            break;
        case HTTP_BAD_REQUEST :
            // The form info was invalid
            $("#error_container").html(xhr.responseText);
            break;
        case HTTP_UNAUTHORIZED :
            // The authorization failed
            $("#error_container").html(xhr.responseText);
            break;
        default : alert('error login');
    }
}

function handleFormComplete(e, xhr, setting) {
    switch(xhr.status) {
        case HTTP_ACCEPTED :
            $("#response_message").html(xhr.responseText);
            renderProfileForm();
            break;
        case HTTP_CREATED :
            if(xhr.responseText == "/MyCart") {
                doPageLoad('/MyCart', false, true);
            } else {
                window.location.reload();
            }
            break;
        case HTTP_BAD_REQUEST :
            $('#error_container').html(xhr.responseText);
            break;
        default : alert('error forms');
    }
}
