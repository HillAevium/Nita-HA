    <div id="content_top">
        <div class="image" style="background:url(/resources/images/<?php echo $topbox['image']; ?>)"></div>
        <div class="summary">
            <?php if(isset($model->price)): ?>
            <div id="<?php echo $model->id; ?>" class="program_add_to_cart">
                <div id="cart_item" style="position:absolute;bottom:0;left:60px;color:#333;width:100px;height:15px;background:none;line-height:15px;font-size:9px;text-align:left;"></div>
                <div class="program_details_add_to_cart" style="float:left;width:130px;height:39px;cursor:pointer;" onclick="handleCartItemClick('<?php echo $model->id; ?>');"></div>
                <div class="program_price">$<?php echo $model->price; ?></div>
            </div>
            <?php endif; ?>
            <h1><?php echo $topbox['title']; ?></h1>
            <div class="hrule"></div>
            <div class="content"><?php echo $topbox['content']; ?></div>
        </div>
    </div>