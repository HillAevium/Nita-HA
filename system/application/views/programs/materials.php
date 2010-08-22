<div class='tab_content_container' id="programs_materials">
    <h3>Course Materials<div></div></h3>
    <div class="content_block">
    <ul>
        <?php
        
        $aMaterials = explode("|",$model->materials);
        if(is_array($aMaterials) && count($aMaterials) > 0 && $aMaterials[0] != '') {
            foreach($aMaterials as $material) {
                echo "<li>$material</li>";
            }
        } else {
            echo "Information pending.";
        }
        ?>
    </ul>
    </div>
</div>