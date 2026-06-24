<?php $this->load->helper('stock_helper.php');?>

<div class="card-body">

<?php 
foreach($records1 as $row) { ?>	
	<form onsubmit="return check_selected_age();" id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/update_quotation_data" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Quotation date</label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2" role='group'>
				<div class="input-group date ">			                  
		    			<input type="text" class="form-control form-control-sm " id="qdate" name="qdate" value="<?php echo date('d-m-Y',strtotime($row->quotation_date))?>" required tabindex='1' readonly>
			      	</div>
    	     		 </div>

	     	    <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Quotation Code:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="text" name="qcode" id="qcode" class="form-control form-control-sm bg-soft-gray"  readonly tabindex='2' value="<?php echo $row->quotation_code; ?>">
				  <input type="hidden" name="qid" id="qid"  value="<?php echo $row->quote_id; ?>">
		     </div>
	     	    <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Revision:<?php echo $row->revision; ?></label>
		</div>
		<div class="form-group row">
		    	 <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Revision date</label>
    	     		 <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3" role='group'>
				<div class="input-group date datepicker1">			                  
		    			<input type="text" class="form-control form-control-sm datepicker1" id="rev_date" name="rev_date" value="<?php echo date('d-m-Y',strtotime($row->revision_date))?>" required tabindex='2'>
					<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
			      	</div>
     		         </div>
     		         <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Project Name</label>
	  		<div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
			  <input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="<?php echo $row->project_name;?>" required tabindex='2'>
					
			
	  		</div>
	  		
		</div>
		<div class="form-group row" >
	    	<div class="col-md-12">
			<div class="dt-responsive">
			<table class='bg-soft-primary' width='100%' cellspacing="0" colspacing="0" border='1' style="font-size:12px;font-weight:bold;">
			<tr>
					<th  style="background-color:#cccccc!important;">Enquiry Code</th>
					<th  style="background-color:#cccccc!important;">Enquiry Date</th>
					<th  style="background-color:#cccccc!important;">Customer</th>
					
				</tr>
				<tr>
					<th><input type="text" id='enquiry_code' name='enquiry_code' value='<?php echo $row->amc_enq_code;?>'></th>
					<th><input type="text" id='enq_date' class="form-control" value="<?php echo $row->enq_date;?>" readonly="TRUE"></th>					
					<th >
						<input type="text" id='cust_name' name='cust_name' value="<?php echo $row->cust_name;?>" >
						<input type="hidden" id='customer_id' name='customer_id' value="<?php echo $row->customer_id;?>" > 
					</th>
					
				</tr>

				
				
			</table>
			</div>
	   	</div>
		</div>
		
		<div class="form-group row" >
		<table id="tab_logic">
		<thead>
		<tr>
		    
			<th width='30%'>Description</th>    
			<th width='20%'>Brand</th>     
			<!-- <th width='10%'>Capacity</th>      -->
		    <th width='10%'>Qty</th>  
			<th width='10%'>Rate</th>  
		    <th width='20%'>Total</th>   	     
			<th></th>
		</tr>
		</thead>
	        <tbody id="mytbbody">
		<?php $i=0;
		foreach($records2 as $r) { ?>
		<tr id="addr<?php echo $i;?>" >
			<td width='30%'>
				
				<input type="text" name ="product_id[]" id = "product_id<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->product_id; ?>"/>
			</td>	
			<td>
				<input type="text" name ="brand[]" id = "brand<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->brand; ?>"/>
					
			</td>
			<!-- <td width='10%'>	
			    <input type="text" name ="capacity[]" id = "capacity<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->capacity; ?>"/>
			</td> -->
			<td>
				<input type="text" name="qty[]" id="qty<?php echo $i;?>"  class="form-control bg-soft-gray form-control-sm" value="<?php echo $r->quantity;?>" onkeyup="calculate_total('<?php echo $i;?>')">
			</td>
			<td width='10%'>
				<input type="text" step='0.01' name="price[]" id="price<?php echo $i;?>" class="form-control form-control-sm"  onkeyup="calculate_total('<?php echo $i;?>')"  required value="<?php echo $r->price; ?>">
			</td>
			<td width='20%'>
				<input type="text" name="total[]" id="total<?php echo $i;?>" value="<?php echo $r->total;?>" class="form-control bg-soft-gray form-control-sm subItemAmt" readonly required>
				<input type="hidden"  name="trans_id[]" value="<?php echo $r->trans_id;?>" >
				<input type="hidden"  name="append_id[]" value="<?php echo $i;?>" >
		</td>
		<td>
				<a id='delete_row' title="Delete" onclick='remove_row("<?php echo $i;?>")' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a>
			</td>
		</tr>
		
		<?php  $i++; } ?> 
		<tr id='addr1'></tr>
		</tbody>
	</table>
	</div>
	
	<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-1 col-lg-1 col-form-label">SubTot</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		     	 <input type="text" id='sub_total' name='sub_total' readonly class="form-control form-control-sm bg-soft-gray" value="<?php echo $row->sub_total;?>" >
		    </div>
			<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">Dis.%:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
			      <input type="number" name="discount" id="discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()"  value="<?php echo $row->discount_percent;?>">
		      </div>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
			      <input type="number" step="0.01" name="discount_amt" id="discount_amt" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value="<?php echo $row->discount;?>" tabindex=8>
		     </div>
	     	     
		    <label class="col-xs-12 col-sm-2 col-md-2  col-lg-2 col-form-label">Total before VAT</label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
		       <input type="text" id='total_before_vat' name='total_before_vat' readonly class="form-control form-control-sm bg-soft-gray" value="<?php echo $row->sub_total-$row->discount;?>" >
		      </div>
		</div>
		<hr class='bg-primary'></hr>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">VAT <span id='vatperid'><?php echo $row->vat_percent?></span> 
	    <input type='checkbox' id='vatbox' value='1' <?php if($row->vat_percent>0) echo 'checked'; ?>  onclick='check_vat_option()' /></label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
		      <input type="text" id='vat_amt' name='vat_amt' class="form-control form-control-sm bg-soft-gray" readonly value="<?php echo $row->vat_amt;?>" readonly>
	     		  <input type="hidden" id='vat_percent' name='vat_percent'  value="<?php echo $row->vat_percent;?>" >
	     		  <input type="hidden" id='vat_percent1' name='vat_percent1' value="<?php echo $vat_percent;?>" >
		      </div>
		      
		
		    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label"> Currency Rate <span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-1">
		    	<select tabindex="13" class="form-select form-control-sm select2" id="currency_id" name="currency_id" onchange="get_currency_conversion()" style='width:175px'>
				
				<?php foreach($currency_list as $s) {?>
				  <option <?php if($s->id==$row->currency_id) echo 'selected'; ?> value="<?php echo $s->id.'@'.$s->rate.'@'.$s->currabrev; ?>"><?php echo $s->country.' '.$s->currabrev;?></option>
				<?php } ?>
		      </select>
	      		<input type="hidden" id='cid' name='cid' value="<?php echo $row->currency_id;?>">
	      		<input type="hidden" id='crate' name='crate' class="form-control form-control-sm bg-soft-gray" readonly value="<?php echo $row->currency_rate;?>">
		      </div>
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Grand Total <span id='currabrev'></span><span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total' readonly class="form-control form-control-sm bg-soft-gray"  value="<?php echo $row->grand_total;?>" required>
		      </div>
		</div>

		<div class="form-group row">
	
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Service Scheme Plan:</label>
		    	<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<select tabindex="1" class="form-select form-control-sm select2" id="service_scheme" name="service_scheme" >
				<option value="">Select</option>
				<?php foreach($service_scheme_records as $s) {?>
				  <option  <?php if($s->scheme_name==$row->service_scheme) echo 'selected'; ?> value="<?php echo $s->scheme_name?>"><?php echo $s->scheme_name;?></option>
				<?php } ?>
			      </select>
				
		       </div>
           
		</div>
		<hr>
		<div class="form-group row">
		<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Select Scope of Work:</label>
		<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
		<select tabindex="1" class="form-select form-control-sm select2" id="scope_work" name="scope_work" >
		<option value="">Select</option>
		<?php foreach($scope_records as $s) {?>
			<option <?php if($s->category==$row->scope_work) echo 'selected'; ?> value="<?php echo $s->category?>"><?php echo $s->category;?></option>
		<?php } ?>
			</select>
		
		</div>

           
		</div>
		
		<hr>
		<div class="form-group row">
		     	<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Validity:</label>
		    	<div class="col-xs-12 col-sm-9 col-md-8 col-lg-3">
			       <input type="text" name="validity" id="validity" class="form-control form-control-sm"    value='<?php echo $row->validity;?>' >
		       </div>
		
		   <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Payment Term:</label>
		    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-3">
			<select name="term1" class="form-select form-control-sm select2">
			<?php foreach($payment_terms as $r) {?>
				<option value="<?php echo $r->pterm_name; ?>">
					<?php echo $r->pterm_name; ?>
				</option>
				<?php } ?>
			</select>
	   		 </div>
		</div>
		<div class="form-group row">
		<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Select Company</label>
			<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
				<select class="form-select form-control-sm select2" id="cmp_id" name="cmp_id">
					<option value="">Select</option>
					<option selected value="1">Dexion</option>
				</select>
			</div>
		</div>
	
		
		<h6>Contact Person Details</h6>
		<div class="form-group row">
			<label class="col-sm-1 control-label">CP Name:</label>
		  	<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input id="cp_name" name="cp_name" tabindex="28" type="text" class="form-control col-sm-2 form-control-sm" value="<?php echo $row->cp_name;?>"/>
			</div>
			<label class="col-sm-1 control-label">Mobile:</label>
			 <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input  id="cp_mobile" name="cp_mobile" tabindex="29" type="text" class="form-control col-sm-2 form-control-sm" value="<?php echo $row->cp_mobile;?>"/>
			</div>
			<label class="col-sm-1 control-label">CP Email:</label>
			 <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input id="cp_email" name="cp_email" tabindex="30" type="email" class="form-control col-sm-2 form-control-sm" value="<?php echo $row->cp_email;?>"/>
			</div>
		</div>	
		
		   <?php if($row->status==1) echo "<b>This Quotation is cancelled, cant Edit now.</b>"; else {?>
		<div class="form-group row">
		<label class="col-sm-1"></label>
		<input type="hidden"  name="enq_id" value="<?php echo $row->enq_master_id;?>" >
		<input type="hidden"  name="quote_id" value="<?php echo $row->quote_id;?>" >
		<!--<input type="hidden"  name="customer_id" value="<?php //echo $row->customer_id;?>" >-->
		<input type="hidden"  name="revision" value="<?php echo $row->revision;?>" >
		<!-- <label class="col-xs-12 col-sm-2 col-md-3 col-lg-3 col-form-label">
			<input type='checkbox' name="create_revision" id="create_revision" value='1' />Create New Revision
		</label> -->
		<?php if($edit_flag==1){?>
		<div class="col-sm-8">
		<button type="submit"  tabindex="31"  id="add" class="btn btn-primary m-b-0">Update Quotation</button>
			<button type="reset"  id='reset' class="btn btn-primary m-b-0">Reset</button>
			<button class="btn btn-primary m-b-0" onclick="goBack()">Back</button>
		</div>
		<?php } ?>
		</div>
		<?php } ?>
		</form>

<?php } ?>
        </div>
    </div>
