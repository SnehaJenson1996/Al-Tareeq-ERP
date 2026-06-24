<div class="x_panel">
    <div class="x_content">
    <form onsubmit="return check_duplicate_exist();" id="main" method="post"
          action="<?php echo base_url().'index.php/'; ?>Hr/add_joining_application_data"
          autocomplete="off" enctype="multipart/form-data">

      <!-- Employee Name -->
      <div class="form-group row">
        <label class="col-sm-3 control-label">Employee Name: <span class="text-danger">*</span></label>
        <div class="col-sm-5">
          <select class="form-control select2" id="employee_id" name="employee_id" required>
            <option value="">Select</option>
            <?php foreach ($user_records as $s) { ?>
              <option <?php if ($this->session->userdata('user_id') == $s->user_id) echo 'selected'; ?>
                      value="<?php echo $s->user_id; ?>">
                <?php echo $s->user_name; ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Joining Date -->
      <div class="form-group row">
        <label class="col-sm-3 control-label">Joining Date:</label>
        <div class="col-sm-5">

        <div class="input-group date">
                    <input type="date" class="form-control form-control-sm" id="joining_date" name="joining_date" value="<?php echo date('Y-m-d'); ?>" tabindex="2">
                    <div class="input-group-addon"></div>


         
        </div>
      </div>
       </div>

      <!-- Joining Type -->
      <div class="form-group row">
        <label class="col-sm-3 control-label">Joining Type: <span class="text-danger">*</span></label>
        <div class="col-sm-5">
          <select class="form-control" name="joining_type" id="joining_type" required>
            <option value="">Please select type</option>
            <option value="Resuming After Leave">Resuming After Leave</option>
            <option value="Observation Period">Observation Period</option>
            <option value="Newly Join">Newly Join</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      <!-- Offer Letter -->
      <div class="form-group row">
        <label class="col-sm-3 control-label">Receive Offer Letter? <span class="text-danger">*</span></label>
        <div class="col-sm-5">
          <select class="form-control" name="offer_letter" id="offer_letter" required>
            <option value="">Please select type</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>

      <!-- Remark -->
      <div class="form-group row">
        <label class="col-sm-3 control-label">Remark:</label>
        <div class="col-sm-5">
          <textarea id="remark" name="remark" rows="2" class="form-control"
                    placeholder="Enter remark here..."></textarea>
        </div>
      </div>

      <!-- Submit -->
      <div class="form-group row">
        <div class="col-sm-offset-3 col-sm-5">
          <button type="submit" id="add" class="btn btn-primary">
            <i class="fa fa-save"></i> Submit
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1;
        $("#add_row").click(function() {
            $('#addr' + i).html("<td>" + (i + 1) + "</td><td><div class='col-sm-6'><input class='form-control' id='documents" + i + "' name='documents[]' type='file'></div></td><td></td>");
            $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
            i++;
        });

        $("#delete_row").click(function() {
            if (i > 1) {
                $("#addr" + (i - 1)).html('');
                i--;
            }
        });

    });
    $("#tab_logic").on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    function calculate_total_days() {}

    document.getElementById("main").addEventListener("submit", function (e) {

    var btn = document.getElementById("add");

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    // Disable immediately
    btn.disabled = true;
    btn.innerHTML = "Processing...";

});

$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%'
    });
});
</script>