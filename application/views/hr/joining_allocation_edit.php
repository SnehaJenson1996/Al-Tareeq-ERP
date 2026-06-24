<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_content">

        <?php $row = $record; ?> <!-- single object -->

        <form onsubmit="return check_duplicate_exist();" id="main" method="post"
              action="<?php echo base_url('index.php/Hr/update_joining_application/'.$row->jid); ?>"
              autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-label-left">

          <!-- Employee Name -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Employee Name:</label>
            <div class="col-md-6 col-sm-6">
              <input type='text' class="form-control form-control-sm bg-soft-gray"
                     value="<?php echo $row->user_name; ?>" readonly />
              <input type='hidden' name="employee_id_hidden"
                     value="<?php echo $row->employee_id; ?>" />
            </div>
          </div>

          <!-- Joining Code -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Joining Code:</label>
            <div class="col-md-6 col-sm-6">
              <input type="text" name="ja_code" id="ja_code" class="form-control bg-light"
                     value="<?php echo $row->joining_code; ?>" readonly tabindex="2">
            </div>
          </div>

          
          
          <!-- Joining Date -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Joining Date:</label>
            <div class="col-md-6 col-sm-6">
                <input type="date" class="form-control" id="joining_date" name="joining_date"
                       value= "<?= $row->joining_date ?? ''; ?>">
            </div>
          </div>

          <!-- Joining Type -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Joining Type:</label>
            <div class="col-md-6 col-sm-6">
              <select class="form-control" name="joining_type" id="joining_type" tabindex="4">
                <option value="">Please select type</option>
                <option value="Resuming After Leave" <?php if ($row->joining_type == 'Resuming After Leave') echo 'selected'; ?>>Resuming After Leave</option>
                <option value="Observation Period" <?php if ($row->joining_type == 'Observation Period') echo 'selected'; ?>>Observation Period</option>
                <option value="Newly Join" <?php if ($row->joining_type == 'Newly Join') echo 'selected'; ?>>Newly Join</option>
                <option value="Other" <?php if ($row->joining_type == 'Other') echo 'selected'; ?>>Other</option>
              </select>
            </div>
          </div>

          <!-- Offer Letter -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Received Offer Letter?</label>
            <div class="col-md-6 col-sm-6">
              <select class="form-control" name="offer_letter" id="offer_letter" tabindex="5">
                <option value="">Please select</option>
                <option value="1" <?php if ($row->offer_letter == '1') echo 'selected'; ?>>Yes</option>
                <option value="0" <?php if ($row->offer_letter == '0') echo 'selected'; ?>>No</option>
              </select>
            </div>
          </div>

          <!-- Remark -->
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align">Remark:</label>
            <div class="col-md-6 col-sm-6">
              <textarea id="remark" name="remark" rows="3" class="form-control"
                        placeholder="Enter remark" tabindex="6"><?php echo $row->remark; ?></textarea>
            </div>
          </div>

          <div class="ln_solid"></div>

          <!-- Submit Button -->
          <div class="item form-group">
            <div class="col-md-6 col-sm-6 offset-md-3">
              <input type="hidden" name="id" value="<?php echo $row->jid; ?>">
              <button type="submit" class="btn btn-success" tabindex="7">
                <i class="fa fa-save"></i> Update
              </button>
              <button type="reset" class="btn btn-secondary">
                <i class="fa fa-refresh"></i> Reset
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
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

    $("#tab_logic").on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
});

function calculate_total_days() {}
</script>