<div class='tab_content_container' id="programs_faculty">
    <h3>Faculty Members<div></div></h3>
    <div class="content_block">
    <ul>
    <?php
    $aFaculty = explode("|",$model->faculty);
    foreach($aFaculty as $faculty) {
    echo "<li>$faculty</li>";
    }
    ?>
   </ul>
   </div>
</div>