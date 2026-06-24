   
<form id="customerForm" action="" method="post" autocomplete='off' enctype="multipart/form-data">
			
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
							<label>Branch <span class="text-danger">*</span></label>
							<?= form_error('branch', '<small class="text-danger">', '</small>') ?>
							<select name="branch" class="form-control">
								<option value=''>Select</option>
								<?php foreach($branch_list as $branch): ?>
									<option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>

				<!-- Row 2: Customer Name + Email -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Customer Name <span class="text-danger">*</span></label>
							<?= form_error('customer_name', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="customer_name" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Email</label>
							<?= form_error('customer_email', '<small class="text-danger">', '</small>') ?>
							<input type="email" name="customer_email" class="form-control">
						</div>
					</div>
				</div>

				<!-- Row 3: Contact Number + Address -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Contact Number</label>
							<?= form_error('customer_number', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="customer_number" class="form-control" pattern="[0-9]+" title="Enter a valid phone number">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>TRN No </label>
							<?= form_error('trn_no', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="trn_no" class="form-control">
						</div>
					</div>
				</div>
					

				<!-- Row 4: Trade License No + Issue Date -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Trade License No </label>
							<?= form_error('trade_license_no', '<small class="text-danger">', '</small>') ?>
							<input type="text" name="trade_license_no" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>License Issue Date </label>
							<?= form_error('license_issue_date', '<small class="text-danger">', '</small>') ?>
							<input type="date" name="license_issue_date" class="form-control">
						</div>
					</div>
				</div>

				<!-- Row 5: Expiry Date + Upload -->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>License Expiry Date</label>
							<?= form_error('license_expiry_date', '<small class="text-danger">', '</small>') ?>
							<input type="date" name="license_expiry_date" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Upload License </label>
							<?= form_error('trade_license_file', '<small class="text-danger">', '</small>') ?>
							<input type="file" name="trade_license_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Location</label>
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
				<!-- Row 7: Contact Person Table -->
				<div class="form-group">
					<label class="col-form-label">Contacts Person</label>
					<div class="col-md-12">
						<?= form_error('contact_name[]', '<small class="text-danger">', '</small>') ?>
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
					<button type="submit" class="btn btn-success">Submit</button>
				</div>

			</form>



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
    alert("hei"); // debug
    $('#addr' + id).remove();
}

document.getElementById('customerForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    let valid = true;
    let errorMessages = [];

    // Remove previous error highlights
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    const form = this;

    // Basic required field validation
    function isEmpty(field, label) {
        if (!field || field.value.trim() === '') {
            errorMessages.push(label + ' is required.');
            valid = false;
            field.classList.add('is-invalid');
        }
    }

    isEmpty(form.customer_name, 'Customer Name');
    // isEmpty(form.customer_email, 'Customer Email');
    // isEmpty(form.customer_number, 'Contact Number');
    // isEmpty(form.trn_no, 'TRN No');
    // isEmpty(form.trade_license_no, 'Trade License No');
    // isEmpty(form.license_issue_date, 'License Issue Date');
    // isEmpty(form.license_expiry_date, 'License Expiry Date');
    isEmpty(form.customer_address, 'Customer Address');

    // Dropdowns
    if (form.branch.value.trim() === '') {
        errorMessages.push('Branch is required.');
        valid = false;
        form.branch.classList.add('is-invalid');
    }

    // if (form.emirate.value.trim() === '') {
    //     errorMessages.push('Location (Emirate) is required.');
    //     valid = false;
    //     form.emirate.classList.add('is-invalid');
    // }

    // File validation
    // const fileInput = form.trade_license_file;
    // if (!fileInput || fileInput.files.length === 0) {
    //     errorMessages.push('Trade License File is required.');
    //     valid = false;
    //     fileInput.classList.add('is-invalid');
    // } else {
    //     const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    //     const fileType = fileInput.files[0].type;
    //     if (!allowedTypes.includes(fileType)) {
    //         errorMessages.push('Trade License File must be PDF or Image.');
    //         valid = false;
    //         fileInput.classList.add('is-invalid');
    //     }
    // }

    // Contact person table validation
    const contactNames = document.getElementsByName('contact_name[]');
    const contactPhones = document.getElementsByName('contact_phone[]');
    const contactEmails = document.getElementsByName('contact_email[]');

    // let atLeastOneFilled = false;

    for (let i = 0; i < contactNames.length; i++) {
        const name = contactNames[i];
        const phone = contactPhones[i];
        const email = contactEmails[i];

      // Only validate if any field in the row is filled
        const anyFilled = name.value.trim() !== '' || phone.value.trim() !== '' || email.value.trim() !== '';
        if (anyFilled) {
            if (name.value.trim() === '') {
                errorMessages.push('Contact Name #' + (i + 1) + ' is required.');
                name.classList.add('is-invalid');
                valid = false;
            }

            if (phone.value.trim() === '') {
                errorMessages.push('Contact Phone #' + (i + 1) + ' is required.');
                phone.classList.add('is-invalid');
                valid = false;
            }

            if (email.value.trim() === '') {
                errorMessages.push('Contact Email #' + (i + 1) + ' is required.');
                email.classList.add('is-invalid');
                valid = false;
            } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
                errorMessages.push('Contact Email #' + (i + 1) + ' is invalid.');
                email.classList.add('is-invalid');
                valid = false;
            }
        }
    }

    if (!valid) {
        alert("Please fix the following errors:\n\n" + errorMessages.join('\n'));
        return;
    }

    // ✅ Prepare FormData for AJAX
    const formData = new FormData(form);

    $.ajax({
        url: '<?= base_url("index.php/Ajax/save_customer_ajax") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,   // ✅ Do not process data
        contentType: false,   // ✅ Let browser set content-type (with boundary)
        success: function (response) {
            if (response.success) {
                $('#customer-success-alert').fadeIn();

                setTimeout(function () {
                    $('#customer-success-alert').fadeOut();

                    // ✅ Close modal
                    $('#myModal').modal('hide');

                    // ✅ Add to dropdown
                    loadCustomerDropdown();
                }, 2000);
            } else {
                alert("Failed to save customer. " + (response.message || ''));
            }
        },
        error: function (xhr, status, error) {
            alert("AJAX Error: " + error);
            console.error("Server response:", xhr.responseText);
        }
    });
});
function loadCustomerDropdown() {
    $.ajax({
        url: '<?= base_url("index.php/Ajax/load_customer_dropdown_html") ?>',
        type: 'GET',
        success: function (html) {
            $('#customer_dropdown_wrapper').html(html);     // Replace old dropdown
            $('.select2').select2();                         // Re-initialize Select2
        },
        error: function (xhr) {
            console.error("Dropdown load error:", xhr.responseText);
        }
    });
}

</script>
