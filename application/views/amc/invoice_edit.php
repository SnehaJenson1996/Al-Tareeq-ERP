<div class="card-body">

<?php
// ✅ FIX: convert object once
$row = $records1;
?>

<form id="main" method="post"
      action="<?php echo base_url().'index.php/AMC/update_invoice_data' ?>"
      autocomplete="off"
      enctype="multipart/form-data">

    <!-- AMC Code and Date -->
    <div class="form-group row">
        <label class="col-form-label col-lg-2">AMC Agreement Code <span style="color:red;">*</span></label>
        <div class="col-lg-3">
            <input type="text" name="invcode" id="invcode"
                   class="form-control form-control-sm"
                   value="<?php echo $row->invoice_code; ?>">
            <input type="hidden" name="invoice_id"
                   value="<?php echo $row->invoice_id; ?>">
        </div>

        <label class="col-form-label col-lg-2">AMC Agreement Date <span style="color:red;">*</span></label>
        <div class="col-lg-2">
            <input type="text" name="invdate" id="invdate"
                   class="form-control form-control-sm"
                   value="<?php echo $row->invoice_date; ?>" readonly>
        </div>
    </div>

    <!-- AMC Dates -->
    <div class="form-group row">
        <label class="col-form-label col-lg-2">AMC Start Date</label>
        <div class="col-lg-2">
            <input type="date" name="amc_start_date"
                   class="form-control form-control-sm"
                   value="<?php echo $row->amc_start_date; ?>">
        </div>

        <label class="col-form-label col-lg-2">AMC End Date</label>
        <div class="col-lg-2">
            <input type="date" name="amc_end_date"
                   class="form-control form-control-sm"
                   value="<?php echo $row->amc_end_date; ?>">
        </div>
    </div>

    <!-- Customer / Project -->
    <div class="form-group row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>Customer</th>
                    <td><?php echo $row->customer_name; ?></td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>
                        <input type="text" name="project_name"
                               class="form-control"
                               value="<?php echo $row->project_name; ?>" readonly>

                        <input type="hidden" name="customer_id"
                               value="<?php echo $row->customer_id; ?>">
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ITEMS -->
    <div class="form-group row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>System</th>
    <th>Price (AED + VAT)</th>
    <th>Qty</th>

    <?php
    if($quotation_info->contract_type == 'Yearly')
    {
        for($i=1;$i<=$quotation_info->no_of_years;$i++)
        {
            echo "<th>Total Price {$i} Year</th>";
        }
    }
    else
    {
        for($i=1;$i<=$quotation_info->no_of_quarters;$i++)
        {
            echo "<th>Q{$i}</th>";
        }
    }
    ?>

    <th>Final Total Rates</th>
                    </tr>
                </thead>

                <tbody>
<?php 
$i = 5000; 

$period_count = ($quotation_info->contract_type == 'Yearly')
    ? $quotation_info->no_of_years
    : $quotation_info->no_of_quarters;

