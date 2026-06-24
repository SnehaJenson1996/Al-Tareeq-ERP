<style>
    .form-control-sm, .form-select {
        font-size: 13px !important;
    }

    label {
        font-weight: 500;
        font-size: 13px;
    }

    .input-group-addon {
        background: #f7f7f7;
        border: 1px solid #ced4da;
        border-left: 0;
        display: flex;
        align-items: center;
        padding: 0 10px;
    }

    .bg-soft-gray {
        background-color: #f9f9f9 !important;
    }

    textarea.form-control-sm {
        resize: none;
    }

    .btn-primary {
        font-size: 13px;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Employee Passport Release Entry</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form onsubmit="return check_selected_age();" id="main" 
              method="post" 
              action="<?php echo base_url() . 'index.php/Hr/add_emp_passport_release'; ?>" 
              autocomplete="off" enctype="multipart/form-data">

            <!-- Employee Selection -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Select Employee: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <select class="form-select form-control select2" id="user_id" name="user_id" required onchange="get_user_info()">
                        <option value="">Select</option>
                        <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <!-- Passport Dates -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Passport Issue Date:</label>
                <div class="col-md-3">
                    <input type="date" class="form-control form-control-sm bg-soft-gray" id="issue_date" 
                           name="issue_date" value="<?php echo date('d-m-Y') ?>" >
                </div>

                <label class="col-md-2 col-form-label">Passport Expiry Date:</label>
                <div class="col-md-3">
                    <input type="date" name="exp_date" id="exp_date" 
                           class="form-control form-control-sm bg-soft-gray" value="<?php echo date('d-m-Y') ?>" >
                </div>
            </div>

            <!-- Employee Code + Passport No -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Employee Number:</label>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm bg-soft-gray" id="user_code" name="user_code" readonly>
                </div>

                <label class="col-md-2 col-form-label">Passport No:</label>
                <div class="col-md-3">
                    <input type="text" name="doc_no" id="doc_no" class="form-control form-control-sm bg-soft-gray" readonly>
                </div>
            </div>

            <!-- Location + Release Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Passport Keeping Location: <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <select class="form-select form-control" id="location" name="location" required>
                        <option value="">Select</option>
                        <option value="Company">Company</option>
                        <option value="Their Own">Their Own</option>
                    </select>
                </div>

                <label class="col-md-2 col-form-label">Passport Release Date: <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <div class="input-group date">
                        <input type="date" class="form-control form-control-sm" id="outdate" 
                               name="outdate" value="<?php echo date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>


            <!-- Return Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Return Date: <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <div class="input-group date">
                        <input type="date" name="indate" id="indate" class="form-control form-control-sm" 
                               value="<?php echo date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Purpose -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Passport Release Purpose: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <textarea id="reason" name="reason" rows="2" 
                              class="form-control form-control-sm" 
                              placeholder="Passport release purpose" required></textarea>
                </div>
            </div>

            <!-- Remark -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Remarks:</label>
                <div class="col-md-5">
                    <textarea id="remark" name="remark" rows="1" 
                              class="form-control form-control-sm" 
                              placeholder="Additional remarks (optional)"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label"></label>
                <div class="col-md-5">
                    <button type="submit" id="add" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- <script type-"text/javascript">
    function get_user_info() {
        alert(data);
    }
    </script> -->
<script>
    function get_user_info() {
        var user_id = document.getElementById("user_id").value;
        //  alert(user_id);
        if (user_id != '') {
           $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_user_passport_info",
                    data: { user_id: user_id },
                    dataType: "json",
                    success: function(msg) {
                        $("#issue_date").val(msg.issue_date);
                        $("#exp_date").val(msg.expiry_date);
                        $("#doc_no").val(msg.passport_number);
                        $("#location").val(msg.posession); 
                        $("#user_code").val(msg.user_code);
                    }
                });

        } else {
            document.getElementById("issue_date").value = '';
            document.getElementById("exp_date").value = '';
            document.getElementById("doc_no").value = '';
            document.getElementById("location").value = '';


        }
    }

    $(document).ready(function () {
    $('.select2').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%'
    });
});
</script>