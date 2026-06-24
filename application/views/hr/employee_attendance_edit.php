<div class="x_panel">
    <div class="x_title">
        <h2>Edit Employee Attendance</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" action="<?= base_url('index.php/Hr/update_emp_attendance') ?>" autocomplete="off">

            <!-- Employee Name -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Employee Name:</label>
                <div class="col-sm-5">
                    <select name="employee_id" id="employee_id" class="form-control select2" required>
                        <option value="">Select Employee</option>
                        <?php foreach($records as $emp): ?>
                            <option value="<?= $emp->employee_id ?>"
                                <?= ($emp->employee_id == $record1->employee_id) ? 'selected' : '' ?>>
                                <?= $emp->user_code . ' - ' . $emp->employee_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Date -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Date:</label>
                <div class="col-sm-5">
                    <input type="date" name="attendance_date" id="attendance_date" class="form-control form-control-sm"
                           value="<?= date('Y-m-d', strtotime($record1->Attendance_date)) ?>" required>
                </div>
            </div>

            <!-- Attendance -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Attendance <span class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select name="attendance" id="attendance" class="form-control" required onchange="showFields()">
                        <option value="">Select</option>
                        <option value="present" <?= ($record1->attendence == 'present') ? 'selected' : '' ?>>Present</option>
                        <option value="absent" <?= ($record1->attendence == 'absent') ? 'selected' : '' ?>>Absent</option>
                        <option value="half_day" <?= ($record1->attendence == 'half_day') ? 'selected' : '' ?>>Half Day</option>

                    </select>
                </div>
            </div>

            <!-- In/Out Time -->
            <div id="inOutTimeFields" style="display: none;">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">In Time:</label>
                    <div class="col-sm-3">
                       <input type="time" name="in_time" id="in_time" class="form-control form-control-sm"
       value="<?= !empty($record1->in_time) ? date('H:i', strtotime($record1->in_time)) : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Out Time:</label>
                    <div class="col-sm-3">
                      <input type="time" name="out_time" id="out_time" class="form-control form-control-sm"
       value="<?= !empty($record1->out_time) ? date('H:i', strtotime($record1->out_time)) : '' ?>">
                    </div>
                </div>
            </div>

            <!-- Remark -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Remark:</label>
                <div class="col-sm-5">
                    <textarea name="remark" id="remark" rows="2" class="form-control form-control-sm"
                              placeholder="Enter remark"><?= $record1->remark ?></textarea>
                </div>
            </div>

            <input type="hidden" name="emp_aId" value="<?= $record1->emp_aId ?>">
                        <input type="hidden" name="employee_id_hidden" value="<?= $record1->employee_id ?>">


            <!-- Submit -->
            <div class="form-group row">
                <div class="col-sm-5 offset-sm-3">
                    <button type="submit" id="add" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#employee_id').select2({ placeholder: "Select Employee", width: '100%' });

    // Show/hide In/Out time fields on page load
    showFields();

    // Client-side validation before submission
    $('#main').on('submit', function(e){
        let dateField = $('#attendance_date');
        let dateVal = dateField.val();
        let today = new Date().toISOString().split('T')[0];

        // Clear previous error
        dateField.next('.text-danger').remove();

        if (!dateVal) {
            e.preventDefault();
            $('<span class="text-danger">Please select a valid date.</span>').insertAfter(dateField);
            return false;
        }

        if (dateVal > today) {
            e.preventDefault();
            $('<span class="text-danger">Future dates are not allowed.</span>').insertAfter(dateField);
            return false;
        }
    });
});

function showFields() {
    var attendance = $('#attendance').val();
    if(attendance === 'present'){
        $('#inOutTimeFields').show();
    } else {
        $('#inOutTimeFields').hide();
    }
}
</script>