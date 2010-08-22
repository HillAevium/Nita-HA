<div class='tab_content_container' id="programs_credits">
  <h3>50 minute Credits<div></div></h3>
    <div class="content_block">
        <?php
        if($model->credits50Min) {
            echo $model->credits50Min;
        } else {
            echo "Information pending.";
        }    
        ?>
    </div>
    <h3>60 Minute Credits<div></div></h3>
    <div class="content_block">
        <?php
        if($model->credits60Min) {
            echo $model->credits60Min;
        } else {
            echo "Information pending.";
        }
        ?>
    </div>
</div>