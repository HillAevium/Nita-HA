    <div id="content_reg_funnel">
        <h1 class="page_title">Sign In</h1>
        <div class="gray_line"></div>
        <form id="login_form" name="login_form" action="/account/login" method="POST">
        <div id="error_container"></div>
        <div id="response_message"></div>
<style>
/*
.forms_row { background:#900; }
.forms_row span, .forms_row label, .forms_row input
    { background:#090; }
/**/
.forms_row span, .forms_row label, .forms_row input, .forms_row a
    { position:absolute; top:0px; }
.forms_row { position:relative; width:100%; height:35px; margin:25px 0px; }
.forms_row label { font-size: 15px; top:10px; }
.forms_row input { top:7px; }
.forms_row span  { top:10px; left:10px; }
.forms_row a     { right:10px; }
</style>
        <div style="position:relative; width:100%;">
            <div class="forms_row">
                <span>I'm a returning customer</span>
                <label style="left:250px;" class="light">Username:</label>
                <input style="left:340px;" name="email" class="extra_small" />
                <label style="left:520px;" class="light">Password:</label>
                <input style="left:610px;" name="password" class="extra_small" />
                <a id="login_submit" class="button_sign_in" href=""></a>
            </div>
            <div class="gray_line"></div>
            <div class="forms_row">
                <span>I'm a new customer</span>
                <a id="register" class="button_create_account" href="#1"></a>
            </div>
            <div class="gray_line"></div>
        </div>
        </form>
    </div> <!-- #content_main_inner -->
