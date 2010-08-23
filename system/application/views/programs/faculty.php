<div class='tab_content_container' id="programs_faculty">
    <h3>Faculty Members<div></div></h3>
    <div class="content_block">
    <ul>
    <?php
    $aFaculty = explode("|",$model->faculty);  
    if(is_array($aFaculty) && count($aFaculty) > 0 && $aFaculty[0] != "") {
        foreach($aFaculty as $faculty) {
            echo "<li>$faculty</li>";
        }
    } else {
        echo "Information pending.";
    }
    ?>
   </ul>
   </div>
</div>