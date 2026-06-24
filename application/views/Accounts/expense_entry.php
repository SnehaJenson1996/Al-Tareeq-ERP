<div class="x_panel">


  <div class="x_content">
    <form class="form-horizontal form-label-left"
          action="<?php echo base_url().'index.php/accounts/add_expense_entry_details'; ?>" 
          id="receipt" method="post" name="receipt">

      <!-- Date -->
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Select Date <span class="text-danger">*</span></label>
        <div class="col-md-3">
          <div class="input-group date datepicker1">
            <input type="text" class="form-control form-control-sm datepicker1" 
                   id="v_date" name="v_date" 
                   value="<?php echo date('d-m-Y'); ?>" required tabindex="1">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
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
    Currency <span class="required">*</span>
</label>
<div class="col-md-3 col-sm-6 col-xs-12">
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

      
      <!-- Debit Entries -->
      <div class="form-group row">
        <div class="col-md-12">
          <h4>Debit Accounts (Dr)</h4>
          <table class="table table-bordered table-hover" id="dr_table">
            <thead class="thead-light">
              <tr>
                <th>Debit Account</th>
                <th>Amount</th>
                <th>Balance</th>
              </tr>
            </thead>
<tbody id="dr_body">

<!-- ROW 1: EXPENSE -->
<tr id="dr_addr0">

    <td>
        <select class="form-control form-control-sm select2"
                id="debtor0"
                name="debtor[]"
                onchange="get_account_balance(0,'dr')"
                required>

            <option value="">Select Expense Account</option>

            <?php foreach($sundry_detors_records as $row) { ?>
                <option value="<?php echo $row->account_id; ?>">
                    <?php echo $row->account_name; ?>
                </option>
            <?php } ?>

        </select>
    </td>

    <td>
        <input type="number"
               step="0.01"
               class="form-control form-control-sm debit_sum"
               name="dr_amount[]"
               id="dr_amount0"
               onkeyup="calculate_grand_total()"
               required>
    </td>

    <td><label id="set_balancedr0"></label></td>

</tr>


<!-- ROW 2: INPUT VAT (FIXED ACCOUNT 226) -->
<tr id="dr_addr1">

    <td>

        <select class="form-control form-control-sm select2"
                name="debtor[]"
                id="debtor1"
                required>

            <?php foreach($sundry_detors_records as $row) { ?>

                <option value="<?php echo $row->account_id; ?>"
                    <?php if($row->account_id == 226) echo "selected"; ?>>

                    <?php echo $row->account_name; ?>

                </option>

            <?php } ?>

        </select>

    </td>

    <td>

        <input type="number"
               step="0.01"
               class="form-control form-control-sm debit_sum"
               name="dr_amount[]"
               id="dr_amount1"
               value="0.00"
               onkeyup="calculate_grand_total()">

    </td>

    <td>
        <label id="set_balancedr1"></label>
    </td>

