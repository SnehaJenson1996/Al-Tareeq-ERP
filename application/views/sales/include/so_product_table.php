         <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Unit</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Amount</th>
              <th>Discount</th>
              <th>Taxable</th>
              <th>Action</th>
            </tr>
          </thead>
        <tbody>
<?php if (!empty($so_products)): $i = 1; ?>
    <?php foreach ($so_products as $item): ?>
        <tr>
            <td><?= $i++ ?></td>

            <td>
                <input type="text" class="form-control" value="<?= isset($item['item_name']) ? $item['item_name'] : '' ?>" readonly>
                <input type="hidden" name="product_id[]" value="<?= isset($item['item_id']) ? $item['item_id'] : '' ?>">
                <!-- <input type="hidden" name="product_desc[]" value="<?= isset($item['prd_description']) ? $item['prd_description'] : '' ?>"> -->
            </td>

            <td>
                <input type="text" class="form-control" value="<?= isset($item['unit_name']) ? $item['unit_name'] : '' ?>" readonly>
                <input type="hidden" name="unit_id[]" value="<?= isset($item['unit_id']) ? $item['unit_id'] : '' ?>">
            </td>

            <td>
                <input type="number" name="so_edit_qty[]" class="form-control so_edit_qty" 
                       value="<?= isset($item['so_qty']) ? $item['so_qty'] : 0 ?>" 
                       data-maxqty="<?= isset($item['available_qty']) ? $item['available_qty'] : 0 ?>" >
            </td>

            <td>
                <input type="text" name="so_edit_unitp[]" class="form-control so_edit_unitp" 
                       value="<?= isset($item['unit_price']) ? $item['unit_price'] : 0 ?>" readonly>
            </td>

            <td>
                <input type="text" name="so_edit_amount[]" class="form-control so_edit_amount" 
                       value="<?= (isset($item['so_qty']) && isset($item['unit_price'])) ? ($item['so_qty'] * $item['unit_price']) : 0 ?>" readonly>
            </td>

            <td>
                <input type="text" name="so_edit_discount[]" class="form-control so_edit_discount" 
                       value="<?= isset($item['so_discount_amount']) ? $item['so_discount_amount'] : 0 ?>">
            </td>

            <td>
                <input type="text" name="so_edit_taxable[]" class="form-control so_edit_taxable" 
                       value="<?= (isset($item['so_qty']) && isset($item['unit_price']) && isset($item['so_discount_amount'])) ? (($item['so_qty'] * $item['unit_price']) - $item['so_discount_amount']) : 0 ?>" readonly>
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="9" class="text-center">No items found</td></tr>
<?php endif; ?>
</tbody>

      