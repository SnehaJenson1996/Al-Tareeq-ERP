<table id="datatable-responsive" class="table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="overflow:scroll">
    <thead>
        <tr>
        <th>Sr.No</th>
        <th>Product Code</th>
        <th>Unit</th>      
        <th>Quantity</th>
        <th>Price</th>
        
        <th>Total</th>
        <th>Storage</th>
        <th width="15%">Barcode</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $i=5000;$up=0;$itot=0;$subtot=0;$ivat=0;$j=1; foreach($records2 as $r) { ?>
        <tr>
            <td><?php echo $j; ?></td>
        <td>
            <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->item_name; ?>" readonly/>
            <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->item_id; ?>"/>
       <br/>Brand:<input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" readonly/>
       <br/><input type="text" class="form-control" name="item_description[]" value="<?php echo $r->item_description; ?>" readonly/></td>
       
        <td>Unit:<select class="form-control" name="item_unit[]" readonly>
        <option value=''>Select</option>
        <?php foreach($active_units as $unit){ ?>
            <option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option>
        <?php } ?>
        </select>
       Packing:<select class="form-control" name="item_packing[]" readonly><option>CTN</option></select>
       </td>
        <td>
            Ordered:<input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>" readonly/>
            Pre Ordered:<input type="number" class="form-control pre_quantity" name="pre_quantity[]" id="pre_quantity<?php echo $i; ?>"  value="<?php echo $r->received_qty; ?>" readonly/>
            Received:<input type="number" class="form-control rec_quantity" name="rec_quantity[]" id="rec_quantity<?php echo $i; ?>" />
        </td>
       
        <td>
            Unit Price:<input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price<?php echo $i; ?>" value="<?php echo $r->price; ?>"/>
            Discount:<input type="number" class="form-control discount_amount" name="discount_amount[]" step='any' id="discount_amount<?php echo $i; ?>" value="<?php echo $r->dis_amt; ?>"/>
            Landing Price:<input type="number" class="form-control landing_price" name="landing_price[]" step='any' id="landing_price<?php echo $i; ?>" /></td>
        
         <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]" value="<?php echo $r->total; ?>"/></td>
        <td><textarea class="form-control" name ="storage[]" id="storage<?php echo $i; ?>" placeholder="Enter Aisle/Rack/Shelf/Bin"></textarea></td>
        <td > 
        <!-- <img width="150PX" class="barcode" data-barcode="<?= $r->item_model ?>" id="barcode<?= $i ?>" /> -->
        <!-- <input type="text" name="barcode[]" class="form-control barcode-input" id="barcode<?php echo $i; ?>" placeholder="Scan Barcode" autocomplete="off" /> -->
<!-- HTML Input -->
<input type="text" name="barcode[]" class="form-control barcode-input" 
       id="barcode<?php echo $i; ?>" placeholder="Scan Barcode" 
       autocomplete="off" oninput="handleBarcodeScan(this)" />
       
<!-- Serial Number Output (Optional) -->
<input type="text" name="serial[]" class="form-control serial-input" 
       id="serial<?php echo $i; ?>" placeholder="Serial Number" readonly />
        </td>
    </tr>
    <?php  $i++;$j++; } ?> 
    </tbody>
</table>
         