</tr>
</tbody>
          </table>
        </div>
      </div>

      <!-- Credit Entries -->
      <div class="form-group row">
        <div class="col-md-12">
          <h4>Credit Accounts (Cr)</h4>
          <table class="table table-bordered table-hover" id="cr_table">
            <thead class="thead-light">
              <tr>
                <th>Credit Account</th>
                <th>Amount</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody id="cr_body">
              <tr id="cr_addr0">
                <td>
                  <select class="form-control form-control-sm select2" 
                          id="creditor0" name="creditor[]" 
                          onchange="get_account_balance(0,'cr')" required>
                    <option value="">Select</option>
                    <?php foreach($credit_records as $row) { ?>
                      <option value="<?php echo $row->account_id; ?>">
                        <?php echo $row->account_name; ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
                <td>
                  <input type="number" step="0.01" min="0" 
                         name="cr_amount[]" id="cr_amount0" 
                         class="form-control form-control-sm credit_sum" 
                         onkeyup="calculate_grand_total()" required>
                </td>
                <td><label id="set_balancecr0"></label></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Totals -->
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Debit Total</label>
        <div class="col-md-3">
          <input class="form-control bg-light" id="debit_total" name="debit_total" type="text" readonly>
        </div>

        <label class="col-md-2 col-form-label">Credit Total</label>
        <div class="col-md-3">
          <input class="form-control bg-light" id="credit_total" name="credit_total" type="text" readonly>
        </div>
      </div>

      <!-- Narration -->
      <div class="form-group row">
        <label class="col-md-2 col-form-label">Narration</label>
        <div class="col-md-8">
          <textarea class="form-control" id="narration" name="narration" rows="3"></textarea>
        </div>
      </div>

      <!-- Submit Buttons -->
      <div class="ln_solid"></div>
      <div class="form-group row">
        <div class="col-md-12 text-center">
          <input type="hidden" id="vtime" name="vtime" value="<?php echo date('h:i:s'); ?>" />
          <input type="hidden" id="invoiceID" name="invoiceID" />
          <input type="hidden" id="check_dr_id" name="check_dr_id" value="" />

          <button type="submit" id="saveBtn" class="btn btn-success" onclick="return check_total();">
            <i class="fa fa-save"></i> Save
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fa fa-refresh"></i> Reset
          </button>
        </div>
      </div>

    </form>
  </div>
</div>




<script>
$(document).ready(function(){
	var i=1;
	$("#dr_add_row").click(function()
	{
	     $('#dr_addr'+i).html("<td><select class='form-select form-control-sm select2 select2Width' id='debtor"+i+"' name='debtor[]' onchange='get_account_balance("+i+",'dr')' requird><option value=''>Select Code</option><?php foreach($sundry_detors_records as $s) {?>  <option value='<?php echo $s->account_id; ?>'><?php echo $s->account_name;?></option><?php } ?></select><br><label id='set_balancedr"+i+"'>Balance</label></td><td><input type='number' step='0.01' name='dr_amount[]' id='dr_amount"+i+"' class='form-control form-control-sm debit_sum' min='0' required onkeyup='calculate_grand_total()'></td><td><a onclick='remove_row_dr("+i+");' id='delete_row1' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	    $('#dr_body tr:last').after('<tr id="dr_addr'+(i+1)+'"></tr>');
	      i++; 	     	
	     $('.select2').select2({ width: "220px" });
	});
	$("#delete_row1").click(function(){
		 if(i>1){
			 $("#dr_addr"+(i-1)).html('');
			 i--;
		 }
	 });
	 
	 var k=1;
	$("#cr_add_row").click(function()
	{
	     $('#cr_addr'+k).html("<td><select class='form-select form-control-sm select2 select2Width' id='debtor"+k+"' name='debtor[]' onchange='get_account_balance("+k+",'dr')' requird><option value=''>Select Code</option><?php foreach($sundry_detors_records as $s) {?>  <option value='<?php echo $s->account_id; ?>'><?php echo $s->account_name;?></option><?php } ?></select><br><label id='set_balancedr"+k+"'>Balance</label></td><td><input type='number' step='0.01' name='dr_amount[]' id='dr_amount"+k+"' class='form-control form-control-sm credit_sum' min='0' required onkeyup='calculate_grand_total()'></td><td><a onclick='remove_row_cr("+k+");' id='delete_row2' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	    $('#cr_body tr:last').after('<tr id="cr_addr'+(k+1)+'"></tr>');
	      k++; 	     	
	     $('.select2').select2({ width: "220px" });
	});
	$("#delete_row2").click(function(){
		 if(k>1){
			 $("#cr_addr"+(k-1)).html('');
			 i--;
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
    
document.getElementById("receipt").addEventListener("submit", function (e) {

    var btn = document.getElementById("saveBtn");

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    // Disable immediately
    btn.disabled = true;
    btn.innerHTML = "Processing...";

});

$(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        placeholder: "Select",
        allowClear: true
    });
});

</script>
