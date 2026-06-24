
<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/update_enquiry_data" id="addform" autocomplete="off"   enctype="multipart/form-data">
		<?php  foreach($records as $row) :?>

		<div class="form-group row">
		
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Enq:Code</label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">						                  
		    			<input type="text" class="form-control form-control-sm" id="amc_enq_code" name="amc_enq_code" value="<?php echo $row->amc_enq_code; ?>" tabindex=1 readonly>
						<input type="hidden" id="amc_enq_id" name="amc_enq_id" value="<?php echo $row->amc_enq_id; ?>" tabindex=1>
				</div>
				<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Customer:</label>
		    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
			     <input type='text' tabindex="5" class="form-control form-control-sm" id="customer_name" name="customer_name" value="<?php echo $row->cust_code." ".$row->cust_name?>" readonly ="true" />
				 <input type='hidden' tabindex="5" class="form-control form-control-sm" id="cust_id" name="cust_id" value="<?php echo $row->cust_id?>" readonly ="true" />
		    </div> 	
    	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Enq:Date </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
				<div class="input-group date datepicker1">			                  
		    		<input type="text" class="form-control form-control-sm datepicker1" id="enq_date" name="enq_date" value="<?php echo date('d-m-Y',strtotime($row->enq_date));?>" required tabindex=1>
				   <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				</div>
    	    </div>
					  
		</div>

		<div class="form-group row">
	  		
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Enq:Type</label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
	    			<select tabindex="4" class="form-select form-control-sm select2 " id="enquiry_type" name="enquiry_type" required onchange="get_div_active();">
					<option value="<?php echo $row->enq_type; ?>"><?php if($row->enq_type =="1"){echo "Company Products";}elseif($row->enq_type =="2"){echo "New Products";}else {echo "Partial Company/Partial New";} ?></option>
					<option value="1">Company Products </option>
					<option value="2">New products </option>
					<option value="3">Partial Company/Partial New</option>
			      </select>
	  		</div>
			  <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Project Name</label>
			<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
					<input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo $row->project_name; ?>">
			</div>

		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Remark/Comments </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-6">
	    			<input type='text' tabindex="9" class="form-control form-control-sm" id="remark" name="remark" placeholder="" value="<?php echo $row->remark; ?>" />	
	  		</div>
		</div>

		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Client Ref No  </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" >			                  
	    			<input type="text" class="form-control form-control-sm" id="client_ref" name="client_ref" value="<?php echo $row->client_ref; ?>" tabindex=8>
    	    </div>
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 col-form-label">Upload Document(PDF/PNG/JPEG) </label>
	  		<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
	    			<input type='file' tabindex="501" class="form-control form-control-sm" id="other_file" name="other_file" placeholder="" />	
	  		</div>
	  		
		</div>
		
		<div class="form-group row">
		<table class="table table-bordered table-hover" id="tab_logic">
			<thead>
			<tr>
					<th>Description</th> 
					<th>Brand</th> 
					<th>Model Name</th>     
					<th>Quantity</th>  
					<th></th>
					
				</th> 
			</tr>
			</thead>
			
			<tbody  id="mytbbody">
			<?php if (!empty($trans_records)){
				$i=0; foreach($trans_records as $s) {?>
				<tr id='addr0'>
					<td>
						<input type="text" name="prod_id[]" id="prod_id<?php echo $i;?>" readonly class="form-control form-control-sm" placeholder="" value="<?php echo $s->product_id;?>">
					</td>
					<!-- <td>
						<select style="width:100%;" tabindex="3" class="form-select form-control-sm select2" id="brand<?php echo $i;?>" name="brand[]" tabindex=1>
							<option value="">Select</option>
							<?php foreach($brand_list as $brand) {
								$selected = "";
								if($s->brand == $brand->brand_id){$selected = "selected";}?>
							<option <?php echo $selected;?> value="<?php echo $brand->brand_id ?>"><?php echo $brand->brand_name;?></option>
							<?php } ?>
						</select>
					</td> -->
					<td><input type="text"  name="brand[]" id="brand<?php echo $i;?>" class="form-control  form-control-sm" value="<?php echo $s->brand;?>"></td> 
					<td><input type="text"  name="model[]" id="model<?php echo $i;?>" class="form-control  form-control-sm" value="<?php echo $s->model;?>"></td> 
					
					<td><input type="number" name="qty[]" id="qty<?php echo $i;?>"  class="form-control form-control-sm" value="<?php echo $s->quantity;?>"></td>
					<td><a title='Delete' class='btn btn-sm bg-orange remove_row'><span class='fa fa-trash'></span></a></td>
				</tr>
				<?php  $i++; }}?>
			</tbody>
			
			</table>
		</div>
		     

		    
		<?php endforeach; ?>
                     

        </div>
		
		<div class="col-sm-8" style="margin-bottom:10px;">
		<button type="submit"  tabindex="31"  id="add" class="btn btn-primary m-b-0">Update Enquiry</button>
			<button type="reset"  id='reset' class="btn btn-primary m-b-0">Reset</button>
			<button class="btn btn-primary m-b-0" onclick="goBack()">Back</button>
		</div>
		
    </div>
</div>
</div>
</div>
<?php
// Generate the <option> elements as a PHP variable
$brandOptions = '<option value="">Select</option>';
foreach ($brand_list as $brand) {
    $brandOptions .= '<option value="' . $brand->brand_id . '" >' . $brand->brand_name . '</option>';
}
?>
</div>

<script>

$(document).ready(function() {
    var i = 1; // Start with 1 because addr0 is already there
	$(document).keypress(function(event) {
		if (event.which == 13) {
		    if ($(event.target).is('input, select')) {
		        event.preventDefault();
		    }
		}
	    });

	$(document).on('keyup', 'input[name^="qty"]', function(event) {
    if (event.which === 13) { // Enter key
        event.preventDefault(); // Prevent form submission

        var currentRow = $(this).closest('tr'); // Get the closest table row
        addNewRow(); // Add new row

        // Optionally, you can focus the first input in the new row
        $('#addr' + i + ' input:first').focus();
    }

	$('.select2').select2();
});
function addNewRow(row) {
	var brandOptions = `<?php echo $brandOptions; ?>`;

    // Construct the HTML content for the new row
    var newRowHtml = `
                <tr id="addr${i}">
                    <td><input type="text" id="prod_id[${i}]" name="prod_id[]" class="form-control form-control-sm"></td>
                    <td><input type="text" id="brand[${i}]" name="brand[]" class="form-control form-control-sm"></td>
                    <td><input type="text" id="model[${i}]" class="form-control form-control-sm" name="model[]"></td>
                    <td><input type="number" id="qty[${i}]" class="form-control form-control-sm" name="qty[]"></td>
                    <td><a title="Delete" class="btn btn-sm bg-orange remove_row"><span class="fa fa-trash"></span></a></td>
                </tr>`;
				
    // Append the new row to the table body
	
    $('#mytbbody').append('<tr id="addr' + i + '">' + newRowHtml + '</tr>');
	$('.select2').select2();
    i++;

}
$(document).on('click', '.remove_row', function() {
        $(this).closest('tr').remove();
    });
});
	
   
  
   
function get_product_info_old(append_id)
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
				document.getElementById("desc_old"+append_id).value=msg.product_description;
		}
	});
	}
}



function confirmcancel(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",
     		type: "POST",
     		data: {table_name:'enquiry_transaction', where_key:'trans_id', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
			}
		        else {
			      	alert("Can't Delete record. Data already used!!!");
		       }
		    },
		});
      		return true;
      	}
        else
        	return false;
	    	
}


</script>
