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
            <div class="x_title">
                <h2>Supplier Registration</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="supplier_form" action="" method="post" autocomplete='off' enctype="multipart/form-data">
                    
					<div class="form-group row ">
						<label class="col-md-2 col-form-label">Branch </span></label>
						<div class="col-md-4">
							<select name="branch" class="form-control">
								<option value=''>Select</option>
								<?php foreach($branch_list as $branch): ?>
									<option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<label class="col-md-2 col-form-label">Supplier Code<span class="text-danger">*</span></label></label>
                        <div class="col-md-3">
                            <input type="text" name="supplier_code" value="<?=$supplier_code?>" class="form-control" readonly >
                        </div>
					</div>
                    <!-- Supplier Details -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Supplier/Company Name <span class="text-danger">*</span></label></label>
                        <div class="col-md-3">
                            <input type="text" name="supplier_name" class="form-control" >
                        </div>

                        <label class="col-md-2 col-form-label">Supplier Type </label></label>
                        <div class="col-md-3">
                            <select name="supplier_type" id="supplier_type" class="form-control">
                                <option value="Local">Local</option>
                                <option value="Overseas">Overseas</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Company Website</label>
                        <div class="col-md-3">
                            <input type="url" name="company_website" class="form-control">
                        </div>

                        <label class="col-md-2 col-form-label">Email ID  </label></label>
                        <div class="col-md-3">
                            <input type="email" name="supplier_email" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Number </label></label>
                        <div class="col-md-3">
                            <input type="text" name="supplier_contact" class="form-control">
                        </div>

                        <label class="col-md-2 col-form-label">TRN No </label></label>
                        <div class="col-md-3">
                            <input type="text" name="trn_no"  id="trn_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Currency </label></label>
                        <div class="col-md-3">
                            <select name="currency" class="form-control">
                                <option value="" disabled>Select Currency</option>
                                <?php foreach ($currency_list as $currency) { ?>
                                    <option value="<?php echo $currency->currency_id; ?>"
                                        <?php echo ($currency->currency_id == 1) ? 'selected' : ''; ?>>
                                        <?php echo $currency->currency_abbr; ?>
                                    </option>
                                <?php } ?>
                            </select>                           
                        </div>
                    </div>                    
                    <div id="trade_license_section">
					<hr>
					<h4>Trade License Details </h4>

					<div class="form-group row">
						<label class="col-md-2 col-form-label">Trade License No </label>
						<div class="col-md-4">
							<input type="text" name="trade_license_no" id="trade_license_no" class="form-control" >
						</div>

						<label class="col-md-2 col-form-label">Issued Date </label>
						<div class="col-md-4">
							<input type="date" name="issued_date" id="issued_date" class="form-control" >
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-2 col-form-label">Expiry Date </label>
						<div class="col-md-4">
							<input type="date" name="expiry_date"  id="expiry_date" class="form-control" >
						</div>

						<label class="col-md-2 col-form-label">Attachment </label>
						<div class="col-md-4">
							<input type="file" name="trade_license_file" id="trade_license_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" >
							<small class="text-muted">Allowed: PDF, JPG, PNG</small>
						</div>
					</div>
</div>

                    <hr>
					<h4>Billing & Shipping Address </label></h4>

					<!-- Checkbox to copy billing to shipping -->
					<div class="form-group row">
						<div class="col-md-6 offset-md-6 text-mid">
							<label>
								<input type="checkbox" id="same_as_billing" onclick="copyBillingAddress()"> 
								Shipping same as billing
							</label>
						</div>
					</div>

					<!-- Billing Address Fields -->
					<div class="form-group row">
						<label class="col-md-3 col-form-label">Billing Address</label>
						<div class="col-md-3">
							<textarea name="billing_address" id="billing_address" class="form-control"></textarea>
						</div>

						<label class="col-md-2 col-form-label">Shipping Address</label>
						<div class="col-md-3">
							<textarea name="shipping_address" id="shipping_address" class="form-control"></textarea>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3 col-form-label">Billing City</label>
						<div class="col-md-3">
							<input type="text" name="billing_city" id="billing_city" class="form-control">
						</div>

						<label class="col-md-2 col-form-label">Shipping City</label>
						<div class="col-md-3">
							<input type="text" name="shipping_city" id="shipping_city" class="form-control">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3 col-form-label">Billing P.O. Box</label>
						<div class="col-md-3">
							<input type="text" name="billing_pobox" id="billing_pobox" class="form-control">
						</div>

						<label class="col-md-2 col-form-label">Shipping P.O. Box</label>
						<div class="col-md-3">
							<input type="text" name="shipping_pobox" id="shipping_pobox" class="form-control">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3 col-form-label">Billing Country</label>
						<div class="col-md-3">
							<input type="text" name="billing_country" id="billing_country" class="form-control">
						</div>

						<label class="col-md-2 col-form-label">Shipping Country</label>
						<div class="col-md-3">
							<input type="text" name="shipping_country" id="shipping_country" class="form-control">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-3 col-form-label">Billing State</label>
						<div class="col-md-3">
							<input type="text" name="billing_state" id="billing_state" class="form-control">
						</div>

						<label class="col-md-2 col-form-label">Shipping State</label>
						<div class="col-md-3">
							<input type="text" name="shipping_state" id="shipping_state" class="form-control">
						</div>
					</div>

					<hr>
						<h4>Bank Details </label></h4>

						<div class="form-group row">
							<div class="col-md-4">
								<label>Bank Name:</label>
								<input type="text" name="bank_name" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Account No:</label>
								<input type="text" name="account_no" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Bank IBAN No:</label>
								<input type="text" name="iban" class="form-control">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-4">
								<label>Bank Branch:</label>
								<input type="text" name="branch_name" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Bank SWIFT:</label>
								<input type="text" name="swift" class="form-control">
							</div>
						</div>

						<hr>
						<h4>Intermediate Bank Details </label></h4>

						<div class="form-group row">
							<div class="col-md-4">
								<label>Bank Name:</label>
								<input type="text" name="inter_bank_name" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Account No:</label>
								<input type="text" name="inter_account_no" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Bank IBAN No:</label>
								<input type="text" name="inter_iban" class="form-control">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-4">
								<label>Bank Branch:</label>
								<input type="text" name="inter_branch" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Bank SWIFT:</label>
								<input type="text" name="inter_swift" class="form-control">
							</div>
						</div>



                    <!-- Dynamic Contact Person -->
                    <hr>
                    <h4>Contact Person(s) </label></h4>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Contact Person</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th><a href="javascript:add_contact_row();" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></a></th>
                                    </tr>
                                </thead>
                                <tbody id="contact_table">
                                    <tr id="contact_row0">
                                        <td><input type="text" name="contact_person[]" class="form-control"></td>
                                        <td><input type="text" name="contact_mobile[]" class="form-control"></td>
                                        <td><input type="email" name="contact_email[]" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" id="contact_count" value="0">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <!-- <button class="btn btn-primary" type="reset">Reset</button> -->
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS: Add/Remove Contact Row -->
<script>
function copyBillingAddress() {
    if ($("#same_as_billing").is(":checked")) {
        $("#shipping_address").val($("#billing_address").val());
        $("#shipping_city").val($("#billing_city").val());
        $("#shipping_pobox").val($("#billing_pobox").val());
        $("#shipping_country").val($("#billing_country").val());
        $("#shipping_state").val($("#billing_state").val());
    } else {
        $("#shipping_address, #shipping_city, #shipping_pobox, #shipping_country, #shipping_state").val('');
    }
}

