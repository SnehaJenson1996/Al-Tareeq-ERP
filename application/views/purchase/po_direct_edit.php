<?php 
	$page_name2='Purchase/purchase_order_list';
	$user = $this->session->userdata('user_id');
?>
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Purchase/update_purchase_order" autocomplete="off" enctype="multipart/form-data">
	
          <!-- page content -->
          <div class="form-group" role="main">
          <div class="">
            <div class="page-title">
             </div>
            <div class="clearfix"></div>
                <div class="x_content">
                  <div class="well" style="overflow: auto">

                    <!-- Row 1: Branch, Supplier, PO Code -->
                    <div class="row mb-3">
                      <div class="col-md-4">
                        <label class="control-label">Branch</label>
                        <select class="form-control" name="Branch_id" id="branch_id" required>
                          <option value="">Select</option>
                          <?php foreach ($branch_records as $b) { ?>
                            <option value="<?php echo $b->branch_id; ?>" 
                              <?php if ($records1[0]->branch_id == $b->branch_id) echo 'selected'; ?>>
                              <?php echo $b->branch_name; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="col-md-4">
                        <label class="control-label">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id" required onchange="get_supplier_info()">
                          <option value="">Select</option>
                          <?php foreach ($supplier_records as $s) { ?>
                            <option value="<?php echo $s->supplier_id; ?>" 
                              <?php if ($records1[0]->supplier_id == $s->supplier_id) echo 'selected'; ?>>
                              <?php echo $s->supplier_name; ?>
                            </option>
                          <?php } ?>
                        </select>  
                      </div>

                      <div class="col-md-4">
                        <label class="control-label">PO Code</label>
                        <input type="text" class="form-control" name="po_code" id="po_code" 
                          readonly value="<?php echo $records1[0]->po_code; ?>">  
                        <input type="hidden" name="po_id" id="po_id" 
                          value="<?php echo $records1[0]->po_id; ?>">  
                      </div>
                    </div>

                    <!-- Row 2: PO Date, Subject, Reference -->
                    <div class="row mb-3">
                      <div class="col-md-4">
                        <label class="control-label">PO Date</label>
                        <input type="date" class="form-control" name="po_date" id="po_date" 
                          value="<?php echo $records1[0]->po_date; ?>">
                      </div>

                      <div class="col-md-4">
                        <label class="control-label">Freight Name</label>
                        <input type="text" class="form-control" name="subject" id="subject" 
                          value="<?php echo $records1[0]->subject; ?>">  
                      </div>

                      <div class="col-md-4">
                        <label class="control-label">Reference</label>
                        <input type="text" class="form-control" name="ref_no" id="ref_no" 
                          value="<?php echo $records1[0]->supplier_ref; ?>">  
                      </div>
                    </div>

                    <!-- Row 3: Freight Mode, Upload Document -->
                    <div class="row mb-3">
                      <div class="col-md-4">
                        <label class="control-label">Freight Mode</label>
                        <select class="form-control" name="freight_mode" id="freight_mode"> 
                          <option value="">Select</option>
                          <option <?php if ($records1[0]->freight_mode == "Sea") echo "selected"; ?> value="Sea">Sea</option>
                          <option <?php if ($records1[0]->freight_mode == "Air") echo "selected"; ?> value="Air">Air</option>
                          <option <?php if ($records1[0]->freight_mode == "Road") echo "selected"; ?> value="Road">Road</option>
                          <option <?php if ($records1[0]->freight_mode == "Courier") echo "selected"; ?> value="Courier">Courier</option>
                        </select>
                      </div>

                      <div class="col-md-4">
                        <label class="control-label">Upload Document</label>
                        <input type="file" class="form-control" name="po_doc" id="po_doc">  
                      </div>

                      <div class="col-md-4" style="margin-top: 30px;">
                        <?php if (!empty($po_doc[0]->doc_path)) { ?>
                          <a href="<?php echo base_url('public/uploaded_documents/' . $po_doc[0]->doc_path); ?>" 
                            target="_blank">
                            <?php echo $po_doc[0]->doc_path; ?>
                          </a>
                        <?php } ?>
                      </div>
                    </div>

                    <!-- Row 4: Prepared / Approved By -->
                    <div class="row mb-4">
                      <!-- <div class="col-md-4">
                        <label class="control-label">Prepared By</label>
                        <input type="text" class="form-control" name="prepared_by" id="prepared_by" readonly
                          value="<?php echo $records1[0]->prepared_by ?? ''; ?>">  
                      </div> -->

                      <!-- <div class="col-md-4">
                        <label class="control-label">Approved By</label>
                        <input type="text" class="form-control" name="approved_by" id="approved_by" 
                          value="<?php echo $records1[0]->approved_by ?? ''; ?>">  
                      </div> -->
                    </div>

                  </div> <!-- well -->
                </div> <!-- x_content -->


            <div class="row col-md-12 col-sm-12" style="overflow: scroll;">
                <div class="x_content" id="rfq_items_list">
                <table id="datatable-responsive" class="table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                      <th>Product Code</th>
                      <th>Brand</th>
                      <th>Description</th>
                      <th>Quantity</th>
                      <th>Unit</th>
                      <!-- <th>Packing</th> -->
                      <th>Price</th>
                      <!-- <th>Dis 1(%)</th>
                      <th>Dis</th>                       -->
                      <!-- <th>Unit Price</th> -->
                      <th>Total</th>
                      <th>Actions</th>                      
                      </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $i=5000;$up=0;$itot=0;$subtot=0;$ivat=0; foreach($records2 as $r) { ?>
                      <tr>
                      <td>
                          <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->item_name; ?>" readonly/>
                          <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->item_id; ?>"/>
                      </td>
                      <td><input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" readonly/></td>
                      <td><input type="text" class="form-control" name="item_description[]" value="<?php echo $r->item_description; ?>"/></td>
                      <td><input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>"/></td>
                      <!-- <td><select class="form-control" name="item_unit[]"><option>KG</option></select></td> -->
                      <!-- <td><select class="form-control" name="item_packing[]"><option>CTN</option></select></td> -->
                      <td><input type="text" class="form-control qty" name="item_unit[]" id="item_unit<?php echo $i; ?>" value="<?php echo $r->unit_name; ?>"/></td>
                      <td><input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price<?php echo $i; ?>" value="<?php echo $r->price; ?>"/></td>
                      <!-- <td><input type="number" class="form-control dis_per" id="discount_per<?php echo $i; ?>" step='any' name="dis_per[]" value="<?php echo $r->dis_per; ?>"/></td>
                      <td><input type="number" class="form-control dis_amt" id="discount_amt<?php echo $i; ?>" step='any' name="dis_amt[]" value="<?php echo $r->dis_amt; ?>"/></td> -->
                      <!-- <td><input type="number" class="form-control final_unit_price" name="final_unit_price[]" step='any' id="final_unit_price<?php echo $i; ?>" value="<?php echo $r->unit_price; ?>"/></td> -->
                      <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]" value="<?php echo $r->total; ?>"/></td>
                      <td>
                        <a href="#" class="addRow" title="Add"><i class="fa fa-plus-circle text-success"></i></a>
                        <a href="#" class="deleteRow" title="Delete"><i class="fa fa-trash text-danger"></i></a>
                      </td>                      
                      </tr>
                  <?php  $i++; } ?> 
                  </tbody>
              </table>
                        
                
              </div>
            </div>
                 
                 
                 
            <br><br><br><br>
            
         <div class="x_content">
  <div class="well" style="overflow: auto">

    <!-- Totals Row -->
    <div class="row mb-3 align-items-center">
      <div class="col-md-1">
        <label class="control-label">Sub Total</label>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" name="sub_total" id="sub_total"
          value="<?php echo $records1[0]->sub_total; ?>" readonly>
      </div>

      <div class="col-md-1">
        <label class="control-label">Discount (%)</label>
      </div>
      <div class="col-md-1">
        <input type="text" class="form-control" name="discount_per" id="discount_per"
          value="<?php echo $records1[0]->discount_percent; ?>">
      </div>

      <div class="col-md-2">
        <input type="text" class="form-control" name="discount_amt" id="discount_amt"
          value="<?php echo $records1[0]->discount; ?>">
      </div>

      <div class="col-md-2">
        <label class="control-label">Transportation Charge</label>
      </div>
      <div class="col-md-2">
        <input type="number" class="form-control" name="transportation_charge" id="transportation_charge"
          value="<?php echo $records1[0]->trans_charge; ?>" step="any">
      </div>
    </div>

    <!-- Charges Row -->
