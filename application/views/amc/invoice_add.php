<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_invoice_data" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Select AMC Quotation <span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-4 col-lg-4" role='group'>
				<select tabindex="1" class="form-select form-control-sm select2" id="qid" name="qid" required onchange="get_quotation_info()" >
				<option value="">Select</option>
				<?php foreach($records as $s) {?>
				  <option value="<?php echo $s->quote_id ?>"><?php echo $s->quotation_code.' '.$s->customer_name;?></option>
				<?php } ?>
			      </select>
    	     		 </div>
					  <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
						<input type="text" name="invcode" id="invcode" class="form-control form-control-sm"  tabindex='1' value="<?php echo $code; ?>">
					</div>
					<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >		                  
		    	<input type="text" class="form-control form-control-sm " id="invdate" name="invdate" value="<?php echo date('d-m-Y')?>" required tabindex='2' readonly>	
    	    </div>
	  		
		</div>
		

		<div class="form-group row">
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">AMC Start Date<span style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >
						                  
		    			<input type="date" class="form-control form-control-sm " id="amc_start_date" name="amc_start_date"  required tabindex='3'>
					
    	    </div>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">AMC End Date<span style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >
						                  
		    			<input type="date" class="form-control form-control-sm " id="amc_end_date" name="amc_end_date"  required tabindex='4'>
					
    	    </div>
		</div>

	
		
		<input type="hidden" id="project_name" name="project_name" value="">

		
		<div id="item_list_id"> 
		<input type='hidden' name="ref_no" id="ref_no"  />	
		</div>
		<hr>

		<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        System SLA for Corrective Maintenance
    </label>

    <div class="col-sm-6">
        <input type="checkbox" id="sla_enabled" name="sla_enabled" value="1"
               onchange="toggleSlaTable()">
        Enable SLA
    </div>
</div>
<div id="sla_section" style="display:none; margin-top:15px;">

    <h6>SLA DETAILS</h6>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Service Item</th>
                <th>Service Availability Period</th>
                <th>Response Time</th>
                <th>Restoration Time</th>
                <th>Resolution Time</th>
                <th></th>
            </tr>
        </thead>

        <tbody id="sla_body"></tbody>
    </table>

    <button type="button" class="btn btn-primary btn-sm" onclick="addSlaRow()">
        + Add SLA
    </button>
</div>
		<div class="form-group row">
    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label text-primary">
        Conditions of AMC:
    </label>
    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
        <textarea class="form-control form-control-sm" rows="6" tabindex="18" name="conditions"></textarea>
    </div>
</div>
<hr>
<div class="form-group row">
    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label text-primary">
        Exclusions from AMC:
    </label>
    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
        <textarea class="form-control form-control-sm" rows="6" tabindex="19" name="exclusions"></textarea>
    </div>
</div>

<hr>
		
		<hr>
		<h6>Company Bank Details</h6>

<div class="form-group row">
    <table class="table table-bordered table-hover" id="tab_logic">
        <thead>
            <tr>
                <th>Select</th>
                <th>Bank Name</th>
                <th>Bank Account</th>
                <th>Bank Branch</th>
                <th>Bank IBAN</th>
                <th>Bank SWIFT</th>
            </tr>
        </thead>

        <tbody id="bank_body">
            <!-- AJAX will load bank rows here -->
        </tbody>
    </table>
</div>		

<hr>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Annexure Details
    </label>

    <div class="col-sm-6">
        <input type="checkbox" id="annexure_enabled" name="annexure_enabled" value="1"
               onchange="toggleAnnexureTable()">
        Enable Annexure
    </div>
</div>

