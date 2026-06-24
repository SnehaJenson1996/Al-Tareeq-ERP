<div class="x_panel">


    <div class="x_content">
        <table class="table table-bordered table-striped">
            <thead style="background-color:#d9edf7;">
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Total Stock</th>
                    <th>Total Reserved</th>
                    <th>Total Pending</th>
                    <th>Available Stock</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($stock as $row): ?>
                    <tr>
                        <td>
                            <a href="<?= base_url('index.php/Inventory/item_reservation_detail/' . $row['item_id']); ?>">
                                <?= $row['item_code']; ?>
                            </a>
                        </td>

                        <td><?= $row['item_name']; ?></td>
                        <td class="text-right"><?= number_format($row['total_stock'], 2); ?></td>
                        <td class="text-right text-warning"><?= number_format($row['total_reserved'], 2); ?></td>
                        <td class="text-right text-danger"><?= number_format($row['total_pending'], 2); ?></td>
                        <td class="text-right text-success">
                            <?= number_format(
                                $row['total_stock'] - $row['total_reserved'],
                                2
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>