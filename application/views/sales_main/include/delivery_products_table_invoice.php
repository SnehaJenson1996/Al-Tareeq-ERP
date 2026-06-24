<table class="table table-striped table-bordered dt-responsive nowrap">
    <thead>
        <tr>
            <th>Product Code</th>
            <th>Description</th>
            <th>Ordered Qty</th>
            <th>Delivered Qty</th>
            <th>Unit</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th>discount Amount</th>
            <th>Taxable Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($qtn_products)) : ?>
            <?php foreach ($qtn_products as $q) : 
                // Find delivered qty from so_products
                $delivered = 0;
                if(!empty($so_products)){
                    foreach($so_products as $so_p){
                        if($so_p['item_id'] == $q['prd_id']){
                            $delivered = $so_p['so_qty'];
                            break;
                        }
                    }
                }
            ?>
                <tr>
                    <td>
                        <?= $q['item_name'] ?>
                        <input type="hidden" name="product_id[]" value="<?= isset($so_p['item_id']) ? $so_p['item_id'] : '' ?>">
                    </td>
                    <td>
                        <?= $q['prd_description'] ?>
                        <input type="hidden" name="product_desc[]" value="<?= isset($q['prd_description']) ? $q['prd_description'] : '' ?>">
                    </td>
                    <td>
                        <?= $q['qty'] ?>
                        <input type="hidden" name="product_orderd_qty[]" value="<?= isset($q['qty']) ? $q['qty'] : '' ?>">

                    </td>
                    <td>
                        <?= $delivered ?>
                        <input type="hidden" name="product_deliverd_qty[]" value="<?= isset($delivered) ? $delivered : '' ?>">
                    </td>
                    <td>
                        <?= $q['unit_name'] ?>
                        <input type="hidden" name="product_unit_id[]" value="<?= isset($q['unit_id']) ? $q['unit_id'] : '' ?>">
                    </td>
                    <td>
                        <?= $q['unit_price'] ?>
                        <input type="hidden" name="unit_price[]" value="<?= isset($q['unit_price']) ? $q['unit_price'] : '' ?>">
                    </td>
                    
                    <td>
                        <?= number_format(($delivered * $q['unit_price']), 2) ?>
                        <input type="hidden" name="total_amount[]" value="<?= $delivered * $q['unit_price'] ?>">
                    </td>
                    <td>
                        <?= $so_p['so_discount_amount'] ?>
                        <input type="hidden" name="discount_amount[]" value="<?= isset($so_p['so_discount_amount']) ? $so_p['so_discount_amount'] : '' ?>">
                    </td>
                    <td>
                        <?= number_format(($delivered * $q['unit_price']-$so_p['so_discount_amount']), 2) ?>
                        <input type="hidden" name="taxable_amount[]" value="<?= $delivered * $q['unit_price']-$so_p['so_discount_amount'] ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="8" class="text-center">No products found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