<div id="annexure_section" style="display:none; margin-top:15px;">

    <h6>ANNEXURE DETAILS</h6>

    <button type="button" class="btn btn-primary btn-sm mb-2"
            onclick="addAnnexureRow()">
        + Add Row
    </button>

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

        <tbody id="annexure_body"></tbody>
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
		     	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Sales Person:</label>
		    	<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
			      <select tabindex="1" class="form-select form-control-sm select2" id="user_id" name="user_id" required style='width:170px'>
				<option value="">Select</option>
				<?php foreach($user_records as $s) {?>
				  <option <?php if($this->session->userdata('user_id')==$s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name;?></option>
				<?php } ?>
			      </select>
		       </div>
		     	
		</div>
		  
		<div class="form-group row">
			<label class="col-sm-2"></label>
			<div class="col-sm-10">
			<button type="submit"  tabindex="6"  id="add" class="btn btn-primary m-b-0">SAVE</button>
			</div>
		</div>
	    </form>

        </div>
    </div>
</div>
</div>
</div>
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
    var qid = $('#qid').val();
    if(qid === '') return;

    console.log('Selected Quotation ID:', qid);
    console.log('Sending AJAX request...');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('index.php/Ajax/ajax_get_amc_quotation_info'); ?>",
        data: { qid: qid },
        success: function(response) {
            console.log('AJAX RESPONSE RECEIVED');
            console.log(response); // 👈 MUST show FULL HTML

            // IMPORTANT: inject HTML
            $('#item_list_id').html(response);
			var branch_id = $('#item_list_id').find('#branch_id_ajax').val();
console.log(branch_id);

// ---------- Load SLA ----------
var sla = $('#item_list_id').find('#quotation_sla').val();

if(sla && sla != '[]')
{
    $('#sla_enabled').prop('checked', true);
    $('#sla_section').show();
    $('#sla_body').html('');
    sla_i = 0;

    JSON.parse(sla).forEach(function(r){

        addSlaRow({
            item: r.service_item,
            avail: r.service_availability_period,
            response: r.response_time,
            restore: r.restoration_time,
            resolve: r.resolution_time
        });

    });

}
else
{
    $('#sla_enabled').prop('checked', false);
    $('#sla_section').hide();
    $('#sla_body').html('');
}


// ---------- Load Annexure ----------
var annex = $('#item_list_id').find('#quotation_annexure').val();

if(annex && annex != '[]')
{
    $('#annexure_enabled').prop('checked', true);
    $('#annexure_section').show();
    $('#annexure_body').html('');
    annex_i = 0;

    JSON.parse(annex).forEach(function(r){

        addAnnexureRow();

        let last = $('#annexure_body tr:last');

        last.find('input[name="type[]"]').val(r.type);
        last.find('input[name="location[]"]').val(r.location);
        last.find('input[name="annex_qty[]"]').val(r.qty);

    });

    calculateAnnexTotal();
}
else
{
    $('#annexure_enabled').prop('checked', false);
    $('#annexure_section').hide();
    $('#annexure_body').html('');
}

			 var projectName = $(response).find('#project_name_ajax').val(); // or however your AJAX sends it
    $('#project_name').val(projectName);
	 load_branch_banks(branch_id);
        },
        error: function(xhr, status, error) {
            console.error('AJAX ERROR:', status, error);
            console.log(xhr.responseText);
        }
    });
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
	// document.getElementById("inv_cr_amount0").value= parseFloat(i_total).toFixed(2);
	// document.getElementById("inv_cr_amount1").value= parseFloat(calVatAmt).toFixed(2);
	// document.getElementById("inv_cr_amount2").value= parseFloat(discount).toFixed(2);
   	
	// var miscellaneous_amt1= parseFloat(document.getElementById("miscellaneous_amt1").value);
	// var miscellaneous_amt2= parseFloat(document.getElementById("miscellaneous_amt2").value);
   	var grand_total = parseFloat(calVatAmt+total_before_vat);//+miscellaneous_amt1+miscellaneous_amt2
	   
	//var crate= document.getElementById('crate').value;
	var crate=1;
	var grand_total = parseFloat(grand_total*crate);
	document.getElementById("grand_total").value= parseFloat(grand_total).toFixed(2);
	//document.getElementById("inv_dr_amount0").value= parseFloat(grand_total*document.getElementById('crate').value).toFixed(2);
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
    var start_date = document.getElementById("amc_start_date").value || 0;
    var end_date = document.getElementById("amc_end_date").value || 0;
	if(start_date == 0 || end_date == 0)
		alert("please make sure you have entered start and end dates.")
	else{
		
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
    
}

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%'
    });
});

