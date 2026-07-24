<table id="datatable-responsive" class="table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Product Code</th>
            <!-- <th>Brand</th> -->
            <th>Description</th>
            <th>Quantity</th>
            <th style="width:120px;">Unit</th>
            <!-- <th>Packing</th> -->
            <th>Price</th>
            <th>Dis (%)</th>
            <th>Dis</th>
            <!-- <th>Dis 2(%)</th> -->
            <!-- <th>Dis</th> -->
            <th>Unit Price</th>
            <th>Total</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $i = 5000;
        $up = 0;
        $itot = 0;
        $subtot = 0;
        $ivat = 0;
        foreach ($records2 as $r) { ?>
            <tr id="<?php echo $i; ?>">
                <td>
                    <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->product_name; ?>" />
                    <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->product_id; ?>" />
                </td>
                <!-- <td><input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" /></td> -->
                <td><input type="text" class="form-control" name="item_description[]" value="<?php echo isset($r->description) ? $r->description : ''; ?>" /></td>
                <td><input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>" /></td>
                <td>
                    <select class="form-control" name="item_unit[]" style="min-width:120px;">
                        <option value="<?php echo $r->unit_id; ?>" selected>
                            <?php echo $r->unit_name; ?>
                        </option>
                    </select>
                </td> <!-- <td><select class="form-control" name="item_packing[]"><option>CTN</option></select></td> -->

                <td><input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price<?php echo $i; ?>" value="<?php echo $r->retail_price; ?>" /></td>
                <td><input type="number" class="form-control dis_per" id="discount_per<?php echo $i; ?>" step='any' name="dis_per[]" /></td>
                <td><input type="number" class="form-control dis_amt" id="discount_amt<?php echo $i; ?>" step='any' name="dis_amt[]" /></td>

                <!-- <td><input type="number" class="form-control dis_per2" id="discount_per2<?php echo $i; ?>" step='any' name="dis_per2[]"/></td> -->
                <!-- <td><input type="number" class="form-control dis_amt2" id="discount_amt2<?php echo $i; ?>" step='any' name="dis_amt2[]"/></td> -->
                <td><input type="number" class="form-control final_unit_price" name="final_unit_price[]" step='any' id="final_unit_price<?php echo $i; ?>" /></td>

                <!-- <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]" value="<?php isset($r->total) ? $r->total : "" ?>"/></td> -->
                <td>
                    <input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]"
                        value="<?php echo isset($r->total) ? $r->total : ''; ?>" />
                </td>
            </tr>
        <?php $i++;
        } ?>
    </tbody>
</table>