foreach($records2 as $r) { 
    $total = $r->quantity * $r->price;
?>
<tr id="addr<?php echo $i; ?>">

    <!-- System -->
    <td>
        <input type="text" name="product_id[]" class="form-control"
               value="<?php echo $r->product_id; ?>">
    </td>

    <!-- Price -->
    <td>
        <input type="text" name="price[]" class="form-control"
               value="<?php echo $r->price; ?>"
               onkeyup="calculate_total('<?php echo $i; ?>')">
    </td>

    <!-- Qty -->
    <td>
        <input type="text" name="qty[]" class="form-control"
               value="<?php echo $r->quantity; ?>"
               onkeyup="calculate_total('<?php echo $i; ?>')">
    </td>

    <!-- PERIOD COLUMNS (YEAR / QUARTER) -->
    <?php for($p = 1; $p <= $period_count; $p++) { ?>
        <td>
            <input type="text"
                   name="period_<?php echo $p; ?>[]"
                   class="form-control"
                   value="<?php echo number_format($total,2); ?>"
                   readonly>
        </td>
    <?php } ?>

    <!-- FINAL TOTAL -->
    <td>
        <input type="text" name="total[]" class="form-control subItemAmt"
               value="<?php echo number_format($total * $period_count,2); ?>" readonly>

        <input type="hidden" name="trans_id[]"
               value="<?php echo $r->trans_id; ?>">
    </td>

    <!-- ACTION -->
    <td>
        <button type="button"
                class="btn btn-danger btn-sm"
                onclick="remove_row('<?php echo $i; ?>')">
            <i class="fa fa-trash"></i>
        </button>
    </td>

</tr>
<?php $i++; } ?>
</tbody>
            </table>
        </div>
    </div>

    <!-- TOTALS -->
    <div class="form-group row">

        <label class="col-lg-1">SubTotal</label>
        <div class="col-lg-2">
            <input type="text" id="sub_total" name="sub_total"
                   class="form-control" readonly
                   value="<?php echo $row->sub_total; ?>">
        </div>
         <label class="col-lg-1">AMC Discount</label>
        <div class="col-lg-2">
            <input type="text" id="amc_discount" name="amc_discount"
                   class="form-control" readonly
                   value="<?php echo $row->amc_discount; ?>" onkeyup="calculate_grand_total()">
        </div>

        <label class="col-lg-1">Discount %</label>
        <div class="col-lg-2">
            <input type="number" id="discount"
                   class="form-control" readonly
                   value="<?php echo $row->discount_percent; ?>"
                   onkeyup="calculate_grand_total()">
        </div>
         <label class="col-lg-1">Discount Amount</label>
        <div class="col-lg-2">
            <input type="number" id="discount_amt"
                   class="form-control" readonly
                   value="<?php echo $row->discount_amt; ?>"
                   onkeyup="calculate_grand_total()">
        </div>
        <label class="col-lg-1">Vat %</label>
        <div class="col-lg-2">
            <input type="number" id="vat_percent"
                   class="form-control" readonly
                   value="<?php echo $row->vat_percent; ?>"
                   onkeyup="calculate_grand_total()">
        </div>
         <label class="col-lg-1">VAt Amount</label>
        <div class="col-lg-2">
            <input type="number" id="vat_amt" readonly
                   class="form-control"
                   value="<?php echo $row->vat_amt; ?>"
                   onkeyup="calculate_grand_total()">
        </div>

        <label class="col-lg-2">Grand Total</label>
        <div class="col-lg-2">
            <input type="text" id="grand_total" name="grand_total"
                   class="form-control"
                   value="<?php echo $row->grand_total; ?>">
        </div>
    </div>

    <input type="checkbox" id="sla_enabled" name="sla_enabled" value="1"
       onchange="toggleSlaTable()"
       <?php if(!empty($sla_records)) echo 'checked'; ?>>
Enable SLA

<div id="sla_section" style="display:none;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Service Item</th>
                <th>Availability</th>
                <th>Response</th>
                <th>Restoration</th>
                <th>Resolution</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="sla_body">

<?php if(!empty($sla_records)) { 
    $i = 0;
    foreach($sla_records as $s) { ?>
<tr id="sla_<?php echo $i; ?>">

    <td>
        <input type="text" name="service_item[]" class="form-control"
               value="<?php echo $s->service_item; ?>">
    </td>

    <td>
        <input type="text" name="service_availability_period[]" class="form-control"
               value="<?php echo $s->service_availability_period; ?>">
    </td>

    <td>
        <input type="text" name="response_time[]" class="form-control"
               value="<?php echo $s->response_time; ?>">
    </td>

    <td>
        <input type="text" name="restoration_time[]" class="form-control"
               value="<?php echo $s->restoration_time; ?>">
    </td>

    <td>
        <input type="text" name="resolution_time[]" class="form-control"
               value="<?php echo $s->resolution_time; ?>">
    </td>

    <td>
        <button type="button" class="btn btn-danger btn-sm"
                onclick="$('#sla_<?php echo $i; ?>').remove()">
            X
        </button>
    </td>

</tr>
<?php $i++; } } ?>

    </tbody>
    </table>
