<div class="header_bar_blue_full">
    <table class="header">
        <tr>
            <td style="width:484px;" class="pad">Profile Information</td>
            <td>Login Information</td>
        </tr>
    </table>
</div>
<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="15%">
            <label>Salutation</label><br />
            <select name="salutation">
                <option value="Mr.">Mr.</option>
                <option value="Mrs.">Mrs.</option>
            </select>
        </td>
        <td width="35%">
            <label>First Name</label><br />
            <input name="firstName" />
        </td>
        <td width="15%">
            <label>M.I.</label><br />
            <input name="middleInitial" />
        </td>
        <td width="35%">
            <label>Last Name</label><br />
            <input name="lastName" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="15%">
            <label>Suffix</label><br />
            <select name="suffix">
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
            </select>
        </td>
        <td width="85%">
            <label>Title</label><br />
            <input name="title" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="25%">
            <label>Email Address (User ID):</label><br />
            <input name="email" />
        </td>
        <td width="25%">
            <label>Password:</label><br />
            <input name="password" type="password" />
        </td>
        <td width="50%">
            <label>Verify Password:</label><br />
            <input name="password2" type="password" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td width="25%">
            <label>Primary Phone</label><br />
            <input name="phone" />
        </td>
        <td width="75%">
            <label>Secondary Phone</label><br />
            <input name="phone2" />
        </td>
    </tr>
</table>

<table style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Will you be attending NITA programs?</label><br />
            <select id="isAttendingClasses" name="isAttendingClasses" onchange="toggleIsAttendingDependentFields();">
                <option value="0">No</option>
                <option value="1" selected="selected">Yes</option>
            </select>
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
    <tr>
        <td colspan="4">
            <input name="_sameAsFirmShipping" type="checkbox" /> Same as Firm's Shipping Address
        </td>
    </tr>
</table>

<table class="isAttendingDependent" style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Ethnic Background:</label><br />
            <select name="ethnicity">
                <option value="White/Caucasian">White/Caucasian</option>
            </select>
        </td>
    </tr>
</table>

<table class="isAttendingDependent" style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Law Interests:</label><br />
            <select name="lawInterests">
                <option value="Medical Malpractice">Medical Malpractice</option>
            </select>
        </td>
    </tr>
</table>

<table class="isAttendingDependent" style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Role:</label><br />
            <select name="role">
                <option value="Role Option 1">Role Option 1</option>
            </select>
        </td>
    </tr>
</table>

<table class="isAttendingDependent" style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Badge Name:</label><br />
            <input name="badgeName" />
        </td>
    </tr>
</table>

<table class="isAttendingDependent" style="width:941px;margin-bottom:18px;">
    <tr>
        <td>
            <label>Will you require handicap access to facilities or assistance at our programs?</label><br />
            <input name="requireAccessibility" type="radio" value="0" /> No
            <input name="requireAccessibility" type="radio" value="1" checked="checked"/> Yes
        </td>
    </tr>
</table>

<div class="header_bar_blue_full isAttendingDependent">
    <table class="header">
        <tr>
            <td style="width:484px;" class="pad">Bar Information</td>
        </tr>
    </table>
</div>

<div class="isAttendingDependent" style="position:relative;">
    <table id="bar_info" style="width:941px;margin-bottom:18px;">
        <tr id="bar_row">
            <td style="width:242px;">
                <label>Bar ID</label><br />
                <input name="barId[]" />
            </td>
            <td style="width:242px;">
                <label>State</label><br />
                <select name="state[]">
                    <option value="CA">CA</option>
                    <option value="NY">NY</option>
                    <option value="WA">WA</option>
                </select>
            </td>
            <td>
                <label>Date</label><br />
                <input name="date[]">
                <!--
                <select name="dateMonth[]">
                    <option value="0"></option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                </select>
                <select name="dateYear[]">
                    <option value="0"></option>
                    <option value="1980">1980</option>
                    <option value="1981">1981</option>
                    <option value="1982">1982</option>
                    <option value="1983">1983</option>
                    <option value="1984">1984</option>
                </select>
                -->
            </td>
        </tr>
    </table>
    <div style="position:absolute;bottom:10px;right:10px;" id="add_another_bar" onclick="addBarRow();">Add another</div>
</div>
