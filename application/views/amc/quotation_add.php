<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_quotation_data" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Select Enquiry <span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-6 col-lg-3" role='group'>
				<select tabindex="1" class="form-select form-control-sm select2" id="enq_id" name="enq_id" required onchange="get_enquiry_info()" >
				<option value="">Select</option>
				<?php foreach($enq_records as $s) {?>
				  <option value="<?php echo $s->amc_enq_id ?>"><?php echo $s->amc_enq_code.' '.$s->cust_name;?></option>
				<?php } ?>
			      </select>
    	     		 </div>

				<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Project Name</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="text" name="project_name" id="project_name" class="form-control form-control-sm bg-soft-gray"  tabindex='3'>
		     	</div>
    	     		
		</div>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Quotation date</label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3" role='group'>
				<div class="input-group date datepicker1">			                  
		    			<input type="text" class="form-control form-control-sm datepicker1" id="qdate" name="qdate" value="<?php echo date('d-m-Y')?>" required tabindex='2'>
					<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
			      	</div>
     		     </div>

	     	    <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Quotation Code:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="text" name="qcode" id="qcode" class="form-control form-control-sm bg-soft-gray"  tabindex='3' value="<?php echo $code; ?>">
		     </div>
		</div>
		<div class="form-group row" >
	    	<div class="col-md-12">
			<div class="dt-responsive">
			<table class='bg-soft-success' width='85%' cellspacing="0" colspacing="0" border='1' style="font-size:12px;font-weight:bold;">
				<tr>
					<th  style="background-color:#cccccc!important;">Enquiry Code</th>
					<th  style="background-color:#cccccc!important;">Enquiry Date</th>
					<th  style="background-color:#cccccc!important;">Customer</th>
					<!-- <th  style="background-color:#cccccc!important;">AMC Start Date</th>
					<th  style="background-color:#cccccc!important;">AMC End Date</th> -->
				</tr>
				<tr>
					<th><label id='enq_code'></label></th>
					<th><input type="text" id='enq_date' class="form-control text-black" value="" readonly="TRUE"></th>					
					<th id='cust_name'> </th>
					<!-- <th><input type="text" id='amc_start_date' name='amc_start_date' > </th>
				
					<th><input type="text" id='amc_end_date' name='amc_end_date' > </th>  -->

				</tr>
				
				<input type="hidden" id='customer_id' name='customer_id' class="form-control" value="" readonly="TRUE">
				<!-- <input type="hidden" id='amc_start_datea' name='amc_start_datea' > 
				<input type="hidden" id='amc_end_datea' name='amc_end_datea' >  -->
				<input type="hidden" id='enq_type' name='enq_type' > 
				
				<input type="hidden" id='enquiry_code' name='enquiry_code' > 
				<input type="hidden" id='enquiry_revision' name='enquiry_revision' > 
			</table>
			</div>
	   	</div>
		</div>
		
		<div id="item_list_id">
		</div>
		
		<div id='product_div1'>
	
		</div>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-1 col-lg-1 col-form-label">SubTot</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		     	 <input type="text" id='sub_total' name='sub_total' readonly class="form-control form-control-sm" value="0" tabindex=12>
		    </div>
			<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">Dis.%:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
			      <input type="number" name="discount" id="discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value=0 tabindex=7>
		      </div>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
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
		   
		</div>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Select Currency<span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      		<select tabindex="14" class="form-select form-control-sm select2" id="currency_id" name="currency_id" required onchange="get_currency_conversion()" style='width:160px'>
					
					<?php foreach($currency_list as $s) {?>
					  <option value="<?php echo $s->id.'@'.$s->rate.'@'.$s->currabrev; ?>"><?php echo $s->country.' '.$s->currabrev;?></option>
					<?php } ?>
			      </select>
		      		<input type="hidden" id='cid' name='cid' >
		      		<input type="hidden" id='crate' name='crate' value='0'>
		      </div>
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Grand Total <span id='currabrev'></span><span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total' readonly class="form-control form-control-sm"  value="0" required>
		      </div>
		</div>
		<div class="form-group row">
	
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Service Scheme Plan:</label>
		    	<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<select tabindex="1" class="form-select form-control-sm select2" id="service_scheme" name="service_scheme" >
				<option value="">Select</option>
				<?php foreach($service_scheme_records as $s) {?>
				  <option value="<?php echo $s->scheme_name?>"><?php echo $s->scheme_name;?></option>
				<?php } ?>
			      </select>
				
		       </div>
           
		</div>
		<div class="form-group row">
	
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Scope of Work:</label>
		    	<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<select tabindex="1" class="form-select form-control-sm select2" id="scope_work" name="scope_work" >
				<option value="">Select</option>
				<?php foreach($scope_records as $s) {?>
				  <option value="<?php echo $s->category?>"><?php echo $s->category;?></option>
				<?php } ?>
			      </select>
				
		       </div>
           
		</div>
		<hr>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-1 col-form-label">Validity:</label>
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-2">
				<input type="text" class="form-control form-control-sm" list="validity"  name="validity" value="15 days from quotation date"/>
			
			</div>
		
		   <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Payment Term:</label>
		    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-3">
				<select name="term1" class="form-select form-control-sm select2">
					<option value="100 % Advance">100 % Advance</option>
					<option value="Quarterly in advance">>Quarterly in advance </option>
					<option value="50 % advance along with LPO and Balance 50% after 6 months">50 % advance along with LPO and Balance 50% after 6 months</option>
					<option value="End of each Quarter">End of each Quarter</option>
				</select>
		    </div>
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Select Company</label>
			<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
				<select class="form-select form-control-sm select2" id="cmp_id" name="cmp_id">
					<option value="">Select</option>
					<option selected value="1">Dexion</option>
				</select>
			</div>
		</div>
		
		<h6>Contact Person Details</h6>
		<br/>
		<div class="form-group row">
			<label class="col-sm-1 control-label">Name:</label>
		  	<div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input id="cp_name" name="cp_name" tabindex="28" type="text" class="form-control col-sm-2 form-control-sm" />
			</div>
			<label class="col-sm-1 control-label">Mobile:</label>
			 <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input  id="cp_mobile" name="cp_mobile" tabindex="29" type="text" class="form-control col-sm-2 form-control-sm" />
			</div>
			<label class="col-sm-1 control-label">Email:</label>
			 <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			<input id="cp_email" name="cp_email" tabindex="30" type="email" class="form-control col-sm-2 form-control-sm" />
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
$(document).ready(function(){
	var i=1;
	$(document).on('click', '#add_row', function() {
        
    });
        $("#delete_row").click(function(){
    		 if(i>1){
			 $("#addr"+(i-1)).html('');
			 i--;
		 }
	 });

	 var i=1;
	$("#add_rowa").click(function()
	{
	     $('#addra'+i).html("<td><input class='form-control' type='text' name='scope_work[]'></td>");
	    $('#mytbody tr:last').after('<tr id="addr'+(i+1)+'"></tr>');
	      i++; 	     	
	});
        $("#remove_rowa").click(function(){
    		 if(i>1){
			 $("#addra"+(i-1)).html('');
			 i--;
		 }
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
function get_enquiry_info() 
 {
   	var enq_id = document.getElementById("enq_id").value;		
   	if(enq_id!='')
   	{
	   	$.ajax({
	   	async:false,
		type: "POST",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_amc_enquiry_info",
		data: {enq_id:enq_id} ,
		dataType: "json",
		success: function(msg){

			var url1= 'index.php/Sales/edit_enquiry/'+msg.enq_id+'/1';
			var x = '<u><a target="blank" href="<?php echo base_url()?>' + url1 + '">'+msg.enquiry_code+'</a></u>';
			document.getElementById("enq_code").innerHTML		=x;
			document.getElementById("enquiry_code").value		=msg.enquiry_code;		
			document.getElementById("enq_date").value			=msg.enquiry_date;
			document.getElementById("project_name").value		=msg.project_name;
			document.getElementById("customer_id").value		=msg.customer_id;		
			document.getElementById("cust_name").innerHTML		=msg.cust_code+' '+msg.cust_name;
			document.getElementById("enquiry_revision").value	=msg.revision;		
			document.getElementById("enq_type").value			=msg.enq_type;
			// document.getElementById("amc_start_date").value		=msg.amc_start_date;
			// document.getElementById("amc_end_date").value		=msg.amc_end_date;
			// document.getElementById("amc_start_datea").value		=msg.amc_start_date;
			// document.getElementById("amc_end_datea").value		=msg.amc_end_date;
			get_enquiry_items_list();
		     }
		});
	}
	else
	{
		document.getElementById("enq_code").innerHTML='';
		document.getElementById("enq_date").value='';
		document.getElementById("customer_id").value='';
		document.getElementById("cust_name").value='';
		document.getElementById("delivery_date").value='';
			
		document.getElementById('item_list_id').innerHTML='';
	}
 } 
 
function get_enquiry_items_list()
{
	var enq_id=$("#enq_id").val();
	var customer_id=$("#customer_id").val();
	$.ajax({
        type: "POST",
        url:"<?php echo base_url()?>index.php/Ajax/get_amc_enquiry_items_for_quote",
        data: {enq_id:enq_id} ,
        success: function(msg){	      	
		document.getElementById('item_list_id').innerHTML=msg;
		$('.select2').select2();
		calculate_grand_total();
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

</script>
