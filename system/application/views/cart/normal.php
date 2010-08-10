<?php

function makeButton($uri, $text) {
    $htmlButton = '<div style="float:right;margin:20px 0;width:100px;height:40px;line-height:40px;font-size:16px;background:#ccc;color:#fff;text-align:center;"
                        onclick="window.location = \''. $uri . '\'">' . $text . '</div>';
    return $htmlButton;
}

switch($buttons) {
    case 'checkout' :
        $button  = makeButton('/cart/review', 'Checkout');
    break;
    case 'login' :
        $button  = makeButton('/account/login', 'Login');
        $button .= makeButton('/account/register', 'Register');
    break;
}
?>
    <div id="content_main_inner">
        <h1 class="page_title"><?php echo $title; ?></h1>
        <table id="items" class="program_list">
            <thead>
                <th align="left">Program</th>
                <th align="left">Program Status</th>
                <th align="left">Price</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach($this->cart->contents() as $item): ?>
                <tr id="<?php echo $item['id']; ?>">
                    <td><?php echo $item['name']; ?></td>
                    <td>FIXME: Space Available!</td>
                    <td><?php echo $item['price']; ?></td>
                    <td>X</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $button; ?>
        <div class="clear"></div>
    </div> <!-- #content_main_inner -->
