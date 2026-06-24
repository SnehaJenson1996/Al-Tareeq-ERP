<!-- ===== Payment Entry (Gentella style) ===== -->
<div class role="main">
  <div class="x_panel">
    <div class="x_content">
  <form class="form-horizontal form-label-left"
      action="<?php echo base_url().'index.php/accounts/add_payment_details'; ?>"
      id="receipt" method="post" name="receipt" autocomplete="off">

  <div class="form-group row">
    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Date <span class="text-danger">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="input-group date datepicker1">
        <input type="text" class="form-control form-control-sm" id="v_date" name="v_date"
               value="<?php echo date('d-m-Y')?>" required tabindex="1">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
      </div>
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Branch <span class="required">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select id="branch_id" name="branch_id" class="form-control form-control-sm select2" required>
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
      Select Supplier <span class="text-danger">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select name="debtor" id="debtor" class="form-control form-control-sm select2" tabindex="2" onchange="get_invoice_list()" required>
        <option value="">Select</option>
        <?php foreach($sundry_detors_records as $s) { ?>
          <option value="<?php echo $s->account_id; ?>"><?php echo $s->account_name ;?></option>
        <?php } ?>
      </select>
    </div>

    <label class="control-label col-md-2 col-sm-3 col-xs-12">
      Transaction Type <span class="text-danger">*</span>
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <select class="form-control form-control-sm select2" name="transaction_type" id="transaction_type" onchange="toggleTransactionFields()" required>
        <option value="">Select</option>
        <option value="cheque">Cheque</option>
        <option value="etransfer">E-Transfer</option>
        <option value="other">Other</option>
      </select>
    </div>
  </div>
  <div class="form-group row">

  <label class="control-label col-md-2 col-sm-3 col-xs-12">
    Currency <span class="required">*</span>
</label>
<div class="col-md-4 col-sm-6 col-xs-12">
    <select id="currency_id" name="currency_id" class="form-control select2" required>
        <option value="">Select Currency</option>
        <?php foreach($currency_list as $currency) { ?>
            <option value="<?php echo $currency->currency_id; ?>">
                <?php echo $currency->currency_abbr; ?>
            </option>
        <?php } ?>
    </select>