</div>

    <!-- NOTES -->
    <div class="form-group row">
        <label class="col-lg-2">Conditions</label>
        <div class="col-lg-8">
            <textarea name="conditions" class="form-control"><?php echo $row->conditions; ?></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2">Exclusions</label>
        <div class="col-lg-8">
            <textarea name="exclusions" class="form-control"><?php echo $row->exclusions; ?></textarea>
        </div>
    </div>

   


   <div class="form-group row">
    <div class="col-lg-2">
        <input type="checkbox" id="annexure_enabled" name="annexure_enabled" value="1"
               onchange="toggleAnnexureTable()"
               <?php if(!empty($annexure_records)) echo 'checked'; ?>>
        Enable Annexure
    </div>
</div>

<div id="annexure_section" style="<?php echo !empty($annexure_records) ? '' : 'display:none;'; ?>">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Type</th>
                <th>Location</th>
                <th>Quantity</th>
                <th></th>
            </tr>
        </thead>

        <tbody id="annexure_body">

        <?php if(!empty($annexure_records)) { 
            $i = 0;
            foreach($annexure_records as $a) { ?>
            <tr id="annex_<?php echo $i; ?>">
                <td><input type="text" name="sl_no[]" class="form-control" value="<?php echo $a->sl_no; ?>"></td>
                <td><input type="text" name="type[]" class="form-control" value="<?php echo $a->type; ?>"></td>
                <td><input type="text" name="location[]" class="form-control" value="<?php echo $a->location; ?>"></td>
                <td>
                    <input type="number" name="quantity[]" class="form-control annex_qty"
                           value="<?php echo $a->quantity; ?>"
                           onkeyup="calculateAnnexTotal()">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="$('#annex_<?php echo $i; ?>').remove(); calculateAnnexTotal();">
                        X
                    </button>
                </td>
            </tr>
        <?php $i++; } } ?>

        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Quantity</th>
                <th>
                    <input type="text" id="annex_total_qty" class="form-control" readonly>
                </th>
                <th></th>
            </tr>
        </tfoot>

    </table>
</div>
    <div class="form-group row">
        <div class="col-lg-10 offset-lg-2">
            <button type="submit" class="btn btn-primary">SAVE</button>
        </div>
    </div>

</form>
</div>



<script>



function remove_row_inv_dr(append_id)
{    	 
$('#inv_dr_addr'+append_id).attr("id","inv_dr_addr"+append_id+"x");
$('#inv_dr_addr'+append_id+"x").remove();
}
function remove_row_inv_cr(append_id)
{    	 
$('#inv_cr_addr'+append_id).attr("id","inv_cr_addr"+append_id+"x");
$('#inv_cr_addr'+append_id+"x").remove();
}


function remove_row(append_id)
   {    	 
        $('#addr'+append_id).attr("id","addr"+append_id+"x");
        $('#addr'+append_id+"x").remove();
        calculate_grand_total();
   }
function get_quotation_info()
{
	var qid = document.getElementById("qid").value;	
   	if(qid!='')
   	{
	   	$.ajax({
	   	async:"false",
		type: "POST",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_amc_quotation_info",
		data: {qid:qid} ,
		success: function(msg){
			
			document.getElementById('item_list_id').innerHTML=msg;
			get_inv_code();
			
				$.ajax({
			   	async:"false",
				type: "POST",
				url:"<?php echo base_url()?>index.php/Ajax/ajax_get_cust_accountId_from_quote",
				data: {qid:qid} ,
				success: function(accid){
					
					var grand_total= document.getElementById("grand_total").value;
					var sub_total= document.getElementById("sub_total").value;
					var discount_amt= document.getElementById("discount_amt").value;
					var vat_amt= document.getElementById("vat_amt").value;
					var crate = document.getElementById('crate').value;
					var x= (grand_total*crate).toFixed(2);	
					
				     }
				});
		     }
		});
	}
}

