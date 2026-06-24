<!-- direct_invoice_form.php -->

<div class="clearfix"></div>

<div class="x_panel">
  <div class="x_content">

    <form class="form-horizontal" id="direct_invoice_form" method="post" action="<?= base_url('index.php/Sales/save_direct_invoice') ?>">

      <!-- Invoice Details -->
      <div class="container p-3 border rounded" style="background:#f9f9f9;">
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Invoice Code</label>
            <input type="text" class="form-control" name="invoice_code" value="<?= $invoice_code ?? '' ?>" readonly>
          </div>
          <div class="col-md-6">
            <label>Invoice Date</label>
            <input type="date" class="form-control" name="invoice_date" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Branch</label>
            <select name="branch_id" id="branch_id" class="form-control" required>
              <option value="">--Select Branch--</option>
              <?php foreach ($branch_list as $branch): ?>
                <option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Customer</label>
          <div id="customer_dropdown_wrapper">
                                            <?= form_error('customer_id', '<small class="text-danger">', '</small>') ?>
                                            <select name="customer_id" id="customer_id" class="form-control select2" >
                                            </select>
                                        </div>
                                        <small><a href="" target="_blank" class='view-employees' data-bs-toggle='modal' data-bs-target='#myModal' data-id="1">+ Add New Customer</a></small>
                                    </div>
          <div class="col-md-6">
            <label>Customer TRN</label>
            <input type="text" class="form-control" id="customer_trn" name="customer_trn">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Delivery Mode</label>
            <select class="form-control" id="delivery_mode" name="delivery_mode">
              <option value="">--Select--</option>
              <option value="Road">Road</option>
              <option value="Air">Air</option>
              <option value="Sea">Sea</option>
              <option value="Courier">Courier</option>
              <option value="Rail">Rail</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Delivered By</label>
            <input type="text" class="form-control" name="deliverd_by" id="deliverd_by">
          </div>
        </div>

      </div>

      <div class="row mb-3">
  
  <!-- Supplier Ref -->
  <div class="col-md-3">
    <label>Supplier's Ref</label>
    <input type="text" class="form-control" name="supplier_ref">
  </div>

  <!-- Other Reference -->
  <div class="col-md-3">
    <label>Other Reference</label>
    <input type="text" class="form-control" name="other_reference">
  </div>

  <!-- Buyer's Order No -->
  <div class="col-md-3">
    <label>Buyer's Order No</label>
    <input type="text" class="form-control" name="buyers_order_no">
  </div>

  <!-- Dated -->
  <div class="col-md-3">
    <label>Dated</label>
    <input type="date" class="form-control" name="buyers_order_date" value="<?= date('Y-m-d') ?>">
  </div>

</div>

      <!-- Product Table -->
      <div class="row mb-3">
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="direct_invoice_table">
            <thead>
             <tr>
    <th style="width: 20%;">Product</th>
    <th style="width: 20%;">Description</th>
    <th style="width: 10%;">Unit</th>
    <th style="width: 10%;">Quantity</th>
    <th style="width: 10%;">Unit Price</th>
    <th style="width: 10%;">Discount</th>
    <th style="width: 15%;">Total</th>
    <th style="width: 5%;">Action</th>    
</tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select name="product_id[]" class="form-control product_select" required>
                    <option value="">--Select Product--</option>
                    <?php foreach ($products as $prod): ?>
                      <option
                        value="<?= $prod->item_id ?>"
                            data-unit_id="<?= $prod->unit_id ?>"

                        data-unit="<?= $prod->unit_name ?>"
                        data-price="<?= $prod->unit_price ?>"
                        data-item_description="<?= $prod->item_description ?>">
                        <?= $prod->item_name ?> (<?= $prod->item_code ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="text" class="form-control product_desc" name="product_desc[]" ></td>
                <td>
                  <select name="unit[]" class="form-control unit_select" readonly>
                    <option value="">--Select Unit--</option>
                  </select>
                </td>
                <td><input type="number" class="form-control qty" name="quantity[]" value="1" min="1"></td>
                <td><input type="text" class="form-control unit_price" name="unit_price[]" ></td>
                <td><input type="text" class="form-control discount" name="discount[]"></td>
                <td><input type="text" class="form-control total" name="total[]" readonly></td>
                <td>
                    <button type="button" class="btn-danger removeProductRow">🗑</button>
                </td>
                    </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-secondary" id="add_row">Add Product</button>
        </div>
      </div>

      <!-- Totals -->
      <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Sub Total</label>
          <input type="text" class="form-control" name="sub_total" readonly>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Discount %</label>
          <input type="text" class="form-control add_discount" name="add_discount_per" value="0">
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Discount Amt</label>
          <input type="text" class="form-control add_discount_amount" name="add_discount_amt" readonly>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Payment / Advance Received</label>
          <input type="text" class="form-control" name="inv_advance_amt" id="inv_advance_amt" placeholder="0.00">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Retention</label>
          <input type="text" class="form-control" name="inv_retention_amt" id="inv_retention_amt" placeholder="0.00">
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Total before VAT</label>
          <input type="text" class="form-control total_before_vat" name="total_before_vat" readonly>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>VAT %</label>
          <input type="text" class="form-control" name="vat_per" value="5">
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
          <label>VAT Amt</label>
          <input type="text" class="form-control" name="vat_amount" readonly>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
          <label>Grand Total</label>
          <input type="text" class="form-control" name="grand_total" readonly>
        </div>
      </div>
      <!-- Remarks -->
      <div class="row mb-3">
        <div class="col-md-12">
          <label>Remarks</label>
          <textarea class="form-control" name="remarks"></textarea>
        </div>
      </div>
      <!--Bank account details--->
      <div class="x_content well mt-4 branch_bank_details" id="branch_bank_details">

      </div>


      <div class="x_content well mt-4">
        <h5><strong>Sales Invoice Account Entry</strong></h5>

        <div class="row mt-3">
          <!-- Debit Table -->
          <div class="col-md-6 col-sm-12">
            <label class="control-label"><strong>Debit Customer (Dr)</strong></label>
            <table class="table table-bordered table-hover" id="inv_dr_table">
              <thead>
                <tr>
                  <th>Account</th>
                  <th>Amount (AED)</th>
                </tr>
              </thead>
              <tbody id="inv_dr_body">
                <tr id="inv_dr_addr0">
                  <td>
                    <select class="form-control select2" id="inv_debtor0" name="inv_debtor[]">
                      <option value="">Select</option>
                      <?php foreach ($sundry_accounts1 as $row) { ?>
                        <!-- <option <?php //if ($row->account_id == 1125) echo 'selected'; 
                                      ?>  -->
                        <option value="<?php echo $row->account_id; ?>">
                          <?php echo $row->account_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="number" step="0.001" name="inv_dr_amount[]" id="inv_dr_amount0"
                      class="form-control debit_sum" min="0">
                  </td>
                </tr>
                <tr id="inv_dr_addr1"></tr>
              </tbody>
            </table>
          </div>

          <!-- Credit Table -->
          <div class="col-md-6 col-sm-12">
            <label class="control-label"><strong>Credit Account (Cr)</strong></label>
            <table class="table table-bordered table-hover" id="inv_cr_table">
              <thead>
                <tr>
                  <th>Account</th>
                  <th>Amount (AED)</th>
                  <th width="10%">
                    <a id="inv_cr_add_row" title="Add" class="btn btn-sm bg-orange">
                      <span class="fa fa-plus"></span>
                    </a>
                  </th>
                </tr>
              </thead>
              <tbody id="inv_cr_body">
                <tr id="inv_cr_addr0">
                  <td>
                    <select class="form-control select2" id="inv_creditor0" name="inv_creditor[]">
                      <option value="">Select</option>
                      <?php foreach ($sundry_accounts2 as $row) { ?>
                        <option <?php if ($row->account_id == 1125) echo 'selected'; ?>
                          value="<?php echo $row->account_id; ?>">
                          <?php echo $row->account_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount0"
                      class="form-control credit_sum" min="0">
                  </td>
                  <td>
                    <a title="Delete" onclick="remove_row_inv_cr(0)" class="btn btn-xs bg-orange">
                      <span class="fa fa-trash"></span>
                    </a>
                  </td>
                </tr>

                <tr id="inv_cr_addr1">
                  <td>
                    <select class="form-control select2" id="inv_creditor1" name="inv_creditor[]">
                      <option value="">Select</option>
                      <?php foreach ($sundry_accounts3 as $row) { ?>
                        <option <?php if ($row->account_id == 228) echo 'selected'; ?>
                          value="<?php echo $row->account_id; ?>">
                          <?php echo $row->account_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount1"
                      class="form-control credit_sum" min="0">
                  </td>
                  <td>
                    <a title="Delete" onclick="remove_row_inv_cr(1)" class="btn btn-xs bg-orange">
                      <span class="fa fa-trash"></span>
                    </a>
                  </td>
                </tr>

                <tr id="inv_cr_addr2">
                  <td>
                    <select class="form-control select2" id="inv_creditor2" name="inv_creditor[]">
                      <option value="">Select</option>
                      <?php foreach ($sundry_accounts3 as $row) { ?>
                        <option <?php if ($row->account_id == 1122) echo 'selected'; ?>
                          value="<?php echo $row->account_id; ?>">
                          <?php echo $row->account_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount2"
                      class="form-control credit_sum" min="0">
                  </td>
                  <td>
                    <a title="Delete" onclick="remove_row_inv_cr(2)" class="btn btn-xs bg-orange">
                      <span class="fa fa-trash"></span>
                    </a>
                  </td>
                </tr>

                <tr id="inv_cr_addr3">
                  <td>
                    <select class="form-control select2" id="inv_creditor3" name="inv_creditor[]">
                      <option value="">Select</option>
                      <?php foreach ($sundry_accounts3 as $row) { ?>
                        <option <?php if ($row->account_id == 694) echo 'selected'; ?>
                          value="<?php echo $row->account_id; ?>">
                          <?php echo $row->account_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount3"
                      class="form-control credit_sum" min="0" value="0">
                  </td>
                  <td>
                    <a title="Delete" onclick="remove_row_inv_cr(3)" class="btn btn-xs bg-orange">
                      <span class="fa fa-trash"></span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <input type="hidden" name="direct_invoice" value="1">

      <div class="x_content well mt-4">
        <div class="row mt-3">
          <div class="col-md-6">
            <!-- Employee Name -->
            <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Prepared By:</label>
                <div class="col-md-6 col-sm-6 ">
                  <select class="form-control select2" 
                    id="employee_prepared" name="employee_prepared">
                    <option value="">Select</option>
                    <?php foreach ($employees as $s) { ?>
                    <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>
          </div>
          <!-- <div class="col-md-6">
            <div class="item form-group">
                <label class="col-form-label col-md-3 col-sm-3 label-align">Received By:</label>
                <div class="col-md-6 col-sm-6 ">
                  <select class="form-control select2" 
                    id="employee_received" name="employee_received">
                    <option value="">Select</option>
                    <?php foreach ($employees as $s) { ?>
                    <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>
          </div>       -->
        </div>      
          <!-- Submit -->
          <div class="row mt-4">
            <div class="col-md-12 text-end">
              <button type="reset" class="btn btn-primary">Cancel</button>
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>      
      </div>
    </form>
  </div>
</div>
<!-- Modal -->
<div id="myModal"  class="modal fade mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- modal-xl for extra width -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
	  <!-- Success message placeholder -->
  <div id="customer-success-alert" class="alert alert-success m-3" style="display: none;">
    Customer saved successfully!
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
<script>

  $(document).ready(function() {
  $('.view-employees').on('click', function (e) {
    e.preventDefault();

    $('#modal-body-content').html("Loading...");
    $('#myModal').modal('show');

    $.ajax({
        url: "<?= base_url('index.php/Ajax/add_new_customer') ?>",
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
 

function openModal() {
  document.getElementById('myModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('myModal').style.display = 'none';
}
  // Function to calculate row total
  function calculateRowTotal(row) {
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let price = parseFloat(row.find('.unit_price').val()) || 0;
    let discount = parseFloat(row.find('.discount').val()) || 0;

    // Total for this row after discount
    let total = (qty * price) - discount;
    row.find('.total').val(total.toFixed(2));

    // Recalculate invoice totals
    calculateInvoiceTotals();
  }

  // Function to calculate invoice totals
  function calculateInvoiceTotals() {
    let subTotal = 0;

    $('#direct_invoice_table tbody tr').each(function() {
      let rowTotal = parseFloat($(this).find('.total').val()) || 0;
      subTotal += rowTotal;
    });

    $('input[name="sub_total"]').val(subTotal.toFixed(2));

    // Invoice-level discount %
    let discount_per = parseFloat($('input[name="add_discount_per"]').val()) || 0;
    let discount_amt = subTotal * discount_per / 100;
    $('input[name="add_discount_amt"]').val(discount_amt.toFixed(2));

    let advance_amt = parseFloat($('input[name="inv_advance_amt"]').val()) || 0;
    let retention_amt = parseFloat($('input[name="inv_retention_amt"]').val()) || 0;
    if (advance_amt < 0) advance_amt = 0;
    if (retention_amt < 0) retention_amt = 0;

    let total_before_vat = subTotal - discount_amt - advance_amt - retention_amt;
    if (total_before_vat < 0) total_before_vat = 0;
    $('input[name="total_before_vat"]').val(total_before_vat.toFixed(2));
    // VAT
    let vat_per = parseFloat($('input[name="vat_per"]').val()) || 0;
    let vat_amt = (total_before_vat) * vat_per / 100;
    $('input[name="vat_amount"]').val(vat_amt.toFixed(2));

    // Grand total
    let grand_total = total_before_vat + vat_amt;
    $('input[name="grand_total"]').val(grand_total.toFixed(2));

    ///Accounts data  qtn_customer
    $("#inv_dr_amount0").val(grand_total || '');
    $("#inv_cr_amount0").val(subTotal || '');
    $("#inv_cr_amount1").val(vat_amt || '');
    $("#inv_cr_amount2").val(discount_amt || '');

  }

  // Recalculate row total when quantity or row discount changes
  $(document).on('input', '.qty, .discount', function() {
    let row = $(this).closest('tr');
    calculateRowTotal(row);
  });

  // Update product selection (description, unit, price)
  $(document).on('change', '.product_select', function() {
    let row = $(this).closest('tr');
    let selected = $(this).find(':selected');
    let item_description = selected.data('item_description');

    // Update description
    row.find('.product_desc').val(item_description);

    // Update unit
    let unitVal = selected.data('unit');
    let unitSelect = row.find('.unit_select');
    unitSelect.empty().append(`<option value="${unitVal}" selected>${unitVal}</option>`);

    // Update unit price
    let price = parseFloat(selected.data('price')) || 0;
    row.find('.unit_price').val(price.toFixed(2));

    // Clear invoice-level fields
    $('input[name="add_discount_per"]').val(0);
    $('input[name="inv_advance_amt"]').val('');
    $('input[name="inv_retention_amt"]').val('');

    // Recalculate row total
    calculateRowTotal(row);
  });

  // Add new row
  $('#add_row').click(function() {
    let newRow = $('#direct_invoice_table tbody tr:first').clone();
    newRow.find('input').val('');
    newRow.find('select').val('');
    newRow.find('.total').val('0.00');
    $('#direct_invoice_table tbody').append(newRow);
  });

  // 🔹 Recalculate totals when additional discount %, advance, retention or VAT changes
  $(document).on('input blur', 'input[name="add_discount_per"], input[name="vat_per"], input[name="inv_advance_amt"], input[name="inv_retention_amt"]', function() {
    calculateInvoiceTotals();
  });
  $('#branch_id').change(function() {
    var branch_id = $(this).val();

    if (branch_id) {
      $.ajax({
        url: '<?= base_url("index.php/Sales/get_branch_related_data") ?>',
        type: 'POST',
        data: {
          branch_id: branch_id
        },
        dataType: 'json',
        success: function(response) {
          // --- Populate Customers ---
          $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
          $.each(response.customers, function(index, customer) {
            $('#customer_id').append(
              '<option value="' + customer.customer_id + '" ' +
              'data-tr="' + customer.customer_TR_no + '">' +
              customer.customer_name + ' (' + customer.customer_code + ') => ' + customer.contact_number +
              '</option>'
            );
          });
          $('#customer_id').trigger('change'); // Refresh select2 if used

          // --- Show Branch Bank HTML ---
          $('#branch_bank_details').html(response.bank_html);
        }
      });
    } else {
      $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
      $('#branch_bank_details').empty();
    }
  });

  $('#customer_id').change(function() {
    var trn = $(this).find(':selected').data('tr'); // Get TRN number
    var customer_id = $(this).val();

    if (trn) {
      $('#customer_trn').val(trn); // Fill TRN field
    }

    if (customer_id) {
      $.ajax({
        type: "POST",
        url: "<?= base_url('index.php/Ajax/ajax_get_cust_accountId_from_cust_id') ?>",
        data: {
          customer_id: customer_id
        },
        success: function(accid) {
          $('#inv_debtor0').val(accid);
        }
      });
    } else {
      $('#customer_trn').val('');
      $('#inv_debtor0').val('');
    }
  });

// Remove product row
$(document).on("click", ".removeProductRow", function () {
    let rowCount = $('#direct_invoice_table tbody tr').length;
    if (rowCount <= 1) {
        alert('At least one product row must remain.');
        return;
    }
    $(this).closest("tr").remove();
    // Recalculate invoice totals
    calculateInvoiceTotals();    
});  
</script>