<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Supplier Updation</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?= base_url('index.php/Company/update_supplier') ?>" method="post" autocomplete='off' enctype="multipart/form-data">
                <input type="hidden" name="supplierid" value="<?= $supplier_id ?>" >  
                
                <div class="form-group row">
                <label class="col-md-2 col-form-label">Branch <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <?= form_error('branch', '<small class="text-danger">', '</small>') ?>
                    <select name="branch" class="form-control">
                        <option value=''>Select</option>
                        <?php foreach ($branch_list as $branch): ?>
                            <option value="<?= $branch->branch_id ?>" <?= ($branch->branch_id == $supplier->branch_id) ? 'selected' : '' ?>>
                                <?= $branch->branch_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label class="col-md-2 col-form-label">Supplier Code <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <input type="text" name="supplier_code" value="<?= $supplier->supplier_code ?>" class="form-control" readonly>
                </div>
            </div>

                <div class="form-group row">
                        <label class="col-md-3 col-form-label">Supplier/Company Name <span class="required">*</span></label>
                        <div class="col-md-3">
                            <input type="text" name="supplier_name" class="form-control" required value="<?= $supplier->supplier_name?>">
                        </div>

                        <label class="col-md-2 col-form-label">Supplier Type</label>
                        <div class="col-md-3">
                            <select name="supplier_type" id="supplier_type" class="form-control">
                                <option value="Local" <?= $supplier->supplier_type== 'Local' ? 'selected' : '' ?>>Local</option>
                                <option value="Overseas" <?= $supplier->supplier_type== 'Overseas' ? 'selected' : '' ?>>Overseas</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Company Website</label>
                        <div class="col-md-3">
                            <input type="url" name="company_website" class="form-control" value="<?= $supplier->website?>">
                        </div>

                        <label class="col-md-2 col-form-label">Email ID</label>
                        <div class="col-md-3">
                            <input type="email" name="supplier_email" class="form-control"  value="<?= $supplier->supplier_email?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Number</label>
                        <div class="col-md-3">
                            <input type="text" name="supplier_contact" class="form-control" value="<?= $supplier->contact_number?>">
                        </div>

                        <label class="col-md-2 col-form-label">TRN No</label>
                        <div class="col-md-3">
                            <input type="text" name="trn_no" id="trn_no" class="form-control" value="<?= $supplier->trn_no?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Currency </label></label>
                        <div class="col-md-3">
                            <select name="currency" class="form-control">                             
                                <?php foreach ($currency_list as $currency) { ?>
                                    <option value="<?php echo $currency->currency_id; ?>" <?= $supplier->currency_id == $currency->currency_id ? 'selected' : '' ?>>
                                        <?php echo $currency->currency_abbr; ?>
                                    </option>
                                <?php } ?>
                            </select>                           
                        </div>
                    </div>                    
                    <hr>
                        <h4>Trade License Details </h4>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Trade License No </label>
                            <div class="col-md-4">
                                <input type="text" name="trade_license_no" id="trade_license_no" class="form-control" value="<?= isset($supplier) ? $supplier->trade_licence_no : '' ?>">
                            </div>

                            <label class="col-md-2 col-form-label">Issued Date </label>
                            <div class="col-md-4">
                                <input type="date" name="issued_date" id="issued_date" class="form-control" value="<?= isset($supplier) ? $supplier->trl_issued_date : '' ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Expiry Date </label>
                            <div class="col-md-4">
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="<?= isset($supplier) ? $supplier->trl_expire_date : '' ?>">
                            </div>

                            <label class="col-md-2 col-form-label">Attachment <?= empty($supplier->trade_license_file) ? : '' ?></label>
                            <div class="col-md-4">
                                <input type="file" name="trade_license_file" id="trade_license_file"  class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Allowed: PDF, JPG, PNG</small>
                                    <?php if (!empty($supplier->tr_file)): ?>
                                        <small>Current File: <a href="<?= base_url('public/supplier/' . $supplier->tr_file) ?>" target="_blank"><?= $supplier->tr_file ?></a></small>
                                    <?php endif; ?>
                               
                                    </div>                               
                            </div>
                        </div>

                    <hr>
                    <h4>Billing & Shipping Address</h4>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-6 text-mid">
                            <label>
                                <input type="checkbox" id="same_as_billing" onclick="copyBillingAddress()"> 
                                Shipping same as billing
                            </label>
                        </div>
                    </div>

                <hr>
                

                
                <!-- Billing and Shipping Fields -->
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Billing Address</label>
                    <div class="col-md-3">
                        <textarea name="billing_address" id="billing_address" class="form-control"><?= isset($supplier->billing_address) ? $supplier->billing_address : '' ?></textarea>
                    </div>

                    <label class="col-md-2 col-form-label">Shipping Address</label>
                    <div class="col-md-3">
                        <textarea name="shipping_address" id="shipping_address" class="form-control"><?= isset($supplier->shipping_address) ? $supplier->shipping_address : '' ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Billing City</label>
                    <div class="col-md-3">
                        <input type="text" name="billing_city" id="billing_city" class="form-control" value="<?= isset($supplier->billing_city) ? $supplier->billing_city : '' ?>">
                    </div>

                    <label class="col-md-2 col-form-label">Shipping City</label>
                    <div class="col-md-3">
                        <input type="text" name="shipping_city" id="shipping_city" class="form-control" value="<?= isset($supplier->shipping_city) ? $supplier->shipping_city : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Billing P.O. Box</label>
                    <div class="col-md-3">
                        <input type="text" name="billing_pobox" id="billing_pobox" class="form-control" value="<?= isset($supplier->billing_po_box) ? $supplier->billing_po_box : '' ?>">
                    </div>

                    <label class="col-md-2 col-form-label">Shipping P.O. Box</label>
                    <div class="col-md-3">
                        <input type="text" name="shipping_pobox" id="shipping_pobox" class="form-control" value="<?= isset($supplier->shipping_po_box) ? $supplier->shipping_po_box : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Billing Country</label>
                    <div class="col-md-3">
                        <input type="text" name="billing_country" id="billing_country" class="form-control" value="<?= isset($supplier->billing_country) ? $supplier->billing_country : '' ?>">
                    </div>

                    <label class="col-md-2 col-form-label">Shipping Country</label>
                    <div class="col-md-3">
                        <input type="text" name="shipping_country" id="shipping_country" class="form-control" value="<?= isset($supplier->shipping_country) ? $supplier->shipping_country : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Billing State</label>
                    <div class="col-md-3">
                        <input type="text" name="billing_state" id="billing_state" class="form-control" value="<?= isset($supplier->billing_state) ? $supplier->billing_state : '' ?>">
                    </div>

                    <label class="col-md-2 col-form-label">Shipping State</label>
                    <div class="col-md-3">
                        <input type="text" name="shipping_state" id="shipping_state" class="form-control" value="<?= isset($supplier->shipping_state) ? $supplier->shipping_state : '' ?>">
                    </div>
                </div>
                <hr>
                <h4>Bank Details</h4>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Bank Name:</label>
                        <input type="text" name="bank_name" class="form-control" value="<?= isset($supplier->bank_name) ? $supplier->bank_name : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Account No:</label>
                        <input type="text" name="account_no" class="form-control" value="<?= isset($supplier->bank_account) ? $supplier->bank_account : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Bank IBAN No:</label>
                        <input type="text" name="iban" class="form-control" value="<?= isset($supplier->bank_IBAN) ? $supplier->bank_IBAN : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Bank Branch:</label>
                        <input type="text" name="branch_name" class="form-control" value="<?= isset($supplier->bank_branch) ? $supplier->bank_branch : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Bank SWIFT:</label>
                        <input type="text" name="swift" class="form-control" value="<?= isset($supplier->bank_swift) ? $supplier->bank_swift : '' ?>">
                    </div>
                </div>

                <hr>
                <h4>Intermediate Bank Details</h4>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Bank Name:</label>
                        <input type="text" name="inter_bank_name" class="form-control" value="<?= isset($supplier->intermidiate_Bname) ? $supplier->intermidiate_Bname : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Account No:</label>
                        <input type="text" name="inter_account_no" class="form-control" value="<?= isset($supplier->intermidiate_Bacc) ? $supplier->intermidiate_Bacc : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Bank IBAN No:</label>
                        <input type="text" name="inter_iban" class="form-control" value="<?= isset($supplier->intermidiate_IBAN) ? $supplier->intermidiate_IBAN : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Bank Branch:</label>
                        <input type="text" name="inter_branch" class="form-control" value="<?= isset($supplier->intermidiate_Bbranch) ? $supplier->intermidiate_Bbranch : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Bank SWIFT:</label>
                        <input type="text" name="inter_swift" class="form-control" value="<?= isset($supplier->intermidiate_swift) ? $supplier->intermidiate_swift : '' ?>">
                    </div>
                </div>

                    <!-- Contact Persons -->
                    <hr>
                    <h4>Contact Person(s)</h4>
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
                                    <?php foreach ($contacts as $index => $contact): ?>
                                    <tr id="contact_row<?= $index ?>">
                                        <td><input type="text" name="contact_person[]" class="form-control" value="<?= $contact->contact_name?>"></td>
                                        <td><input type="text" name="contact_mobile[]" class="form-control" value="<?= $contact->contact_phone?>"></td>
                                        <td><input type="email" name="contact_email[]" class="form-control" value="<?= $contact->contact_email?>"></td>
                                        <td>
                                            <?php if ($index > 0): ?>
                                            <a href="javascript:remove_contact_row(<?= $index ?>);" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <input type="hidden" id="contact_count" value="<?= count($contacts) - 1 ?>">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS: Add/Remove Contact Row -->