function load_branch_banks(branch_id)
{
    console.log("Loading banks for:", branch_id);

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('index.php/AMC/get_branch_banks'); ?>",
        data: { branch_id: branch_id },
        success: function(html) {
            $('#bank_body').html(html);   // ✅ FIXED HERE
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

function toggleSlaTable()
{
    if($('#sla_enabled').is(':checked')){
        $('#sla_section').show();

        // load default SLA only if empty
        if($('#sla_body').children().length === 0){

            let defaults = [
                {
                    item: "Critical / Emergency",
                    avail: "24/7 call-out services",
                    response: "1-2 hrs",
                    restore: "3-6 hrs",
                    resolve: "2-3 Days"
                },
                {
                    item: "Major / High",
                    avail: "24/7 call-out services",
                    response: "4-8 hrs",
                    restore: "1-2 Days",
                    resolve: "5 Days"
                },
                {
                    item: "Minor / Medium",
                    avail: "24/7 call-out services",
                    response: "1 Day",
                    restore: "3 Working Days",
                    resolve: "1 Week"
                }
            ];

            defaults.forEach(function(d){
                addSlaRow(d);
            });
        }

    } else {
        $('#sla_section').hide();
    }
}
let sla_i = 0;



function addSlaRow(d = null)
{
    let item = d ? d.item : '';
    let avail = d ? d.avail : '';
    let response = d ? d.response : '';
    let restore = d ? d.restore : '';
    let resolve = d ? d.resolve : '';

    $('#sla_body').append(`
        <tr id="sla_${sla_i}">
            <td><input type="text" name="service_item[]" value="${item}" class="form-control"></td>
            <td><input type="text" name="service_availability_period[]" value="${avail}" class="form-control"></td>
            <td><input type="text" name="response_time[]" value="${response}" class="form-control"></td>
            <td><input type="text" name="restoration_time[]" value="${restore}" class="form-control"></td>
            <td><input type="text" name="resolution_time[]" value="${resolve}" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="$('#sla_${sla_i}').remove()">
                    X
                </button>
            </td>
        </tr>
    `);

    sla_i++;
}

let annex_i = 0;

function toggleAnnexureTable()
{
    if($('#annexure_enabled').is(':checked')){
        $('#annexure_section').show();

        if($('#annexure_body').children().length === 0){
            addAnnexureRow(); // default row
        }

    } else {
        $('#annexure_section').hide();
        $('#annexure_body').html('');
    }
}


function addAnnexureRow()
{
    let sl = $('#annexure_body tr').length + 1; // 👈 auto serial number

    $('#annexure_body').append(`
        <tr id="annex_${annex_i}">
            <td>
                <input type="text" name="sl_no[]" class="form-control" value="${sl}" readonly>
            </td>
            <td><input type="text" name="type[]" class="form-control"></td>
            <td><input type="text" name="location[]" class="form-control"></td>
            <td>
                <input type="number" name="annex_qty[]" class="form-control annex_qty"
                       onkeyup="calculateAnnexTotal()">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="removeAnnexRow(${annex_i})">
                    X
                </button>
            </td>
        </tr>
    `);

    annex_i++;

    calculateAnnexTotal();
}
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

function removeAnnexRow(id)
{
    $('#annex_' + id).remove();
    calculateAnnexTotal();
    renumberAnnex();
}
function renumberAnnex()
{
    $('#annexure_body tr').each(function(index){
        $(this).find('td:first input').val(index + 1);
    });
}

function toggleContractCount()
{
    var type = $('#contract_type').val();

    if(type == 'Yearly')
    {
        $('#year_block').show();
        $('#quarter_block').hide();

        $('#no_of_quarters').val('');
    }
    else if(type == 'Quarterly')
    {
        $('#quarter_block').show();
        $('#year_block').hide();

        $('#no_of_years').val('');
    }
    else
    {
        $('#year_block').hide();
        $('#quarter_block').hide();
    }
}
</script>
