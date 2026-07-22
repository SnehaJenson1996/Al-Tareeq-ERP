<div class="x_panel">
  <div class="x_title">
    <h2>Receipt Entry <small>Add / Manage Receipt Details</small></h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <br />
   <form id="receipt" name="receipt" method="post" 
      action="<?php echo base_url().'index.php/accounts/add_receipt_details'; ?>" 
      class="form-horizontal form-label-left">

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Date <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="input-group date datepicker1">
        <input type="text" id="v_date" name="v_date"
          value="<?php echo date('d-m-Y'); ?>"
          class="form-control" required>
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Branch <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select id="branch_id" name="branch_id" class="form-control select2" required>
        <option value="">Select Branch</option>
        <?php foreach($branch_list as $b) { ?>
          <option value="<?php echo $b->branch_id; ?>">
            <?php echo $b->branch_name; ?>
          </option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Customer <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select id="debtor" name="debtor" class="form-control select2"
        onchange="get_invoice_list()" required>
        <option value="">Select</option>
        <?php foreach($receipt_Creditors as $s) { ?>
          <option value="<?php echo $s->account_id; ?>"
            data-customer-id="<?php echo $s->customer_id; ?>">
            <?php echo $s->account_name; ?>
          </option>
        <?php } ?>
      </select>
      <input type="hidden" name="customer_id" id="customer_id">
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12" for="instrument_bank_date">
      Instrument Date <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="input-group date datepicker1">
        <input type="text" id="instrument_bank_date" name="instrument_bank_date"
          value="<?php echo date('d-m-Y'); ?>" class="form-control" required>
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Transaction Type <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select id="transaction_type" name="transaction_type"
        class="form-control select2"
        onchange="toggleTransactionFields()" required>
        <option value="">Select</option>
        <option value="cheque">Cheque</option>
        <option value="etransfer">E-Transfer</option>
        <option value="other">Other</option>
      </select>
    </div>
  </div>

  <div class="form-group row" id="transaction_fields" style="display: none;">
    <label class="control-label col-md-2 col-sm-3 col-xs-12" id="transaction_label">
      Transaction No
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <input type="text" id="transaction_no" name="transaction_no" 
             class="form-control" placeholder="Enter Cheque No / Transaction ID">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <label id="invoice_details" class="control-label"></label>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <h4>Receipt Details</h4>
      <hr class="mt-0 mb-2" />
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12" id="debt_list"></div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-bordered table-hover" id="cr_table">
        <thead class="thead-light">
          <tr>
            <th>Debit Account (Dr)</th>
            <th>Debit Amount</th>
            <th width="10%">
              <a id="cr_add_row" title="Add" class="btn btn-sm btn-success" style="cursor:pointer;">
                <span class="fa fa-plus"></span>
              </a>
            </th>
          </tr>
        </thead>
        <tbody id="cr_body">
          <tr id="cr_addr0">
            <td>
              <select class="form-control select2" id="creditor0" name="creditor[]" 
                      onchange="get_account_balance(0,'cr')" required>
                <option value="">Select</option>
                <?php foreach($sundry_detors_records as $row) { ?>
                  <option value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?></option>
                <?php } ?>
              </select>
              <small class="text-muted" id="set_balancecr0">Balance</small>
            </td>
            <td>
              <input type="number" step="0.01" min="0" id="cr_amount0" name="cr_amount[]" 
                     class="form-control credit_sum" onkeyup="calculate_grand_total()" required>
            </td>
            <td class="text-center">
              <a onclick="remove_row_cr(0)" class="btn btn-danger btn-sm" title="Delete" style="cursor:pointer;">
                <span class="fa fa-trash"></span>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">Debit Total</label>
    <div class="col-md-4 col-sm-6 col-xs-12 mb-2 mb-md-0">
      <input type="text" id="debit_total" name="debit_total" class="form-control bg-light" readonly>
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12">Credit Total</label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <input type="text" id="credit_total" name="credit_total" class="form-control bg-light" readonly>
    </div>
  </div>

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">Narration</label>
    <div class="col-md-10 col-sm-9 col-xs-12">
      <textarea id="narration" name="narration" class="form-control" rows="3"></textarea>
    </div>
  </div>

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">Prepared By</label>
    <div class="col-md-4 col-sm-6 col-xs-12 mb-2 mb-md-0">
      <select class="form-control select2" id="employee_prepared" name="employee_prepared">
        <option value="">Select</option>
        <?php foreach ($employees as $s) { ?>
          <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
        <?php } ?>
      </select>
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12">Approved By</label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select class="form-control select2" id="employee_approved" name="employee_approved" style="width:100%;">
        <option value="">Select</option>
        <?php foreach ($employees as $s) { ?>
          <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>

  <div class="ln_solid"></div>

  <div class="form-group row">
    <div class="col-md-12 text-center">
      <input type="hidden" id="vtime" name="vtime" value="<?php echo date('h:i:s'); ?>" />
      <input type="hidden" id="selected_invoice_ids" name="selected_invoice_ids" />
      <input type="hidden" id="filtered_invoice_codes" name="filtered_invoice_codes" />
      <input type="hidden" id="check_dr_id" name="check_dr_id" value="" />

      <button type="submit" class="btn btn-success" onclick="return check_total();">
        <i class="fa fa-save"></i> Save
      </button>
      <button type="button" class="btn btn-secondary" onclick="customReset()">
        <i class="fa fa-refresh"></i> Reset
      </button>
    </div>
  </div>

</form>
  </div>
</div>
<!-- jQuery (already required by Bootstrap Datepicker) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (v3 or v4 is fine, depending on your setup) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>

<!-- Bootstrap Datepicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
  function updateSelectedInvoiceIds() {
    let selected = [];
    document.querySelectorAll('input[name="invoiceID[]"]:checked').forEach(function(cb) {
      selected.push(cb.value);
    });
    document.getElementById('selected_invoice_ids').value = selected.join(',');
  }

  function toggleTransactionFields() {
    const type = document.getElementById("transaction_type").value;
    const transactionFields = document.getElementById("transaction_fields");
    //const bankField = document.getElementById("bank_field"); // Uncomment if using bank field
    const label = document.getElementById("transaction_label");

    // Close select2 dropdown
    $('#transaction_type').select2('close');

    if (type === 'cheque') {
      transactionFields.style.display = 'flex';
      //bankField.style.display = 'block'; // Uncomment if using bank field
      label.innerHTML = 'Cheque Number <span class="text-danger">*</span>';
    } else if (type === 'etransfer') {
      transactionFields.style.display = 'flex';
      //bankField.style.display = 'block'; // Uncomment if using bank field
      label.innerHTML = 'Transaction ID <span class="text-danger">*</span>';
    } else if (type === 'other') {
      transactionFields.style.display = 'flex';
      //bankField.style.display = 'none'; // Uncomment if using bank field
      label.innerHTML = 'Remarks <span class="text-danger">*</span>';
      //document.getElementById("bank_name").value = '';
    } else {
      transactionFields.style.display = 'none';
      document.getElementById("transaction_no").value = '';
      //document.getElementById("bank_name").value = '';
    }
  }
$(document).ready(function() {
  // Initialize Select2 for all dropdowns
  $('.select2').select2({ width: '100%' });

  // (Keep your existing dynamic credit row code below)
  var k = 1;
  $('#cr_add_row').click(function() {
    let newRow = `
      <tr id="cr_addr${k}">
        <td>
          <select class="form-control select2" id="creditor${k}" name="creditor[]" onchange="get_account_balance(${k},'cr')" required>
            <option value="">Select</option>
            <?php foreach($sundry_detors_records as $row) { ?>
              <option value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?></option>
            <?php } ?>
          </select>
          <small id="set_balancecr${k}" class="text-muted">Balance</small>
        </td>
        <td>
          <input type="number" step="0.01" name="cr_amount[]" id="cr_amount${k}" class="form-control credit_sum" min="0" required onkeyup="calculate_grand_total()">
        </td>
        <td>
          <a onclick="remove_row_cr(${k})" class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></a>
        </td>
      </tr>`;
    $('#cr_body').append(newRow);
    $('#creditor'+k).select2({ width: '100%' });
    k++;
  });

    // Initialize Bootstrap Datepicker
  
});

$('.datepicker1').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
  function remove_row_cr(id) {
    $('#cr_addr' + id).remove();
    calculate_grand_total();
  }

  function get_account_balance(append_id, type) {
    var tmp = (type == 'dr') ? 'debtor' : 'creditor';
    var account_id = document.getElementById(tmp + append_id).value;
    var today = "<?php echo date('Y-m-d')?>";

    $.ajax({
      url: "<?php echo site_url('Accounts/get_account_balance'); ?>",
      type: 'POST',
      data: {account_id: account_id, today: today},
      success: function(msg) {
        if (msg) {
          document.getElementById('set_balance' + type + append_id).innerHTML = 'Balance: ' + msg;
        }
      }
    });
  }

  function calculate_grand_total() {
    var i_total = 0;
    $('.debit_sum').each(function() {
      var val = parseFloat($(this).val()) || 0;
      i_total += val;
    });

    var k_total = 0;
    $('.credit_sum').each(function() {
      var val = parseFloat($(this).val()) || 0;
      k_total += val;
    });

    $('#debit_total').val(i_total.toFixed(2));
    $('#credit_total').val(k_total.toFixed(2));
  }

  function check_total() {
    var dr_total = parseFloat($('#debit_total').val()) || 0;
    var cr_total = parseFloat($('#credit_total').val()) || 0;
    if (dr_total !== cr_total) {
      alert("Both debit total and credit total must match");
      return false;
    }
    return true;
  }

  function get_invoice_list() {
    var debtorSelect = document.getElementById('debtor');
    var account_id = debtorSelect.value;

    // Get customer_id from selected option data attribute
    var customer_id = debtorSelect.options[debtorSelect.selectedIndex]?.getAttribute('data-customer-id');
    document.getElementById('customer_id').value = customer_id || '';

    if (account_id != '') {
      $.ajax({
        url: "<?php echo site_url('Ajax/get_invoice_list'); ?>",
        type: 'POST',
        data: { account_id: account_id },
        success: function(msg) {
          document.getElementById('debt_list').innerHTML = msg;
        }
      });
    } else {
      document.getElementById('debt_list').innerHTML = '';
    }
  }

  // Call this on checkbox invoiceID[] click to update selected_invoice_ids hidden input
  // function p_check() {
  //   let selected = [];
  //   document.querySelectorAll('input[name="invoiceID[]"]:checked').forEach(function(cb) {
  //     selected.push(cb.value);
  //   });
  //   document.getElementById('selected_invoice_ids').value = selected.join(',');
  // }

function p_check() {
  let selectedIds = [];
  let selectedCodes = [];

  $('.case').each(function () {
    let invoiceId = $(this).val();
    let drInput = $('input[name="dr_amount[' + invoiceId + ']"]');
    let invoiceCode = $(this).data('invoice-code');  // get invoice code from data attribute

    if ($(this).is(':checked')) {
      drInput.prop('disabled', false);
      selectedIds.push(invoiceId);
      if(invoiceCode) selectedCodes.push(invoiceCode);
    } else {
      drInput.prop('disabled', true).val('');
    }
  });

  $('#selected_invoice_ids').val(selectedIds.join(','));
  $('#filtered_invoice_codes').val(selectedCodes.join(','));
}

function customReset() {

  // 1. Reset entire form (basic fields)
  document.getElementById("receipt").reset();

  // 2. Reset Select2 dropdowns
  $('#debtor').val('').trigger('change');
  $('#transaction_type').val('').trigger('change');

  // 3. Hide transaction fields
  $('#transaction_fields').hide();
  $('#transaction_no').val('');

  // 4. Clear invoice list (AJAX grid)
  $('#debt_list').html('');

  // 5. Clear hidden fields
  $('#customer_id').val('');
  $('#selected_invoice_ids').val('');
  $('#filtered_invoice_codes').val('');

  // 6. Reset totals
  $('#debit_total').val('');
  $('#credit_total').val('');

  // 7. Reset credit table (keep only first row)
  $('#cr_body').html(`
    <tr id="cr_addr0">
      <td>
        <select class="form-control select2" id="creditor0" name="creditor[]" onchange="get_account_balance(0,'cr')" required>
          <option value="">Select</option>
          <?php foreach($sundry_detors_records as $row) { ?>
            <option value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?></option>
          <?php } ?>
        </select>
        <small class="text-muted" id="set_balancecr0">Balance</small>
      </td>
      <td>
        <input type="number" step="0.01" min="0" id="cr_amount0" name="cr_amount[]" class="form-control credit_sum" required>
      </td>
      <td class="text-center">
        <a onclick="remove_row_cr(0)" class="btn btn-danger btn-sm">
          <span class="fa fa-trash"></span>
        </a>
      </td>
    </tr>
  `);

  // Reinitialize Select2 for recreated dropdown
  $('#creditor0').select2({ width: '100%' });

  // 8. Reset date fields (optional: today again)
  $('#v_date').val('<?php echo date('d-m-Y'); ?>');
  $('#instrument_bank_date').val('<?php echo date('d-m-Y'); ?>');
}

</script>
