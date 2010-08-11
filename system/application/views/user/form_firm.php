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
<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="50%">
            <label>Company Name*</label><br />
            <input name="name" />
        </td>
        <td width="25%">
            <label>Primary Phone Number</label><br />
            <input name="phone1" />
        </td>
        <td width="25%">
            <label>Fax</label><br />
            <input name="fax" />
        </td>
    </tr>
</table>
<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="25%">
            <label>Billing Address</label><br />
            <input name="billingAddress1" />
        </td>
        <td width="25%">
            <label>City</label><br />
            <input name="billingCity" />
        </td>
        <td width="25%">
            <label>State</label><br />
            <select name="billingState">
                <option value="CA">CA</option>
                <option value="NY">NY</option>
            </select>
        </td>
        <td width="25%">
            <label>Zip</label><br />
            <input name="billingZip" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="25%">
            <label>Shipping Address (for course materials)<br />No P.O. Boxes please</label><br />
            <input name="shippingAddress1" />
        </td>
        <td width="25%">
            <label>City</label><br />
            <input name="shippingCity" />
        </td>
        <td width="25%">
            <label>State</label><br />
            <select name="shippingState">
                <option value="CA">CA</option>
                <option value="NY">NY</option>
                <option value="WA">WA</option>
            </select>
        </td>
        <td width="25%">
            <label>Zip</label><br />
            <input name="shippingZip" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="25%">
            <label>Firm Size</label>
            <select name="firmSize">
                <option value="1">1</option>
                <option value="2-9">2-9</option>
                <option value="10-19">10-19</option>
                <option value="20-49">20-49</option>
                <option value="50-99">50-99</option>
                <option value="100+">100+</option>
            </select>
        </td>
        <td width="25%">
            <label>Type of Practice</label><br />
            <select name="practiceType">
                <option value="Type 1">Type 1</option>
                <option value="Type 2">Type 2</option>
                <option value="Type 3">Type 3</option>
                <option value="Type 4">Type 4</option>
                <option value="Type 5">Type 5</option>
            </select>
        </td>
        <td width="50%">
            <label>Training Director</label><br />
            <input name="trainingDirector" />
        </td>
    </tr>
</table>
