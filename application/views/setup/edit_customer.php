<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />
            <form action="<?= base_url('index.php/Setup/update_customer') ?>" method="post" enctype="multipart/form-data" autocomplete="off" enctype="multipart/form-data">
<input type="hidden"
       name="customer_id"
       value="<?= $customer->customer_id ?>">
                <div class="row">

                    <!-- Customer Code -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Code <span class="text-danger">*</span></label>
                            <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
<input type="text"
       name="customer_code"
       value="<?= $customer->customer_code ?>"
       class="form-control"
       readonly>                        </div>
                    </div>
               
                    <!-- Customer Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <?= form_error('customer_name', '<small class="text-danger">', '</small>') ?>
<input type="text"
       name="customer_name"
       value="<?= $customer->customer_name ?>"
       class="form-control">                        </div>
                    </div>
                    </div>

                    <div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Location <span class="text-danger">*</span></label>
							<?= form_error('emirate', '<small class="text-danger">', '</small>') ?>
							<select name="location" class="form-control">
    <option value="">Select</option>

    <option value="Abu Dhabi"
        <?= ($customer->location=='Abu Dhabi')?'selected':'' ?>>
        Abu Dhabi
    </option>

    <option value="Dubai"
        <?= ($customer->location=='Dubai')?'selected':'' ?>>
        Dubai
    </option>

    <option value="Sharjah"
        <?= ($customer->location=='Sharjah')?'selected':'' ?>>
        Sharjah
    </option>

    <option value="Ajman"
        <?= ($customer->location=='Ajman')?'selected':'' ?>>
        Ajman
    </option>

</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Address <span class="text-danger">*</span></label>
							<?= form_error('customer_address', '<small class="text-danger">', '</small>') ?>
<textarea name="customer_address"
          class="form-control"><?= $customer->customer_address ?></textarea>						</div>
					</div>
					
				
				</div>	

<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label>Office Telephone</label>
<input type="text"
       name="office_telephone"
       value="<?= $customer->office_telephone ?>"
       class="form-control">
            </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Office Fax</label>
<input type="text"
       name="office_fax"
       value="<?= $customer->office_fax ?>"
       class="form-control">        </div>
    </div>

    <div class="col-md-4">
			<div class="form-group">
							<label>Email </label>
							
<input type="email"
       name="customer_email"
       value="<?= $customer->customer_email ?>"
       class="form-control">						</div>
           
    </div>

</div>
<div class="row">
	<div class="col-md-4">
	 <div class="form-group">
         <label>Reference Code</label>
<input type="text"
       name="reference_code"
       value="<?= $customer->reference_code ?>"
       class="form-control">			</div>
</div>



    <div class="col-md-4">
        <div class="form-group">
            <label>Customer Group</label>
            <select name="customer_group_id" class="form-control">

<?php foreach($customer_groups as $g){ ?>

<option value="<?= $g->customer_group_id ?>"
<?= ($customer->customer_group_id==$g->customer_group_id)?'selected':'' ?>>

<?= $g->customer_group_name ?>

</option>

<?php } ?>

</select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Agent Code</label>
            <input type="text"
       name="agent_code"
       value="<?= $customer->agent_code ?>"
       class="form-control">
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

