<<<<<<< HEAD
<div id="content_top"></div>
<div id="content_main">
    <div id="content_main_inner">
        <div id="breadcrumb"></div>
        <ul>
            <li>Name: <?php echo $program->name; ?></li>
            <li>City: <?php echo $program->city; ?></li>
            <li>Location: <?php echo $program->location?></li>
            <li>Program Dates: <?php echo $program->programDates; ?></li>
            <li>Cost: <?php echo $program->price; ?></li>
            <?php if(count($program->director) == 1):?>
            <li>Director:
                <a href=<?php echo "'".$program->director[0]['bio']."'"; ?>>
                <?php echo $program->director[0]['name']; ?>
                </a>
            </li>
            <?php else: ?>
            <li>Directors:
                <?php foreach($program->director as $director): ?>
                <a href=<?php echo "'".$director['bio']."'"; ?>><?php echo $director['name']; ?></a>&nbsp;
                <?php endforeach; ?>
            </li>
            <?php endif; ?>
        </ul>
    </div> <!-- #content_main_inner -->
</div> <!-- #content_main -->
