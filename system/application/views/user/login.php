    <div id="content_main_inner">
        <h1 class="page_title">Sign In / Create Account</h1>
        <div class="gray_line"></div>
        <form id="login_form" name="login_form" action="/account/login" method="POST">
        <div id="error_container"></div>
        <div id="response_message"></div>
        <table width="100%">
            <tr>
                <td style="width:260px; vertical-align:middle; font-size:11pt; text-transform:uppercase; letter-spacing:0.5pt;">I'm a <span class="blue">returning customer</span></td>
                <td align="left" style="vertical-align:middle;">
                    <label class="light">Username:</label>&nbsp;&nbsp;<input name="username" class="extra_small" />
                </td>
                <td style="vertical-align:middle;">
                    <label class="light">Password:</label>&nbsp;&nbsp;<input name="password" type="password" class="extra_small" />
                </td>
                <td align="right" style="padding:22px 0;">
                    <div class="button_sign_in" onclick="$('#login_form').submit();"></div>
                </td>
            </tr>
        </table>
        </form>
        <div class="gray_line"></div>
        <table width="100%">
            <tr>
                <td style="width:260px; vertical-align:middle; font-size:11pt; text-transform:uppercase; letter-spacing:0.5pt; ">I'm a <span class="blue">new customer</span></td>
                <td align="left" style="padding:19px 0;">
                    <div class="button_create_account" onclick="window.location='/account/register'"></div>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <div class="gray_line"></div>
    </div> <!-- #content_main_inner -->
