<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Employee Registration</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?= base_url('index.php/Company/update_employee/' . $employee->employee_id) ?>" method="post" enctype="multipart/form-data" autocomplete='off'>

                    <!-- Basic Details -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Employee Name <span class="required">*</span></label>
                        <div class="col-md-3">
                            <input type="text" name="employee_name" class="form-control" value="<?= $employee->employee_name ?>" required>
                        </div>

                        <label class="col-md-2 col-form-label">Branch <span class="required">*</span></label>
                        <div class="col-md-3">
                            <select name="branch_id" class="form-control" required>
                                <option value="">Select</option>
                                <?php foreach ($branch_list as $branch): ?>
                                    <option value="<?= $branch->branch_id ?>" <?= ($employee->branch_id == $branch->branch_id) ? 'selected' : '' ?>>
                                        <?= $branch->branch_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Mobile Number</label>
                        <div class="col-md-3">
                            <input type="text" name="mobile" class="form-control" value="<?= $employee->mobile ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Gender</label>
                        <div class="col-md-3">
                            <select name="gender" class="form-control">
                                <option value="">Select</option>
                                <option value="Male" <?= ($employee->gender == 'Male') ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($employee->gender == 'Female') ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Birth Date</label>
                        <div class="col-md-3">
                            <input type="date" name="birth_date" class="form-control" value="<?= $employee->birth_date ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Nationality</label>
                        <div class="col-md-3">
                            <input type="text" name="nationality" class="form-control" value="<?= $employee->nationality ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Date of Joining</label>
                        <div class="col-md-3">
                            <input type="date" name="joining_date" class="form-control" value="<?= $employee->joining_date ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Upload Photo</label>
                        <div class="col-md-3">
                            <?php if ($employee->employee_photo): ?>
                                <img src="<?= base_url('public/employee/' . $employee->employee_photo) ?>" width="80" class="mb-2"><br>
                                <input type="hidden" name="old_employee_photo" value="<?= $employee->employee_photo ?>">
                            <?php endif; ?>
                            <input type="file" name="employee_photo" accept=".pdf,.png,.jpeg,.jpg" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            Department <span class="text-danger">*</span>
                        </label>

                        <div class="col-md-3">
                            <select name="department_id" class="form-control" required>
                                <option value="">Select</option>
                                <?php foreach($department_list as $dept){ ?>
                                    <option value="<?= $dept->dept_id ?>"
                                        <?= ($employee->department_id == $dept->dept_id) ? 'selected' : '' ?>>
                                        <?= $dept->dept_name ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <label class="col-md-2 col-form-label">
                            UID <span class="text-danger">*</span>
                        </label>

                        <div class="col-md-3">
                            <input type="text"
                                name="uid_number"
                                class="form-control"
                                value="<?= $employee->uid_number ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            Designation <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-3">
                            <select name="designation_id" class="form-control" required>
                                <option value="">Select</option>

                                <?php foreach($designation_list as $d){ ?>
                                    <option value="<?= $d->id ?>"
                                        <?= ($employee->designation_id == $d->id) ? 'selected' : '' ?>>
                                        <?= $d->designation_name ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <label class="col-md-2 col-form-label">
                            Employee Code <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-3">
                            <input type="text"
                                name="employee_code"
                                id="employee_code"
                                class="form-control"
                                value="<?= $employee->user_code ?>"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            Digital Signature
                        </label>

                        <div class="col-md-3">
                            <?php if (!empty($employee->signature_file)) { ?>
                                <div style="margin-bottom:8px;">
                                    <img src="<?= base_url('public/employee/' . $employee->signature_file) ?>"
                                        width="120"
                                        style="border:1px solid #ddd;padding:3px;">
                                </div>

                                <input type="hidden"
                                    name="old_signature_file"
                                    value="<?= $employee->signature_file ?>">
                            <?php } ?>

                            <input type="file"
                                name="signature_file"
                                accept=".png,.jpg,.jpeg"
                                class="form-control">

                        </div>
                    </div>
                        

                    <hr>
                    <h4>Passport Details</h4>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Name (as in Passport)</label>
                        <div class="col-md-3">
                            <input type="text" name="passport_name" class="form-control" value="<?= $employee->passport_name ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Passport Number</label>
                        <div class="col-md-3">
                            <input type="text" name="passport_number" class="form-control" value="<?= $employee->passport_number ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Passport Issue Date</label>
                        <div class="col-md-3">
                            <input type="date" name="passport_issue_date" class="form-control" value="<?= $employee->passport_issue_date ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Passport Expiry Date</label>
                        <div class="col-md-3">
                            <input type="date" name="passport_expiry_date" class="form-control" value="<?= $employee->passport_expiry_date ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Passport Issue Place</label>
                        <div class="col-md-3">
                            <input type="text" name="passport_issue_place" class="form-control" value="<?= $employee->passport_issue_place ?>">
                        </div>
                    </div>

                    <hr>
                    <h4>Labor Card Details</h4>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Work Permit No (MOL)</label>
                        <div class="col-md-3">
                            <input type="text" name="work_permit_no" class="form-control" value="<?= $employee->work_permit_no ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Personal ID No (MOL)</label>
                        <div class="col-md-3">
                            <input type="text" name="personal_id_no" class="form-control" value="<?= $employee->personal_id_no ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Labor Card Issue Date</label>
                        <div class="col-md-3">
                            <input type="date" name="labor_issue_date" class="form-control" value="<?= $employee->labor_issue_date ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Labor Card Expiry Date</label>
                        <div class="col-md-3">
                            <input type="date" name="labor_expiry_date" class="form-control" value="<?= $employee->labor_expiry_date ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Upload Labor Card</label>
                        <div class="col-md-3">
                            <?php if ($employee->labor_card_image): ?>
                                <img src="<?= base_url('public/employee//' . $employee->labor_card_image) ?>" width="80" class="mb-2"><br>
                                <input type="hidden" name="old_labor_card_image" value="<?= $employee->labor_card_image ?>">
                            <?php endif; ?>
                            <input type="file" name="labor_card_image" accept=".pdf,.png,.jpeg,.jpg" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h4>Emirates ID Details</h4>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Emirates ID Number</label>
                        <div class="col-md-3">
                            <input type="text" name="eid_number" class="form-control" value="<?= $employee->eid_number ?>">
                        </div>

                        <label class="col-md-2 col-form-label">EID Issue Date</label>
                        <div class="col-md-3">
                            <input type="date" name="eid_issue_date" class="form-control" value="<?= $employee->eid_issue_date ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">EID Expiry Date</label>
                        <div class="col-md-3">
                            <input type="date" name="eid_expiry_date" class="form-control" value="<?= $employee->eid_expiry_date ?>">
                        </div>

                        <label class="col-md-2 col-form-label">Upload EID</label>
                        <div class="col-md-3">
                            <?php if ($employee->eid_image): ?>
                                <img src="<?= base_url('public/employee/' . $employee->eid_image) ?>" width="80" class="mb-2"><br>
                                <input type="hidden" name="old_eid_image" value="<?= $employee->eid_image ?>">
                            <?php endif; ?>
                            <input type="file" name="eid_image" accept=".pdf,.png,.jpeg,.jpg" class="form-control">
                        </div>
                    </div>
                    

                    <hr>
                    <h4>Salary Details</h4>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Salary Withdrawing Via</label>
                        <div class="col-md-3">
                            <select name="salary_mode" class="form-control">
                                <option value="">Select</option>
                                <option value="Bank" <?= ($employee->salary_mode == 'Bank') ? 'selected' : '' ?>>Bank</option>
                                <option value="Cash" <?= ($employee->salary_mode == 'Cash') ? 'selected' : '' ?>>Cash</option>
                            </select>
                        </div>

                        <label class="col-md-2 col-form-label">Card Number</label>
                        <div class="col-md-3">
                            <input type="text" name="card_number" class="form-control" value="<?= $employee->card_number ?>">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <a href="<?= base_url('index.php/Company/list_employee') ?>" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>
