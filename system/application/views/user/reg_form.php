<script type="text/javascript">
$(document).ready(function() {
    $("form").submit(function() { return false; });
    
    $("#register").click(function() {
        // FIXME
        // Remove for production
        addTestValues();
        renderPreFirmForm();
        return false;
    });
    
    $("#isAttendingClasses").change(function(event) {
        switch($(event.target).val()) {
        case '1':
            $("#instructions").text("Firm info optional");
            $(".isAttendingDependant").hide();
            $('input[name="userType"]').val('individual');
            $("#page_title").html('Create A New Individual Account');
            break;
        case '0':
            $("#instructions").text("Firm info required");
            $(".isAttendingDependant").show();
            $('input[name="userType"]').val('group');
            $("#page_title").html('Create A New Group Account');
            break;
        }
        renderFirmForm();
    });
    renderLogin();
});
</script>
    <div id="content_reg_form" style="display:none;">
        <h1 class="page_title" id="page_title"></h1>
        <div class="gray_line"></div>
        <div id="forms_container">
            <div id="error_container"></div>
            <div id="response_message"></div>
            <div id="instructions"></div>
            <div id="user_type_box">
                <div class="header_bar_blue_full">
                    <table class="header">
                        <tr>
                            <td style="width:484px;" class="pad">Your Account Type</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div id="user_type" style="padding:25px; 0px;">
                    <label style="margin:25px 0px;">Will you be attending Nita programs?</label>
                    <select id="isAttendingClasses" name="isAttendingClasses">
                        <option>Choose...</option>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>
            <form id="firm_form" name="firm_form" action="/account/register/form/firm" method="POST">
                <?php echo $firmForm; ?>
            </form>
            <form style="display:none;" id="profile_form" name="profile_form" action="/account/register/form/profile" method="POST">
                <?php echo $profileForm; ?>
            </form>
        </div>
        <div class="gray_line"></div>
        <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
            <a id="back" class="button_continue" style="position:absolute; top:34px; left:13px;" href=""></a>
            <a id="continue" class="button_continue" style="position:absolute; top:34px; right:13px;" href=""></a>
        </div>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
