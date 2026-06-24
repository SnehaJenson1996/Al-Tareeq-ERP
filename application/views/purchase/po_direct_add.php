   <!-- page content -->
<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/add_po_direct_records" autocomplete="off" enctype="multipart/form-data">

  <!-- page content -->
  <div class="form-group" role="main">
    <div class="">
      <div class="page-title">

      </div>
      <div class="clearfix"></div>
      <!-- replace the inner contents of your .well with this -->
      <div class="x_content">
        <div class="well" style="overflow: auto">
          <!-- Row 1: Branch, Supplier, PO Code, PO Date -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="control-label">Branch</label>
              <select class="form-control" name="branch_id" id="branch_id" required>
                <option value="">Select</option>
                <?php foreach ($branch_records as $b) { ?>
                  <option value="<?php echo $b->branch_id ?>"><?php echo $b->branch_name ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-4">
              <label class="control-label">Supplier</label>
              <div id="supplier_dropdown_wrapper">
                <select class="form-control" name="supplier_id" id="supplier_id" required onchange="get_supplier_info()">
                  <option value="">Select</option>
                  <?php foreach ($supplier_records as $s) { ?>
                    <option value="<?php echo $s->supplier_id ?>"><?php echo $s->supplier_code . ' - ' . $s->supplier_name; ?></option>
                  <?php } ?>
                </select>
              </div>
            <small><a href="" target="_blank" class='' data-bs-toggle='modal' data-bs-target='#newSupplierModal' data-id="1" id="add_supplier_link">+ Add New Supplier</a></small>
            </div>

            <div class="col-md-4">
              <label class="control-label">PO Code</label>
              <input type="text" class="form-control" name="po_code" id="po_code" readonly value="<?php echo $Code; ?>">
            </div>
          </div>

          <!-- Row 2: Subject, Reference, Freight Mode -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="control-label">PO Date</label>
              <input type="date" class="form-control" name="po_date" id="po_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-4">
              <label class="control-label">Freight Name</label>
              <input type="text" class="form-control" name="subject" id="subject">
            </div>

            <div class="col-md-4">
              <label class="control-label">Reference</label>
              <input type="text" class="form-control" name="ref_no" id="ref_no">
            </div>
          </div>

          <!-- Row 3: Project Name, Upload -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="control-label">Freight Mode</label>
              <select class="form-control" name="freight_mode" id="freight_mode">
                <option value=""></option>
                <option value="Sea">Sea</option>
                <option value="Air">Air</option>
                <option value="Road">Road</option>
                <option value="Courier">Courier</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="control-label">Project Name</label>
              <input type="text" class="form-control" name="project" id="project">
            </div>

            <div class="col-md-4">
              <label class="control-label">Upload File</label>
              <input type="file" class="form-control" name="po_doc" id="po_doc">
            </div>
          </div>
          <div class="row mb-3">
        <!-- <div class="col-md-4">
          <label class="control-label">Prepared By</label>
          <input type="text" class="form-control" name="prepared_by" id="prepared_by"
                value="<?php echo $this->session->userdata('user_name'); ?>" readonly>
        </div> -->
            <!-- <div class="col-md-4">
              <label class="control-label">Approved By</label>
              <input type="text" class="form-control" name="approved_by" id="approved_by">
            </div> -->
          </div>
        </div>

        <div class="row col-md-12 col-sm-12" style="overflow: scroll;">
          <!-- form color picker -->
          <div class="x_content">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th width="13%">Product Code</th>
                  <th>Brand</th>
                  <th>Description</th>

                  <th>Unit</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <!-- <th>Dis 1(%)</th>
                  <th>Dis</th>
                  <th>Unit Price</th> -->
                  <th>Total</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <select class="form-control" name="item_id[]" id='item0' onchange='get_item_by_id(0)'>
                      <option value=''>Select</option>
                      <option value="new">+ Add New Product</option>
                      <?php foreach ($active_items as $item) { ?>
                        <option value='<?php echo $item->item_id ?>'><?php echo $item->item_name; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td><input class="form-control" type="text" name="item_brand[]" id="brand0"></td>
                  <td><input class="form-control" type="text" name="item_description[]" id="description0"></td>
                  <td>
                    <select class="form-control" name="item_unit[]" id='unit0'>
                      <option value=''>Select</option>
                      <?php foreach ($active_units as $unit) { ?>
                        <option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option>
                      <?php } ?>
                    </select>
                  </td>

                  <td>
                    <input class="form-control quantity" type="number" name="item_quantity[]" id="quantity0">
                  </td>
                  <td>
                    <input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price0" />
                  </td>
                  <!-- <td>
                    <input type="number" class="form-control dis_per" id="discount_per0" step='any' name="dis_per[]" />
                  </td>
                  <td>
                    <input type="number" class="form-control dis_amt" id="discount_amt0" step='any' name="dis_amt[]" />
                  </td>
                  <td>
                    <input type="number" class="form-control final_unit_price" name="final_unit_price[]" step='any' id="final_unit_price0" />
                  </td> -->
                  <td>
                    <input type="number" class="form-control total_price" id="total_price0" step='any' name="total_price[]" />
                  </td>
                  <td>
                    <a href="#" class="addRow" title="Add"><i class="fa fa-plus-circle text-success"></i></a>
                    <a href="#" class="deleteRow" title="Delete"><i class="fa fa-trash text-danger"></i></a>
                  </td>
                </tr>
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
                <input type="text" class="form-control" name="total_beforvat" id="total_beforvat" readonly>
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
                <input type="text" class="form-control" name="currency" id="currency" readonly>
              </div>

              <div class="col-md-2">
                <label class="control-label">Conversion Rate</label>
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" name="conversion_rate" id="conversion_rate">
              </div>

              <div class="col-md-2">
                <label class="control-label">Grand Total (Base Currency)</label>
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" name="base_currency_grand_total" id="base_currency_grand_total" readonly>
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
            <div class="row mb-3 mt-3">
              <div class="col-md-6">
                <label class="control-label">Delivery Terms</label>
                <textarea class="form-control" name="delivery_terms" id="delivery_terms" rows="4"><?php echo $records1[0]->delivery_term; ?></textarea>
              </div>

              <div class="col-md-6">
                <label class="control-label">General Terms</label>
                <textarea class="form-control" name="general_terms" id="general_terms" rows="4"><?php echo $records1[0]->general_term; ?></textarea>
              </div>
            </div>

          <!-- Prepared By, Checked By, Approved By row -->
          <div class="row mb-3">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Prepared By</label>
                <select class="form-control select2" id="employee_prepared" name="employee_prepared" required>
                  <option value="">Select</option>
                  <?php foreach ($employees as $s) { ?>
                  <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Checked By</label>
                <select class="form-control select2" id="employee_checked" name="employee_checked" required>
                  <option value="">Select</option>
                  <?php foreach ($employees as $s) { ?>
                  <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Approved By</label>
                <select class="form-control select2" id="employee_approved" name="employee_approved" required>
                  <option value="">Select</option>
                  <?php foreach ($employees as $s) { ?>
                  <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          </div> <!-- /.well -->
          
            <!-- Buttons Row -->
            <div class="row mb-3">
              <div class="col-md-12 text-center">
                <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
            </div>
        </div> <!-- /.x_content -->

      </div>



      <!-- /page content -->
</form>

<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addItemModalLabel">Add New Product</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="addItemModalContent">
        <!-- Item form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<div id="newSupplierModal"  class="modal fade mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- modal-xl for extra width -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Supplier Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Success message placeholder -->
      <div id="supplier-success-alert" class="alert alert-success m-3" style="display: none;">
        Supplier saved successfully!
      </div>

      <div class="modal-body" id="modal-body-content">
        <!-- Dynamic content will be injected here -->
        Loading...
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('delivery_terms');
  CKEDITOR.replace('general_terms');
</script>

<script>
  $(document).ready(function() {
    $('#branch_id').change(function() {
      var branch_id = $(this).val();

      if (branch_id) {
        $.ajax({
          url: '<?= base_url("index.php/Company/get_supplier_by_branch") ?>',
          type: 'POST',
          data: {
            branch_id: branch_id
          },
          dataType: 'json',
          success: function(data) {
            $('#supplier_id').empty().append('<option value="">-- Select Supplier --</option>');
            $.each(data, function(index, supplier) {
              $('#supplier_id').append(
                '<option value="' + supplier.supplier_id + '" ' +
                'data-tr="' + supplier.trn_no + '">' +
                supplier.supplier_name + ' (' + supplier.supplier_code + ') => ' + supplier.contact_number +
                '</option>'
              );
            });
            $('#supplier_id').trigger('change'); // Refresh select2 if used
          }
        });
      } else {
        $('#supplier_id').empty().append('<option value="">-- Select Customer --</option>');
      }
    });

    let rowIndex = 1; // start from 1 (row 0 exists in HTML)
    // Add new row
    $(document).on('click', '.addRow', function(e) {
      e.preventDefault();

      const newRow = `
        <tr>
            <td>
                <select class="form-control " name="item_id[]" id="item${rowIndex}" onchange="get_item_by_id(${rowIndex})">
                  <option value="">Select</option>
                  <option value="new">+ Add New Product</option>
                  <?php foreach ($active_items as $item) { ?>
                    <option value="<?php echo $item->item_id ?>"><?php echo $item->item_name; ?></option>
                  <?php } ?>
                </select>
            </td>
            <td><input class="form-control" type="text" name="item_brand[]" id="brand${rowIndex}"></td>
            <td><input class="form-control" type="text" name="item_description[]" id="description${rowIndex}"></td>
            <td>
                <select class="form-control" name="item_unit[]" id="unit${rowIndex}">
                    <option value="">Select</option>
                    <?php foreach ($active_units as $unit) { ?>
                        <option value="<?php echo $unit->unit_id ?>"><?php echo $unit->unit_name; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input class="form-control quantity" type="number" name="item_quantity[]" id="quantity${rowIndex}"></td>
            <td><input type="number" class="form-control unit_price" name="unit_price[]" step="any" id="unit_price${rowIndex}" /></td>
            <td><input type="number" class="form-control total_price" name="total_price[]" step="any" id="total_price${rowIndex}" /></td>
            <td>
              <a href="#" class="addRow" title="Add"><i class="fa fa-plus-circle text-success"></i></a>
              <a href="#" class="deleteRow" title="Delete"><i class="fa fa-trash text-danger"></i></a>
            </td>
        </tr>`;

      $('#datatable-responsive tbody').append(newRow);

      // reinitialize select2 for the added select (if you're using Select2)
      if (typeof $(`#item${rowIndex}`).select2 === 'function') {
        $(`#item${rowIndex}`).select2();
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

    // Row-level change — FIXED: selector string closed correctly
    $(document).on('input change', '.quantity, .unit_price, .dis_per, .dis_amt', function() {
      var $row = $(this).closest('tr');
      calculateRow($row);
      calculateAll();
    });

    // Global discount/VAT/charges change
    $('#discount_per, #discount_amt, #vat_per, #transportation_charge, #customs_charge, #other_charge').on('input change', calculateAll);

    function calculateRow($row) {
      let qty = parseFloat($row.find('.quantity').val()) || 0;
      let unitPrice = parseFloat($row.find('.unit_price').val()) || 0;

      let disPer = parseFloat($row.find('.dis_per').val()) || 0;
      let disAmt = parseFloat($row.find('.dis_amt').val()) || 0;

      let rowTotal = qty * unitPrice;

      // Sync percent/amount (row)
      if ($row.find('.dis_per').is(':focus')) {
        disAmt = (rowTotal * disPer) / 100;
        $row.find('.dis_amt').val(disAmt.toFixed(2));
      } else if ($row.find('.dis_amt').is(':focus')) {
        disPer = rowTotal ? (disAmt / rowTotal) * 100 : 0;
        $row.find('.dis_per').val(disPer.toFixed(2));
      } else {
        disAmt = (rowTotal * disPer) / 100;
        $row.find('.dis_amt').val(disAmt.toFixed(2));
      }

      let finalRowTotal = rowTotal - disAmt;

      // Final Unit Price
      let finalUnitPrice = (qty > 0) ? finalRowTotal / qty : 0;
      $row.find('.final_unit_price').val(finalUnitPrice.toFixed(2));

      // Total Price (row)
      $row.find('.total_price').val(finalRowTotal.toFixed(2));
    }

    function calculateAll() {
      let subtotal = 0;

      // Sum total from all rows
      $('#datatable-responsive tbody tr').each(function() {
        subtotal += parseFloat($(this).find('.total_price').val()) || 0;
      });

      $('#sub_total').val(subtotal.toFixed(2));

      // Global Discount
      let discountPer = parseFloat($('#discount_per').val()) || 0;
      let discountAmt = parseFloat($('#discount_amt').val()) || 0;

      if ($('#discount_per').is(':focus')) {
        discountAmt = (subtotal * discountPer) / 100;
        $('#discount_amt').val(discountAmt.toFixed(2));
      } else if ($('#discount_amt').is(':focus')) {
        discountPer = subtotal ? (discountAmt / subtotal) * 100 : 0;
        $('#discount_per').val(discountPer.toFixed(2));
      } else {
        discountAmt = (subtotal * discountPer) / 100;
        $('#discount_amt').val(discountAmt.toFixed(2));
      }

      let afterDiscount = subtotal - discountAmt;


      // --- Charges ---
      var transcharg = parseFloat($('#transportation_charge').val()) || 0;
      var customcharg = parseFloat($('#customs_charge').val()) || 0;
      var othercharg = parseFloat($('#other_charge').val()) || 0;

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
    }
  }); // end ready

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

  $(document).ready(function() {
    // Form validation before submit
    $("#main").on("submit", function(e) {
      alert("Validating form..."); // Debug alert
      let isValid = true;
      let errorMsg = "";

      // --- Validate main PO fields ---
      if ($("#branch_id").val() === "") {
        errorMsg += "• Please select a Branch.\n";
        isValid = false;
      }

      if ($("#supplier_id").val() === "") {
        errorMsg += "• Please select a Supplier.\n";
        isValid = false;
      }

      if ($("#po_date").val() === "") {
        errorMsg += "• Please select a PO Date.\n";
        isValid = false;
      }

      // --- Validate at least one item row ---
      let hasItem = false;
      $("#datatable-responsive tbody tr").each(function(index) {
        const item = $(this).find("select[name='item_id[]']").val();
        const qty = parseFloat($(this).find("input[name='item_quantity[]']").val());
        const unitPrice = parseFloat($(this).find("input[name='unit_price[]']").val());

        // At least one valid item
        if (item && qty > 0 && unitPrice > 0) {
          hasItem = true;
        }

        // Row-level validation
        if (item === "" && (qty > 0 || unitPrice > 0)) {
          errorMsg += `• Row ${index + 1}: Please select a Product Code.\n`;
          isValid = false;
        } else if (item !== "" && (isNaN(qty) || qty <= 0)) {
          errorMsg += `• Row ${index + 1}: Please enter a valid Quantity.\n`;
          isValid = false;
        } else if (item !== "" && (isNaN(unitPrice) || unitPrice <= 0)) {
          errorMsg += `• Row ${index + 1}: Please enter a valid Unit Price.\n`;
          isValid = false;
        }
      });

      if (!hasItem) {
        errorMsg += "• Please add at least one valid item with quantity and price.\n";
        isValid = false;
      }

      // --- Validate totals ---
      if ($("#grand_total").val() === "" || parseFloat($("#grand_total").val()) <= 0) {
        errorMsg += "• Grand Total cannot be zero.\n";
        isValid = false;
      }

      // --- If validation fails ---
      if (!isValid) {
        e.preventDefault();
        alert("Please fix the following errors:\n\n" + errorMsg);
      }
    });

    // Restrict invalid inputs (negative or non-numeric)
    $(document).on('input', "input[type='number']", function() {
      let val = $(this).val();
      if (val < 0) $(this).val('');
    });

    $('#add_supplier_link').on('click', function (e) {
      e.preventDefault();

      $('#modal-body-content').html("Loading...");
      $('#newSupplierModal').modal('show');

      $.ajax({
          url: "<?= base_url('index.php/Ajax/add_new_supplier') ?>",
          type: "POST",
          success: function(response) {
              console.log("AJAX Success");
              $('#modal-body-content').html(response);
          },
          error: function(xhr, status, error) {
              console.log("AJAX Error:");
              console.log(xhr.responseText);
              alert("AJAX error: " + error);
          }
      });
    });
  });

  $(document).on('change', 'select[name="item_id[]"]', function() {
    if ($(this).val() === 'new') {
      const currentSelect = $(this);
      $('#addItemModal').data('currentSelect', currentSelect);

      // Load the form via AJAX
      $.ajax({
        url: '<?= base_url("index.php/Item/add_item_form") ?>',
        type: 'GET',
        success: function(html) {
          //   console.log('Loaded HTML:', html);
          // console.log('Form loaded successfully');
          // console.log('Loaded HTML contains addItemForm:', html.indexOf('id="addItemForm"'));
          // console.log('Loaded HTML contains form tag:', html.indexOf('<form'));
          $('#addItemModalContent').html(html);
  console.log($('#addItemModalContent').find('form').length);
  console.log($('#addItemModalContent').html());
  console.log($('#addItemModalContent').find('form').length);
  console.log($('#addItemModalContent').find('#addItemForm').length); 
         $('#addItemModal').modal('show');
          $('#addItemModalContent').find('#addItemForm').off('submit').on('submit', function(e) {
            alert(11);
            e.preventDefault();
            const currentSelect = $('#addItemModal').data('currentSelect');
            const formData = new FormData(this);
            console.log('Submitting form with action:', $(this).attr('action'));
            $.ajax({
              url: $(this).attr('action'),
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(response) {
                if (response.status === 'success') {
                  $('#addItemModal').modal('hide');
                  if (currentSelect && currentSelect.length) {
                    currentSelect.append(
                      $('<option>', {
                        value: response.item_id,
                        text: response.item_name
                      })
                    ).val(response.item_id).trigger('change');
                  }
                } else {
                  alert(response.message || 'Unable to save item.');
                }
              },
              error: function(xhr, status, error) {
                console.error('Item save failed', xhr, status, error);
                alert('Item save failed: ' + error);
              }
            });
          });
        },
        error: function(xhr, status, error) {
          console.error('Failed to load add item form', status, error, xhr.responseText);
          alert('Failed to load item form. Error: ' + error);
        }
      });

      // Reset dropdown until new item is saved
      $(this).val('').trigger('change');
    } else {
      // If normal item selected, populate brand/description/unit via your existing get_item_by_id function
      const id = $(this).attr('id').replace('item', '');
      get_item_by_id(id);
    }
  });

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