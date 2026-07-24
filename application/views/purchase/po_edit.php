<?php
$page_name2 = 'Purchase/purchase_order_list';
$user = $this->session->userdata('user_id');
?>
<form id="main" method="post" action="<?php echo base_url('index.php/Purchase/update_purchase_order'); ?>" autocomplete="off" enctype="multipart/form-data">

  <div class="x_content">
    <div class="well" style="overflow: auto;">

      <!-- Row 1: Quotation, PO Code, PO Date -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="control-label">Quotation</label>
          <select class="form-control" name="quotation_id" id="quotation_id" required onchange="get_quotation_info()">
            <option value="<?php echo isset($records1[0]->qtn_id) ? $records1[0]->qtn_id : ''; ?>">
              <?php echo isset($records1[0]->quotation_code) ? $records1[0]->quotation_code : ''; ?>
            </option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="control-label">PO Code</label>
          <input type="text" class="form-control" name="po_code" id="po_code" readonly value="<?php echo isset($records1[0]->po_code) ? $records1[0]->po_code : ''; ?>">
          <input type="hidden" name="po_id" id="po_id" value="<?php echo isset($records1[0]->po_id) ? $records1[0]->po_id : ''; ?>">
        </div>
        <div class="col-md-4">
          <label class="control-label">PO Date</label>
          <input type="date" class="form-control" name="po_date" id="po_date" value="<?php echo isset($records1[0]->po_date) ? $records1[0]->po_date : ''; ?>">
        </div>
      </div>

      <!-- Row 2: Supplier, Reference -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="control-label">Branch</label>
          <input type="text" class="form-control" name="Branch_name" id="Branch_name" value="<?php echo isset($records1[0]->branch_name) ? $records1[0]->branch_name : ''; ?>" readonly>
          <input type="hidden" name="Branch_id" id="Branch_id" value="<?php echo isset($records1[0]->branch_id) ? $records1[0]->branch_id : ''; ?>">
        </div>
        <div class="col-md-4">
          <label class="control-label">Supplier</label>
          <input type="text" class="form-control" name="supplier_name" id="supplier_name" value="<?php echo isset($records1[0]->supplier_name) ? $records1[0]->supplier_name : ''; ?>" readonly>
          <input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo isset($records1[0]->supplier_id) ? $records1[0]->supplier_id : ''; ?>">
        </div>
        <div class="col-md-4">
          <label class="control-label">Reference</label>
          <input type="text" class="form-control" name="ref_no" id="ref_no" value="<?php echo isset($records1[0]->supplier_ref) ? $records1[0]->supplier_ref : ''; ?>">
        </div>
      </div>

      <!-- Row 3: Subject, Freight Mode -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="control-label">Freight Mode</label>
          <select class="form-control" name="freight_mode" id="freight_mode">
            <option value="">--Select--</option>
            <option value="Sea" <?php echo (isset($records1[0]->freight_mode) && $records1[0]->freight_mode == 'Sea') ? 'selected' : ''; ?>>Sea</option>
            <option value="Air" <?php echo (isset($records1[0]->freight_mode) && $records1[0]->freight_mode == 'Air') ? 'selected' : ''; ?>>Air</option>
            <option value="Road" <?php echo (isset($records1[0]->freight_mode) && $records1[0]->freight_mode == 'Road') ? 'selected' : ''; ?>>Road</option>
            <option value="Courier" <?php echo (isset($records1[0]->freight_mode) && $records1[0]->freight_mode == 'Courier') ? 'selected' : ''; ?>>Courier</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="control-label">Freight Forwarder</label>
          <input type="text" class="form-control" name="subject" id="subject" value="<?php echo isset($records1[0]->subject) ? $records1[0]->subject : ''; ?>">
        </div>
        <div class="col-md-4">
          <label class="control-label">Project Name</label>
          <input type="text" class="form-control" name="project" id="project" readonly>
        </div>

      </div>

      <!-- Row 4: Upload Document -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="control-label">Upload Document</label>
          <input type="file" class="form-control" name="po_doc" id="po_doc">
        </div>
        <div class="col-md-4">
          <?php if (!empty($po_doc[0]->doc_path)) { ?>
            <a href="<?php echo base_url('public/uploaded_documents/' . $po_doc[0]->doc_path); ?>" target="_blank">
              <?php echo $po_doc[0]->doc_path; ?>
            </a>
          <?php } ?>
        </div>
        <!-- <div class="col-md-4">
          <label>Prepared By</label>
          <input type="text" class="form-control" name="sales_person" value="<?php echo isset($records1[0]->sales_person) ? $records1[0]->sales_person : ''; ?>">
        </div> -->
      </div>

      <!-- Items Table -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="table-responsive">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap">
              <thead>
                <tr>
                  <th>Product Code</th>
                  <!-- <th>Brand</th> -->
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Packing</th>
                  <th>Price</th>
                  <!-- <th>Dis (%)</th>
                  <th>Dis</th>
                  <th>Unit Price</th> -->
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 5000;
                foreach ($records2 as $r) { ?>
                  <tr>
                    <td>
                      <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->product_name; ?>" readonly />
                      <input type="hidden" name="item_id[]" value="<?php echo $r->product_id; ?>" />
                    </td>
                    <!-- <td><input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" readonly /></td> -->
                    <td><input type="text" class="form-control" name="item_description[]" value="<?php echo $r->description; ?>" /></td>
                    <td><input type="number" class="form-control qty" name="item_quantity[]" value="<?php echo $r->quantity; ?>" /></td>
                    <td><input type="text" class="form-control qty" name="item_unit[]" value="<?php echo $r->unit_name; ?>" /></td>
                    <!-- <td><select class="form-control" name="item_unit[]"><option>KG</option></select></td> -->
                    <td><select class="form-control" name="item_packing[]">
                        <option>CTN</option>
                      </select></td>
                    <td><input type="number" class="form-control unit_price" name="unit_price[]" step="any" value="<?php echo $r->price; ?>" /></td>
                    <!-- <td><input type="number" class="form-control dis_per" name="dis_per[]" step="any" value="<?php echo $r->dis_per; ?>"/></td>
                    <td><input type="number" class="form-control dis_amt" name="dis_amt[]" step="any" value="<?php echo $r->dis_amt; ?>"/></td>
                    <td><input type="number" class="form-control final_unit_price" name="final_unit_price[]" step="any" value="<?php echo $r->unit_price; ?>"/></td> -->
                    <td><input type="number" class="form-control total_price" name="total_price[]" step="any" value="<?php echo $r->total; ?>" /></td>
                  </tr>
                <?php $i++;
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Row 5: Totals -->
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Sub Total</label>
          <input type="text" class="form-control" id="sub_total" name="sub_total" value="<?php echo isset($records1[0]->sub_total) ? $records1[0]->sub_total : ''; ?>" readonly>
        </div>
        <div class="col-md-2">
          <label>Discount(%)</label>
          <input type="text" class="form-control" name="discount_per" id="discount_per" value="<?php echo isset($records1[0]->discount_percent) ? $records1[0]->discount_percent : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Discount</label>
          <input type="text" class="form-control" name="discount_amt" id="discount_amt" value="<?php echo isset($records1[0]->discount) ? $records1[0]->discount : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Transportation Charge</label>
          <input type="number" class="form-control" name="transportation_charge" id="transportation_charge" value="<?php echo isset($records1[0]->trans_charge) ? $records1[0]->trans_charge : ''; ?>">
        </div>
      </div>

      <!-- Row 6: Additional Charges -->
      <div class="row mb-3">

        <div class="col-md-3">
          <label>Freight Charge</label>
          <input type="number" class="form-control" name="customs_charge" id="customs_charge" value="<?php echo isset($records1[0]->cust_charge) ? $records1[0]->cust_charge : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Other Charges</label>
          <input type="number" class="form-control" name="other_charge" id="other_charge" value="<?php echo isset($records1[0]->add_charge) ? $records1[0]->add_charge : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Total before VAT</label>
          <input type="text" class="form-control" name="total_beforvat" id="total_beforvat" value="<?php echo isset($records1[0]->total_beforevat) ? $records1[0]->total_beforevat : ''; ?>" readonly>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-2">
          <label>VAT(%)</label>
          <input type="text" class="form-control" name="vat_per" id="vat_per" value="<?php echo isset($records1[0]->vat_percent) ? $records1[0]->vat_percent : ''; ?>">
        </div>
        <div class="col-md-2">
          <label>VAT Amount</label>
          <input type="text" class="form-control" name="vat_amount" id="vat_amount" value="<?php echo isset($records1[0]->vat_amt) ? $records1[0]->vat_amt : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Grand Total</label>
          <input type="text" class="form-control" name="grand_total" id="grand_total" value="<?php echo isset($records1[0]->grand_total) ? $records1[0]->grand_total : ''; ?>" readonly>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-3">
          <label>Currency</label>
          <input type="text" class="form-control" name="currency" id="currency" value="<?php echo isset($records1[0]->currency_abbr) ? $records1[0]->currency_abbr : ''; ?>" readonly>
        </div>
        <div class="col-md-3">
          <label>Conversion Rate</label>
          <input type="text" class="form-control" name="conversion_rate" id="conversion_rate" value="<?php echo isset($records1[0]->conversion_rate) ? $records1[0]->conversion_rate : ''; ?>">
        </div>
        <div class="col-md-3">
          <label>Grand Total (Base Currency)</label>
          <input type="text" class="form-control" name="base_currency_grand_total" id="base_currency_grand_total" value="<?php echo isset($records1[0]->base_currency_grand_total) ? $records1[0]->base_currency_grand_total : ''; ?>" readonly>
        </div>
      </div>

      <!-- Row 7: Terms -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Validity</label>
          <input type="text" class="form-control" name="validity" value="<?php echo isset($records1[0]->validity) ? $records1[0]->validity : ''; ?>">
        </div>
        <div class="col-md-6">
          <label>Payment Terms</label>
          <input type="text" class="form-control" name="payment_terms" value="<?php echo isset($records1[0]->payment_term) ? $records1[0]->payment_term : ''; ?>">
        </div>

      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Delivery Terms</label>
          <textarea class="form-control" name="delivery_terms" id="delivery_terms"><?php echo isset($records1[0]->delivery_term) ? $records1[0]->delivery_term : ''; ?></textarea>
        </div>
        <div class="col-md-6">
          <label>General Terms</label>
          <textarea class="form-control" name="general_terms" id="general_terms"><?php echo isset($records1[0]->general_term) ? $records1[0]->general_term : ''; ?></textarea>
        </div>
      </div>

      <!-- Row 8: Prepared & Approved By -->
      <div class="row mb-3">
        <!-- <div class="col-md-3">
          <label>Prepared By</label>
          <input type="text" class="form-control" name="sales_person" value="<?php echo isset($records1[0]->sales_person) ? $records1[0]->sales_person : ''; ?>">
        </div> -->
        <!-- <div class="col-md-3">
          <label>Approved By</label>
          <input type="text" class="form-control" name="approved_by" value="<?php echo isset($records1[0]->approved_by) ? $records1[0]->approved_by : ''; ?>">
        </div> -->
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

      <!-- Row 9: Buttons -->
      <div class="row mb-3">
        <div class="col-md-12">
          <!-- <button type="reset" class="btn btn-primary">Reset</button> -->
          <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
          <button type="submit" name="action" value="update" class="btn btn-success">
            Update
          </button>
          <button type="submit" name="action" value="approve" class="btn btn-warning">
            Approve PO
          </button>
        </div>
      </div>

    </div>
  </div>
</form>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('delivery_terms', {
    height: 120,
    removePlugins: 'elementspath',
    resize_enabled: false
  });
  CKEDITOR.replace('general_terms', {
    height: 120,
    removePlugins: 'elementspath',
    resize_enabled: false
  });

  function get_quotation_info() {
    var quotation_id = document.getElementById("quotation_id").value;

    if (quotation_id != '') {
      $.ajax({
        async: "false",
        type: "POST",
        url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_quote_info",
        data: {
          quotation_id: quotation_id
        },
        dataType: "json",
        success: function(msg) {
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
    } else {

      document.getElementById('quote_items_list').innerHTML = '';
    }
  }

  function get_quote_items_list(quotation_id) {

    $.ajax({
      type: "POST",
      url: "<?php echo base_url() ?>index.php/Ajax/get_quote_items_for_po",
      data: {
        quotation_id: quotation_id
      },
      success: function(msg) {
        document.getElementById('quote_items_list').innerHTML = msg;
      }
    });

  }
  $(document).ready(function() {
    // Event listener for input changes
    $(document).on('input change', '.qty, .unit_price, .dis_per, .dis_amt, .dis_per2, .dis_amt2', function() {
      var row_id = $(this).closest('tr');

      calculateRow(row_id);
      calculateAll();
    });
    // Event listener for global discount, VAT, and conversion rate
    $('#discount_per, #discount_amt, #vat_per, #transportation_charge, #customs_charge, #other_charge, #conversion_rate')
      .on('input change', function() {
        calculateAll();
      });

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
      var subtotal = 0;

      // Sum row totals
      $('tbody tr').each(function() {
        var rowTotal = parseFloat($(this).find('.total_price').val()) || 0;
        subtotal += rowTotal;
      });
      $('#sub_total').val(subtotal.toFixed(2));

      // Global discount % and amount
      var discountPer = parseFloat($('#discount_per').val()) || 0;
      var discountAmt = parseFloat($('#discount_amt').val()) || 0;

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

      var afterDiscount = subtotal - discountAmt;

      // Charges
      var transport = parseFloat($('#transportation_charge').val()) || 0;
      var customs = parseFloat($('#customs_charge').val()) || 0;
      var other = parseFloat($('#other_charge').val()) || 0;

      var totalBeforeVat = afterDiscount + transport + customs + other;
      $('#total_beforvat').val(totalBeforeVat.toFixed(2));

      // VAT
      var vatPer = parseFloat($('#vat_per').val()) || 0;
      var vatAmt = (totalBeforeVat * vatPer) / 100;
      $('#vat_amount').val(vatAmt.toFixed(2));

      // Grand Total
      var grandTotal = totalBeforeVat + vatAmt;
      $('#grand_total').val(grandTotal.toFixed(2));

      // Base Currency Grand Total
      var conversionRate = parseFloat($('#conversion_rate').val()) || 1;
      var baseCurrencyGrandTotal = grandTotal * conversionRate;
      $('#base_currency_grand_total').val(baseCurrencyGrandTotal.toFixed(2));
    }

    // Initial calculation
    calculateAll();
  });
</script>