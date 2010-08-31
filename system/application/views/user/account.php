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
        trigger: 'click',
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
#button_bar { position:relative; height:35px; font-size:13px; font-weight:bold;}

#button_bar span { color:#0b73bc; text-align:center; padding-top:2px; cursor:pointer; }
#button_bar span.current { color:#545454; cursor:pointer; }
#panels { min-height:400px; }
#panels h2 { height:25px; font-size:16px; padding:15px 0 0 12px; color:#0f4964; }
div.info_box { padding:0px 6px 15px 12px; margin-bottom:5px; font-size:12px; line-height:16px;}
div.info_box p { font-size:12px; line-height:16px; }

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
        
        <span id="account">Home</span> |
        <?php if($display == 'single'): ?>
        <span id="cle">View CLE Credits</span> |
        <?php endif; ?>
        <span id="orders">Previous Orders</span>
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
<style>
#cle_pane { padding:0px; margin:0px; }
.cle_row  { padding:0px; margin:20px 0px; width:100%; height:125px; }
.cle_cell { padding:0px; margin:0px 10px 0px 0px; width:175px; height:115px; float:left; }
#cle_pane p { font-size:12px; padding:15px 0px 25px 20px; margin:0px; }
.cle_cell { background:#d3ebe9; border:2px solid #5b6be3; }
</style>
        <div id="cle_pane">
            <h2>CLE Credits</h2>
            <?php $rows = count($profile->bar) / 6; ?>
            <?php for($i = 0; $i < 2; $i++): ?>
            <div class="cle_row">
            <?php foreach($profile->bar as $bar): ?>
                <div class="cle_cell">
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
            <?php endforeach; ?>
            </div>
            <?php endfor; ?>
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
            <a id="continue" class="button_continue" style="position:absolute; top:34px; right:13px;" href=""></a>
            <a id="back" class="button_back" style="position:absolute; top:34px; left:13px;" href=""></a>
        </div>
        <div class="gray_line"></div>
    </div>
</div>