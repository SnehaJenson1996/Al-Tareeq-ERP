<div class="x_panel">
  <div class="x_title">
    <h2>Employee Attendance Entry</h2>
    <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
    </ul>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Hr/add_emp_attendance_data" autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-label-left">

      <!-- Employee -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name <span style="color:red">*</span></label>
        <div class="col-md-5 col-sm-6 col-xs-12">
          <select tabindex="1" class="form-control select2" id="employee_id" name="employee_id" required>
            <option value="">Select</option>
            <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
          </select>
        </div>
      </div>

      <!-- Date -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Date</label>
        <div class="col-md-5 col-sm-6 col-xs-12">

         <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="Attendance_date" name="Attendance_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>

       
          </div>
        </div>
      </div>

      <!-- Attendance -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Attendance <span style="color:red">*</span></label>
        <div class="col-md-5 col-sm-6 col-xs-12">
          <select tabindex="3" class="form-control" id="attendance" name="attendance" required onchange="showFields()">
            <option value="">Select</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="half_day">Half Day</option>    
          </select>
        </div>
      </div>

      <!-- In/Out Time -->
      <div id="inOutTimeFields" style="display: none;">
        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">In Time</label>
          <div class="col-md-3 col-sm-6 col-xs-12">
            <input type="time" id="in_time" name="in_time" tabindex="4" class="form-control" value="<?php echo date('H:i'); ?>" onblur="calculateTotalDuration(0)">
          </div>
        </div>

        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Out Time</label>
          <div class="col-md-3 col-sm-6 col-xs-12">
            <input type="time" id="out_time" name="out_time" tabindex="5" class="form-control" value="<?php echo date('H:i'); ?>" onblur="calculateTotalDuration(0)">
          </div>
        </div>
      </div>

      <!-- Remark -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Remark</label>
        <div class="col-md-5 col-sm-6 col-xs-12">
          <textarea id="remark" name="remark" rows="2" placeholder="Remark" tabindex="6" class="form-control"></textarea>
        </div>
      </div>

      <!-- Submit -->
      <div class="ln_solid"></div>
      <div class="form-group row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <button type="submit" id="add" tabindex="7" class="btn btn-success">Submit</button>
        </div>
      </div>

    </form>
  </div>
</div>


<script>
    function showFields() {
        var attendance = document.getElementById("attendance").value;
        var inOutTimeFields = document.getElementById("inOutTimeFields");
        if (attendance === "present" || attendance === "half_day") {
        inOutTimeFields.style.display = "block";
    } else {
            inOutTimeFields.style.display = "none";
        }
    }
    $(document).ready(function() {
 $('#employee_id').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%' // ensures it fits the Bootstrap column
    });
    });

    // function calculateTotalDuration(index) {
    //     var inTime = document.getElementById('in_time' + index).valueAsDate;
    //     var outTime = document.getElementById('out_time' + index).valueAsDate;

    //     if (inTime && outTime) {
    //         var totalTime = new Date(outTime - inTime);
    //         var hours = totalTime.getUTCHours();
    //         var minutes = totalTime.getUTCMinutes();

    //         // Format the total time
    //         var formattedTotalTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;

    //         // Set the value to the Total Time field
    //         document.getElementById('total_time' + index).value = formattedTotalTime;
    //     }
    // }
    
document.getElementById("main").addEventListener("submit", function(e) {
    let attendanceDate = document.getElementById("Attendance_date").value;
    let today = new Date().toISOString().split('T')[0]; // YYYY-MM-DD

    // Clear previous errors
    document.querySelectorAll('.text-danger').forEach(el => el.innerHTML = '');

    let valid = true;

    // Check if date is empty
    if (!attendanceDate) {
        showError('Attendance_date', 'Please select a date.');
        valid = false;
    }

    // Prevent future dates
    if (attendanceDate > today) {
        showError('Attendance_date', 'Future dates are not allowed.');
        valid = false;
    }

    // Attendance selection validation
    let attendance = document.getElementById("attendance").value;
    if (!attendance) {
        showError('attendance', 'Please select attendance.');
        valid = false;
    }

    // In/Out time validation for present
    if (attendance === 'present') {
        let inTime = document.getElementById("in_time").value;
        let outTime = document.getElementById("out_time").value;

        if (!inTime || !outTime) {
            showError('in_time', 'Please enter both In Time and Out Time.');
            valid = false;
        } else if (inTime >= outTime) {
            showError('out_time', 'Out Time must be after In Time.');
            valid = false;
        }
    }

    if (!valid) {
        e.preventDefault(); // Prevent form submission
    }
});

function showError(fieldId, message) {
    let field = document.getElementById(fieldId);
    let errorLabel = document.createElement('label');
    errorLabel.className = 'text-danger';
    errorLabel.innerText = message;
    if (field.nextElementSibling) {
        field.nextElementSibling.remove(); // Remove old message
    }
    field.parentNode.appendChild(errorLabel);
}


</script>