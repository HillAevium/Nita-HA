<div id="content_top"></div>
<div id="content_main">
    <div id="content_main_inner">
        <div id="breadcrumb"></div>
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->    
        <table class="program_list_header">
            <tr>
                <td width="25%" style="border-right:1px solid #ddd;">Program Title <a class="ordered_desc" href=""></a></td>
                <td width="18%" style="border-right:1px solid #ddd;">Type <a class="ordered_desc" href=""></a></td>
                <td width="18%" style="border-right:1px solid #ddd;">Dates <a class="ordered_desc" href=""></a></td>
                <td width="19%" style="border-right:1px solid #ddd;">Location <a class="ordered_desc" href=""></a></td>
                <td width="15%">Price</td>
                <td width="5%"></td>
            </tr>
        <table class="program_list" style="border-top:1px solid #ddd;">
            <?php foreach($programs as $program): ?>
            <tr>
                <td width="25%">
                    <?php echo "<a href='/shop/program/{$program->id}'>{$program->name}</a>"?>
                </td>
                <td width="18%"><?php echo $program->type; ?></td>
                <td width="18%"><?php echo $program->programDates; ?></td>
                <td width="19%"><?php echo $program->location . '<br/>' . $program->city; ?></td>
                <td width="15%">$<?php echo $program->price; ?></td>
                <td width="5%" valign="middle"><a class="add_to_cart" href=""></a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <!-- <div class="pagination"><?php echo $pagination; ?></div> -->
    </div> <!-- #content_main_inner -->
</div> <!-- #content_main -->
