<?php $this->load->helper('stock_helper.php'); ?>
<style type="text/css">

.select2Width {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 320px !important;
  min-width: 320px !important;
}

</style>

<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_quot_direct" autocomplete="off" enctype="multipart/form-data">
			
<div class="form-group row">                        
    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Select Branch<span style="color: red;"> * </span>:</label><br/>
       <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">

		<select name="branch" id="branch" class="form-control">
            <option value=''>Select</option> <?php foreach ($branch_list as $branch): ?> <option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option> <?php endforeach; ?>
         </select> 
		</div>
     </div>
	
	<div class="form-group row">

	


		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Select Customer<span style="color: red;"> * </span>:</label><br/>
		
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			<select name="customer_id" id="customer_id" class="form-control select2"> </select>
		</div>
		<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Project Name<span style="color: red;"> * </span>:</label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			<input type="text" name="project_name" id="project_name" required class="form-control form-control-sm bg-soft-gray"  tabindex='3'>
		</div>	  
		<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Quotation date</label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
			<div class="input-group date datepicker1">			                  
				<input type="text" class="form-control form-control-sm datepicker1" id="qdate" name="qdate" value="<?php echo date('d-m-Y')?>" required tabindex='2'>
			<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
				</div>
	<div id='cust_div' style="display:none;">
		<div class="form-group row" >	
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Customer Name<span style="color: red;"> * </span>:</label>
		    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-2">
			     <input type='text' tabindex="3" class="form-control form-control-sm" id="customer_name" name="customer_name" placeholder="" />
		    </div> 	
		    <label class="col-xs-12 col-sm-3 col-md-1 col-lg-2 col-form-label"> Email</label>
		    <div class="col-xs-12 col-sm-10 col-md-2 col-lg-2">
			     <input type='text' tabindex="6" class="form-control form-control-sm" id="cust_email" name="cust_email"  placeholder="" />
		    </div> 	
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label"> Mobile No:</label>
		    <div class="col-xs-12 col-sm-10 col-md-2 col-lg-2">
			     <input type='text' tabindex="7" class="form-control form-control-sm" id="cust_mobile" name="cust_mobile"  placeholder="" />
		    </div> 	
		</div>	
	</div>
	<div class="form-group row">
		<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Quotation Code<span style="color: red;"> * </span>:</label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			<input type="text" name="qcode" id="qcode" class="form-control form-control-sm bg-soft-gray"  tabindex='3' value="<?php echo $Code; ?>">
		</div>
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Client Ref No  </label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >			                  
				<input type="text" class="form-control form-control-sm" id="client_ref" name="client_ref" tabindex="6" >
		</div>
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Quotation Print<span style="color: red;"> * </span></label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			<select tabindex="4" class="form-select form-control-sm select2 " id="quot_print_type" name="quot_print_type" tabindex="9" required>
				<option value="1">Non-comprehensive</option>
				<option value="2">Comprehensive</option>
				</select>
		</div>
	</div>
	<div class="form-group row">
		<!-- <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Upload Document <br/>(PDF/PNG/JPEG)</label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
				<input type='file' class="form-control form-control-sm" id="qo_file" name="qo_file" tabindex="7" placeholder="" />	
		</div> -->
		

    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">
       Location
    </label>

    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
        <input type="text" name="project_location" id="project_location"
               class="form-control form-control-sm">
    </div>

    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">
        Subject
    </label>

    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
        <textarea name="subject" id="subject"
                  class="form-control form-control-sm"
                  rows="4"></textarea>
    </div>

	<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">AMC Type <span style="color: red;"> * </span></label>
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			<select tabindex="4" class="form-select form-control-sm select2 " id="enquiry_type" name="enquiry_type" tabindex="8" required>
				<option value="2">New products </option>
				<option value="1">Company Products </option>
				<option value="3">Partial Company/Partial New</option>
				</select>
		</div>





		
	</div>
	<div class="form-group row align-items-center">

    <label class="col-sm-2 col-form-label">Contract Type</label>

    <div class="col-sm-2">
        <select class="form-control form-control-sm"
                name="contract_type"
                id="contract_type"
                onchange="toggleContractCount()">
            <option value="">Select</option>
            <option value="Yearly">Yearly</option>
            <option value="Quarterly">Quarterly</option>
        </select>
    </div>

    <div class="col-sm-3" id="year_block" style="display:none;">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">No. of Years</label>
            <input type="number" class="form-control form-control-sm"
                   name="no_of_years" id="no_of_years" min="1">
        </div>
    </div>

    <div class="col-sm-3" id="quarter_block" style="display:none;">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">No. of Quarters</label>
            <input type="number" class="form-control form-control-sm"
                   name="no_of_quarters" id="no_of_quarters" min="1">
        </div>
    </div>

