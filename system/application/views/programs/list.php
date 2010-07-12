<div id="list_panel">
  <div class="pagination"><?php echo $pagination; ?></div>
  <table class="product_list">
    <thead>
      <tr>
        <td width="40%">Program Title <a class="ordered_desc" href=""></a></td>
        <td width="20%">Dates <a class="ordered_desc" href=""></a></td>
        <td width="20%">Location <a class="ordered_desc" href=""></a></td>
        <td width="15%">Price</td>
        <td width="5%"></td>
      </tr>
    </thead>
    <tbody id="items">
<?php foreach($programs as $program): ?>
      <tr id='<?php echo $program->id; ?>'>
        <td>
            <?php echo $program->name; ?>
        </td>
        <td><?php echo $program->programDates?></td>
        <td><?php echo $program->location . '<br/>' . $program->city; ?></td>
        <td><?php echo $program->price; ?></td>
        <td valign="middle"><div class="add_to_cart" id="cartItem"></div></td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  
  <div class="pagination"><?php echo $pagination; ?></div>
</div>
