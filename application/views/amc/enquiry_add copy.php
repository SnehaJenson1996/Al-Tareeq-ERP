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
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_new_enquiry" autocomplete="off" enctype="multipart/form-data">
			
	<div class="form-group row">
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Select Customer<span style="color: red;"> * </span></label><br/>
		
		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
	    		<select tabindex="3" class="form-select form-control-sm select2" id="customer_id" name="customer_id" onchange= "get_customer_info()">
					<option value="">Select</option>
	      			<option value="new">New Customer</option>
					<?php foreach($cust_records as $s) {?>
					<option value="<?php echo $s->customer_id ?>"><?php echo $s->cust_code.' '.$s->cust_name;?></option>
					<?php } ?>
			    </select>
	  		</div>
			  
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Enquiry Code</label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">						                  
		    			<input type="text" class="form-control form-control-sm" id="amc_enq_code" name="amc_enq_code" value="<?php echo $code; ?>" tabindex=1>
				</div>
    	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Enquiry Date <span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
				<div class="input-group date datepicker1">			                  
		    			<input type="text" class="form-control form-control-sm datepicker1" id="enq_date" name="enq_date" value="<?php echo date('d-m-Y')?>" required tabindex=1>
					<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
			      	</div>
    	     		 </div>
					  
		</div>
		<div id='cust_div' style="display:none;">
		<div class="form-group row" >	
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Customer Name<span style="color: red;"> * </span>:</label>
		    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
			     <input type='text' tabindex="5" class="form-control form-control-sm" id="customer_name" name="customer_name" placeholder="" />
		    </div> 	
		    <label class="col-xs-12 col-sm-3 col-md-1 col-lg-1 col-form-label"> Email</label>
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
	  		
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Enquiry Type <span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
	    			<select tabindex="4" class="form-select form-control-sm select2 " id="enquiry_type" name="enquiry_type" required>
					<option value="">Select</option>
					<option value="2">New products </option>
					<option value="1">Company Products </option>
					<option value="3">Partial Company/Partial New</option>
			      </select>
	  		</div>

			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Start Date<span style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
				<div class="input-group date datepicker1">			                  
		    			<input type="text" class="form-control form-control-sm datepicker1" id="amc_start_date" name="amc_start_date" value="<?php echo date('d-m-Y')?>" required tabindex=1>
					<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
			    </div>
    	    </div>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">End Date<span style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
				<div class="input-group date datepicker1">			                  
		    			<input type="text" class="form-control form-control-sm datepicker1" id="amc_end_date" name="amc_end_date" value="<?php echo date('d-m-Y')?>" required tabindex=1>
					<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
			    </div>
    	    </div> 
		</div>
		<div class="form-group row">
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Project Name</label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
					<select tabindex="4" class="form-select form-control-sm select2 " id="inv_no" name="inv_no" onchange="get_inv_items();" >
				</select>
			</div>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Remark/Comments </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-6">
	    			<input type='text' tabindex="9" class="form-control form-control-sm" id="remark" name="remark" placeholder="" />	
	  		</div>
		</div>
		
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Client Ref No  </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >			                  
	    			<input type="text" class="form-control form-control-sm" id="client_ref" name="client_ref"  tabindex=8>
    	    </div>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 col-form-label">Upload Document(PDF/PNG/JPEG) </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
	    			<input type='file' tabindex="501" class="form-control form-control-sm" id="other_file" name="other_file" placeholder="" />	
	  		</div>
	  		
		</div>
		<h4>Details</h4>
	   <div class="form-group row" >
	   <table class="table table-bordered table-hover" id="tab_logic">
			<thead>
				<tr>
					<th>Product</th> 
					<th>Description</th>      
					<th>Quantity</th>  
					<th><a id="add_row" title="Add" class="btn btn-sm bg-orange"><span class="fa fa-plus"></span></a></th>
				</tr>
			</thead>
			<tbody id="mytbbody">
				<tr id='addr0'>
					<td><input type='text' name='prod_id[0]' class='form-control bg-soft-gray form-control-sm'></td>
					<td><input type='text' name='desc[0]' class='form-control form-control-sm'></td>
					<td><input type='number' name='qty[0]' class='form-control form-control-sm'></td>
					<td><a title='Delete' class='btn btn-sm bg-orange remove_row'><span class='fa fa-trash'></span></a></td>
				</tr>
			</tbody>
		</table>
	    </div>

		
		
		<div class="form-group row">
		<label class="col-sm-2"></label>
		<div class="col-sm-10">
		<button type="submit"  tabindex="502"  id="add" class="btn btn-primary m-b-0">Submit</button>
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
    var i = 1; // Start with 1 because addr0 is already there
	$("#add_row").click(function() {
    // Construct the HTML content for the new row
    var newRowHtml = "<td><input type='text' name='prod_id["+i+"]' class='form-control bg-soft-gray form-control-sm'></td>" +
                     "<td><input type='text' name='desc["+i+"]' class='form-control form-control-sm'></td>" +
                     "<td><input type='number' name='qty["+i+"]' class='form-control form-control-sm'></td>" +
                     "<td><a title='Delete' class='btn btn-sm bg-orange remove_row'><span class='fa fa-trash'></span></a></td>";

    // Append the new row to the table body
    $('#mytbbody').append('<tr id="addr' + i + '">' + newRowHtml + '</tr>');

    // Increment the row counter
    i++;
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
		document.getElementById("inv_no").disabled = true;
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
  function get_inv_items()
{
	var qid = document.getElementById("inv_no").value;	
	
   	if(qid!='')
   	{
	   	$.ajax({
	   	async:"false",
		type: "POST",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_invoice_info_for_amc",
		data: {qid:qid} ,
		success: function(msg){
			document.getElementById('mytbbody').innerHTML=msg;
			}
		});
		    
	}
}

</script>