</div>
	<h4>Details</h4>
	<div class="form-group row" >
	   <table class="table table-bordered table-hover" id="tab_logic">
    <thead id="quotation_header">
        <tr>
            <th>System</th>
            <th>Price (AED + VAT)</th>
            <th>Qty</th>
            <th>Final Total Rates</th>
            <th></th>
        </tr>
    </thead>

    <tbody id="mytbbody">
    </tbody>
</table>
	</div>
<div class="text-right mt-2">
    <button type="button" class="btn btn-success btn-sm" onclick="addNewRow()">
        <i class="fa fa-plus"></i> Add Row
    </button>
</div>
		
		
	<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-1 col-lg-1 col-form-label">SubTot</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		     	 <input type="text" id='sub_total' name='sub_total' readonly class="form-control form-control-sm" value="0" tabindex=12>
		    </div>
				<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">AMC Dis:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" name="amc_discount" id="amc_discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value=0 tabindex=7>
		      </div>
			<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">Dis.%:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" name="discount" id="discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value=0 tabindex=7>
		      </div>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" step="0.01" name="discount_amt" id="discount_amt" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value='0' tabindex=8>
		     </div>
	     	   
		    <label class="col-xs-12 col-sm-2 col-md-2  col-lg-2 col-form-label">Total before VAT</label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
		       <input type="text" id='total_before_vat' name='total_before_vat' readonly class="form-control form-control-sm" value="0" >
		      </div>
		</div>
		<hr class='bg-primary'></hr>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">VAT (<?php echo $vat_percent?>%) 
		    <input type='checkbox' id='vatbox' value='1' checked onclick='check_vat_option()' /></label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
		      <input type="text" id='vat_amt' name='vat_amt' class="form-control form-control-sm" readonly="TRUE" value="0" readonly>
		      <input type="hidden" id='vat_percent' name='vat_percent' class="form-control" value="<?php echo $vat_percent;?>" >
	        </div>
			<!-- <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Select Currency<span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
				<select tabindex="14" class="form-select form-control-sm select2" id="currency_id" name="currency_id" required onchange="get_currency_conversion()" style='width:160px'>
				<option value="">Select currency</option>
				<?php foreach($currency_list as $s) {?>
					<option value="<?php echo $s->id.'@'.$s->rate.'@'.$s->currabrev; ?>"><?php echo $s->country.' '.$s->currabrev;?></option>
				<?php } ?>
				</select>
				<input type="hidden" id='cid' name='cid' >
				<input type="hidden" id='crate' name='crate' value='0'>
		    </div> -->
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Grand Total <span id='currabrev'></span><span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total' readonly class="form-control form-control-sm"  value="0" required>
		    </div>
		</div>
		
		<!-- <div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Terms and Exclusion:</label> 
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
			<textarea class="form-control" id="exclusion_terms" name="exclusion_terms" rows="4" placeholder="">
</textarea>   
				</div>       
		</div> -->
		<div class="form-group row">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">
        Scope of Work:
    </label>

    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
        <textarea class="form-control"
                  id="scope_work"
                  name="scope_work"
                  rows="6">1. Carrying out systematic inspection of the equipment, lubricating, motor tuning, and adjustment, testing for normal working and general servicing for good working and submitting the service report duly signed by the concerned authority.

2. Electricals, Mechanical & Civil necessity requirements to engineer while attending Preventive Maintenances should be provided by Customer.

3. In case of non-availability of the spare component for replacement quotation shall be provided. After the approval the work will be done and invoice shall be submitted. Payment after invoice submission as per usual process.</textarea>
    </div>
</div>
       <div class="form-group row">   
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">
        Payment Terms:
    </label>

    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
        <textarea class="form-control"
                  id="term1"
                  name="term1"
                  rows="6">Quarterly after each PPM CDC payment.

This price will be varied according to the changes in quantity, design size specification etc.

We hope the above meets your requirements and waiting to execute your order with highest priority.

Please do not hesitate to contact the undersigned for any further enquiry.

We assure you our best services all the time.</textarea>
    </div>
