<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />
            <form action="<?= base_url('index.php/Company/update_customer') ?>" method="post" enctype="multipart/form-data" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="customer_id" value="<?= $customer_id ?>">

                <div class="row">
                    <!-- Branch -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Branch <span class="text-danger">*</span></label>
                            <?= form_error('branch', '<small class="text-danger">', '</small>') ?>
                            <select name="branch" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($branch_list as $branch): ?>
                                    <option value="<?= $branch->branch_id ?>" <?= ($branch->branch_id == $customer_data->branch_id) ? 'selected' : '' ?>>
                                        <?= $branch->branch_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Customer Code -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Code <span class="text-danger">*</span></label>
                            <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            <input type="text" name="customer_code" class="form-control" value="<?= $customer_data->customer_code ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Customer Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <?= form_error('customer_name', '<small class="text-danger">', '</small>') ?>
                            <input type="text" name="customer_name" class="form-control" value="<?= $customer_data->customer_name ?>">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <?= form_error('customer_email', '<small class="text-danger">', '</small>') ?>
                            <input type="email" name="customer_email" class="form-control" value="<?= $customer_data->customer_email ?>" onfocus="this.removeAttribute('readonly');">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Contact Number -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Number</label>
                            <?= form_error('customer_number', '<small class="text-danger">', '</small>') ?>
                            <input type="text" name="customer_number" class="form-control" pattern="[0-9]+" value="<?= $customer_data->contact_number ?>">
                        </div>
                    </div>

                    <!-- TRN No -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>TRN No </label>
                            <!-- <?= form_error('trn_no', '<small class="text-danger">', '</small>') ?> -->
                            <input type="text" name="trn_no" class="form-control" value="<?= $customer_data->customer_TR_no ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Trade License No -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Trade License No </label>
                            <!-- <?= form_error('trade_license_no', '<small class="text-danger">', '</small>') ?> -->
                            <input type="text" name="trade_license_no" class="form-control" value="<?= $customer_data->customer_TL_no ?>">
                        </div>
                    </div>

                    <!-- License Issue Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>License Issue Date </label>
                            <!-- <?= form_error('license_issue_date', '<small class="text-danger">', '</small>') ?> -->
                            <input type="date" name="license_issue_date" class="form-control" value="<?= $customer_data->licence_issue_date ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- License Expiry Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>License Expiry Date</label>
                            <!-- <?= form_error('license_expiry_date', '<small class="text-danger">', '</small>') ?> -->
                            <input type="date" name="license_expiry_date" class="form-control" value="<?= $customer_data->licence_exp_date ?>">
                        </div>
                    </div>

                    <!-- Upload License -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Upload License</label>
                            <input type="file" name="trade_license_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <?php if (!empty($customer_data->licence_file)): ?>
                                <small>Current File: <a href="<?= base_url('public/customer/' . $customer_data->licence_file) ?>" target="_blank"><?= $customer_data->licence_file ?></a></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <!-- Location -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Emirate <span class="text-danger">*</span></label>
                            <?= form_error('emirate', '<small class="text-danger">', '</small>') ?>
                            <select name="emirate" class="form-control">
                                <?php
                                $emirates = ['Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'];
                                foreach ($emirates as $emirate): ?>
                                    <option value="<?= $emirate ?>" <?= ($customer_data->emirate == $emirate) ? 'selected' : '' ?>><?= $emirate ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="customer_address" class="form-control"><?= $customer_data->customer_address ?></textarea>
                        </div>
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