</div>
</div>
</div>
</div>


<script>
	

	$(document).ready(function(){
		var i=1;
		$("#add_row").click(function()
		{
			// Build product options as a JavaScript array using PHP
var productlist = `
    <?php foreach ($products as $s) { ?>
        <option value='<?php echo $s->product_id; ?>'>
            <?php echo htmlspecialchars($s->product_description, ENT_QUOTES, 'UTF-8'); ?>
        </option>
    <?php } ?>
		`;

		$('#addr' + i).html(`
			<td>
				<select tabindex='11' class='form-select form-control-sm select2' 
					id='product_id${i}' 
					name='new_product_id[]' 
					onchange='get_trading_product_info(${i})' 
					style='width:350px;'>
					<option value=''>Select</option>
					${productlist}
				</select>
			</td>
			<td>
				<span>Qty:</span>
				<input type='number' name='new_qty[]' id='qty${i}' tabindex='14' 
					class='form-control form-control-sm' onchange='calculate_total(${i})' ><br>
				
				<span>Price:</span>
				<input type='number' name='new_price[]' id='price${i}' tabindex='14' 
					class='form-control form-control-sm' onchange='calculate_total(${i})' readonly>
			</td>
			<td width='20%'>
				<input type='number' step='0.01' name='dis_per[]' id='dis_per${i}'  
					class='form-control bg-soft-gray form-control-sm' placeholder='0%' 
					onchange='calculate_discount(event, "${i}")'><br>
				
				<input type='number' step='0.01' name='dis_val[]' id='dis_val${i}' 
					class='form-control form-control-sm' 
					onchange='calculate_discount(event, "${i}")' value='0' tabindex='9'><br>
			</td>
			<td>
				<input type='number' step='any' name='new_total[]' id='total${i}' 
					tabindex='14' class='form-control form-control-sm subItemAmt'>
			</td>
			<td>
				<a id='delete_row' title='Delete' onclick='remove_row(${i})' 
					class='btn btn-xs bg-orange remove1'>
					<span class='fa fa-trash'></span>
				</a>
			</td>
		`);
		$('#mytbbody tr:last').after('<tr id="addr'+(i+1)+'"></tr>');
			i++; 	     	
			$('.select2').select2({ width: "300px" });
		});

		$("#delete_row").click(function(){
    		 if(i>1){
				$("#addr"+(i-1)).html('');
				i--;
			}
	 	});
        // Project  
		var j=1;
		$("#add_row1").click(function()
		{
			$('#product_div'+j).html("<table class='table table-bordered table-hover' id='pdetails"+j+"'><thead><tr style='background-color:#94C973!important; font-weight:bold'><th>"+(j+1)+"</th><th><input type='text' name='desc[]' id='main_heading' class='form-control form-control-sm' placeholder='Add Main Heading' ></th><th><input type='text' name='qty"+j+"' id='qty"+j+"' class='form-control form-control-sm' placeholder='Quantity' value='1' onchange='calculate_total("+i+")' '></th><th><input type='number' step=any name='price"+j+"' id='price"+j+"' class='form-control form-control-sm' onchange='calculate_total("+i+")' ></th><th><input type='number' step=any name='total"+j+"' id='total"+j+"' class='form-control form-control-sm subItemAmt' readonly></th><th width='10%'><input type='hidden' id='row_id_d"+j+"' value='0'/><input type='hidden' name='product_div_value[]'  value='"+j+"'/><a id='add_sub_row"+j+"' onclick=add_nxt_row('d"+j+"',0) title='Add' class='btn btn-sm bg-orange' ><span class='fa fa-plus'></span></a><a id='delete_row' title='Delete' onclick='remove_product_div("+j+")' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></th></tr></thead><tr><td colspan='4'><table id='tsub_details"+j+"' width='100%' style='background-color:#cccccc!important;'><tr><td>Sub Heading</td><td>Action</td></tr><tbody id='mybody_d"+j+"'><tr id='d0_ptr0' ><td><textarea name='sub_details"+j+"[]' id='sub_detailsd"+j+"0' class='form-control form-control-sm'></textarea><input type='hidden' name='qty"+j+"[]' id='qtyd00' tabindex='10' class='form-control form-control-sm' value='1'></td><td><a title='Delete' onclick=remove_subrow('d"+j+"',0) class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td></tr><tr id='d"+j+"_ptr1'></tr></tbody></table></td></tr></table>");
			$('#product_div'+j).after("<div id='product_div"+(j+1)+"'></div>");
			j++; 
			$('.select2').select2({ width: "350px" });
		});
	 
   });  

   function remove_row(append_id)
   {    	 
        $('#addr'+append_id).attr("id","addr"+append_id+"x");
        $('#addr'+append_id+"x").remove();
        calculate_grand_total();
   }
   function remove_product_div(append_id)
   {    	 
        $('#product_div'+append_id).remove();
        calculate_grand_total();
   }
   function remove_subrow(div_id,append_id)
   {    	
   	var x= div_id+'_ptr'+append_id;
        $('#'+x).remove();
        calculate_grand_total();
   }
  function add_nxt_row(div_id, append_id)
  {
  	const myArray = div_id.split("d");
  	var one= myArray[0];
  	var two= myArray[1];
	var pcode= parseFloat($('#row_id_'+div_id).val());
  	var k = parseFloat(pcode);
  	var m = parseFloat(k+1);
  	var tmp =div_id+'_ptr'+k;
  	var tmp2 ='mybody_'+div_id;
  	var tmp3 =div_id+'_ptr'+m;
  
  	 $('#'+tmp).html("<td><textarea name='sub_details"+two+"[]' id='sub_details"+div_id+k+"' class='form-control form-control-sm' ></textarea><input type='hidden' name='qty"+two+"[]' id='qty"+div_id+k+"' tabindex='10' class='form-control form-control-sm' value='1'></td><td  align='center'><a title='Delete' onclick=remove_subrow('"+div_id+"','"+k+"') class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	  $('#'+tmp).after("<tr id='"+tmp3+"'></tr>");
	  $('#row_id_'+div_id).val(m);
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
				document.getElementById("price"+append_id).value=msg.price;
		}
	});
	}
	else
	{
		document.getElementById("desc"+append_id).value='';
	}
}
function get_enquiry_info() 
 {
   	var enq_id = document.getElementById("enq_id").value;	
   	if(enq_id!='')
   	{
	   	$.ajax({
	   	async:"false",
		type: "POST",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_enquiry_info",
		data: {enq_id:enq_id} ,
		dataType: "json",
		success: function(msg){
			var url1= 'index.php/Sales/edit_enquiry/'+msg.enq_id+'/1';
			var x = '<u><a target="blank" href="<?php echo base_url()?>' + url1 + '">'+msg.enquiry_code+'</a></u>';
			document.getElementById("enq_code").innerHTML=x;
			document.getElementById("enquiry_code").value=msg.enquiry_code;
			document.getElementById("enq_date").value=msg.enquiry_date;
			//document.getElementById("customer_id").value=msg.customer_id;
			document.getElementById("cust_name").innerHTML=msg.cust_code+' '+msg.cust_name;
			document.getElementById("delivery_date").value=msg.delivery_date;
			
			get_enquiry_items_list();
		     }
		});
	}
	else
	{
		document.getElementById("enq_code").innerHTML='';
		document.getElementById("enq_date").value='';
		//document.getElementById("customer_id").value='';
		document.getElementById("cust_name").value='';
		document.getElementById("delivery_date").value='';
			
		document.getElementById('item_list_id').innerHTML='';
	}
 } 
 
