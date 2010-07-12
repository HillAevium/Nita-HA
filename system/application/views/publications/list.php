<div id="content_top"></div>
<div id="content_main">
    <div id="content_main_inner">
        <div id="breadcrumb"></div>
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->  
        <table class="product_list">
            <thead>
                <tr>
                    <td width="80%">Publication Title <a class="ordered_desc" href=""></a></td>
                    <td width="15%">Price</td>
                    <td width="5%"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($publications as $pub): ?>
                <tr>
                    <td><?php echo $pub->name; ?></td>
                    <td>$<?php echo $pub->price; ?></td>
                    <td valign="middle"><a class="add_to_cart" href=""></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->
    </div> <!-- #content_main_inner -->
</div> <!-- #content_main -->
