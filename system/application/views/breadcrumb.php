<?php
/*
 |-------------------------------------------------------+
 |               Data structures expected                |
 |-------------------------------------------------------+
 |
 | $breadcrumb - an indexed array of 2 value arrays. One
 |               value is the 'id' which contains the uri
 |               to load. The other is 'name' which holds
 |               the text to display on the page.
 |
 |-------------------------------------------------------+
 */
?>
<?php $size = sizeof($breadcrumb); ?>

<div id="breadcrumb">
    <?php for($i = 0; $i < $size - 1; $i++): ?>
    <div class="parent" id="<?php echo $breadcrumb[$i]['id']; ?>">
        <?php echo $breadcrumb[$i]['name']; ?>
    </div>
    <?php endfor; ?>
    <div class="current">
        <?php echo $breadcrumb[$size - 1]['name'] ?>
    </div>
</div>