    <div id="content_main_inner">
        <table class="product_list">
            <thead>
                <tr>
                    <td width="80%">Publication Title <a class="ordered_desc" href=""></a></td>
                    <td width="15%">Price</td>
                    <td width="5%"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($models as $model): ?>
                <tr>
                    <td><?php echo $model->name; ?></td>
                    <td>$<?php echo $model->price; ?></td>
                    <td valign="middle"><a class="add_to_cart" href=""></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> <!-- #content_main_inner -->