function get_inv_code()
{
	//var type = document.getElementById("inv_type").value;
	var ref_no = '';	
	$('.select3').select2({ width: "220px" });
	$.ajax({
		type: "POST",
		url:"<?php echo base_url()?>index.php/AMC/get_invoice_code",
		data: {ref_no:ref_no} ,
		success: function(msg){	  
			document.getElementById("invcode").value=msg;
		     }
	});
}



 function calculate_percent()
 {
    var grand_total = parseFloat(document.getElementById("grand_total").value);
	var advance_percent = document.getElementById("received_percent").value;	
	var adv_per= parseFloat(advance_percent/100);
	var x= parseFloat(grand_total*adv_per).toFixed(2);
	//alert(x);
	document.getElementById("received_amt").value=x;
	calculate_advance();
 }
 
 
 function calculate_advance()
 {
    	var grand_total = parseFloat(document.getElementById("grand_total").value);
	var received_amt = document.getElementById("received_amt").value;	
	var bal_amt= parseFloat(grand_total-received_amt);
	document.getElementById("balance").value=bal_amt;
 }
 function calculate_total(append_id)
{
	var price = parseFloat(document.getElementById("price"+append_id).value);
	var quantity = parseFloat(document.getElementById("qty"+append_id).value);
	var total = price*quantity;
	document.getElementById("total"+append_id).value=parseFloat(total).toFixed(2);

	calculate_grand_total();
}
function calculate_grand_total()
{
	var i_value=0;i_total=0;
	$('.subItemAmt').each(function()
	{
		i_value=$(this).val();
		if(i_value=='')
			 i_value = 0;
		else
			i_total+=parseFloat(i_value);
	});
	if(isNaN(i_total)) var s_total = 0;

	document.getElementById("sub_total").value= parseFloat(i_total).toFixed(2);

	if(document.getElementById("discount").value==0)
		var discount=0;
	else
	{
		var discount_per = parseFloat(document.getElementById("discount").value/100);
		var discount= i_total*discount_per
	}
	var total_before_vat = i_total-discount;
	
	document.getElementById("discount_amt").value= parseFloat(discount).toFixed(2);
	document.getElementById("total_before_vat").value= parseFloat(total_before_vat).toFixed(2);

	var vat_percent= document.getElementById("vat_percent").value;
	var vat_per= parseFloat(vat_percent/100);
   	var calVatAmt = parseFloat(total_before_vat*vat_per);
	document.getElementById("vat_amt").value= parseFloat(calVatAmt).toFixed(2);
	document.getElementById("inv_cr_amount0").value= parseFloat(i_total).toFixed(2);
	document.getElementById("inv_cr_amount1").value= parseFloat(calVatAmt).toFixed(2);
	document.getElementById("inv_cr_amount2").value= parseFloat(discount).toFixed(2);
   	
	var miscellaneous_amt1= parseFloat(document.getElementById("miscellaneous_amt1").value);
	var miscellaneous_amt2= parseFloat(document.getElementById("miscellaneous_amt2").value);
   	var grand_total = parseFloat(calVatAmt+total_before_vat+miscellaneous_amt1+miscellaneous_amt2);
	
	//var crate= document.getElementById('crate').value;
	var crate=1;
	var grand_total = parseFloat(grand_total*crate);
	document.getElementById("grand_total").value= parseFloat(grand_total).toFixed(2);
	document.getElementById("inv_dr_amount0").value= parseFloat(grand_total*document.getElementById('crate').value).toFixed(2);
}

