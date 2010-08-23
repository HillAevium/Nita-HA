<div class="program">
<?php echo $model->description; ?><br /><br />
<?php echo date("n/j/Y",strtotime($model->startDate)) . " - " . date("n/j/Y",strtotime($model->endDate)); ?><br /><br />
<?php echo $model->facilityName . '<br />' . $model->city . ', ' . $model->state; ?>
</div>