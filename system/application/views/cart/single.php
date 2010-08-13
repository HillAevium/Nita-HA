            <?php foreach($this->cart->contents() as $item): ?>
            <div id="<?php echo $item['id']; ?>">
                <table>
                    <tr>
                        <td style="width:30%">
                            <div id="item_details">
                                <?php echo $item['name']; ?>
                            </div>
                        </td>
                        <td style="width:30%">
                            <div id="item_status">
                                <?php //TODO echo $item['status']; ?>
                                <?php echo $spaces[0]; ?>
                            </div>
                        </td>
                        <td style="width:30%;">
                            <div id="item_price">
                                <?php echo $item['price']; ?>
                            </div>
                        </td>
                        <td style="width:10%;">
                            <div class="item_remove" id="<?php echo $item['id']; ?>">
                                Remove
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>