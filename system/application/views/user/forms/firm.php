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
.row        { position:relative; width:100%; height:55px; margin-top:25px; }
.row-text   { position:relative; width:100%; height:250px; margin-top:25px; }
.cell       { position:absolute; height:100%; width:290px; }
.cell-med   { position:absolute; height:100%; width:230px; }
.cell-small { position:absolute; height:100%; width:180px; }
.cell-tiny  { position:absolute; height:100%; width:120px; }
.cell-text  { position:absolute; height:100%; width:400px; }

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

#form_container label    { position:absolute; top:0px; left:0px; font-size:12px; }
#form_container input    { position:absolute; top:30px; left:0px; width:100%; font-size:15px; }
#form_container select   { position:absolute; top:30px; left:0px; width:100%; font-size:15px; }
#form_container textarea { position:absolute; top:30px; left:0px; width:100%; font-size:15px; height:220px; }
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