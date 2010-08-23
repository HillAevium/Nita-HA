    <h2>Order History</h2>
    Not Complete
    <div>
        <table>
            <thead>
                <tr>
                    <th align="left">Date</th>
                    <th align="left">Programs</th>
                    <th align="left">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td><?php echo $order['date']; ?></td>
                    <td><?php echo join("<br />", $order['programs']); ?></td>
                    <td><?php echo $order['price']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="gray_line"></div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>