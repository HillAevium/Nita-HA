    <h2 style="margin-bottom:10px;">Order History</h2>

    
        <?php foreach($orders as $order): ?>
        <div class="info_box">
        <div style="float:left;width:300px;"><?php echo $order['date']; ?></div>
        <div style="float:left;width:300px;"><?php echo join("<br />", $order['programs']); ?></div>
        <div style="float:right;width:300px;padding-right:6px;text-align:right;">$<?php echo $order['price']; ?></div>
        <div class="clear"></div>
        </div>
        <div class="gray_line" style="margin:15px 0;"></div>
        <?php endforeach; ?>
    