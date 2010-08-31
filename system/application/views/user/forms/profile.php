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
<script type="text/javascript">
$(document).ready(function() {
    $(".row :input , .row-text :input").focus(animateMasks);
});

function animateMasks(event) {
    $('.cell-mask,.row-mask').show();
    var cell = $(this).parent().offset();
    var offset = $(this).parents("#form_container").offset();
    var cell_position = {
        top: (cell.top - offset.top),
        left: (cell.left - offset.left) - 5,
        width: $(this).parent().width(),
        height: $(this).parent().height()
    };
    var row_position = {
        top: cell_position.top,
        height: cell_position.height
    };
    $('.cell-mask').animate(cell_position,300,'swing');
    $('.row-mask').animate(row_position,300,'swing');
    $(this).blur(function() {
        $('.cell-mask,.row-mask').hide();
    });
}

</script>

<style type="text/css">
/* Wireframing
#form_container { background:#000; color:#fff; border:1px solid #fff; };
#form_container div { border:1px solid #00f; }
#form_container label { color:#fff; background:#090; }
.row { background:#900; }
.row-text { background:#900; }
.cell { background:#00f; }
.cell-med { background:#00f; }
.cell-small { background:#00f; }
.cell-tiny { background:#00f; }
.cell-text { background:#00f; }
/**/


#form_container { position:relative; }
.row        { position:relative; width:100%; height:65px; margin-top:15px; }
.row-text   { position:relative; width:100%; height:260px; margin-top:15px; }
.cell       { position:absolute; height:100%; width:290px; }
.cell-med   { position:absolute; height:100%; width:230px; }
.cell-small { position:absolute; height:100%; width:180px; }
.cell-tiny  { position:absolute; height:100%; width:120px; }
.cell-text  { position:absolute; height:100%; width:400px; }

.current-row { background:#e4f0ef; border:2px solid #5b6be3; }
.current-field { background:#d3ebe9; border-left:2px solid #5b6be3; border-right:2px solid #5b6be3; }
.cell-mask { position:absolute; top:0px; left:0px; width:100px; height:65px; background:#ccdbe7; border:1px solid #427aa5; display:none; z-index:2; }
.row-mask  { position:absolute; top:0px; left:0px; width:100%; height:65px; background:#e5edf3; border:1px solid #7fa5c2; display:none; z-index:1; }

.first { left:5px; }
.second { left:330px; }
.third { left: 540px; }
.fourth { left: 760px; }

.second-name { left:160px; }
.third-name  { left:485px; }
.fourth-name { left:640px; }

.second-pass { left:270px; }
.third-pass  { left:535px; }

.second-bar  { left:330px; }
.third-bar   { left:595px; }
.fourth-bar  { left:730px; }

#form_container label    { position:absolute; top:5px; left:0px; font-size:12px; z-index:3; line-height:15px; }
#form_container input    { position:absolute; top:35px; left:0px; width:95%; font-size:15px; z-index:3; }
#form_container select   { position:absolute; top:35px; left:0px; width:95%; font-size:15px; z-index:3; }
#form_container textarea { position:absolute; top:35px; left:0px; width:95%; font-size:15px; height:220px; z-index:3; }
</style>

<!-- FIXME - These form fields are required by the validator.
If they are going to be omitted from the form and perhaps
calculated based on the State/Province then we need to change
the definition.
 -->
<input type="hidden" name="shippingCountry" value="USA" />
<input type="hidden" name="billingCountry" value="USA" />
<input type="hidden" name="isAttendingClasses" value="1" />

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