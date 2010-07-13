    <div id="content_main_inner">
        <table class="program_list_header">
            <tr>
                <td width="25%" style="border-right:1px solid #ddd;">Program Title <a class="ordered_desc" href=""></a></td>
                <td width="18%" style="border-right:1px solid #ddd;">Type <a class="ordered_desc" href=""></a></td>
                <td width="18%" style="border-right:1px solid #ddd;">Dates <a class="ordered_desc" href=""></a></td>
                <td width="19%" style="border-right:1px solid #ddd;">Location <a class="ordered_desc" href=""></a></td>
                <td width="15%">Price</td>
                <td width="5%"></td>
            </tr>
	    </table>
        <table class="program_list" style="border-top:1px solid #ddd;" id="items">
            <?php foreach($models as $model): ?>
            <tr id="<?php echo $model->id; ?>">
                <td width="25%"><?php echo $model->name; ?></td>
                <td width="18%"><?php echo $model->type; ?></td>
                <td width="18%"><?php echo $model->programDates; ?></td>
                <td width="19%"><?php echo $model->location . '<br/>' . $model->city; ?></td>
                <td width="15%">$<?php echo $model->price; ?></td>
                <td width="5%" valign="middle"><div class="add_to_cart" id="cart_item"></div></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div> <!-- #content_main_inner -->