<div class="row mb-3 align-items-center">
  <div class="col-md-2">
    <label class="control-label">Freight Charge</label>
  </div>
  <div class="col-md-2">
    <input type="number" class="form-control" name="customs_charge" id="customs_charge"
      value="<?php echo $records1[0]->cust_charge; ?>" step="any">
  </div>

  <div class="col-md-2">
    <label class="control-label">Other Charges</label>
  </div>
  <div class="col-md-2">
    <input type="number" class="form-control" name="other_charge" id="other_charge"
      value="<?php echo $records1[0]->add_charge; ?>" step="any">
  </div>

  <div class="col-md-2">
    <label class="control-label">Total Before VAT</label>
  </div>
  <div class="col-md-2">
    <input type="text" class="form-control" name="total_beforvat" id="total_beforvat"
      value="<?php echo $records1[0]->total_beforevat; ?>" readonly>
  </div>
</div>

    <!-- VAT & Grand Total Row -->
    <div class="row mb-3 align-items-center">
      <div class="col-md-1">
        <label class="control-label">VAT (%)</label>
      </div>
      <div class="col-md-1">
        <input type="text" class="form-control" name="vat_per" id="vat_per"
          value="<?php echo $records1[0]->vat_percent; ?>">
      </div>

      <div class="col-md-2">
        <input type="text" class="form-control" name="vat_amount" id="vat_amount"
          value="<?php echo $records1[0]->vat_amt; ?>" readonly>
      </div>

      <div class="col-md-2">
        <label class="control-label">Grand Total</label>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" name="grand_total" id="grand_total"
          value="<?php echo $records1[0]->grand_total; ?>" readonly>
      </div>
    </div>

    <!-- Currency & Conversion Rate Row -->
    <div class="row mb-3 align-items-center">
      <div class="col-md-2">
        <label class="control-label">Currency</label>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" name="currency" id="currency"
          value="<?php echo isset($records1[0]->currency_abbr) ? $records1[0]->currency_abbr : ''; ?>" readonly>
      </div>

      <div class="col-md-2">
        <label class="control-label">Conversion Rate</label>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" name="conversion_rate" id="conversion_rate"
          value="<?php echo isset($records1[0]->conversion_rate) ? $records1[0]->conversion_rate : ''; ?>">
      </div>

      <div class="col-md-2">
        <label class="control-label">Grand Total (Base Currency)</label>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" name="base_currency_grand_total" id="base_currency_grand_total"
          value="<?php echo isset($records1[0]->base_currency_grand_total) ? $records1[0]->base_currency_grand_total : ''; ?>" readonly>
      </div>
    </div>

    <!-- Terms Row -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="control-label">Validity</label>
        <input type="text" class="form-control" name="validity" id="validity"
          value="<?php echo $records1[0]->validity ?? ''; ?>">
      </div>

      <div class="col-md-6">
        <label class="control-label">Payment Terms</label>
        <input type="text" class="form-control" name="payment_terms" id="payment_terms"
          value="<?php echo $records1[0]->payment_term; ?>">
      </div>
    </div>

    <!-- Delivery & General Terms -->
      <div class="row mb-3">
      <div class="col-md-6">
        <label class="control-label">Delivery Terms</label>
        <textarea class="form-control" name="delivery_terms" id="delivery_terms" rows="4"><?php echo $records1[0]->delivery_term; ?></textarea>
      </div>

      <div class="col-md-6">
        <label class="control-label">General Terms</label>
        <textarea class="form-control" name="general_terms" id="general_terms" rows="4"><?php echo $records1[0]->general_term; ?></textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Prepared By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_prepared" name="employee_prepared" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>" <?= (isset($records1[0]->prepared_by) && $records1[0]->prepared_by == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>
      <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Checked By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_checked" name="employee_checked" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>" <?= (isset($records1[0]->checked_by) && $records1[0]->checked_by == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>
      <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Approved By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_approved" name="employee_approved" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>" <?= (isset($records1[0]->approved_by) && $records1[0]->approved_by == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>      
    </div>    
 <div class="row col-md-12 col-sm-12 mt-3">
    <!-- Buttons Row -->
    <div class="row mt-4">
      <div class="col-md-12 text-center">
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="submit" name="action" value="approve" class="btn btn-warning">
      Approve PO
    </button>
      </div>
    </div>
    </div>

  </div> <!-- /.well -->
</div> <!-- /.x_content -->

      

        <!-- /page content -->
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('delivery_terms');
  CKEDITOR.replace('general_terms');
</script>

<script>
    $(document).ready(function () {
    var rowIndex = 1; // start from 1 (row 0 exists in HTML)
    // Add new row
    $(document).on('click', '.addRow', function(e) {
      e.preventDefault();

      var newRow = '';
      newRow += '<tr>';
      newRow += '            <td>';
      newRow += '                <select class="form-control" name="item_id[]" id="item' + rowIndex + '" onchange="get_item_by_id(' + rowIndex + ')">';
      newRow += '                  <option value="">Select</option>';
      newRow += '                  <option value="new">+ Add New Product</option>';
      <?php foreach ($active_items as $item) { ?>
      newRow += '                    <option value="<?php echo $item->item_id ?>"><?php echo $item->item_name; ?></option>';
      <?php } ?>
      newRow += '                </select>';
      newRow += '            </td>';
      newRow += '            <td><input class="form-control" type="text" name="item_brand[]" id="brand' + rowIndex + '"></td>';
      newRow += '            <td><input class="form-control" type="text" name="item_description[]" id="description' + rowIndex + '"></td>';
      newRow += '            <td><input class="form-control quantity" type="number" name="item_quantity[]" id="quantity' + rowIndex + '"></td>';      
      newRow += '            <td>';
      newRow += '                <select class="form-control" name="item_unit[]" id="unit' + rowIndex + '">';
      newRow += '                    <option value="">Select</option>';
      <?php foreach ($active_units as $unit) { ?>
      newRow += '                        <option value="<?php echo $unit->unit_id ?>"><?php echo $unit->unit_name; ?></option>';
      <?php } ?>
      newRow += '                </select>';
      newRow += '            </td>';
      newRow += '            <td><input type="number" class="form-control unit_price" name="unit_price[]" step="any" id="unit_price' + rowIndex + '" /></td>';
      newRow += '            <td><input type="number" class="form-control total_price" name="total_price[]" step="any" id="total_price' + rowIndex + '" /></td>';
      newRow += '            <td>';
      newRow += '              <a href="#" class="addRow" title="Add"><i class="fa fa-plus-circle text-success"></i></a>';
      newRow += '              <a href="#" class="deleteRow" title="Delete"><i class="fa fa-trash text-danger"></i></a>';
      newRow += '            </td>';
      newRow += '        </tr>';

      $('#datatable-responsive tbody').append(newRow);

      // reinitialize select2 for the added select (if you're using Select2)
      if (typeof $('#item' + rowIndex).select2 === 'function') {
        $('#item' + rowIndex).select2();
      }
      rowIndex++;
    });

    // Delete row
    $(document).on('click', '.deleteRow', function(e) {
      e.preventDefault();
      let rowCount = $('#datatable-responsive tbody tr').length;
      if (rowCount <= 1) {
          alert('At least one product row must remain.');
          return;
      }      
      $(this).closest('tr').remove();
      calculateAll();
    });

    // Event listener for input changes
    $(document).on('input change', '.qty, .unit_price, .dis_per, .dis_amt, .dis_per2, .dis_amt2', function () {
        var row_id = $(this).closest('tr');
      
        calculateRow(row_id);
        calculateAll();
    });
    // Event listener for global fields
       $('#discount_per, #discount_amt, #vat_per, #transportation_charge, #customs_charge, #other_charge, #conversion_rate').on('input change', calculateAll);


    function calculateRow($row) {
        var qty = parseFloat($row.find('.qty').val()) || 0;
        var price = parseFloat($row.find('.unit_price').val()) || 0;

        var disPer1 = parseFloat($row.find('.dis_per').val()) || 0;
        var disAmt1 = parseFloat($row.find('.dis_amt').val()) || 0;

        var disPer2 = parseFloat($row.find('.dis_per2').val()) || 0;
        var disAmt2 = parseFloat($row.find('.dis_amt2').val()) || 0;

        var rowTotal = qty * price;

        // First Discount
        if ($row.find('.dis_per').is(':focus')) {
            disAmt1 = (rowTotal * disPer1) / 100;
            $row.find('.dis_amt').val(disAmt1.toFixed(2));
        } else if ($row.find('.dis_amt').is(':focus')) {
            disPer1 = (rowTotal === 0) ? 0 : (disAmt1 / rowTotal) * 100;
            $row.find('.dis_per').val(disPer1.toFixed(2));
        } else {
            disAmt1 = (rowTotal * disPer1) / 100;
            $row.find('.dis_amt').val(disAmt1.toFixed(2));
        }

        var subtotalAfterFirst = rowTotal - disAmt1;

        // Second Discount
        if ($row.find('.dis_per2').is(':focus')) {
            disAmt2 = (subtotalAfterFirst * disPer2) / 100;
            $row.find('.dis_amt2').val(disAmt2.toFixed(2));
        } else if ($row.find('.dis_amt2').is(':focus')) {
            disPer2 = (subtotalAfterFirst === 0) ? 0 : (disAmt2 / subtotalAfterFirst) * 100;
            $row.find('.dis_per2').val(disPer2.toFixed(2));
        } else {
            disAmt2 = (subtotalAfterFirst * disPer2) / 100;
            $row.find('.dis_amt2').val(disAmt2.toFixed(2));
        }

        var finalRowTotal = subtotalAfterFirst - disAmt2;
        $row.find('.total_price').val(finalRowTotal.toFixed(2));
    }

    function calculateAll() {
        let subtotal = 0;

        // Sum all row totals
        $('tbody tr').each(function () {
            let rowTotal = parseFloat($(this).find('.total_price').val()) || 0;
            subtotal += rowTotal;
        });

        $('#sub_total').val(subtotal.toFixed(2));

        // Global Discount
        let discountPer = parseFloat($('#discount_per').val()) || 0;
        let discountAmt = parseFloat($('#discount_amt').val()) || 0;

        // Recalculate depending on focus
        if ($('#discount_per').is(':focus')) {
            discountAmt = (subtotal * discountPer) / 100;
            $('#discount_amt').val(discountAmt.toFixed(2));
        } else if ($('#discount_amt').is(':focus')) {
            discountPer = (subtotal === 0) ? 0 : (discountAmt / subtotal) * 100;
            $('#discount_per').val(discountPer.toFixed(2));
        } else {
            discountAmt = (subtotal * discountPer) / 100;
            $('#discount_amt').val(discountAmt.toFixed(2));
        }

        let afterDiscount = subtotal - discountAmt;

          // --- Charges ---
          var transcharg  = parseFloat($('#transportation_charge').val()) || 0;
          var customcharg = parseFloat($('#customs_charge').val()) || 0;
          var othercharg  = parseFloat($('#other_charge').val()) || 0;

          // ✅ Total before VAT
          var totalBeforeVAT = afterDiscount + transcharg + customcharg + othercharg;
          $('#total_beforvat').val(totalBeforeVAT.toFixed(2));

          // --- VAT ---
          var vatPer = parseFloat($('#vat_per').val()) || 0;
          var vatAmt = (totalBeforeVAT * vatPer) / 100;
          $('#vat_amount').val(vatAmt.toFixed(2));

          // --- Grand Total ---
          var grandTotal = totalBeforeVAT + vatAmt;
          $('#grand_total').val(grandTotal.toFixed(2));

          // --- Base Currency Grand Total ---
          var conversionRate = parseFloat($('#conversion_rate').val()) || 1;
          var baseCurrencyGrandTotal = grandTotal * conversionRate;
          $('#base_currency_grand_total').val(baseCurrencyGrandTotal.toFixed(2));
    }
  });

  // get_item_by_id stays outside ready (your original function)
  function get_item_by_id(row_no) {
    var item_id = $('#item' + row_no).val();
    if (item_id != '') {
      $.ajax({
        url: '<?= base_url("index.php/Item/get_item_by_id") ?>',
        type: 'POST',
        data: {
          item_id: item_id
        },
        dataType: "json",
        success: function(response) {
          // $('#brand' + row_no).val(response.brand_name);
          $('#description' + row_no).val(response.item_description);
          $('#unit' + row_no).val(response.item_unit).change();
          $('#actual_price' + row_no).val(response.mrp_aed);
          $('#unit' + row_no).prop('required', true);
          $('#quantity' + row_no).prop('required', true);
          $('#actual_price' + row_no).prop('required', true);
        }
      });
    } else {
      $('#brand' + row_no).text('');
      $('#description' + row_no).text('');
      $('#unit' + row_no).val('').change();
      $('#actual_price' + row_no).val('');
      $('#unit' + row_no).prop('required', false);
      $('#quantity' + row_no).prop('required', false);
      $('#actual_price' + row_no).prop('required', false);
    }
  }
    
  function get_quotation_info() {
		var quotation_id = document.getElementById("quotation_id").value;

		if (quotation_id != '') {
			$.ajax({
				async: "false",
				type: "POST",
				url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_quote_info",
				data: { quotation_id: quotation_id },
				dataType: "json",
				success: function (msg) {
					 document.getElementById("supplier_id").value = msg.supplier_id;
					 document.getElementById("supplier_name").value = msg.supplier_code + ' ' + msg.supplier_name;
					 get_quote_items_list(quotation_id);
           document.getElementById("sub_total").value = msg.subtotal;
           document.getElementById("discount_per").value = msg.discount_percent;
           document.getElementById("discount_amt").value = msg.discount;
           document.getElementById("vat_per").value = msg.vat_percent;
           document.getElementById("vat_amount").value = msg.vat_amt;
           document.getElementById("grand_total").value = msg.grand_total;
           document.getElementById("validity").value = msg.validity;
           document.getElementById("payment_terms").value = msg.payment_term;
           document.getElementById("delivery_terms").value = msg.delivery_term;
           document.getElementById("general_terms").value = msg.general_term;
				}
			});
		}
		else {

			document.getElementById('quote_items_list').innerHTML = '';
		}
	}

  function get_quote_items_list(quotation_id)
  {
    
    $.ajax({
          type: "POST",
          url:"<?php echo base_url()?>index.php/Ajax/get_quote_items_for_po",
          data: {quotation_id:quotation_id} ,
          success: function(msg){	       	
              document.getElementById('quote_items_list').innerHTML=msg;
        }
    });
    
  }

  function get_supplier_info() {
		var supplier_id = document.getElementById("supplier_id").value;

		if (supplier_id != '') {
			$.ajax({
				async: "false",
				type: "POST",
				url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_supplier_info",
				data: { supplier_id: supplier_id },
				dataType: "json",
				success: function (result) {
            document.getElementById("currency").value = result.currency_abbr;
				}
			});
		}
		else {
      document.getElementById("currency").value = '';
		}
	}

</script>