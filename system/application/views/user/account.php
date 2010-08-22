<?php
// For a multi-user load the profile information
// into a JSON object so it can be injected into
// the form
if($display == 'multi') {
    $userProfiles = json_encode($userProfiles);
    echo <<<JS

<script type="text/javascript">
var profiles = $userProfiles;
</script>
JS;
}
?>

<script type="text/javascript" src="/resources/js/profile.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    controller = new ProfileController({
        buttonBar: "#button_bar",
        container: "#panels",
        speed: '350',
        trigger: 'mouseenter',
        panels: [
            {button: "#account", pane: "#account_pane"},
            <?php if($display == 'single'): ?>
            {button: "#cle", pane: "#cle_pane"},
            <?php endif; ?>
            {button: "#orders", pane: "#order_pane"}
        ]
    });
    controller.go();
});
</script>
<style>
/*
#content_main_inner { background:#000; border:1px solid #fff; color:#fff; }
#button_bar { border:1px solid #0f0; }
#button_bar div { border:1px solid #f00; }
#panels { border:1px solid #00f; }
#panels div { border:1px solid #f00; }
*/
#button_bar { position:relative; height:35px; }

#button_bar div#account { position:absolute; top:5px; bottom:5px; left:5px; width:120px; }
<?php if($display == 'single'): ?>
#button_bar div#cle     { position:absolute; top:5px; bottom:5px; left:130px; width:100px; }
#button_bar div#orders  { position:absolute; top:5px; bottom:5px; left:235px; width:150px; }
<?php else: ?>
#button_bar div#orders  { position:absolute; top:5px; bottom:5px; left:130px; width:150px; }
<?php endif; ?>
#button_bar div { background:#eee; color:#000; text-align:center; padding-top:2px; }
#button_bar div:hover { background:#222; color:#fff; cursor:pointer; }
#button_bar div.current { background:#ddd; color:#000; cursor:default; }
#panels { min-height:400px; }
#panels h2 { height:35px; font-size:20px; padding-top:5px; }
div.info_box { padding:15px; background:#ddd; margin-bottom: 5px; }

</style>
<div id="content_main_inner">
    <div style="position:relative;width:100%;height:80px;">
        <h1 style="position:absolute;" class="page_title"><?php echo $title; ?></h1>
        <?php if($this->mod_auth->isAuthenticated()): ?>
        <span class="auth-text">Signed in as <?php echo $this->mod_auth->credentials->user['name']?><br /><a href="/account/logout">Sign Out</a></span>
        <?php else: ?>
        <span class="auth-text">Have an account?<br /><a href="/MyAccount">Login</a></span>
        <?php endif; ?>
    </div>
    <div id="button_bar">
        <div id="account">Account</div>
        <?php if($display == 'single'): ?>
        <div id="cle">CLE</div>
        <?php endif; ?>
        <div id="orders">Order History</div>
    </div>
    <div class="gray_line"></div>
    <div id="panels">
        <div id="account_pane">
            <?php
            $args['account'] = $account;
            $args['orders'] = $orders;
            if($display == 'multi') {
                $args['userProfiles'] = $userProfiles;
                $args['userOrders'] = $userOrders;
            } else {
                $args['profile'] = $profile;
            }
            echo $this->load->view('user/profile');
            ?>
        </div>
        <?php if($display == 'single'): ?>
        <div id="cle_pane">
            <h2>CLE Credits</h2>
            <?php foreach($profile->bar as $bar): ?>
            <div class="info_box">
                <p>
                <?php
                $br = "<br />";
                echo $bar['state'] . $br;
                echo "Bar ID: " . $bar['barId'] . $br;
                echo "Date: " . $bar['month'] . " " . $bar['year'] . $br;
                echo "Credits: " . $bar['cle'] . $br;
                ?>
                </p>
            </div>
            <div class="gray_line"></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div id="order_pane">
            <?php echo $this->load->view('user/orders', array('orders' => $orders), true); ?>
        </div>
    </div>
    <div id="forms_container" style="display:none;">
        <div id="error_container"></div>
        <div id="response_message"></div>
        <div id="instructions"></div>
        <form style="display:none;" id="firm_form" name="firm_form" action="/account/firm" method="POST">
            <?php echo $firmForm; ?>
        </form>
        <form style="display:none;" id="profile_form" name="profile_form" action="/account/profile" method="POST">
            <?php echo $profileForm; ?>
        </form>
        <form style="display:none;" id="bar_form" name="bar_form" action="/account/bar" method="POST">
            <?php //echo $barForm; ?>
        </form>
        <div class="gray_line"></div>
        <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
            <div id="submit_form" class="button_continue" style="position:absolute; top:34px; right:13px;"></div>
        </div>
        <div class="gray_line"></div>
    </div>
</div>