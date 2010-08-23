<script type="text/javascript">
$(document).ready(function() {
    $("form").submit(function() { return false; });
    $('#group').click(selectRegType);
    $('#individual').click(selectRegType);
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
