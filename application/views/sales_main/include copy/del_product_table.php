<thead>
    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Qty</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($so_products)): $i = 1; ?>
        <?php foreach ($so_products as $item): ?>
            <tr>
                <td><?= $i++ ?></td>

                <td>
                    <input type="text" class="form-control" value="<?= isset($item['product_name']) ? $item['product_name'] : '' ?>" readonly>
                    <input type="hidden" name="product_id[]" value="<?= isset($item['product_id']) ? $item['product_id'] : '' ?>">
                    <!-- <input type="hidden" name="product_desc[]" value="<?= isset($item['prd_description']) ? $item['prd_description'] : '' ?>"> -->
                </td>

                <td>
                    <input type="number" name="quantity[]" class="form-control so_edit_qty"
                        value="<?= isset($item['quantity']) ? $item['quantity'] : 0 ?>" readonly>
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" class="text-center">No items found</td>
        </tr>
    <?php endif; ?>
</tbody>