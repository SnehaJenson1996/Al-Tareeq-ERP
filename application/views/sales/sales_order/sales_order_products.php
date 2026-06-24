<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Amount</th>
            <th>Discount amount</th>
            <th>Taxable amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($qtn_products)): ?>
            <?php foreach ($qtn_products as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <?= $item['item_name'] ?><br>
                        <?= $item['prd_description'] ?>
                        <input type="hidden" name="product_id[]" value="<?= $item['prd_id'] ?>">
                        <input type="hidden" name="product_desc[]" value="<?= $item['prd_description'] ?>">
                    </td>
                    <td>
                        <?= $item['unit_name'] ?>
                        <input type="hidden" name="unit_id[]" value="<?= $item['unit_id'] ?>">
                    </td>
                    <td>
                        <input type="text" name="so_qty[]" value="<?= $item['available_qty'] ?>" data-maxqty="<?= $item['available_qty'] ?>" class="form-control so_qty">
                    </td>
                    <td>
                        <input type="text" name="so_unitp[]" value="<?= $item['unit_price'] ?>" class="form-control so_unitp" readonly>
                    </td>
                    <td>
                        <input type="text" name="so_amount[]" value="<?= number_format(($item['available_qty'] * $item['unit_price']), 2) ?>" class="form-control so_amount" readonly>
                    </td>
                    <td>
                        <input type="text" name="so_discount[]" value="<?= $item['discount_amount'] ?>" class="form-control so_discount">
                    </td>
                    <td>
                        <input type="text" name="so_taxable[]" value="<?= number_format((($item['available_qty'] * $item['unit_price']) - $item['discount_amount']), 2) ?>" class="form-control so_taxable" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
