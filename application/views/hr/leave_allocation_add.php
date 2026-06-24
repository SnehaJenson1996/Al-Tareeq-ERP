<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form onsubmit="return check_duplicate_exist();" id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/add_leave_application_data" autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-label-left">

                    <!-- Employee Name -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Employee Name:<span style="color:red;">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                           <select class="form-control select2" 
                            id="employee_id" name="employee_id" required>
                        <option value="">Select</option>
                        <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
                    </select>
                        </div>
                    </div>

                    <!-- Application Date -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Application date:</label>
            <div class="col-md-6 col-sm-6 ">
                <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="application_date" name="application_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>
                </div>
            </div>
        </div>

                    <!-- Leave Type -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Leave Type:<span style="color:red;">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select class="form-control" name="ltype_id" id="ltype_id" tabindex="3" required>
                                <option value="" selected disabled>Please select leave type</option>
                                <option value="Personal Leave">Personal Leave</option>
                                <option value="Annaul Leave">Annual Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Compensatory Leave">Compensatory Leave</option>
                                <option value="Sick/Casaul Leave">Sick/Casaul Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Leave From - To -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Leave From - To:<span style="color:red;">*</span></label>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo date('Y-m-d') ?>" tabindex="4" required onchange="calculate_total_days()">
                            <label id="leave_exists" style="color:red;"></label>
                        </div>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo date('Y-m-d') ?>" tabindex="5" required onchange="calculate_total_days()">
                        </div>
                    </div>

                    <!-- Total Days -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Total Days:</label>
                        <div class="col-md-2 col-sm-2 ">
                            <input type="text" class="form-control" id="total_date" name="total_date" readonly tabindex="6">
                        </div>
                    </div>

                    <!-- Contact & Address -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Contact & Address In Leave:</label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="outside_contact" name="outside_contact" rows="2" class="form-control" placeholder="Contact & Address Outside Country" tabindex="7"></textarea>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Reason:</label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="reason" name="reason" rows="2" class="form-control" placeholder="Specify reason for leave" tabindex="8"></textarea>
                        </div>
                    </div>

                    <!-- Last Joining Date -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Joining Date From Last Leave:<span style="color:red;">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="date" class="form-control" id="last_date" name="last_date" value="<?php echo date('Y-m-d'); ?>" tabindex="9" required>
                        </div>
                    </div>

                    <!-- Charge Handed To -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Charge Handed To:<span style="color:red;">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select class="form-control select2" id="replcement" name="replcement" tabindex="10" required>
                                <option value="">Select</option>
                                <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Documents -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Upload Documents:</label>
                        <div class="col-md-6 col-sm-6 ">
                            <table class="table table-bordered table-hover" id="tab_logic">
                                <tbody>
                                    <tr id="addr0">
                                        <td>1</td>
                                        <td>
