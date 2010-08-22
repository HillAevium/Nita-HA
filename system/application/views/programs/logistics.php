<div class='tab_content_container' id="programs_logistics">
  <h3>Max Capacity<div></div></h3>
  <div class="content_block">
    <?php
    if($model->maxCapacity) {
        echo $model->maxCapacity;
    } else {
        echo "Information pending.";
    }
    ?>
  </div>
  <h3>Current Registrations<div></div></h3>
  <div class="content_block">
    <?php
    if($model->registrants) {
        echo $model->registrants;
    } else {
        echo "Information pending.";
    }
    ?>
  </div>
</div>