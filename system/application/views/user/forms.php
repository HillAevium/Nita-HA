    <div id="content_reg_funnel">
        <h1 class="page_title">Sign In</h1>
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
                    <div id="submit_form" class="button_sign_in" onclick="$('#login_form').submit();"></div>
                </td>
            </tr>
        </table>
        </form>
        <div class="gray_line"></div>
        <h1 class="page_title">Create an Account</h1>
        <div class="gray_line"></div>
        <table class="reg_funnel">
            <tr>
                <td align="left">
                    <div id="group">
                        <img src="/resources/images/reg_funnel_button_group.png" alt="Group" title="Group" />
                    </div>
                </td>
                <td align="right">
                    <div id="individual">
                        <img src="/resources/images/reg_funnel_button_individual.png" alt="Individual" title="Individual" />
                    </div>
                </td>
            </tr>
        </table>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
