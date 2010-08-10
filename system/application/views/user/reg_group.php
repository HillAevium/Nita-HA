    <div id="content_main_inner">
        <h1 class="page_title"><?php echo $title; ?></h1>
        <div id="sign_in" class="small">Already have an account? <a href="/account/login">Sign in!</a></div>
        <div class="gray_line"></div>
        <div class="form">
            <form name="regForm" action="/account/register/regtype/group" method="POST">
                <?php echo $form; ?>
            </form>
        </div>
        <div class="gray_line"></div>
        <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
            <label style="position:absolute; top:16px; left:3px;">Attach a NITA Application</label>
            <input id="" type="file" name="" style="position:absolute; top:36px; left:3px;" />
            <div id="browse" class="button_browse" style="position:absolute; top:36px; left:223px;"></div>
            <div id="add_account" class="button_add_account" style="position:absolute; top:34px; right:119px;" onclick="addAccount();"></div>
            <div id="submit_form" class="button_continue" style="position:absolute; top:34px; right:13px;" onclick="document.forms['regForm'].submit();"></div>
        </div>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
