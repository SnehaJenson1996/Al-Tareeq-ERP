<style>
    /* --- Gentellela Form Style --- */
    .x_panel {
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e5e5e5;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
    }

    .x_title h2 {
        font-size: 16px;
        font-weight: 600;
        color: #007bff;
    }

    label.col-form-label {
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .form-control-sm,
    .form-select {
        font-size: 13px !important;
        border-radius: 6px !important;
        height: 32px !important;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        padding: 6px 18px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .text-danger {
        color: red !important;
    }

    .select2Width {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 240px !important;
        min-width: 240px !important;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Add Allowance / Deduction</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/add_allowances_data" autocomplete="off" enctype="multipart/form-data">

            <!-- Allowance Type -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Allowance Type <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select tabindex="1" class="form-control" id="allowance_type" name="allowance_type" required>
                        <option value="">Select</option>
                        <option value="A">Allowances</option>
                        <option value="D">Deductions</option>
                    </select>
                </div>
            </div>

            <!-- Allowance Name -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Allowance Name <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control form-control-sm" id="allowance_name" name="allowance_name"
                           onblur="check_dept_exist();" placeholder="Enter Allowance Name" tabindex="2" required>
                    <label id="dept_exits" class="text-danger"></label>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-5">
                   <button type="submit" id="add" tabindex="3" class="btn btn-primary" disabled>
    <i class="fa fa-save"></i> Submit
</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
var isSubmitting = false;  // Flag to prevent multiple submits
var isDuplicate = false;   // Flag to track if duplicate exists

function check_dept_exist() {
    var atype = $('#allowance_type').val();
    var aname = $('#allowance_name').val().trim();

    // Basic validation
    if (atype === '' || aname === '') {
        $('#add').prop('disabled', true);
        isDuplicate = false;
        $('#dept_exits').html('');
        return;
    }

    // AJAX call to check duplicate
    $.ajax({
        url: "<?php echo site_url('Ajax/check_duplicate_exist2'); ?>",
        type: 'POST',
        data: {
            table_name: 'allowance_master',
            column_name1: 'allowance_type',
            post_id1: atype,
            column_name2: 'allowance_name',
            post_id2: aname
        },
        async: false, // synchronous to ensure flag is updated before submit
        success: function(msg) {
            if (msg != 0) {
                $('#dept_exits').html("<span class='text-danger'>Name already exists</span>");
                $('#add').prop('disabled', true);
                isDuplicate = true;
            } else {
                $('#dept_exits').html("<span style='color:green'>Name available</span>");
                $('#add').prop('disabled', false);
                isDuplicate = false;
            }
        },
        error: function() {
            $('#dept_exits').html("<span class='text-danger'>Error checking name</span>");
            $('#add').prop('disabled', true);
            isDuplicate = true;
        }
    });
}

// Trigger duplicate check on input change
$('#allowance_type').on('change', check_dept_exist);
$('#allowance_name').on('keyup blur', check_dept_exist);

// Prevent multiple submissions and stop submit if duplicate exists
$('#main').on('submit', function(e) {
    if (isSubmitting) {
        e.preventDefault(); // block second click
        return false;
    }

    if (isDuplicate) {
        e.preventDefault(); // stop submission if duplicate exists
        alert('Cannot submit: Name already exists!');
        return false;
    }

    isSubmitting = true;

    // Disable submit button immediately
    $('#add').prop('disabled', true);
    $('#add').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
});
</script>