</div>
		<div class="form-group row">   
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">
        No. of Visits
    </label>

    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
        <textarea class="form-control"
                  id="ppm_details"
                  name="ppm_details"
                  rows="6">1 - 4 Visits (One in every three months.) with unlimited callout.

2 - We will not be responsible for any loss or damage caused by any accidents or due to the natural calamity to the system.

3 - Operator replacement is not included in this contract.

4 - Spare parts replacement will be payment after completion of approved work and submission of invoice.</textarea>
    </div>
</div>
		<hr>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Validity:</label>
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<input type="text" class="form-control form-control-sm" list="validity"  name="validity" value="15 days from quotation date"/>
			
			</div>
		
		</div>
		<!-- <div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Select Company</label>
			<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
				<select class="form-select form-control-sm select2" id="cmp_id" name="cmp_id">
					<option value="">Select</option>
					<option selected value="1">Dexion</option>
				</select>
			</div>
		</div> -->
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

		<h6>Contact Person Details</h6>
		<br/>
		<div class="form-group row">
    <label class="col-md-1 control-label">Name</label>
    <div class="col-md-3">
        <input id="cp_name" name="cp_name"
               type="text"
               class="form-control form-control-sm">
    </div>

    <label class="col-md-1 control-label">Mobile</label>
    <div class="col-md-3">
        <input id="cp_mobile" name="cp_mobile"
               type="text"
               class="form-control form-control-sm">
    </div>

    <label class="col-md-1 control-label">Email</label>
    <div class="col-md-3">
        <input id="cp_email" name="cp_email"
               type="email"
               class="form-control form-control-sm">
    </div>
</div>


		<div class="col-sm-10">
		<button type="submit"  tabindex="22"  id="add" class="btn btn-primary m-b-0">Create Quotation</button>
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
$(document).ready(function() {


 $('#customer_id').select2({
            placeholder: "-- Select Customer --",
            allowClear: true,
            width: '100%'
        });


 /* ==========================================================
           2. AJAX: LOAD NEW CUSTOMER MODAL
        ========================================================== */
        $('.view-employees').on('click', function(e) {
            e.preventDefault();
            $('#modal-body-content').html("Loading...");
            $('#myModal').modal('show');
            $.ajax({
                url: "<?= base_url('index.php/Ajax/add_new_customer') ?>",
                type: "POST",
                success: function(response) {
                    $('#modal-body-content').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("AJAX error: " + error);
                }
            });
        });

 /* ==========================================================
           3. BRANCH → CUSTOMER FILTER
        ========================================================== */
        $('#branch').on('change', function() {
            const branch_id = $(this).val();
            const $customer = $('#customer_id');
            $customer.empty().append('<option value="">-- Select Customer --</option>');
            if (branch_id) {
                $.ajax({
                    url: '<?= base_url("index.php/Company/get_customers_by_branch") ?>',
                    type: 'POST',
                    data: {
                        branch_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(_, customer) {
                            $customer.append(`<option value="${customer.customer_id}">
                            ${customer.customer_name} (${customer.customer_code}) → ${customer.contact_number}
                        </option>`);
                        });
                        $customer.trigger('change');
                    }
                });
            }
        });

    var i = 1; 

	$(document).keypress(function(event) {
		if (event.which == 13) {
		    if ($(event.target).is('input, select')) {
		        event.preventDefault();
		    }
		}
	    });

	$(document).on('keyup', 'input[name^="price"]', function(event) {
    if (event.which === 13) { // Enter key
        event.preventDefault(); // Prevent form submission

        var currentRow = $(this).closest('tr'); // Get the closest table row
        addNewRow(); // Add new row

        // Optionally, you can focus the first input in the new row
        $('#addr' + i + ' input:first').focus();
    }
});


    $(document).on("click", ".remove_row", function() {
        var rowId = $(this).closest("tr").attr("id");
        $("#" + rowId).remove();
    });
});

   function get_customer_info() 
 {
   	var customer_id = document.getElementById("customer_id").value;
	if(customer_id=='new')
	{		
		document.getElementById("cust_div").style.display = "block";
		document.getElementById("customer_name").required = true;
	}
	else
	{
		document.getElementById("customer_name").required = false;
		document.getElementById("cust_div").style.display = "none";
		get_invoice_list();
    }
	
 }   

  function get_div_active()
{
	var enq_type=$("#enquiry_type").val();
	var cust_id = $("#customer_id").val();
	if (enq_type != 2){
		$.ajax({
			type: "POST",
			url:"<?php echo base_url()?>index.php/Ajax/get_invoices_of_customer",
			data: {cust_id:cust_id} ,
			success: function(msg){	       	
			document.getElementById('enq_form').innerHTML=msg;
			$('.select2').select2();
			}
		});
	}
}

