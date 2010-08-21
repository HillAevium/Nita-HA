<?php
if(isset($profile)) {
$a = json_encode($profile);
echo <<<JS
<script type="text/javascript">$(document).ready(function() {injectProfile($a);});</script>
JS;
}
?>

<script type="text/javascript">
function injectProfile(a) {
    if(typeof a == 'undefined') { alert('undefined'); return; }
    /**/
    $('#profile_form input[name="billingAddress1"]').val(a.billingAddress1);
    $('#profile_form input[name="billingCity"]').val(a.billingCity);
    $('#profile_form input[name="billingState"]').val(a.billingState);
    $('#profile_form input[name="billingZip"]').val(a.billingZip);
    $('#profile_form input[name="shippingAddress1"]').val(a.shippingAddress1);
    $('#profile_form input[name="shippingCity"]').val(a.shippingCity);
    $('#profile_form input[name="shippingState"]').val(a.shippingState);
    $('#profile_form input[name="shippingZip"]').val(a.shippingZip);
    $('#profile_form input[name="salutation"]').val(a.salutation);
    $('#profile_form input[name="firstName"]').val(a.firstName);
    $('#profile_form input[name="middleInitial"]').val(a.middleInitial);
    $('#profile_form input[name="lastName"]').val(a.lastName);
    $('#profile_form input[name="suffix"]').val(a.suffix);
    $('#profile_form input[name="title"]').val(a.title);
    $('#profile_form input[name="email"]').val(a.email);
    $('#password').remove();
    $('#profile_form input[name="phone"]').val(a.phone);
    $('#profile_form input[name="phone2"]').val(a.phone2);
    $('#profile_form input[name="isAttendingClasses"]').val(a.isAttendingClasses);
    $('#profile_form input[name="role"]').val(a.role);
    $('#profile_form input[name="badgeName"]').val(a.badgeName);
    $('#profile_form input[name="requireAccessibility"]').val(a.requireAccessibility);
    $('#profile_form input[name="haveScholarship"]').val(a.haveScholarship);
    $('#bar_row').remove();
    $('#bar_add').remove();
    for(var i in a.bar) {
        $("#profile_form").append('<input type="hidden" name="barId[]" value="'+a.bar[i].barId+' />');
        $("#profile_form").append('<input type="hidden" name="state[]" value="'+a.bar[i].state+' />');
        $("#profile_form").append('<input type="hidden" name="month[]" value="'+a.bar[i].month+' />');
        $("#profile_form").append('<input type="hidden" name="year[]" value="'+a.bar[i].year+' />');
    }
    /**/
}
</script>

<!-- FIXME - These form fields are required by the validator.
If they are going to be omitted from the form and perhaps
calculated based on the State/Province then we need to change
the definition.
 -->
<input type="hidden" name="shippingCountry" value="USA" />
<input type="hidden" name="billingCountry" value="USA" />

<div class="header_bar_blue_full">
    <table class="header">
        <tr>
            <td style="width:484px;" class="pad">Your Company/Firm</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
<div id="form_container">
    <div class="cell-mask">&nbsp;</div>
    <div class="row-mask">&nbsp;</div>
    <div class="row">
        <div class="cell-tiny first">
            <label>Salutation</label>
            <input name="salutation" />
        </div>
        <div class="cell second-name">
            <label>First Name</label>
            <input name="firstName" />
        </div>
        <div class="cell-tiny third-name">
            <label>Middle Initial</label>
            <input name="middleInitial" />
        </div>
        <div class="cell fourth-name">
            <label>Last Name</label>
            <input name="lastName" />
        </div>
    </div>
    <div class="row">
        <div class="cell-tiny first">
            <label>Suffix</label>
            <input name="suffix" />
        </div>
        <div class="cell second-name">
            <label>Title</label>
            <input name="title" />
        </div>
    </div>
    <div class="row">
        <div class="cell-med first">
            <label>Email Address (User ID)</label>
            <input name="email" />
        </div>
        <div id="password">
            <div class="cell-med second-pass">
                <label>Password</label>
                <input name="password" type="password" />
            </div>
            <div class="cell-med third-pass">
                <label>Verify Password</label>
                <input name="password2" type="password" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="cell-med first">
            <label>Primary Phone</label>
            <input name="phone" />
        </div>
        <div class="cell-med second-pass">
            <label>Secondary Phone</label>
            <input name="phone2" />
        </div>
    </div>
    <div class="row">
        <div class="cell-med first">
            <label>Will you be attending Nita programs?</label>
            <select id="isAttendingClasses" name="isAttendingClasses" onchange="toggleIsAttendingDependentFields();">
                <option value="0">No</option>
                <option value="1" selected="selected">Yes</option>
            </select>
        </div>
    </div>
    <div class="isAttendingDependent">
        <div class="row">
            <div class="cell first">
                <label>Billing Address</label><br />
                <input name="billingAddress1" />
            </div>
            <div class="cell-small second">
                <label>City</label><br />
                <input class="small2" name="billingCity" />
            </div>
            <div class="cell-small third">
                <label>State</label>
                <input name="billingState" />
            </div>
            <div class="cell-small fourth">
                <label>Zip</label><br />
                <input name="billingZip" />
            </div>
        </div>
        <div class="row">
            <div class="cell first">
                <label>Shipping Address (for course materials)<br />No P.O. Boxes Please</label><br />
                <input name="shippingAddress1" />
            </div>
            <div class="cell-small second">
                <label>City</label><br />
                <input name="shippingCity" />
            </div>
            <div class="cell-small third">
                <label>State</label>
                <input name="shippingState" />
            </div>
            <div class="cell-small fourth">
                <label>Zip</label><br />
                <input name="shippingZip" />
            </div>
        </div>
        <div class="row-text">
            <div class="cell-text first">
                <label>Ethnic Background</label>
                <textarea name="ethnicity"></textarea>
            </div>
        </div>
        <div class="row-text">
            <div class="cell-text first">
                <label>Law Interests</label>
                <textarea name="lawInterests"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="cell-small first">
                <label>Role</label>
                <input name="role" />
            </div>
        </div>
        <div class="row">
            <div class="cell first">
                <label>Badge Name</label>
                <input name="badgeName" />
            </div>
        </div>
        <div class="row">
            <div class="cell-small first">
                <label style="width:500px;">Is your attendance at our programs contingent upon receipt of a scholarship?</label>
                <input name="haveScholarship" />
            </div>
        </div>
        <div class="row">
            <div class="cell-small first">
                <label style="width:500px;">Will you require handicap access to facilities or assistance at our programs?</label>
                <input name="requireAccessibility" />
            </div>
        </div>
        <div class="row" id="bar_row">
            <div class="cell first">
                <label>Bar ID</label>
                <input name="barId[]" />
            </div>
            <div class="cell-med second-bar">
                <label>State</label>
                <input name="state[]" />
            </div>
            <div class="cell-tiny third-bar">
                <label>Date</label>
                <input name="month[]" />
            </div>
            <div class="cell-tiny fourth-bar">
                <label>&nbsp;</label>
                <input name="year[]" />
            </div>
        </div>
        <div class="row" id="bar_add">
            <div class="cell first add-bar" onclick="addBarRow();">
                Add another Bar ID
            </div>
        </div>
    </div>
</div>