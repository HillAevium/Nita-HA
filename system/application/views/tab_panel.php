<?php
/*
 |-------------------------------------------------------+
 |               Data structures expected                |
 |-------------------------------------------------------+
 |
 | array $tabs - an alement for each tab in the panel
 |               containing an array of two strings. One
 |               for the display name to be shown on the
 |               page, the other for the system name to
 |               use in the ajax call. There is also an
 |               optional content element that will be
 |               injected into the pane if it is supplied.
 |               The names are 'displayName', 'systemName',
 |               and 'content'.
 |
 | array $class - the css class names to use. these
 |                classes control the color of the tab
 |                background and border. The array uses
 |                two names, 'tabs' and 'border'.
 |
 |-------------------------------------------------------+
 */
?>

<div class="tab_panel" id="tab_panel">
  <ul class="tabs">
    <?php foreach($tabs as $tab): ?>
      <li id="tab">
        <div class='<?php echo $class['tabs']; ?>'
             id='<?php echo $tab['id']; ?>'>
          <?php echo $tab['name']; ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  
  <div class="panes">
    <?php foreach($tabs as $tab): ?>
      <div class='<?php echo $class['border']; ?>'
           id='<?php echo $tab['id']; ?>'>
        <?php
        if(isset($tab['content'])) {
            echo $tab['content'];
        }
        ?>
      </div>
    <?php endforeach; ?>
  </div>
</div> <!-- #tab_panel -->