</div>
</div>

  <div class="form-group row" id="transaction_fields" style="display:none;">
    <label class="control-label col-md-2 col-sm-3 col-xs-12" id="transaction_label">
      Transaction No
    </label>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <input type="text" class="form-control form-control-sm" name="transaction_no" id="transaction_no" placeholder="Enter Cheque No / Transaction ID">
    </div>

    <div id="bank_name_group" style="display:none; padding: 0; margin: 0;">
      <label class="control-label col-md-2 col-sm-3 col-xs-12" id="bank_label">
        Bank Name
      </label>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <input type="text" class="form-control form-control-sm" name="bank_name" id="bank_name" placeholder="Enter Bank Name">
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-12">
      <label id="invoice_details" class="control-label"></label>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-12">
      <h4>Payment Details</h4>
      <hr class="mt-0 mb-2" />
    </div>
  </div>

    <!-- Invoice List Output (server fills) -->
    <div class="form-group row">
      <div class="col-md-12" id="debt_list"></div>
    </div>

    <!-- Credit Table -->
    <div class="x_content well">
      <h5><strong>Credit Accounts</strong></h5>
      <table class="table table-bordered table-hover" id="cr_table">
        <thead class="bg-light">
          <tr>
            <th>Debit Account (Cr)</th>
            <th>Debit Amount</th>
            <th width="10%">
              <a id="cr_add_row" title="Add" class="btn btn-sm bg-orange">
                <i class="fa fa-plus"></i>
              </a>
            </th>
          </tr>
        </thead>
        <tbody id="cr_body">
          <tr id="cr_addr0">
            <td>
              <select class="form-control form-control-sm select2" id="creditor0" name="creditor[]" onchange="get_account_balance(0,'cr')" required>
                <option value="">Select</option>
                <?php foreach($receipt_Creditors as $row) { ?>
                  <option value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?></option>
                <?php } ?>
              </select>
              <small id="set_balancecr0" class="text-muted">Balance</small>
            </td>
            <td>
              <input type="number" step="0.01" name="cr_amount[]" id="cr_amount0" class="form-control form-control-sm credit_sum" min="0" onkeyup="calculate_grand_total()" required>
            </td>
            <td>
              <a onclick="remove_row_cr(0)" class="btn btn-xs bg-orange remove1" title="Delete">
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
          <tr id="cr_addr1"></tr>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="control-label">Debit Total</label>
        <input type="text" class="form-control form-control-sm bg-soft-gray" id="debit_total" name="debit_total" readonly>
      </div>
      <div class="col-md-6">
        <label class="control-label">Credit Total</label>
        <input type="text" class="form-control form-control-sm bg-soft-gray" id="credit_total" name="credit_total" readonly>
      </div>
    </div>

    <!-- Narration -->
    <div class="form-group row">
      <label class="col-md-2 col-sm-3 col-xs-12 control-label">Narration</label>
      <div class="col-md-8 col-sm-9 col-xs-12">
        <textarea class="form-control" id="narration" name="narration" rows="2"></textarea>
      </div>
    </div>

    <!-- Prepared By/Approved By -->
    <div class="form-group row">
      <label class="control-label col-md-2 col-sm-3 col-xs-12">Prepared By</label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select class="form-control select2" 
          id="employee_prepared" name="employee_prepared">
          <option value="">Select</option>
          <?php foreach ($employees as $s) { ?>
          <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
          <?php } ?>
        </select>
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-12">Approved By</label>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <select class="form-control select2" id="employee_approved" name="employee_approved" style="width:100%;">
          <option value="">Select</option>
          <?php foreach ($employees as $s) { ?>
          <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <!-- Buttons -->
    <div class="ln_solid"></div>
    <div class="form-group text-center">
      <input type="hidden" id="vtime" name="vtime" value="<?php echo date('h:i:s');?>" />
      <input type="hidden" id="selected_invoice_ids" name="selected_invoice_ids" />
      <input type="hidden" id="filtered_invoice_codes" name="filtered_invoice_codes" />
      <input type="hidden" id="selected_amounts" name="selected_amounts" />
      <input type="hidden" id="check_dr_id" name="check_dr_id" value="" />

      <button type="submit" class="btn btn-success" onclick="return check_total();">Save</button>
      <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
  </form>
  </div>
  </div>
</div>


<script>
$(document).ready(function(){
	// var i=1;
	// $("#dr_add_row").click(function()
	// {
	//      $('#dr_addr'+i).html("<td><select class='form-select form-control-sm select2 select2Width' id='debtor"+i+"' name='debtor[]' onchange=get_account_balance("+i+",'dr') requird><option value=''>Select Code</option><?php foreach($sundry_detors_records as $s) {?>  <option value='<?php echo $s->account_id; ?>'><?php echo $s->account_name;?></option><?php } ?></select><br><label id='set_balancedr"+i+"'>Balance</label></td><td><input type='number' step='0.01' name='dr_amount[]' id='dr_amount"+i+"' class='form-control form-control-sm debit_sum' min='0' required onkeyup='calculate_grand_total()'></td><td><a onclick='remove_row_dr("+i+");' id='delete_row1' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	//     $('#dr_body tr:last').after('<tr id="dr_addr'+(i+1)+'"></tr>');
	//       i++; 	     	
	//      $('.select2').select2({ width: "220px" });
	// });
	// $("#delete_row1").click(function(){
	// 	 if(i>1){
	// 		 $("#dr_addr"+(i-1)).html('');
	// 		 i--;
	// 	 }
	//  });
	 
	 var k=1;
	$("#cr_add_row").click(function()
{
  $('#cr_addr'+k).html("<td><select class='form-select form-control-sm select2 select2Width' id='creditor"+k+"' name='creditor[]' onchange=get_account_balance("+k+",'cr') required><option value=''>Select</option><?php foreach($receipt_Creditors as $s) {?>  <option value='<?php echo $s->account_id; ?>'><?php echo $s->account_name;?></option><?php } ?></select><br><label id='set_balancecr"+k+"'>Balance</label></td><td><input type='number' step='0.01' name='cr_amount[]' id='cr_amount"+k+"' class='form-control form-control-sm credit_sum' min='0' required onkeyup='calculate_grand_total()'></td><td><a onclick='remove_row_cr("+k+");' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
  $('#cr_body tr:last').after('<tr id="cr_addr'+(k+1)+'"></tr>');
  k++;

  // initialize select2 on new element
  $('#creditor'+(k-1)).select2({ width: "220px" });
});
	$("#delete_row2").click(function(){
		 if(k>1){
			 $("#cr_addr"+(k-1)).html('');
			 k--;
		 }
	 });
});  


function remove_row_cr(append_id)
{    	 
$('#cr_addr'+append_id).attr("id","cr_addr"+append_id+"x");
$('#cr_addr'+append_id+"x").remove();
}

function get_account_balance(append_id,type)
{
	if(type=='dr')
		tmp='debtor';
	else
		tmp='creditor';

	var account_id=document.getElementById(tmp+append_id).value;
	var today="<?php echo date('Y-m-d')?>";
	$.ajax
	({
		url: "<?php echo site_url('Accounts/get_account_balance'); ?>",
		type: 'POST',
		data: {account_id: account_id, today:today },
		success: function(msg) {
			if(msg)
			{
				//alert(msg);
				document.getElementById('set_balance'+type+append_id).innerHTML='Balance: '+msg;
				
			}
		}
	});
}
function calculate_grand_total()
{
	var i_value=0;i_total=0;
	$('.debit_sum').each(function()
	{
		i_value=$(this).val();
		if(i_value=='')
			 i_value = 0;
		else
			i_total+=parseFloat(i_value);
	});
	if(isNaN(i_total)) var dr_total = 0;
	
	var k_value=0;k_total=0;
	$('.credit_sum').each(function()
	{
		k_value=$(this).val();
		if(k_value=='')
			 k_value = 0;
		else
			k_total+=parseFloat(k_value);
	});
	if(isNaN(k_total)) var cr_total = 0;

	document.getElementById("debit_total").value= parseFloat(i_total).toFixed(2);
	document.getElementById("credit_total").value= parseFloat(k_total).toFixed(2);
	//check_total();
}

function check_total()
{
 	var dr_total=$('#debit_total').val();
	var cr_total=$('#credit_total').val();
	 if(parseFloat(cr_total) != parseFloat(dr_total))
	{
	     alert("Both debit total and credit total must match");
	     return false;
	}
}
function get_invoice_amount()
{
	var invoice_id=document.getElementById('invoice_id').value;
	var today="<?php echo date('Y-m-d')?>";
	if(invoice_id!='')
	{
		$.ajax
		({
			url: "<?php echo site_url('Ajax/get_invoice_amount'); ?>",
			type: 'POST',
			data: {invoice_id: invoice_id },
			dataType: "json",
			
			success: function(msg) {
				if(msg)
				{
					//alert(msg);
					document.getElementById('invoice_details').innerHTML='<h5>Invoice amount: '+msg.grand_total+' Paid amount: '+msg.paid_total+' Balance: '+msg.balance_total+'</h5>';
					
				}
			}
		});
	}
	else
	{
		document.getElementById('invoice_details').innerHTML='';
	}
}
// function get_grn_list() {
// 		var supplier_id = document.getElementById('supplier_id').value;
// 		alert("Selected Supplier ID: " + supplier_id);
// 		if (supplier_id != '') {
// 			$.ajax
// 				({
// 					url: "<?php echo site_url('Ajax/get_grn_list'); ?>",
// 					type: 'POST',
// 					data: { supplier_id: supplier_id },
// 					success: function (msg) {
// 						document.getElementById('debt_list').innerHTML = msg;
// 					}
// 				});
// 		}
// 		else {
// 			document.getElementById('debt_list').innerHTML = '';
// 		}
// 	}
function get_invoice_list()
{
  var supplier_id = document.getElementById('debtor').value;
  console.log("Selected Supplier ID:", supplier_id);

  if(supplier_id != '')
  {
    $.ajax({
      url: "<?php echo site_url('Ajax/get_grn_list'); ?>",
      type: 'POST',
      data: {supplier_id: supplier_id },
      success: function(msg) {
          console.log("Response:", msg);

        document.getElementById('debt_list').innerHTML = msg;
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', error);
        alert('Failed to load GRN list: ' + error);
      }
    });
  }
  else
  {
    document.getElementById('debt_list').innerHTML = '';
  }
}
// $('.datepicker1').datepicker({
//   format: 'dd-mm-yyyy',
//   autoclose: true,
//   todayHighlight: true
// });

// function p_check() {
// 	var checked= $('input[name="select_checkbox[]"]:checked').length;
	
// 	var allVals = [];
// 	$(".case:checked").each(function() {
// 		allVals.push($(this).val());
// 	});
// 	document.getElementById("selected_tr").value=allVals;
// }
function p_check() {
  let selectedIds = [];
  let selectedCodes = [];
  let amountsObj = {};

  $('.case').each(function () {
    let invoiceId = $(this).val();
    let drInput = $('input[name="dr_amount[' + invoiceId + ']"]');
    let invoiceCode = $(this).data('invoice-code'); // get invoice code from data attribute

    if ($(this).is(':checked')) {
      drInput.prop('disabled', false);

      selectedIds.push(invoiceId);
      if (invoiceCode) selectedCodes.push(invoiceCode);

      let amountVal = drInput.val();
      if (amountVal === undefined || amountVal === '') amountVal = 0;
      amountsObj[invoiceId] = parseFloat(amountVal);
    } else {
      drInput.prop('disabled', true).val('');
    }
  });

  $('#selected_invoice_ids').val(selectedIds.join(','));
  $('#filtered_invoice_codes').val(selectedCodes.join(','));
  $('#selected_amounts').val(JSON.stringify(amountsObj)); // amounts as JSON object
}



function toggleTransactionFields() {
  const type = document.getElementById("transaction_type").value;
  const transRow = document.getElementById("transaction_fields");
  const label = document.getElementById("transaction_label");
  const bankLabel = document.getElementById("bank_label");

  if (type === 'cheque') {
    transRow.style.display = 'flex';
    label.innerHTML = 'Cheque Number <span style="color:red;">*</span>';
    bankLabel.style.display = 'block';
  } else if (type === 'etransfer') {
    transRow.style.display = 'flex';
    label.innerHTML = 'Transaction ID <span style="color:red;">*</span>';
    bankLabel.style.display = 'block';
  } else if (type === 'other') {
    transRow.style.display = 'flex';
    label.innerHTML = 'Remarks <span style="color:red;">*</span>';
    bankLabel.style.display = 'none';
    document.getElementById('bank_name').value = '';
  } else {
    transRow.style.display = 'none';
    document.getElementById('transaction_no').value = '';
    document.getElementById('bank_name').value = '';
  }
}

$(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        placeholder: "Select",
        allowClear: true
    });
});
</script>