<?php foreach($sales_rep_list as $r){ ?>

<option value="<?= $r->sales_rep_id ?>"
<?= ($customer->sales_rep_id==$r->sales_rep_id)?'selected':'' ?>>

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

<?php foreach($sales_area_list as $s){ ?>

<option value="<?= $s->sales_area_id ?>"
<?= ($customer->sales_area_id==$s->sales_area_id)?'selected':'' ?>>

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

<option value="Africa"
<?= ($customer->continent=='Africa')?'selected':'' ?>>
Africa
</option>

<option value="Asia"
<?= ($customer->continent=='Asia')?'selected':'' ?>>
Asia
</option>

<option value="Europe"
<?= ($customer->continent=='Europe')?'selected':'' ?>>
Europe
</option>

<option value="North America"
<?= ($customer->continent=='North America')?'selected':'' ?>>
North America
</option>

<option value="South America"
<?= ($customer->continent=='South America')?'selected':'' ?>>
South America
</option>

<option value="Australia"
<?= ($customer->continent=='Australia')?'selected':'' ?>>
Australia
</option>

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
<input type="number"
       name="credit_limit"
       value="<?= $customer->credit_limit ?>"
       class="form-control">        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Credit Days</label>
<input type="number"
       name="credit_days"
       value="<?= $customer->credit_days ?>"
       class="form-control">        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Max Discount %</label>
<input type="number"
       name="max_discount_percent"
       value="<?= $customer->max_discount_percent ?>"
       class="form-control">        </div>
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
<input type="text"
       name="tax_registration_no"
       value="<?= $customer->tax_registration_no ?>"
       class="form-control">        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>Country</label>
<input type="text"
       name="tax_country"
       value="<?= $customer->tax_country ?>"
       class="form-control">        </div>
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
<input type="text"
       name="tax_code"
       value="<?= $customer->tax_code ?>"
       class="form-control">        </div>
    </div>

</div>
				
				

                <!-- Contact Persons -->
                <div class="form-group">
                    <label class="col-form-label">Contact Persons</label>
                    <table class="table" id="contact_table">
                        <?php
                        $row_index = 0;
                        if (!empty($contact_data)) {
                            foreach ($contact_data as $contact) { ?>
                                <tr id="addr<?= $row_index ?>">
                                    <td><input type="text" name="contact_name[]" value="<?= $contact->contact_name ?>" class="form-control" placeholder="Name"></td>
                                    <td><input type="text" name="contact_phone[]" value="<?= $contact->contact_phone ?>" class="form-control" placeholder="Phone"></td>
                                    <td><input type="email" name="contact_email[]" value="<?= $contact->contact_email ?>" class="form-control" placeholder="Email"></td>
                                    <td>
                                        <a href="javascript:delete_row(<?= $row_index ?>)" class="btn btn-sm bg-blue"><span class="glyphicon glyphicon-trash"></span></a>
                                    </td>
                                </tr>
                            <?php $row_index++; }
                        } ?>
                        <!-- Add empty row -->
                        <tr id="addr<?= $row_index ?>">
                            <td><input type="text" name="contact_name[]" class="form-control" placeholder="Name"></td>
                            <td><input type="text" name="contact_phone[]" class="form-control" placeholder="Phone"></td>
                            <td><input type="email" name="contact_email[]" class="form-control" placeholder="Email"></td>
                            <td>
                                <a href="javascript:add_row()" class="btn btn-sm bg-blue"><span class="glyphicon glyphicon-plus"></span></a>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" id="num_rows" name="num_rows" value="<?= $row_index ?>">
                </div>

                <!-- Submit -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">Update Customer</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- JS for Add/Delete Row -->
<script>
    function add_row() {
        var num_rows = parseInt($('#num_rows').val());
        var i = num_rows + 1;

        var new_row = `
            <tr id="addr${i}">
                <td><input class="form-control" name="contact_name[]" type="text" placeholder="Enter Name"></td>
                <td><input class="form-control" name="contact_phone[]" type="text" placeholder="Enter Contact Number"></td>
                <td><input class="form-control" name="contact_email[]" type="email" placeholder="Enter Email ID"></td>
                <td>
                    <a href="javascript:delete_row(${i})" title="Delete" class="btn btn-sm bg-blue">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
            </tr>
        `;

        $('#contact_table').append(new_row);
        $('#num_rows').val(i);
    }

    function delete_row(row_id) {
        $('#addr' + row_id).remove();
    }

document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Stop form from submitting
    let form = this;
    let valid = true;
    let errors = [];

    // Remove old invalid highlights
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    function checkRequired(input, name) {
        if (!input || input.value.trim() === '') {
            errors.push(`${name} is required.`);
            input.classList.add('is-invalid');
            valid = false;
        }
    }

    // Required fields
    checkRequired(form.branch, 'Branch');
    checkRequired(form.customer_name, 'Customer Name');
    // checkRequired(form.trn_no, 'TRN No');
    // checkRequired(form.trade_license_no, 'Trade License No');
    // checkRequired(form.license_issue_date, 'License Issue Date');
    // checkRequired(form.license_expiry_date, 'License Expiry Date');
    checkRequired(form.emirate, 'Emirate');

    // Optional but format-specific fields
    const emailInput = form.customer_email;
    if (emailInput && emailInput.value.trim() !== '' && !/^\S+@\S+\.\S+$/.test(emailInput.value)) {
        errors.push('Customer Email is invalid.');
        emailInput.classList.add('is-invalid');
        valid = false;
    }

    // File field: optional, but check format if file is selected
    const fileInput = form.trade_license_file;
    if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            errors.push('License file must be PDF, JPG, or PNG.');
            fileInput.classList.add('is-invalid');
            valid = false;
        }
    }

    // At least one full contact row
    // const names = document.getElementsByName('contact_name[]');
    // const phones = document.getElementsByName('contact_phone[]');
    // const emails = document.getElementsByName('contact_email[]');

    // let contactValid = false;

    // for (let i = 0; i < names.length; i++) {
    //     const name = names[i];
    //     const phone = phones[i];
    //     const email = emails[i];

    //     const filled = name.value.trim() || phone.value.trim() || email.value.trim();

    //     if (filled) {
    //         if (!name.value.trim()) {
    //             name.classList.add('is-invalid');
    //             errors.push(`Contact Name #${i + 1} is required.`);
    //             valid = false;
    //         }
    //         if (!phone.value.trim()) {
    //             phone.classList.add('is-invalid');
    //             errors.push(`Contact Phone #${i + 1} is required.`);
    //             valid = false;
    //         }
    //         if (!email.value.trim()) {
    //             email.classList.add('is-invalid');
    //             errors.push(`Contact Email #${i + 1} is required.`);
    //             valid = false;
    //         } else if (!/^\S+@\S+\.\S+$/.test(email.value.trim())) {
    //             email.classList.add('is-invalid');
    //             errors.push(`Contact Email #${i + 1} is invalid.`);
    //             valid = false;
    //         }
    //         contactValid = true;
    //     }
    // }

    // if (!contactValid) {
    //     errors.push('At least one contact person must be fully filled (Name, Phone, Email).');
    //     valid = false;
    // }

    // Show error alert or submit
    if (!valid) {
        alert("Please correct the following errors:\n\n" + errors.join("\n"));
    } else {
        form.submit(); // All good, submit now
    }
});
</script>