// Add Contact Person Row
let contactIndex = 0;
function add_contact_row() {
    contactIndex++;
    let newRow = `
        <tr id="contact_row${contactIndex}">
            <td><input type="text" name="contact_person[]" class="form-control"></td>
            <td><input type="text" name="contact_mobile[]" class="form-control"></td>
            <td><input type="email" name="contact_email[]" class="form-control"></td>
            <td><a href="javascript:void(0)" onclick="remove_contact_row(${contactIndex})" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>`;
    $("#contact_table").append(newRow);
}

// Remove Contact Person Row
function remove_contact_row(index) {
    $("#contact_row" + index).remove();
}

// Form validation before submission
$(document).ready(function () {

    $("#supplier_form").on("submit", function (e) {
        e.preventDefault(); // always prevent default and handle via AJAX

        let isValid = true;

        function loadSupplierDropdown() {
            $.ajax({
                url: '<?= base_url("index.php/Ajax/load_supplier_dropdown_html") ?>',
                type: 'GET',
                success: function (html) {
                    $('#supplier_dropdown_wrapper').html(html);
                    if ($('.select2').length) $('.select2').select2();
                },
                error: function (xhr) {
                    console.error('Dropdown load error:', xhr.responseText);
                }
            });
        }

        // File validation
        const fileInput = $("input[name='trade_license_file']")[0];
        const file = fileInput && fileInput.files ? fileInput.files[0] : null;
        if (file) {
            const allowed = ['application/pdf', 'image/jpeg', 'image/png'];
            if (!allowed.includes(file.type)) {
                $("input[name='trade_license_file']").after("<small class='text-danger'>Only PDF, JPG, or PNG files are allowed</small>");
                isValid = false;
            }
        }

        if (!isValid) return; // stop if validation failed

        // Prepare FormData for AJAX
        const form = this;
        const formData = new FormData(form);

        $.ajax({
            url: '<?= base_url("index.php/Ajax/save_customer_ajax") ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(form).find('button[type=submit]').prop('disabled', true);
            },
            success: function (response) {
                if (response && response.success) {
                    // show supplier success alert if present
                    if ($('#supplier-success-alert').length) {
                        $('#supplier-success-alert').fadeIn();
                        setTimeout(function () { $('#supplier-success-alert').fadeOut(); }, 2000);
                    } else {
                        alert('Supplier saved successfully');
                    }

                    // Close modal
                    if ($('#newSupplierModal').length) $('#newSupplierModal').modal('hide');

                    // Refresh supplier dropdown (replace wrapper content and init select2)
                    if (typeof loadSupplierDropdown === 'function') {
                        loadSupplierDropdown();
                    } else {
                        // fallback: fetch HTML and replace wrapper
                        $.ajax({
                            url: '<?= base_url("index.php/Ajax/load_supplier_dropdown_html") ?>',
                            type: 'GET',
                            success: function (html) {
                                $('#supplier_dropdown_wrapper').html(html);
                                if ($('.select2').length) $('.select2').select2();
                            },
                            error: function(xhr){ console.error('Dropdown load error:', xhr.responseText); }
                        });
                    }
                } else {
                    alert('Failed to save supplier. ' + (response && response.message ? response.message : ''));
                }
            },
            error: function (xhr, status, error) {
                alert('AJAX Error: ' + error);
                console.error('Server response:', xhr.responseText);
            },
            complete: function() {
                $(form).find('button[type=submit]').prop('disabled', false);
            }
        });
    });
});
</script>
