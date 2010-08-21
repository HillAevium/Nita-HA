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
                <span style="position:absolute;right:0px;bottom:0px;font-size:13px;">Signed in as <?php echo $this->mod_auth->credentials->user['name']; ?><br /><a href="/account/logout">Sign Out</a></span>
                <?php else: ?>
                <span style="position:absolute;right:0px;bottom:0px;font-size:13px;">Have an account?<br /><a href="/MyAccount">Login</a></span>
                <?php endif; ?>
            </div>
            <div class="gray_line"></div>
            <div id="billing_container" style="display:none;">
                <?php echo $this->load->view('cart/billing', array('info' => $info), true); ?>
            </div>
            <div id="cart_container">
                <?php echo $this->load->view('cart/list', array('spaces' => $spaces, 'display' => $display), true); ?>
            </div>
            
            <div class="gray_line"></div>
            <?php if($display == 'multi'): ?>
            <div id="add_profile" style="float:left;margin:10px 0;" onclick="controller.addProfile();">Add New Profile +</div>
            <?php endif; ?>
            
            <!-- TODO - Fix buttons -->
            <?php if($button == 'login'): ?>
            <div id="login" class="button_generic" style="margin-left:5px;">Login</div>
            <div id="register" class="button_generic">Register</div>
            <?php else: ?>
            <div id="review" class="button_generic">Review</div>
            <div id="billing" class="button_generic" style="display:none;">Billing</div>
            <div id="finish" class="button_generic" style="display:none; margin-left:5px;">Finish</div>
            <div id="display" class="button_generic" style="display:none;">Cart</div>
            <div id="print" class="button_generic" style="display:none;">Print</div>
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