function get_currency_conversion()
{
	var str=$('#currency_id').val();
	var myarray = str.split("@");
	var cid=myarray[0];
	var crate=myarray[1];
	var currabrev=myarray[2];
	document.getElementById('cid').value=cid;
	document.getElementById('crate').value=crate;
	document.getElementById('currabrev').innerHTML=currabrev;
	
	var grand_total= document.getElementById("grand_total").value;
	var crate = document.getElementById('crate').value;
	var x= (grand_total*crate).toFixed(2);	
	document.getElementById("inv_dr_amount0").value=x; 
			
	//calculate_grand_total();
}
function check_vat_option()
{
	var checkBox = document.getElementById("vatbox");	
	var vat_percent=document.getElementById("vat_percent1").value;
	
	// If the checkbox is checked, display the output text
	if (checkBox.checked == true){
		$("#vat_percent").val(vat_percent);	
		calculate_grand_total();
		//var total_before_vat = document.getElementById("total_before_vat").value;
		//var subtot=parseFloat(total_before_vat*(vat_percent/100)).toFixed(2);
		//var x= parseFloat(total_before_vat)+parseFloat(subtot);
	 	//document.getElementById("vat_amt").value=subtot;
	 	//document.getElementById("grand_total").value=parseFloat(x).toFixed(2);
	 	
	} else {
	 
		$("#vat_percent").val(0);
	 	document.getElementById("vat_amt").value=0.00;	
		calculate_grand_total();
		//var total_before_vat = document.getElementById("total_before_vat").value;
	 	//document.getElementById("grand_total").value=total_before_vat;
	}
}

