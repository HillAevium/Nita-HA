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
    <?php if(isset($tabPageTitle)): ?>
    <h1 class="page_title"><?php echo $tabPageTitle; ?></h1>
    <div class="gray_line"></div>
    <?php endif; ?>
    <div class="<?php echo $class['tab_panel_class']; ?>" id="tab_panel">
        <ul class="tabs">
        <?php foreach($tabs as $tab): ?>
            <li id="tab">
                <div id='<?php echo $tab['id']; ?>'>
                    <span>
                        <a id="<?php echo $tab['id']; ?>" href="<?php echo $tab['href']; ?>"><?php echo $tab['name']; ?></a>
                    </span>
                    <div></div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        
        <div class="panes">
            <?php foreach($tabs as $tab): ?>
            <div id='<?php echo $tab['id']; ?>' class="border">
                <?php
                if(isset($tab['content'])) {
                    echo $tab['content'];
                }
                ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div> <!-- #tab_panel -->
    <div class="clear"></div>