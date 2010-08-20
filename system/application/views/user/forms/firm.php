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
<!-- FIXME - These form fields are required by the validator.
If they are going to be omitted from the form and perhaps
calculated based on the State/Province then we need to change
the definition.
 -->
<input type="hidden" name="shippingCountry" value="USA" />
<input type="hidden" name="billingCountry" value="USA" />

<input type="hidden" name="userType" value="" />

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
.cell-mask { position:absolute; top:0px; left:0px; width:100px; height:65px; background:#d3ebe9; border:2px solid #5b6be3; display:none; z-index:2; }
.row-mask  { position:absolute; top:0px; left:0px; width:100%; height:65px; background:#e4f0ef; border:2px solid #5b6be3; display:none; z-index:1; }

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

#form_container label    { position:absolute; top:7px; left:5px; font-size:12px; z-index:3; }
#form_container input    { position:absolute; top:35px; left:0px; width:95%; font-size:15px; z-index:3; }
#form_container select   { position:absolute; top:35px; left:0px; width:95%; font-size:15px; z-index:3; }
#form_container textarea { position:absolute; top:35px; left:0px; width:95%; font-size:15px; height:220px; z-index:3; }
</style>
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