function get_account_balance(append_id,type,crdr_type)
{
	if(type=='inv_dr' || type=='inv_cr')
		var x= 'inv_';
	else
		var x= 'receipt_';
		
	var account_id=document.getElementById(x+crdr_type+append_id).value;
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
// function check_total()
// {
// 	var type = document.getElementById("inv_type").value;
// 	if(type=='TI')
// 	{
		
// 		document.getElementById("inv_debtor0").required = true;
// 		document.getElementById("inv_dr_amount0").required = true;
		
// 		var a1 = parseFloat(document.getElementById("inv_cr_amount0").value);
// 		var a2 = parseFloat(document.getElementById("inv_cr_amount1").value);
// 		var a3 = parseFloat(document.getElementById("inv_cr_amount2").value);
// 		var a4 =parseFloat(document.getElementById("inv_cr_amount3").value);
// 		var k_total= parseFloat(a1+a2+a4-a3).toFixed(2);
		
// 	 	var dr_total=$('#inv_dr_amount0').val();

// 		 if(parseFloat(k_total) != parseFloat(dr_total))
// 		{
// 		     alert("Both debit total and credit total must match");
// 		     return false;
// 		}
// 	}
// 	else
// 	{
		
// 		document.getElementById("inv_debtor0").required = false;
// 		document.getElementById("inv_dr_amount0").required = false;
// 		return true;
// 	}
// }
function calculate_discount(event,append_id){
	var total=0;
	var new_tot=0;
	if(event.target.id == "dis_per"+append_id){
		var dis_per = event.target.value;
		if( !isNaN(dis_per) && dis_per > 0 ){
		document.getElementById("dis_val"+append_id).value = 0;
		var price = parseFloat(document.getElementById("price"+append_id).value);
		var quantity = parseFloat(document.getElementById("qty"+append_id).value);
		var total = price*quantity;
		 new_tot = total - ((dis_per/100)*total);
		 document.getElementById("total"+append_id).value=parseFloat(new_tot).toFixed(2);
		

	}
	else{
		calculate_total(append_id);
	}
	}
	else if(event.target.id == "dis_val"+append_id){
		var dis_val = event.target.value;
		if(!isNaN(dis_val) && dis_val != 0){
		document.getElementById("dis_per"+append_id).value = '';
		var price = parseFloat(document.getElementById("price"+append_id).value);
		var quantity = parseFloat(document.getElementById("qty"+append_id).value);
		var total = price*quantity;
		 new_tot = total - dis_val;
		 document.getElementById("total"+append_id).value=parseFloat(new_tot).toFixed(2);
		
	}
	else{
		calculate_total(append_id);
	}
	}
	
	calculate_grand_total();
	
}
function generateTable() {
    var numInstallments = parseInt(document.getElementById("num_installments").value, 10);
    var start_date = document.getElementById("amc_start_date").value;
    var end_date = document.getElementById("amc_end_date").value;
    var grand_total = parseFloat(document.getElementById("grand_total").value);

    var tableHTML = "<table class='table'><tr><th>Installment Name</th><th>Period</th><th>Payment Date</th><th>Amount</th></tr>";

    // Convert start_date and end_date to Date objects
    var startDateObj = new Date(start_date);
    var endDateObj = new Date(end_date);

    // Calculate total duration in milliseconds
    var totalDuration = endDateObj.getTime() - startDateObj.getTime();
    
    // Calculate payment amount
    var pay_amt = grand_total / numInstallments;

    // Initialize toDate for the first installment
    var toDate = new Date(startDateObj);

    for (var i = 0; i < numInstallments; i++) {
        // Calculate fromDate for the first installment
        var fromDate = new Date(toDate);

        // For the first installment, set toDate to the end of the period
        if (i === 0) {
            toDate.setTime(startDateObj.getTime() + (totalDuration / numInstallments));
        } else {
            // For subsequent installments, set fromDate to the next day after the previous toDate
            fromDate.setDate(toDate.getDate() + 1);
            // Calculate the toDate based on the same interval as before
            toDate = new Date(fromDate);
            toDate.setTime(fromDate.getTime() + (totalDuration / numInstallments));
        }

        // Format dates as YYYY-MM-DD for HTML input[type='date']
        var fromDateString = fromDate.toISOString().split('T')[0];
        var toDateString = toDate.toISOString().split('T')[0];

        // Determine installment name (e.g., "1st Installment")
        var suffix = (i === 0) ? "st" : (i === 1) ? "nd" : (i === 2) ? "rd" : "th";
        var installmentName = (i + 1) + suffix;
        var ins_name = installmentName + ' Installment';

        // Add row to the table with the installment name
        tableHTML += "<tr><td><input type='text' name='ins_name[" + i + "]' value='" + ins_name + "' readonly/></td><td>From<input type='date' name='from[" + i + "]' value='" + fromDateString + "'/> To<input type='date' name='to[" + i + "]' value='" + toDateString + "'/></td><td><input type='date' name='payment_date[" + i + "]' value='" + fromDateString + "'></td><td><input type='number' name='installment_amount[" + i + "]' value='" + pay_amt.toFixed(2) + "'></td></tr>";
    }

    tableHTML += "</table>";

    // Insert the generated table HTML into the DOM
    document.getElementById("installments_table").innerHTML = tableHTML;
}

$(document).ready(function() {
    var selected_qid = $('#qid').val();
    if(selected_qid) {
        get_quotation_info(); // load rows for the selected quotation
    }
});
function toggleSlaTable()
{
    if($('#sla_enabled').is(':checked')){
        $('#sla_section').show();

        // only add defaults if no PHP rows exist
        if($('#sla_body').children().length === 0){
            let defaults = [
                {
                    item: "Critical / Emergency",
                    avail: "24/7 call-out services",
                    response: "1-2 hrs",
                    restore: "3-6 hrs",
                    resolve: "2-3 Days"
                }
            ];

            defaults.forEach(d => addSlaRow(d));
        }

    } else {
        $('#sla_section').hide();
    }
}
function toggleAnnexureTable()
{
    if($('#annexure_enabled').is(':checked')){
        $('#annexure_section').show();
    } else {
        $('#annexure_section').hide();
    }
}
$(document).ready(function () {

    // SLA
    if($('#sla_enabled').is(':checked')){
        $('#sla_section').show();
    } else {
        $('#sla_section').hide();
    }

    // Annexure
    if($('#annexure_enabled').is(':checked')){
        $('#annexure_section').show();
        calculateAnnexTotal();
    } else {
        $('#annexure_section').hide();
    }

});

function calculateAnnexTotal()
{
    let total = 0;

    $('.annex_qty').each(function(){
        let val = parseFloat($(this).val());

        if(!isNaN(val)){
            total += val;
        }
    });

    $('#annex_total_qty').val(total);
}



    

</script>
