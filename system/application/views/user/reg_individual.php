    <div id="content_main_inner">
        <h1 class="page_title"><?php echo $title; ?></h1>
        <div id="sign_in" class="small">Already have an account? <a href="/account/login">Sign in!</a></div>
        <div class="gray_line"></div>
        <div id="forms_container">
            <div id="error_container"></div>
            <div id="response_message"></div>
            <!--
            <form id="verification_form" name="verification_form" style="display:none;" action="/account/verify" method="POST">
                <?php //echo $verificationForm; ?>
            </form>
             -->
            <form id="registration_form" name="registration_form" action="/account/register/regtype/individual" method="POST">
                <?php echo $registrationForm; ?>
            </form>
        </div>
        <div class="gray_line"></div>
        <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
            <label style="position:absolute; top:16px; left:3px;">Attach a NITA Application</label>
            <input id="" type="file" name="" style="position:absolute; top:36px; left:3px;" />
            <div id="browse" class="button_browse" style="position:absolute; top:36px; left:223px;"></div>
            <div id="continue" class="button_continue" style="position:absolute; top:34px; right:13px;"></div>
        </div>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
