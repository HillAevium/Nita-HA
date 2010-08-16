<?php
// TODO
// Make the id of each selection the rowid
// from the cart to make the lookup easier
// on the ajax submit.

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
        <h1 class="page_title"><?php echo $titles['cart']; ?></h1>
        <div class="gray_line"></div>
        <div id="billing_container" style="display:none;">
            <?php echo $this->load->view('cart/billing', array('info' => $info), true); ?>
        </div>
        <div id="cart_container">
            <?php echo $this->load->view('cart/list', array('spaces' => $spaces, 'display' => $display), true); ?>
        </div>
        
        <div class="gray_line"></div>
        <?php if($display == 'multi'): ?>
        <div id="add_profile" style="float:left;margin:10px 0;">Add New Profile +</div>
        <div id="profile_box">
            <h1 class="page_title" style="margin-top:-40px;">Create a New Profile</h1>
            <button class="close">Close</button>
            <!-- TODO Make this form -->
            <?php //echo $this->load->view('user/form_profile_child', null, true); ?>
        </div>
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