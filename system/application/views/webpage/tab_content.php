<?php if(isset($model->childPages)): ?>
    <div class="grandchild_tab_panel">
        <ul class="grandchild_tabs">
        <?php foreach($model->childPages as $tab): ?>
            <li><a href="#<?php echo $tab->nita_nav_name; ?>"><?php echo $tab->nita_page_name; ?><div></div></a></li>
        <?php endforeach; ?>
        </ul>
        
        <?php foreach($model->childPages as $tab): ?>
        <div class="tab_content_container" id="<?php echo $tab->nita_nav_name; ?>">
            <?php
            echo preg_replace("#style=\"[^\"]*\"#","",$tab->nita_page_text);
            ?>
        </div>
        <?php endforeach; ?>
        
    </div> <!-- #tab_panel -->
<?php endif; ?>
<div class="tab_content_container" id="programs_materials">
    <?php echo preg_replace("#style=\"[^\"]*\"#","",$model->nita_page_text); ?>
</div>