function get_product_description(append_id)
{
	var pcode= $('#order_code'+append_id).val();
	var newStr = pcode.replaceAll(',','');
	$('#pcode'+append_id).val(newStr);
	$.ajax
	({
		url: "<?php echo site_url('Product/get_product_description'); ?>",
		type: 'POST',
		data: {post_id: pcode },
		success: function(msg) {
			if(msg!=0)
			{
				//alert(msg);
				$('#desc'+append_id).val(msg);
				
			}
			else
			{
				alert('Match not found');
			}
		}
	});
}

function get_enquiry_info()
{
	var enq_id=$("#enq_id").val();
	var rev_version=1;	
	$.ajax({
        type: "POST",
        url:"<?php echo base_url()?>index.php/Ajax/get_enquiry_items_for_enq",
        data: {enq_id:enq_id, rev_version:1} ,
        success: function(msg){	       	
		document.getElementById('mytbbody').innerHTML=msg;
	     }
	});
}

function get_invoice_list(){
	var cust_id= document.getElementById("customer_id").value;
	
	$.ajax
	({
		url: "<?php echo site_url('Ajax/ajax_get_inv_list'); ?>",
		type: 'POST',
		data: {cust_id: cust_id},
		success: function(msg) {
			var supp = document.getElementById("inv_no");
			supp.innerHTML = "<option value=''>Select</option>"; // Clearing inner HTML and adding default option

			var invoice = JSON.parse(msg);

			// Create new option element for the single invoice
			var optionElement = document.createElement("option");
			optionElement.value = invoice.invoice_id;
			optionElement.text = invoice.invoice_code;
			supp.appendChild(optionElement);
		}
	});

}

function populateProductOptions() {
	var enq_type=$("#enquiry_type").val();
  	var productDropdown = document.getElementById("prd_type");
  var selectedOption = mainDropdown.value;

  // Clear existing options
  secondaryDropdown.innerHTML = "";
  $.ajax
	({
		url: "<?php echo site_url('Ajax/ajax_get_subcategory'); ?>",
		type: 'POST',
		data: {cat:selectedOption},
		success: function(msg) {
			var options = JSON.parse(msg);
			$("#prd_type").append('<option value="">Select</option>');
			for (var obj of options) {
				$("#prd_type").append('<option value="' + obj.category_id + '">' + obj.category_name + '</option>');
			}
		// 	msg.forEach(function(obj) {
    	// console.log("Name: " + obj.category_id );
		// 	});
				
		}
	});
  
  }

  function calculate_total(append_id)
{
	
	var price 	 = parseFloat(document.getElementById("price"+append_id).value);
	var quantity = parseFloat(document.getElementById("qty"+append_id).value);
	
	var total 	 = price*quantity;
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
	if (document.getElementById("amc_discount").value>0){
		var amc_discount = parseFloat(document.getElementById("amc_discount").value).toFixed(2)
	}
	else{
		var amc_discount =0;
	}
		
	if(document.getElementById("discount").value==0)
	 	var discount=0;
	 else
	 {
	 	var discount_per = parseFloat(document.getElementById("discount").value/100);
	 	var discount= i_total*discount_per;
	 	document.getElementById("discount_amt").value= parseFloat(discount).toFixed(2);
	 }
	 var discount= document.getElementById("discount_amt").value;
	 var total_before_vat = i_total-amc_discount-discount;
	
	document.getElementById("total_before_vat").value= parseFloat(total_before_vat).toFixed(2);


	var vat_percent= document.getElementById("vat_percent").value;
	var vat_per= parseFloat(vat_percent/100);
   	var calVatAmt = parseFloat(total_before_vat*vat_per);
	document.getElementById("vat_amt").value= parseFloat(calVatAmt).toFixed(2);
   	var grand_total = parseFloat(calVatAmt+total_before_vat);
	
	var crate=1;
	var grand_total = parseFloat(grand_total*crate);
	document.getElementById("grand_total").value= parseFloat(grand_total).toFixed(2);
}