function get_enquiry_items_list()
{
	var enq_id=$("#enq_id").val();
	var rev_version=1;	
	$.ajax({
        type: "POST",
        url:"<?php echo base_url()?>index.php/Ajax/get_enquiry_items_for_quote",
        data: {enq_id:enq_id, rev_version:1} ,
        success: function(msg){	       	
		document.getElementById('item_list_id').innerHTML=msg;
	     }
	});
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
	 	var discount= i_total*discount_per;
	 	document.getElementById("discount_amt").value= parseFloat(discount).toFixed(2);
	 }
	 var discount= document.getElementById("discount_amt").value;
	 var total_before_vat = i_total-discount;
	
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
	var vat_percent=document.getElementById("vat_percent1").value;
	// If the checkbox is checked, display the output text
	if (checkBox.checked == true){
		$("#vat_percent").val(vat_percent);	
		document.getElementById('vatperid').innerHTML=vat_percent+' %';
		
		calculate_grand_total();
		//var total_before_vat = document.getElementById("total_before_vat").value;
		//var subtot=parseFloat(total_before_vat*(vat_percent/100)).toFixed(2);
		//var x= parseFloat(total_before_vat)+parseFloat(subtot);
	 	//document.getElementById("vat_amt").value=subtot;
	 	//document.getElementById("grand_total").value=parseFloat(x).toFixed(2);
	 	
	} else {
	 
		$("#vat_percent").val(0);
		document.getElementById('vatperid').innerHTML=0.00+' %';
	 	document.getElementById("vat_amt").value=0.00;	
		calculate_grand_total();
		//var total_before_vat = document.getElementById("total_before_vat").value;
	 	//document.getElementById("grand_total").value=total_before_vat;
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
function get_product_description(append_id)
{
	var pcode= $('#order_code'+append_id).val();
	var newStr = pcode.replaceAll(',','');
	//$('#pcode'+append_id).val(newStr);
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


function get_product_info(append_id)
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
				document.getElementById("order_code"+append_id).value=msg.pcode;
				document.getElementById("pcode"+append_id).value=msg.pcode;
				document.getElementById("desc"+append_id).value=msg.product_description;
				document.getElementById("size"+append_id).value=msg.size;
				get_stock(append_id);
		}
	});
	}
	else
	{
		document.getElementById("order_code"+append_id).value='';
		document.getElementById("pcode"+append_id).value='';
		document.getElementById("desc"+append_id).value='';
		document.getElementById("size"+append_id).value='';
	}
}

function get_stock(append_id)
{	
	var order_code =document.getElementById("order_code"+append_id).value;
	var size =document.getElementById("size"+append_id).value;
	var warehouse='1'
   	if(size!='')
   	{
	   	$.ajax({
	   	async:"false",
		type: "POST",
		dataType: "json",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_current_stock",
		data: {order_code:order_code, size:size, warehouse:warehouse} ,
		success: function(msg){
			document.getElementById("stock"+append_id).value=msg.stock;
			document.getElementById("postock"+append_id).value=msg.po_stock;
			document.getElementById("avgprice"+append_id).value=msg.avg_price;
		     }
		});
	}
}

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
		 document.getElementById("dis_val"+append_id).value = ((dis_per/100)*total);
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

</script>



