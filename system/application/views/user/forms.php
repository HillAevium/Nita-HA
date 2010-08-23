    <div id="content_reg_funnel">
        <h1 class="page_title">Sign In</h1>
        There are 3 accounts already setup for each of the user types.<br />
        Super User: super@test.com<br />
        Normal User: normal@test.com<br />
        Child User: child@test.com<br />
        Password for all 3: 111111
        <div class="gray_line"></div>
        <form id="login_form" name="login_form" action="/account/login" method="POST">
        <div id="error_container"></div>
        <div id="response_message"></div>
        <table width="100%">
            <tr>
                <td style="width:260px; vertical-align:middle; font-size:11pt; text-transform:uppercase; letter-spacing:0.5pt;">I'm a <span class="blue">returning customer</span></td>
                <td align="left" style="vertical-align:middle;">
                    <label class="light">Username:</label>&nbsp;&nbsp;<input name="email" class="extra_small" />
                </td>
                <td style="vertical-align:middle;">
                    <label class="light">Password:</label>&nbsp;&nbsp;<input name="password" type="password" class="extra_small" />
                </td>
                <td align="right" style="padding:22px 0;">
                    <a id="login_submit" class="button_sign_in" href="" ></a>
                </td>
            </tr>
        </table>
        </form>
        <div class="gray_line"></div>
        <h1 class="page_title">Create an Account</h1>
        <div class="gray_line"></div>
        <div class="reg_funnel">
            <a id="group" class="button_group" href=""></a>
            <a id="individual" class="button_individual" href=""></a>
        </div>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
