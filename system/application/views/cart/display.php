<?php
// TODO
// Make the id of each selection the rowid
// from the cart to make the lookup easier
// on the ajax submit.
$spaces[] = 4;
foreach($this->cart->contents() as $item) {
    //TODO $space[] = $item['space'];
    $spaces[] = 4;
}

$params = json_encode(array(
    'init' => ($button != 'login'),
    'display' => $display,
    'titles' => $titles,
    'spaces' => $spaces
));

$profiles = json_encode($profiles);

echo <<<JS

<script type="text/javascript">
    var profiles = $profiles;
    var params = $params;
</script>
JS;

?>

    <div id="content_main_inner">
        <div id="cart_pane">
            <div style="position:relative;width:100%;height:80px;">
                <h1 style="position:absolute;" class="page_title"><?php echo $titles['cart']; ?></h1>
                <?php if($this->mod_auth->isAuthenticated()): ?>
                <span class="auth-text">Logged in as <?php echo $this->mod_auth->credentials->user['name']; ?><br /><a href="/account/logout">Sign Out</a></span>
                <?php else: ?>
                <span class="auth-text">Have an account?<br /><a href="/MyAccount">Login</a></span>
                <?php endif; ?>
            </div>
            <div class="gray_line"></div>
            <div id="billing_container" style="display:none;">
                <?php echo $this->load->view('cart/billing', array('info' => $info), true); ?>
            </div>
            <div id="cart_container">
                <?php echo $this->load->view('cart/list', array('spaces' => $spaces, 'display' => $display), true); ?>
            </div>

            <?php if($display == 'multi'): ?>
            <a id="add_profile" href="" style="float:left;margin:20px 0;"></a>
            <?php endif; ?>
            
            <!-- TODO - Fix buttons -->
            <?php if($button == 'login'): ?>
            <a class="nav_button button_sign_in" href="/MyAccount"></a>
            <?php else: ?>
            <a id="back" class="nav_button button_continue" href="" style="display:none; float:left; margin-left:0px;"></a>
            <a id="continue" class="nav_button button_continue" href=""></a>
            <a id="print" class="nav_button button_continue" href="" style="display:none;"></a>
            <?php endif; ?>
            <div class="clear"></div>
        </div>
        <?php if($display == 'multi'): ?>
        <div id="forms_container" style="display:none;">
            <div id="error_container"></div>
            <div id="response_message"></div>
            <div id="instructions"></div>
            <form style="display:none;" id="profile_form" name="profile_form" action="/account/profile_add" method="POST">
                <?php echo $profileForm; ?>
            </form>
            <div class="gray_line"></div>
            <div style="position:relative; width:100%; height:82px; background:#f8f8f8; margin:1px 0;">
                <div id="submit_form" class="button_continue" style="position:absolute; top:34px; right:13px;"></div>
            </div>
            <div class="gray_line"></div>
        </div>
        <?php endif; ?>
    </div>