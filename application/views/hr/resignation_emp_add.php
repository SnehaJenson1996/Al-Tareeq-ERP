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

    .table th, .table td {
        vertical-align: middle !important;
        font-size: 13px;
    }

    .btn-sm {
        font-size: 12px !important;
        padding: 3px 8px;
    }

    .bg-blue {
        background-color: #007bff !important;
        color: #fff !important;
    }

    .bg-blue:hover {
        background-color: #0056b3 !important;
        color: #fff !important;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Employee Resignation Entry</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form  id="main" 
              method="post" 
              action="<?php echo base_url() . 'index.php/Hr/add_emp_resignation_data'; ?>" 
              autocomplete="off" enctype="multipart/form-data">

            <!-- Employee Name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Employee Name: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <select class="form-select form-control select2" 
                            id="employee_id" name="employee_id" required>
                        <option value="">Select</option>
                        <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <!-- Resignation Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Resignation Date: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <div class="input-group date datepicker1">
                        <!-- <input type="text" class="form-control form-control-sm" id="resignation_date" 
                               name="resignation_date" value="<?php echo date('d-m-Y'); ?>" required>
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div> -->


                          <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="resignation_date" name="resignation_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>
                </div>
                  </div>
                  


                </div>
            </div>

            <!-- Last Working Date -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Effective Last Working Date: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <!-- <div class="input-group date datepicker1"> -->
                        <!-- <input type="text" class="form-control form-control-sm" id="last_working_date" 
                               name="last_working_date" value="<?php echo date('d-m-Y'); ?>" required>
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div> -->

 <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="last_working_date" name="last_working_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>
                </div>

                    </div>
                </div>
            </div>

            <!-- Notice Period -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Total Notice Period Days: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" id="notice_days" name="notice_days" required>
                </div>
            </div>

            <!-- Reason -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Resignation Reasons: <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <textarea id="reason" name="reason" rows="2" class="form-control form-control-sm" 
                              placeholder="Enter resignation reason" required></textarea>
                </div>
            </div>

            <!-- Document Upload -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Upload Documents: <small>(jpeg, jpg, png, doc, pdf)</small></label>
                <div class="col-md-8">
                    <table class="table table-bordered table-hover" id="tab_logic">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:40px;">#</th>
                                <th style="width:220px;">File</th>
                                <th>Document Type</th>
                                <th style="width:100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="addr0">
                                <td>1</td>
                                <td>
                                    <input class="form-control form-control-sm" id="documents_res" 
                                           name="documents_res[]" type="file" accept=".jpeg,.jpg,.png,.doc,.pdf">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm" name="document_types[]" id="document_types">
                                        <option value="" selected disabled>Select document type</option>
                                        <option value="Resignation Letter">Resignation Letter</option>
                                        <option value="Resignation Form">Resignation Form</option>
                                        <option value="MOHRE Cancellation Paper">MOHRE Cancellation Paper</option>
                                        <option value="Clearance Paper">Clearance Paper</option>
                                        <option value="Final Settlement Letter">Final Settlement Letter</option>
                                        <option value="Labor Cancellation">Labor Cancellation</option>
                                        <option value="Visa Cancellation">Visa Cancellation</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a id="add_row" title="Add" class="btn btn-sm bg-blue"><span class="fa fa-plus"></span></a>
                                    <a id="delete_row" title="Delete" class="btn btn-sm bg-blue"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                            <tr id="addr1"></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit -->
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    var i = 1;

    $("#add_row").click(function() {
        let newRow = `
            <tr id="addr${i}">
                <td>${i + 1}</td>
                <td>
                    <input class="form-control form-control-sm" 
                           id="documents_res${i}" 
                           name="documents_res[]" 
                           type="file">
                </td>
                <td>
                    <select class="form-control form-control-sm" 
                            name="document_types[]" 
                            id="document_types${i}">
                        <option value="" selected disabled>Please select document type</option>
                        <option value="Resignation Letter">Resignation Letter</option>
                        <option value="Resignation Form">Resignation Form</option>
                        <option value="MOHRE Cancellation Paper">MOHRE Cancellation Paper</option>
                        <option value="Clearance Paper">Clearance Paper</option>
                        <option value="Final Settlement Letter">Final Settlement Letter</option>
                        <option value="Labor Cancellation">Labor Cancellation</option>
                        <option value="Visa Cancellation">Visa Cancellation</option>
                        <option value="Other">Other</option>
                    </select>
                </td>
                <td></td>
            </tr>
        `;

        $('#tab_logic').append(newRow);
        i++;
    });

    $("#delete_row").click(function() {
        if (i > 1) {
            $("#addr" + (i - 1)).remove();
            i--;
        }
    });
});

    // function calculate_total_days() {
    //     var startDateStr = document.getElementById('start_date').value;
    //     var endDateStr = document.getElementById('end_date').value;

    //     // Parse start date and end date in d-m-Y format
    //     var startDateArr = startDateStr.split('-');
    //     var endDateArr = endDateStr.split('-');

    //     var startDate = new Date(startDateArr[2], startDateArr[1] - 1, startDateArr[0]);
    //     var endDate = new Date(endDateArr[2], endDateArr[1] - 1, endDateArr[0]);

    //     const time = Math.abs(endDate - startDate);

    //     const days = Math.ceil(time / (1000 * 60 * 60 * 24));

    //     document.getElementById("total_date").value = days;
    // }

    // // Call calculate_total_days() when there is a change in start_date or end_date fields
    // document.getElementById('start_date').addEventListener('input', calculate_total_days);
    // document.getElementById('end_date').addEventListener('input', calculate_total_days);


$(document).ready(function () {

   $("#main").on("submit", function (e) {

    let resignation = $("#resignation_date").val();
    let lastWorking = $("#last_working_date").val();

    console.log("Resignation:", resignation);
    console.log("Last Working:", lastWorking);

    if (!resignation || !lastWorking) return true;

    // FIX: safe parsing (IMPORTANT)
    let resDate = new Date(resignation + "T00:00:00");
    let lastDate = new Date(lastWorking + "T00:00:00");

    console.log("Parsed Resignation:", resDate);
    console.log("Parsed Last:", lastDate);

    if (isNaN(resDate) || isNaN(lastDate)) {
        alert("Invalid date format");
        e.preventDefault();
        return false;
    }

    if (lastDate.getTime() < resDate.getTime()) {
        alert("❌ Effective Last Working Date cannot be earlier than Resignation Date.");
        e.preventDefault();
        return false;
    }

    return true;
});

});
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%'
    });
});

</script>