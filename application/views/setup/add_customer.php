<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />
			
				<form id="customerForm" action="<?php echo base_url().'index.php/'; ?>Setup/save_customer" method="post" autocomplete='off' enctype="multipart/form-data">
			
				<!-- Row 1: Customer Code + Branch -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Customer Code <span class="text-danger">*</span></label>
							<?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="customer_code" value="<?= $customer_code ?>" class="form-control" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Customer Name <span class="text-danger">*</span></label>
							<?= form_error('customer_name', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="customer_name" class="form-control">
						</div>
					</div>
				
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Location <span class="text-danger">*</span></label>
							<?= form_error('emirate', '<small class="text-danger">', '</small>') ?>
							<select name="emirate" class="form-control">
								<option value="">Select</option>
								<option value="Abu Dhabi">Abu Dhabi</option>
								<option value="Dubai">Dubai</option>
								<option value="Sharjah">Sharjah</option>
								<option value="Ajman">Ajman</option>
								<option value="Umm Al Quwain">Umm Al Quwain</option>
								<option value="Ras Al Khaimah">Ras Al Khaimah</option>
								<option value="Fujairah">Fujairah</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Address <span class="text-danger">*</span></label>
							<?= form_error('customer_address', '<small class="text-danger">', '</small>') ?>
							<textarea name="customer_address" class="form-control"></textarea>
						</div>
					</div>
					
				
				</div>	
				<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label>Office Telephone</label>
            <input type="text" name="office_telephone" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Office Fax</label>
            <input type="text" name="office_fax" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
			<div class="form-group">
							<label>Email </label>
							
							<input type="email" name="customer_email" class="form-control">
						</div>
           
    </div>

</div>
<div class="row">
	<div class="col-md-4">
	 <div class="form-group">
         <label>Reference Code</label>
            <input type="text" name="reference_code" class="form-control">
			</div>
</div>



    <div class="col-md-4">
        <div class="form-group">
            <label>Customer Group</label>
            <select name="customer_group_id" class="form-control">
                <option value="">Select</option>
                <?php foreach($customer_groups as $g){ ?>
                    <option value="<?= $g->customer_group_id ?>">
                        <?= $g->customer_group_name ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Agent Code</label>
            <input type="text" name="agent_code" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
       
    </div>

</div>
<div class="row">
    <div class="col-md-4">

 <div class="form-group">
            <label>Sales Rep Code</label>
            <select name="sales_rep_id" class="form-control">
                <option value="">Select</option>
                <?php foreach($sales_rep_list as $r){ ?>
                    <option value="<?= $r->sales_rep_id ?>">
                        <?= $r->sales_rep_name ?>
                    </option>
                <?php } ?>
            </select>
        </div>
		 </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Sales Area</label>
            <select name="sales_area_id" class="form-control">
                <option value="">Select</option>
                <?php foreach($sales_area_list as $s){ ?>
                    <option value="<?= $s->sales_area_id ?>">
                        <?= $s->sales_area_name ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Continent</label>
            <select name="continent" class="form-control">
                <option value="">Select</option>
                <option>Africa</option>
                <option>Asia</option>
                <option>Europe</option>
                <option>North America</option>
                <option>South America</option>
                <option>Australia</option>
            </select>
        </div>
    </div>


</div>
<div class="row">

   <div class="col-md-6">
    <div class="form-group">
        <label>Payment Terms</label>
        <textarea name="payment_terms" class="form-control" rows="3"></textarea>
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Credit Limit</label>
            <input type="number" name="credit_limit" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Credit Days</label>
            <input type="number" name="credit_days" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Max Discount %</label>
            <input type="number" step="0.01" name="max_discount_percent" class="form-control">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <h4><b>Tax Information</b></h4>
        <hr>
    </div>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label>Tax Registration No</label>
            <input type="text" name="tax_registration_no" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Country</label>
            <input type="text" name="tax_country" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Emirate <small>(Applicable for UAE)</small></label>
            <select name="tax_emirate" class="form-control">
                <option value="">Select Emirate</option>
                <option value="Abu Dhabi">Abu Dhabi</option>
                <option value="Dubai">Dubai</option>
                <option value="Sharjah">Sharjah</option>
                <option value="Ajman">Ajman</option>
                <option value="Umm Al Quwain">Umm Al Quwain</option>
                <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                <option value="Fujairah">Fujairah</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Tax Code</label>
            <input type="text" name="tax_code" class="form-control">
        </div>
    </div>

</div>
				
				
				<!-- Row 7: Contact Person Table -->
				<div class="form-group">
					<label class="col-form-label">Contacts Person</label>
					<div class="col-md-12">
						<table class="table">
							<tr id="addr0">
								<td><input type="text" name="contact_name[]" placeholder="Enter Name" class="form-control"></td>
								<td><input type="text" name="contact_phone[]" placeholder="Enter Phone" class="form-control"></td>
								<td><input type="email" name="contact_email[]" placeholder="Enter Email" class="form-control"></td>
								<td><a href="javascript:add_row()" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span></a></td>
							</tr>
						</table>
						<input type="hidden" id="num_rows" name="num_rows" value="0">
					</div>
				</div>

				<!-- Submit Buttons -->
				<div class="ln_solid"></div>
				<div class="form-group text-center">
					<button type="reset" class="btn btn-primary">Reset</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</div>

			</form>

			</div>
		</div>
	</div>
</div>

<script>
    function add_row() {
    var num_rows = parseInt($('#num_rows').val());
    var i = num_rows + 1;

    var new_row = `
        <tr id="addr${i}">
            <td><input class="form-control" id="contact_name${i}" name="contact_name[]" type="text" placeholder="Enter Name"></td>
            <td><input class="form-control" id="contact_number${i}" name="contact_number[]" type="text" placeholder="Enter Contact Number"></td>
            <td><input class="form-control" id="contact_email${i}" name="contact_email[]" type="text" placeholder="Enter Email ID"></td>
            <td><a onclick="delete_row(${i})" title="Delete" class="btn btn-sm bg-blue"><span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
    `;

    $("#addr" + num_rows).after(new_row);
    $('#num_rows').val(i);
}

function delete_row(id) {
    // alert("hei"); // debug
    $('#addr' + id).remove();
}
</script>
