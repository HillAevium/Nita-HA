    <div id="content_main_inner">
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->
        <table id="items" class="program_list">
            <thead>
                <tr>
                    <td width="25%" style="border-right:1px solid #ddd;">Program Title <a class="ordered_desc" href=""></a></td>
                    <td width="18%" style="border-right:1px solid #ddd;">Type <a class="ordered_desc" href=""></a></td>
                    <td width="18%" style="border-right:1px solid #ddd;">Dates <a class="ordered_desc" href=""></a></td>
                    <td width="19%" style="border-right:1px solid #ddd;">Location <a class="ordered_desc" href=""></a></td>
                    <td width="15%">Price</td>
                    <td width="5%"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($programs as $program): ?>
                <tr id="<?php echo $program->id; ?>">
                    <td width="25%"><?php echo $program->name; ?></td>
                    <td width="18%"><?php echo $program->type; ?></td>
                    <td width="18%"><?php echo $program->programDates; ?></td>
                    <td width="19%"><?php echo $program->location . '<br/>' . $program->city; ?></td>
                    <td width="15%">$<?php echo $program->price; ?></td>
                    <td width="5%" valign="middle"><div class="add_to_cart" id="cart_item"></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->
    </div> <!-- #content_main_inner -->