function check_vat_option()
{
	var checkBox = document.getElementById("vatbox");
	var vat_percent="<?php echo $vat_percent?>";
	if (checkBox.checked == true){
		$("#vat_percent").val(vat_percent);		
		calculate_grand_total();
	 	
	} else {
	 
		$("#vat_percent").val(0);
	 	document.getElementById("vat_amt").value=0.00;
		calculate_grand_total();
	}
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
	calculate_grand_total();
}

 
function copy_billing_address()
{
	var checkBox = document.getElementById("copy_address");
	// If the checkbox is checked, display the output text
	if (checkBox.checked == true){
		var billing_addr1 = document.getElementById("billing_addr1").value;
		var billing_city = document.getElementById("billing_city").value;
		var billing_state = document.getElementById("billing_state").value;
		var billing_po = document.getElementById("billing_po").value;
		var billing_country = document.getElementById("billing_country").value;
		
	 	document.getElementById("shipping_addr1").value=billing_addr1;
	 	document.getElementById("shipping_city").value=billing_city;
	 	document.getElementById("shipping_state").value=billing_state;
	 	document.getElementById("shipping_po").value=billing_po;
	 	document.getElementById("shipping_country").value=billing_country;
	 	
	} else {
	 
	 	document.getElementById("shipping_addr1").value='';
	 	document.getElementById("shipping_city").value='';
	 	document.getElementById("shipping_state").value='';
	 	document.getElementById("shipping_po").value='';
	 	document.getElementById("shipping_country").value='';
	}
}

function get_trading_product_info(append_id)
{
	var product_id= document.getElementById("product_id"+append_id).value;
	if(product_id!='')
	{
	$.ajax
	({
		url: "<?php echo site_url('Product/ajax_get_product_details'); ?>",
		type: 'POST',
		data: {product_id: product_id },
		dataType: "json",
		success: function(msg) {
				document.getElementById("desc"+append_id).value=msg.item_desc;
		}
	});
	}
	else
	{
		document.getElementById("desc"+append_id).value='';
	}
}
var i = 1;