<script>
    function add_contact_row() {
        var count = parseInt($('#contact_count').val()) + 1;
        var html = "<tr id='contact_row" + count + "'>" +
            "<td><input type='text' name='contact_person[]' class='form-control'></td>" +
            "<td><input type='text' name='contact_mobile[]' class='form-control'></td>" +
            "<td><input type='email' name='contact_email[]' class='form-control'></td>" +
            "<td><a href='javascript:remove_contact_row(" + count + ");' class='btn btn-sm btn-danger'><span class='glyphicon glyphicon-trash'></span></a></td>" +
            "</tr>";
        $('#contact_table').append(html);
        $('#contact_count').val(count);
    }

    function remove_contact_row(id) {
        $('#contact_row' + id).remove();
    }
</script>
<script>
function copyBillingAddress() {
    if (document.getElementById('same_as_billing').checked) {
        document.getElementById('shipping_address').value = document.getElementById('billing_address').value;
        document.getElementById('shipping_city').value = document.getElementById('billing_city').value;
        document.getElementById('shipping_pobox').value = document.getElementById('billing_pobox').value;
        document.getElementById('shipping_country').value = document.getElementById('billing_country').value;
        document.getElementById('shipping_state').value = document.getElementById('billing_state').value;
    } else {
        document.getElementById('shipping_address').value = '';
        document.getElementById('shipping_city').value = '';
        document.getElementById('shipping_pobox').value = '';
        document.getElementById('shipping_country').value = '';
        document.getElementById('shipping_state').value = '';
    }
}

$(document).ready(function () {

    toggleSupplierValidation();

    $("#supplier_type").change(function () {
        toggleSupplierValidation();
    });

});

function toggleSupplierValidation() {

    let supplierType = $("#supplier_type").val();

    if (supplierType === "Overseas") {

        // Remove required
        $("#trn_no, #trade_license_no, #issued_date, #expiry_date, #trade_license_file")
            .removeAttr("required");

        // Hide *
        $(".mandatory-mark").hide();

    } else {

        // Add required
        $("#trn_no, #trade_license_no, #issued_date, #expiry_date, #trade_license_file")
            .removeAttr("required");

        // Show *
        $(".mandatory-mark").hide();
    }
}
</script>

