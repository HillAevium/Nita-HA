<div id="content_main">
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
    <tbody>
<?php foreach($programs as $program): ?>
      <tr>
        <td>
          <?php echo "<a href='/main/program/{$program->id}'>{$program->name}</a>"?>
        </td>
        <td><?php echo $program->programDates?></td>
        <td><?php echo $program->location . '<br/>' . $program->city; ?></td>
        <td><?php echo $program->price; ?></td>
      </tr>
<?php endforeach; ?>
    </tbody>
  </table>
  <div class="pagination"><?php echo $pagination; ?></div>
</div> <!-- #content_main -->
