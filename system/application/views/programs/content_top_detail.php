    <div class="image"></div>
    <div class="content_top_content">
        <div class="program_add_to_cart"></div>
        <h1><?php echo $model->name; ?></h1>
        <?php echo $model->description; ?>
        <?php echo $model->dates; ?>
        <?php echo $model->location . '<br />' . $program->city . ', ' . $program->state; ?>
    </div>