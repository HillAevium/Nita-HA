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
<?php $size = sizeof($breadcrumb) - 1; ?>

<div id="breadcrumb">
    <?php for($i = 0; $i < $size - 1; $i++): ?>
    <div class="parent" id="<?php echo $breadcrumb[$i]['id']; ?>">
        <span><?php echo $breadcrumb[$i]['name']; ?></span>
        <div></div>
    </div>
    <?php endfor; ?>
    <div class="current">
        <span><?php echo $breadcrumb[$size - 1]['name'] ?></span>
        <div></div>
    </div>
    <!-- Search box open button -->
    <?php if($breadcrumb['hasSearch']): ?>
    <div class="search_open" id="search_open">Search</div>
    <?php endif; ?>
</div>