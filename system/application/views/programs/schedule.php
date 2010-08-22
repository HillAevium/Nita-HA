<div class='tab_content_container' id="programs_schedule">
    <h3>Registration Dates<div></div></h3>
    <div class="content_block">
        <?php
        if($model->registerStart) {
            echo $model->registerStart ." - " . $model->registerEnd;
        } else {
            echo "Information pending.";
        }
        ?>
    </div>
    <h3>Program Dates<div></div></h3>
    <div class="content_block">
        <?php
        if($model->startDate) {
            echo $model->startDate ." - " . $model->endDate;
        } else {
            echo "Information pending.";
        }
        ?>
    </div>
</div>