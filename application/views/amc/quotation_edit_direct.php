<?php $this->load->helper('stock_helper.php');?>

<div class="card-body">

<?php 
foreach($records1 as $row) { ?>	
	<form onsubmit="return check_selected_age();" id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/update_quot_direct" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Quotation date</label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2" role='group'>
				<div class="input-group date ">			                  
		    			<input type="text" class="form-control form-control-sm " id="qdate" name="qdate" value="<?php echo date('d-m-Y',strtotime($row->quotation_date))?>" required tabindex='1' readonly>
			      	</div>
    	     		 </div>

	     	    <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Quotation Code:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-1 col-lg-2">
			      <input type="text" name="qcode" id="qcode" class="form-control form-control-sm bg-soft-gray"  readonly tabindex='2' value="<?php echo $row->quotation_code; ?>">
				  <input type="hidden" name="qid" id="qid"  value="<?php echo $row->quote_id; ?>">
		     </div>
	     	    <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Revision:<?php echo $row->revision; ?></label>
		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Revision date</label>
				<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2" role='group'>
					<div class="input-group date datepicker1">			                  
						<input type="text" class="form-control form-control-sm datepicker1" id="rev_date" name="rev_date" value="<?php echo date('d-m-Y',strtotime($row->revision_date))?>" required tabindex='2'>
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Project Name</label>
				<div class="col-xs-12 col-sm-9 col-md-4 col-lg-2">
					<input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="<?php echo $row->project_name;?>" required tabindex='2'>
				</div>
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Quotation Print<span style="color: red;"> * </span></label>
				<div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
					<select tabindex="4" class="form-select form-control-sm select2 " id="quot_print_type" name="quot_print_type" tabindex="9" required>
						<option <?php if($row->quot_print_type == "1") echo 'selected';?> value="1">Non-comprehensive</option>
						<option <?php if($row->quot_print_type == "2") echo 'selected';?> value="2">Comprehensive</option>
						</select>
				</div>
		</div>
		
		<div class="form-group row">

		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Project Location</label>
				<div class="col-xs-12 col-sm-9 col-md-4 col-lg-2">
					<input type="text" class="form-control form-control-sm" id="project_location" name="project_location" value="<?php echo $row->project_location;?>" tabindex='2'>
				</div>


		 <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Subject</label>
    <div class="col-xs-12 col-sm-9 col-md-4 col-lg-2">
        <textarea id="subject"
                  name="subject"
                  class="form-control form-control-sm"
                  rows="4"
                  tabindex="2"><?php echo $row->subject; ?></textarea>
    </div>
				</div>


				<div class="form-group row align-items-center">

    <label class="col-sm-2 col-form-label">Contract Type</label>

    <div class="col-sm-2">
       <select name="contract_type" id="contract_type">
    <option value="">Select</option>
    <option value="Yearly" <?= ($row->contract_type=='Yearly')?'selected':'' ?>>Yearly</option>
    <option value="Quarterly" <?= ($row->contract_type=='Quarterly')?'selected':'' ?>>Quarterly</option>
</select>
    </div>

    <div class="col-sm-3" id="year_block" style="display:none;">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">No. of Years</label>
            <input type="number" id="no_of_years"
       value="<?= $row->no_of_years ?>">

        </div>
    </div>

    <div class="col-sm-3" id="quarter_block" style="display:none;">
        <div class="d-flex align-items-center">
            <label class="mr-2 mb-0">No. of Quarters</label>
            <input type="number" id="no_of_quarters"
       value="<?= $row->no_of_quarters ?>">
        </div>
    </div>
	 </div>


		<div class="form-group row" >
	    	<div class="col-md-12">
			<div class="dt-responsive">
			<table class='bg-soft-primary' width='100%' cellspacing="0" cellpadding="0" border='1' style="font-size:12px;font-weight:bold;">

    <tr>
        <th style="background-color:#cccccc!important;">Branch</th>
        <th style="background-color:#cccccc!important;">Customer</th>
    </tr>

    <tr>
       <th>
    <input type="text" 
           value="<?php echo $row->branch_name; ?>" 
           readonly>

    <input type="hidden" 
           name="branch" 
           value="<?php echo $row->branch_id; ?>">
</th>

        <th>
            <input type="text" 
                   id="cust_name" 
                   name="cust_name" 
                   value="<?php echo $row->customer_name; ?>">

            <input type="hidden" 
                   id="customer_id" 
                   name="customer_id" 
                   value="<?php echo $row->customer_id; ?>">
        </th>
    </tr>

</table>
			</div>
	   	</div>
		</div>
		
		<div class="form-group row" >
		<table class="table table-bordered table-hover" id="tab_logic">
		<thead>
<tr>
    <th>System</th>
    <th>Quantity</th>
    <th>Price</th>

    <?php
    $count = 0;

    if ($row->contract_type == 'Yearly') {
        $count = (int)$row->no_of_years;
    } elseif ($row->contract_type == 'Quarterly') {
        $count = (int)$row->no_of_quarters;
    }

    for ($p = 1; $p <= $count; $p++) {
        echo "<th>Total Price $p Year</th>";
    }
    ?>

    <th>Total</th>
    <th>Action</th>
</tr>
</thead>
	        <tbody id="mytbbody">
		<?php $i=0;
		foreach($records2 as $r) { ?>
		<tr id="addr<?php echo $i;?>" >
			<td width='30%'>
				
				<input type="text" name ="product_id[]" id = "product_id<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->product_id; ?>"/>
			</td>	
			<!-- <td>
				<input type="text" name ="brand[]" id = "brand<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->brand; ?>"/>
					
			</td> -->
			<!-- <td width='10%'>	
			    <input type="text" name ="model[]" id = "model<?php echo $i;?>" class="form-control form-control-sm" value ="<?php echo $r->model; ?>"/>
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
			<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">AMC Dis:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" name="amc_discount" id="amc_discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value="<?php echo $row->amc_discount;?>" tabindex=7>
		      </div>
			<label class="col-xs-12 col-sm-1 col-md-1 col-lg-1 col-form-label">Dis.%:</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" name="discount" id="discount" class="form-control form-control-sm"  onkeyup="calculate_grand_total()"  value="<?php echo $row->discount_percent;?>">
		      </div>
	    	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-1">
			      <input type="number" step="0.01" name="discount_amt" id="discount_amt" class="form-control form-control-sm"  onkeyup="calculate_grand_total()" value="<?php echo $row->discount;?>" tabindex=8>
		     </div>
	     	     
		    <label class="col-xs-12 col-sm-2 col-md-2  col-lg-2 col-form-label">Total before VAT</label>
		    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
		       <input type="text" id='total_before_vat' name='total_before_vat' readonly class="form-control form-control-sm bg-soft-gray"        value="<?php echo number_format(($row->sub_total - $row->amc_discount - $row->discount), 2, '.', ''); ?>">
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
		      
		
		    <!-- <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label"> Currency Rate <span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-1">
		    	<select tabindex="13" class="form-select form-control-sm select2" id="currency_id" name="currency_id" onchange="get_currency_conversion()" style='width:175px'>
				
				<?php foreach($currency_list as $s) {?>
				  <option <?php if($s->id==$row->currency_id) echo 'selected'; ?> value="<?php echo $s->id.'@'.$s->rate.'@'.$s->currabrev; ?>"><?php echo $s->country.' '.$s->currabrev;?></option>
				<?php } ?>
		      </select>
	      		<input type="hidden" id='cid' name='cid' value="<?php echo $row->currency_id;?>">
	      		<input type="hidden" id='crate' name='crate' class="form-control form-control-sm bg-soft-gray" readonly value="<?php echo $row->currency_rate;?>">
		      </div> -->
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Grand Total <span id='currabrev'></span><span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total' readonly class="form-control form-control-sm bg-soft-gray"  value="<?php echo $row->grand_total;?>" required>
		      </div>
		</div>

		<!-- <div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Terms and Exclusion:</label> 
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
			<textarea class="form-control" id="exclusion_terms" name="exclusion_terms" rows="4" placeholder=""><?php echo $row->exclusion_terms;?></textarea>   
				</div>       
		</div> -->
		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Scope of Work:</label>
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<textarea class="form-control" id="scope_work" name="scope_work" rows="4" placeholder=""><?php echo $row->scope_work;?></textarea>          
			</div>
		</div>
        <div class="form-group row">   
		<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Payment Terms:</label>
		    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<textarea class="form-control" id="term1" name="term1" rows="4" placeholder=""><?php echo $row->payment_term;?></textarea> 
				
		    </div>
		</div>
		<div class="form-group row">   
		<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">No. of Visits</label>
		    <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
			<textarea class="form-control" id="ppm_details" name="ppm_details" rows="4" placeholder=""><?php echo $row->ppm_details;?></textarea>
		    </div>
		</div>
		<hr>
		<div class="form-group row">
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Validity:</label>
			<div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
				<input type="text" class="form-control form-control-sm" list="validity"  name="validity" value="<?php echo $row->validity;?>"/>
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
		
		<h6>Contact Person Details</h6>
		<div class="form-group row">
    <label class="col-md-1 control-label">Name</label>
    <div class="col-md-3">
			<input id="cp_name" name="cp_name" tabindex="28" type="text" class="form-control  form-control-sm" value="<?php echo $row->cp_name;?>"/>
			</div>
    <label class="col-md-1 control-label">Mobile</label>
    <div class="col-md-3">
			<input  id="cp_mobile" name="cp_mobile" tabindex="29" type="text" class="form-control  form-control-sm" value="<?php echo $row->cp_mobile;?>"/>
			</div>
    <label class="col-md-1 control-label">Email</label>
    <div class="col-md-3">
			<input id="cp_email" name="cp_email" tabindex="30" type="email" class="form-control  form-control-sm" value="<?php echo $row->cp_email;?>"/>
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
<button type="button" class="btn btn-primary m-b-0" onclick="window.history.back();">
    Back
</button>		</div>
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
let edit_mode = true;

let contractType = "<?= $row->contract_type ?>";
let yearCount = <?= (int)$row->no_of_years ?>;
let quarterCount = <?= (int)$row->no_of_quarters ?>;

// saved items from PHP
let saved_items = <?= json_encode($records2 ?? []) ?>;
</script>
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

function goBack() {
    window.location.href = "<?= base_url('index.php/AMC/quotation_direct_list') ?>";
}

function toggleContractCount()
{
    let type = $('#contract_type').val();

    if(type === 'Yearly')
    {
        $('#year_block').show();
        $('#quarter_block').hide();
        $('#no_of_quarters').val('');
    }
    else if(type === 'Quarterly')
    {
        $('#quarter_block').show();
        $('#year_block').hide();
        $('#no_of_years').val('');
    }
    else
    {
        $('#year_block, #quarter_block').hide();
    }
}
function loadExistingRows()
{
    $('#mytbbody').empty();

    $.each(saved_items, function(i, item){

        let qty = parseFloat(item.quantity ?? item.qty ?? 0);
        let price = parseFloat(item.price ?? 0);

        let periodTotal = qty * price;

        let count = (contractType === 'Yearly')
            ? yearCount
            : quarterCount;

        let grandPeriodTotal = periodTotal * count;

        let row = `<tr id="addr${i}">`;

        row += `
            <td>
                <input type="text"
                    name="product_id[]"
                    value="${item.product_id ?? ''}"
                    class="form-control form-control-sm">
            </td>

            <td>
                <input type="number"
                    name="qty[]"
                    id="qty${i}"
                    value="${qty}"
                    class="form-control form-control-sm"
                    onchange="calculate_total(${i})">
            </td>

            <td>
                <input type="number"
                    name="price[]"
                    id="price${i}"
                    value="${price}"
                    class="form-control form-control-sm"
                    onchange="calculate_total(${i})">
            </td>
        `;

        for(let p = 1; p <= count; p++)
        {
            row += `
                <td>
                    <input type="text"
                        name="period_${p}[]"
                        id="period_${p}_${i}"
                        value="${periodTotal.toFixed(2)}"
                        class="form-control form-control-sm"
                        readonly>
                </td>
            `;
        }

        row += `
            <td>
                <input type="text"
                    name="total[]"
                    id="total${i}"
                    value="${grandPeriodTotal.toFixed(2)}"
                    class="form-control form-control-sm subItemAmt"
                    readonly>
            </td>

            <td>
                <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="remove_row(${i})">X</button>
            </td>
        </tr>
        `;

        $('#mytbbody').append(row);
    });

    calculate_grand_total();
}
$(document).ready(function () {

    toggleContractCount();   // MUST FIRST

    // ensure values are ready before building table
    setTimeout(function () {

        if(edit_mode)
        {
            loadExistingRows();
        }

    }, 100);

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



