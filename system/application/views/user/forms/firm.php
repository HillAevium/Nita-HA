<?php if(isset($account)): ?>
<?php //echo "<pre>".print_r($account,true)."</pre>";die(); ?>
<?php
$a = json_encode($account);
echo <<<JS
<script type="text/javascript">$(document).ready(function(){injectFirm($a);})</script>;
JS;
endif;
?>
<script type="text/javascript">
function injectFirm(a) {
    $('#firm_form input[name="name"]').val(a.name);
    $('#firm_form input[name="phone1"]').val(a.phone1);
    $('#firm_form input[name="fax"]').val(a.fax);
    $('#firm_form input[name="billingAddress1"]').val(a.billingAddress1);
    $('#firm_form input[name="billingCity"]').val(a.billingCity);
    $('#firm_form input[name="billingState"]').val(a.billingState);
    $('#firm_form input[name="billingZip"]').val(a.billingZip);
    $('#firm_form input[name="shippingAddress1"]').val(a.shippingAddress1);
    $('#firm_form input[name="shippingCity"]').val(a.shippingCity);
    $('#firm_form input[name="shippingState"]').val(a.shippingState);
    $('#firm_form input[name="shippingZip"]').val(a.shippingZip);
    $('#firm_form input[name="firmSize"]').val(a.firmSize);
    $('#firm_form input[name="practiceType"]').val(a.practiceType);
    $('#firm_form input[name="trainingDirector"]').val(a.trainingDirector);
}
</script>



<!-- FIXME - These form fields are required by the validator.
If they are going to be omitted from the form and perhaps
calculated based on the State/Province then we need to change
the definition.
 -->
<input type="hidden" name="shippingCountry" value="USA" />
<input type="hidden" name="billingCountry" value="USA" />

<input type="hidden" name="userType" value="" />

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
    <div class='row'>
        <div class="cell first">
            <label>Company Name*</label><br />
            <input name="name" />
        </div>
        <div class="cell second">
            <label>Primary Phone Number</label><br />
            <input name="phone1" />
        </div>
        <div class="cell" style="left:650px;">
            <label>Fax</label><br />
            <input name="fax" />
        </div>
    </div>
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
    <div class="row">
        <div class="cell first">
            <label>Firm Size</label>
            <input name="firmSize" />
        </div>
        <div class="cell-small second">
            <label>Type of Practice</label>
            <input name="practiceType" />
        </div>
        <div class="cell third">
            <label>Training Director</label>
            <input name="trainingDirector" />
        </div>
    </div>
</div>