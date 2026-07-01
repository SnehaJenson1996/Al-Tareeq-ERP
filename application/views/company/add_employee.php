<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Employee Registration</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?= base_url('index.php/Company/save_employee/') ?>" method="post" enctype="multipart/form-data" autocomplete='off' id="employee">

                        <!-- Basic Details -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                Employee Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-3">
                                <input type="text" name="employee_name" class="form-control"  required>
                            </div>

                            <label class="col-md-2 col-form-label">Branch <span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <select name="branch_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($branch_list as $branch): ?>
                                        <option value="<?= $branch->branch_id ?>" >
                                            <?= $branch->branch_name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Mobile Number<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="mobile" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label" >Gender <span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <select name="gender" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="Male" >Male</option>
                                    <option value="Female" >Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" required>Birth Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="birth_date" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label" required>Nationality <span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="nationality" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" required>Date of Joining <span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="joining_date" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">Upload Photo</label>
                            <div class="col-md-3">                                
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
                                        <option value="<?= $dept->dept_id ?>">
                                            <?= $dept->dept_name ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <label class="col-md-2 col-form-label">
                                UID <span class="text-danger">*</span>
                            </label>

                            <div class="col-md-3">
                                <input type="text" name="uid_number" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Designation <span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <select name="designation_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($designation_list as $d): ?>
                                        <option value="<?= $d->id ?>">
                                            <?= $d->designation_name ?>
                                        </option>
                                    <?php endforeach; ?>
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
                                    value="<?= $user_code ?>">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-form-label">
                                Digital Signature
                            </label>

                            <div class="col-md-3">
                                <input type="file"
                                    name="signature_file"
                                    accept=".png,.jpg,.jpeg"
                                    class="form-control">
                            </div>

                        </div>
                        
                        <hr>
                        <h4>Passport Details</h4>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name (as in Passport)<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="passport_name" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">Passport Number<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="passport_number" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Passport Issue Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="passport_issue_date" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">Passport Expiry Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="passport_expiry_date" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Passport Issue Place<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="passport_issue_place" class="form-control" >
                            </div>
                        </div>

                        <hr>
                        <h4>Labor Card Details</h4>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Work Permit No (MOL)<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="work_permit_no" class="form-control" ">
                            </div>

                            <label class="col-md-2 col-form-label">Personal ID No (MOL)<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="personal_id_no" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Labor Card Issue Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="labor_issue_date" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">Labor Card Expiry Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="labor_expiry_date" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Upload Labor Card<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                
                                <input type="file" name="labor_card_image" accept=".pdf,.png,.jpeg,.jpg" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h4>Emirates ID Details</h4>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Emirates ID Number<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="text" name="eid_number" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">EID Issue Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="eid_issue_date" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">EID Expiry Date<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                                <input type="date" name="eid_expiry_date" class="form-control" >
                            </div>

                            <label class="col-md-2 col-form-label">Upload EID<span class="text-danger">*</label></label>
                            <div class="col-md-3">
                               
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
                                    <option value="Bank" >Bank</option>
                                    <option value="Cash" >Cash</option>
                                </select>
                            </div>

                            <label class="col-md-2 col-form-label">Card Number</label>
                            <div class="col-md-3">
                                <input type="text" name="card_number" class="form-control">
                            </div>
                        </div>

                        <hr>
                      

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Software Access</label>
                            <div class="col-md-3">
                                <input type="checkbox" id="software_access" name="software_access" value="1">
                            </div>
                        </div>

                        <div id="user_access_block" style="display:none; margin-top:20px;">
                        <h4>User Access Details</h4>
                           

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Login ID (Company Email) <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="email" id="user_login" name="user_login" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Password</label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="password" id="user_password" name="user_password" class="form-control">
                                </div>
                            </div>
                     </div>

                        <div class="ln_solid"></div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <!-- <a href="<?= base_url('index.php/Company/list_employee') ?>" class="btn btn-secondary">Back</a> -->
                                <button type="button" id="saveBtn" class="btn btn-success">Save</button>
                            </div>
                        </div>

                    </form>

            </div>
        </div>
    </div>
</div>
<script>
   

    $(document).ready(function(){
    $('#software_access').on('change', function(){
        if($(this).is(':checked')){
            $('#user_access_block').slideDown();
        } else {
            $('#user_access_block').slideUp();
        }
    });
});
 $(document).ready(function() {
    $('#user_login').on('blur', function() {
        var user_login = $(this).val();

        if (user_login !== '') {
            $.ajax({
                url: '<?= base_url("index.php/Setup/check_user_login_duplicate") ?>', 
                type: 'POST',
                data: { user_login: user_login },
                success: function(response) {
                    
                    if (response==1) {
                        alert('This User Login already exists.');
                        $('#user_login').val('');
                    } 
                }
            });
        }
    });
});
$(document).ready(function () {

    //  Prevent Enter key from submitting form
    $('#employee').on('keydown', function (e) {
        if (e.key === "Enter" && e.target.tagName !== "TEXTAREA") {
            e.preventDefault();
            return false;
        }
    });

    //  UID field: Enter → move to Employee Code
    $('input[name="uid_number"]').on('keydown', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            $('#employee_code').focus();
            return false;
        }
    });

    //  Save button click (manual submit + prevent double click)
    $('#saveBtn').on('click', function () {

        var btn = $(this);

        // prevent double click
        if (btn.prop('disabled')) {
            return false;
        }

        // disable button + change text
        btn.prop('disabled', true).text('Processing...');

        // submit form manually
        $('#employee').submit();
    });

});
</script>
