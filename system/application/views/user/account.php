<script type="text/javascript" src="/resources/js/profile.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    controller = new ProfileController({
        buttonBar: "#button_bar",
        container: "#panels",
        speed: '400',
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
    <h1 class="page_title"><?php echo $title; ?></h1>
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
            $args['profile'] = $profile;
            $args['orders'] = $orders;
            if($display == 'multi') {
                $args['userProfiles'] = $userProfiles;
                $args['userOrders'] = $userOrders;
            } else {
                $args['cle'] = 'TODO';
            }
            echo $this->load->view('user/profile');
            ?>
        </div>
        <?php if($display == 'single'): ?>
        <div id="cle_pane">
            <h2>CLE Credits</h2>
            <?php foreach($profile['bar'] as $bar): ?>
            <div class="info_box">
                <p>
                <?php
                $br = "<br />";
                echo $bar['state'] . $br;
                echo "Bar ID: " . $bar['barId'] . $br;
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
</div>