<input class="form-control doc-file" name="documents[]" type="file">
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary add_row"><span class="fa fa-plus"></span></a>
<a class="btn btn-sm btn-danger delete_row"><span class="fa fa-trash"></span></a>
<a class="btn btn-sm btn-warning clear_file"><span class="fa fa-times"></span></a>

                                        </td>
                                    </tr>
                                    <tr id="addr1"></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <!-- Submit Button -->
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    let i = 1;

    // ADD ROW
    $(document).on('click', '.add_row', function () {
        let row = `
        <tr>
            <td>${i + 1}</td>
            <td>
                <input class="form-control doc-file" name="documents[]" type="file">
            </td>
            <td>
                <a class="btn btn-sm btn-primary add_row"><span class="fa fa-plus"></span></a>
                <a class="btn btn-sm btn-danger delete_row"><span class="fa fa-trash"></span></a>
                <a class="btn btn-sm btn-warning clear_file" style="display:none;">
                    <span class="fa fa-times"></span>
                </a>
            </td>
        </tr>`;
        $('#tab_logic tbody').append(row);
        i++;
    });

    // DELETE ROW
    $(document).on('click', '.delete_row', function () {
        if ($('#tab_logic tbody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });

    // CLEAR FILE
    $(document).on('click', '.clear_file', function () {
        $(this).closest('tr').find('.doc-file').val('');
        $(this).hide();
    });

    // SHOW CLEAR BUTTON ONLY WHEN FILE SELECTED
    $(document).on('change', '.doc-file', function () {
        $(this).closest('tr').find('.clear_file').show();
    });
});
    //this following function is calculate total days
   function calculate_total_days() {
        var startDateStr = document.getElementById('start_date').value;
        var endDateStr = document.getElementById('end_date').value;

        if (!startDateStr || !endDateStr) {
            document.getElementById("total_date").value = 0;
            return;
        }

        // Parse start date and end date in Y-m-d format
        var startDateArr = startDateStr.split('-');
        var endDateArr = endDateStr.split('-');

        var startDate = new Date(startDateArr[0], startDateArr[1] - 1, startDateArr[2]);
        var endDate = new Date(endDateArr[0], endDateArr[1] - 1, endDateArr[2]);

        const time = endDate - startDate;

        if (time < 0) {
            document.getElementById("total_date").value = 0; // invalid range
            return;
        }

        // Add 1 to include both start and end date
        const days = Math.floor(time / (1000 * 60 * 60 * 24)) + 1;

        document.getElementById("total_date").value = days;
    }

    //add a calender to hide privious date thi functionality
    var date = new Date();
    var tdate = date.getDate();
    var month = date.getMonth() + 1;

    if (tdate < 10) {
        tdate = '0' + tdate;
    }
    if (month < 10) {
        month = '0' + month;
    }

    var year = date.getUTCFullYear();
    var mindate = year + "-" + month + "-" + tdate;

   // document.getElementById("application_date").setAttribute('min', mindate);
    // document.getElementById("start_date").setAttribute('min', mindate);
    // document.getElementById("end_date").setAttribute('min', mindate);
    document.getElementById("last_date").setAttribute('min', mindate);
    console.log(mindate);

 window.onload = function() {
        calculate_total_days();
    };

   $(document).on('change', '.doc-file', function () {
    $(this).closest('tr').find('.clear_file').show();
});

$(document).ready(function () {
    $('.clear_file').hide();
});
    // function check_date_exist() {
    //     var startDate = $('#start_date').val();

    //     $.ajax({
    //         url: "<?php echo site_url('Ajax/check_exist_leave_application'); ?>",
    //         type: 'POST',
    //         data: {
    //             start_date: startDate
    //         },
    //         success: function(msg) {
    //             if (msg != 0) {
    //                 $('#leave_exists').text("Leave record already exists for the selected start date.");
    //             } else {
    //                 $('#leave_exists').text("");
    //             }
    //         }
    //     });
    // }

    $(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%'
    });
});
</script>



<!-- <script>
    function check_date_exist() {
        var empId = $('#employee_id').val();
        var appDate = $('#application_date').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var leaveType = $('#ltype_id').val();
        $.ajax({
            url: "<?php echo site_url('Ajax/check_duplicate_exist2'); ?>",
            type: 'POST',
            data: {
                table_name: 'employee_leave',
                column_name1: 'employee_id',
                post_id1: empId,
                column_name2: 'application_date',
                post_id2: appDate,
                column_name3: 'start_date',
                post_id3: startDate,
                column_name4: 'end_date',
                post_id4: endDate,
                column_name5: 'leave_type',
                post_id5: leaveType,
            },
            success: function(msg) {
                if (msg != 0) {
                    $('#leave_exits').html("leave already exits from date");
                    $('#start_date').val('');
                } else {
                    $('#leave_exits').html("");
                }
            }
        });
    }



    ///new 
    // function check_date_exist() {
    //     var empId = $('#employee_id').val();
    //     var appDate = $('#application_date').val();
    //     var startDate = $('#start_date').val();
    //     var endDate = $('#end_date').val();
    //     var leaveType = $('#ltype_id').val();

    //     // Convert date format to YYYY-MM-DD
    //     appDate = formatDate(appDate);
    //     startDate = formatDate(startDate);
    //     endDate = formatDate(endDate);

    //     $.ajax({
    //         url: "<?php echo site_url('Ajax/check_exist_leave_application'); ?>",
    //         type: 'POST',
    //         data: {
    //             table_name: 'employee_leave',
    //             column_name1: 'employee_id',
    //             post_id1: empId,
    //             column_name2: 'application_date',
    //             post_id2: appDate,
    //             column_name3: 'start_date',
    //             post_id3: startDate,
    //             column_name4: 'end_date',
    //             post_id4: endDate,
    //             column_name5: 'leave_type',
    //             post_id5: leaveType,
    //         },
    //         success: function(response) {
    //             if (response != 0) {
    //                 $('#leave_exits').html("Leave already exists from this date");
    //                 $('#start_date').val('');
    //             } else {
    //                 $('#leave_exits').html("");
    //                 // calculate_total_days(); // Recalculate total days after successful check
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error(xhr.responseText);
    //         }
    //     });
    // }

    // // Function to format date to YYYY-MM-DD
    // function formatDate(date) {
    //     var parts = date.split("-");
    //     retur n parts[2] + "-" + parts[1] + "-" + parts[0];
    // }
</script> -->