<div class='tab_content_container' id="programs_overview">
    <h3>Program Type<div></div></h3>
    <div class="content_block">
        <?php echo $model->type; ?>
    </div>
    <h3>Product ID<div></div></h3>
    <div class="content_block">
        <?php echo $model->id; ?>
    </div>
    <h3>Location<div></div></h3>
    <div class="content_block">
        <?php echo $model->address1 . " " . $model->city . ", " . $model->state; ?>
    </div>
    <h3>Dates<div></div></h3>
    <div class="content_block">
        <?php echo $model->startDate . " - " . $model->endDate; ?>
    </div>
    <h3>Description<div></div></h3>
    <div class="content_block">
        <?php echo $model->description; ?>
    </div>
</div>