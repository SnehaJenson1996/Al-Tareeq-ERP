<table id="datatable-responsive" class="table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="overflow:scroll;">
    <thead>
        <tr>
            <th>Sl. No</th>
            <th>Product Code</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th width="15%">Serial Number</th>
            <th>Price</th>

            <th>Total</th>


        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        $up = 0;
        $itot = 0;
        $subtot = 0;
        $ivat = 0;
        $j = 1;
        foreach ($records2 as $r) { ?>
            <tr>
                <td><?php echo $j; ?></td>
                <td>
                    <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->product_name; ?>" readonly />
                    <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->product_id; ?>" />
                    <!-- <br />Brand:<input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" readonly /> -->
                    <br /><input type="text" class="form-control" name="item_description[]" value="<?php echo $r->description; ?>" readonly />
                </td>

                <td>
                    Unit:
                    <select class="form-control" name="item_unit[]" readonly>
                        <option value=''>Select</option>
                        <?php foreach ($active_units as $unit) { ?>
                            <option value='<?php echo $unit->unit_id ?>'
                                <?php echo ($unit->unit_id == $r->unit_id) ? 'selected' : ''; ?>>
                                <?php echo $unit->unit_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    Ordered:<input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>" readonly />
                    Pre Ordered:<input type="number" class="form-control pre_quantity" name="pre_quantity[]" id="pre_quantity<?php echo $i; ?>" value="<?php echo $r->received_qty; ?>" readonly />
                    Received:<input type="number" class="form-control rec_quantity" onchange="test(event);" name="rec_quantity[]" id="rec_quantity<?php echo $i; ?>" data-index="<?php echo $i; ?>" /><small id="error_msg<?php echo $i; ?>" class="text-danger" style="display: none;"></small>
                </td>
                <td class="serial-container" id="serial_container<?php echo $i; ?>"> </td>

                <td>Unit Price:<input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price<?php echo $i; ?>" value="<?php echo $r->price; ?>" />
                    Landing Price:<input type="number" class="form-control landing_price" name="landing_price[]" step='any' id="landing_price<?php echo $i; ?>" /></td>

                <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]" value="<?php echo $r->total; ?>" /></td>


            </tr>
        <?php $i++;
            $j++;
        } ?>
    </tbody>
</table>