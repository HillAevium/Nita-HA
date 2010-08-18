<div class='tab_content_container' id="programs_materials">
    <h3>Course Materials<div></div></h3>
    <div class="content_block">
    <ul>
        <?php
        $aMaterials = explode("|",$model->materials);
        foreach($aMaterials as $material) {
        echo "<li>$material</li>";
        }
        ?>
    </ul>
    </div>
</div>