function addNewRow()
{
    let rowId = i++;

    let type = $('#contract_type').val();
    let count = 0;

    if(type == 'Yearly') count = parseInt($('#no_of_years').val()) || 0;
    else if(type == 'Quarterly') count = parseInt($('#no_of_quarters').val()) || 0;

    let row = `<tr id="addr${rowId}">`;

    row += `
       <td>
  <input type="text" name="prod_id[]" class="form-control">
</td>

<td>
  <input type="number" name="price[]" id="price${rowId}" class="form-control"
         onkeyup="calculateDynamicRow(${rowId})">
</td>

<td>
  <input type="number" name="qty[]" id="qty${rowId}" class="form-control"
         onkeyup="calculateDynamicRow(${rowId})">
</td>
    `;

    for(let y=1;y<=count;y++)
    {
        row += `
            <td>
                <input type="text" id="period_${y}_${rowId}" class="form-control" readonly>
            </td>
        `;
    }

    row += `
        <td><input type="text" id="final_total_${rowId}" class="form-control subItemAmt" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
    </tr>`;

    $('#mytbbody').append(row);

    calculateDynamicRow(rowId); // 🔥 IMPORTANT
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

function buildDynamicTable()
{
    let type = $('#contract_type').val();

    let count = 0;
    let label = '';

    if(type === 'Yearly')
    {
        count = parseInt($('#no_of_years').val()) || 0;
        label = 'Year';
    }
    else if(type === 'Quarterly')
    {
        count = parseInt($('#no_of_quarters').val()) || 0;
        label = 'Quarter';
    }

    let header = `
        <tr>
            <th>System</th>
            <th>Price (AED + VAT)</th>
            <th>Qty</th>
    `;

    for(let i=1;i<=count;i++)
    {
        header += `<th>Total Price ${i}${getOrdinal(i)} ${label}</th>`;
    }

    header += `
            <th>Final Total Rates</th>
            <th></th>
        </tr>
    `;

    $('#quotation_header').html(header);

    rebuildRows(count);
}
function getOrdinal(n)
{
    let s = ["th","st","nd","rd"];
    let v = n % 100;

    return s[(v-20)%10] || s[v] || s[0];
}
function rebuildRows(count)
{
    let rows = '';

    rows += '<tr id="addr0">';

    rows += `
        <td>
            <input type="text" name="prod_id[]" class="form-control">
        </td>

        <td>
            <input type="number" name="price[]" id="price0" class="form-control"
       onkeyup="calculateDynamicRow(0)">
        </td>

        <td>
            <input type="number" name="qty[]" id="qty0" class="form-control"
       onkeyup="calculateDynamicRow(0)">
        </td>
    `;

    for(let i=1;i<=count;i++)
    {
        rows += `
            <td>
            <input type="text"
                   id="period_${i}_0"
                   name="period_total_${i}[]"
                   class="form-control"
                   readonly>
        </td>
        `;
    }

    rows += `
        <td>
            <input type="text"
       id="final_total_0"
       name="final_total[]"
        class="form-control subItemAmt"
       readonly>
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm">
                X
            </button>
        </td>
    `;

    rows += '</tr>';

    $('#mytbbody').html(rows);
}
$('#contract_type').change(function(){
    toggleContractCount();
    buildDynamicTable();
});

$('#no_of_years').on('keyup change', function(){
    buildDynamicTable();
});

$('#no_of_quarters').on('keyup change', function(){
    buildDynamicTable();
});
function calculateRow(row)
{
    let price = parseFloat($('#price'+row).val()) || 0;
    let qty   = parseFloat($('#qty'+row).val()) || 0;

    let amount = price * qty;

    let type = $('#contract_type').val();

    let count = (type == 'Yearly')
        ? parseInt($('#no_of_years').val())
        : parseInt($('#no_of_quarters').val());

    let finalTotal = 0;

    for(let i=1;i<=count;i++)
    {
        $('#period_total_'+i+'_'+row).val(amount.toFixed(2));
        finalTotal += amount;
    }

    $('#final_total_'+row).val(finalTotal.toFixed(2));
}
  
function calculateDynamicRow(row)
{
    let price = parseFloat($('#price'+row).val()) || 0;
    let qty   = parseFloat($('#qty'+row).val()) || 0;

    let total = price * qty;

    let type = $('#contract_type').val();

    let count = 0;

    if(type == 'Yearly')
        count = parseInt($('#no_of_years').val()) || 0;
    else if(type == 'Quarterly')
        count = parseInt($('#no_of_quarters').val()) || 0;

    if(count <= 0)
    {
        $('#final_total_'+row).val(total.toFixed(2));
        calculate_grand_total();
        return;
    }

   let finalTotal = 0;

for(let i = 1; i <= count; i++)
{
    $('#period_'+i+'_'+row).val(total.toFixed(2)); // same value in each year
    finalTotal += total;
}

$('#final_total_'+row).val(finalTotal.toFixed(2));


    calculate_grand_total();
}
$(document).on('input', 'input[id^="price"], input[id^="qty"]', function () {

    let row = $(this).closest('tr').attr('id').replace('addr','');

    calculateDynamicRow(row);
});

$(document).ready(function () {
    buildDynamicTable();   // 🔥 ADD THIS
});
let sla_i = 0;

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
function addSlaRow(d = null)
{
    $('#sla_body').append(`
        <tr id="sla_${sla_i}">
            <td><input type="text" name="service_item[]" value="${d?.item || ''}" class="form-control"></td>
            <td><input type="text" name="service_availability_period[]" value="${d?.avail || ''}" class="form-control"></td>
            <td><input type="text" name="response_time[]" value="${d?.response || ''}" class="form-control"></td>
            <td><input type="text" name="restoration_time[]" value="${d?.restore || ''}" class="form-control"></td>
            <td><input type="text" name="resolution_time[]" value="${d?.resolve || ''}" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="$('#sla_${sla_i}').remove()">X</button></td>
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
            addAnnexureRow();
        }
    } else {
        $('#annexure_section').hide();
        $('#annexure_body').html('');
    }
}

function addAnnexureRow()
{
    let sl = $('#annexure_body tr').length + 1;

    $('#annexure_body').append(`
        <tr id="annex_${annex_i}">
            <td><input type="text" name="sl_no[]" value="${sl}" class="form-control" readonly></td>
            <td><input type="text" name="type[]" class="form-control"></td>
            <td><input type="text" name="location[]" class="form-control"></td>
            <td><input type="number" name="annex_qty[]" class="form-control annex_qty" onkeyup="calculateAnnexTotal()"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeAnnexRow(${annex_i})">X</button></td>
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
        if(!isNaN(val)) total += val;
    });

    $('#annex_total_qty').val(total);
}

function removeAnnexRow(id)
{
    $('#annex_' + id).remove();
    calculateAnnexTotal();
}
</script>
