    <div id="content_main_inner">
        <h1 class="page_title"><?php echo $title; ?></h1>
        <div id="sign_in" class="small">Already have an account? <a href="/account/login">Sign in!</a></div>
        <div class="gray_line"></div>
        <div id="forms_container">
            <div id="error_container"></div>
            <div id="response_message"></div>
            <?php if(isset($instructions)): ?>
            <div id="instructions">
               <?php echo $instructions; ?>
            </div>
            <?php endif; ?>
            <form id="firm_form" name="firm_form" action="/account/register/form/firm" method="POST">
                <?php echo $firmForm; ?>
            </form>
            <form style="display:none;" id="profile_form" name="profile_form" action="/account/register/form/profile" method="POST">
                <?php echo $profileForm; ?>
            </form>
        </div>
        <div class="gray_line"></div>
        <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
            <label style="position:absolute; top:16px; left:3px;">Attach a NITA Application</label>
            <input id="" type="file" name="" style="position:absolute; top:36px; left:3px;" />
            <div id="browse" class="button_browse" style="position:absolute; top:36px; left:223px;"></div>
            <div id="submit_form" class="button_continue" style="position:absolute; top:34px; right:13px;" onclick="$('#firm_form').submit();"></div>
        </div>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
