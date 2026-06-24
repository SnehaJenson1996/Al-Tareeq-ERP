<table id="pr_items_table" class="table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Product Code</th>
            <th>Brand</th>
            <th>Description</th>
            <th>Quantity</th>
           <th style="width:120px;">Unit</th>
            <th>Price</th>
            <th>Dis (%)</th>
            <th>Dis</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $i=5000;
    foreach($records as $r) { ?>
        <tr id="<?php echo $i; ?>">
            <td>
                <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->product_name; ?>" readonly/>
                <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->product_id; ?>"/>
            </td>
            <td><input type="text" class="form-control" name="item_brand[]" value="<?php echo isset($r->brand_name) ? $r->brand_name : ''; ?>"/></td>
            <td><input type="text" class="form-control" name="item_description[]" value="<?php echo isset($r->item_description) ? $r->item_description : ''; ?>"/></td>
            <td><input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>"/></td>
          <td>
    <select class="form-control" name="item_unit[]" style="min-width:120px;">
        <option><?php echo $r->unit_name; ?></option>
    </select>
</td>
            <td><input type="number" class="form-control unit_price" name="unit_price[]" step="any" id="unit_price<?php echo $i; ?>" value="<?php echo isset($r->unit_price) ? $r->unit_price : 0; ?>"/></td>
            <td><input type="number" class="form-control dis_per" id="discount_per<?php echo $i; ?>" step="any" name="dis_per[]"/></td>
            <td><input type="number" class="form-control dis_amt" id="discount_amt<?php echo $i; ?>" step="any" name="dis_amt[]"/></td>
            <td><input type="number" class="form-control final_unit_price" name="final_unit_price[]" step="any" id="final_unit_price<?php echo $i; ?>" /></td>
            <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step="any" name="total_price[]" value="<?php echo isset($r->total) ? $r->total : 0; ?>"/></td>
        </tr>
    <?php $i++; } ?> 
    </tbody>
</table>
