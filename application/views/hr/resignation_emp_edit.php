<div class="x_panel">
  <div class="x_title">
    <h2>Employee Resignation</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
   <?php $row = $record; ?>

    <form id="main" method="post" 
          action="<?php echo base_url('index.php/Hr/update_emp_resignation'); ?>" 
          enctype="multipart/form-data" 
          autocomplete="off" 
          onsubmit="return check_duplicate_exist();">

      <div class="form-horizontal form-label-left">

        <!-- Employee Name -->
        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="employee_id" id="employee_id" class="form-control select2" required>
      <option value="">Select Employee</option>

      <?php foreach($user_records as $emp): ?>
       <option value="<?= $emp->employee_id ?>"
                                <?= ($emp->employee_id == $row->employee_id) ? 'selected' : '' ?>>
                                <?= $emp->user_code . ' - ' . $emp->employee_name ?>
                            </option>
      <?php endforeach; ?>

    </select>
          </div>
        </div>

        <!-- Resignation Code -->
        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Resignation Code:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="ra_code" class="form-control" value="<?php echo $row->resign_code; ?>" readonly>
          </div>
        </div>

       <!-- Resignation Date -->
<div class="form-group row">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Resignation Date:</label>

    <div class="col-md-6 col-sm-6 col-xs-12">

            <input type="date"
                   class="form-control"
                   name="resignation_date"
                   value="<?= $row->resignation_date ?? ''; ?>">


        </div>
    </div>


<!-- Last Working Date -->
<div class="form-group row">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Effective Last Working Date:</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        

            <input type="date"
                   class="form-control"
                   name="last_working_date"
                   value="<?= $row->last_working_date ?? ''; ?>">

    </div>
</div>

        <!-- Notice Period -->
        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Notice Period (Days):</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" class="form-control" name="notice_days" value="<?php echo $row->notice_days; ?>">
          </div>
        </div>

        <!-- Reason -->
        <div class="form-group row">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Resignation Reason:</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <textarea class="form-control" name="reason" rows="3"><?php echo $row->reason; ?></textarea>
          </div>
        </div>

        <!-- Documents Upload -->
       <div class="form-group row">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">
        Upload Documents <small>(jpeg, jpg, png, doc, pdf)</small>:
    </label>

    <div class="col-md-8 col-sm-8 col-xs-12">

        <table class="table table-bordered table-hover" id="tab_logic">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File</th>
                    <th>Document Type</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <!-- Existing files -->
                <?php if (!empty($file_records)) {
                    $i = 0;
                    foreach ($file_records as $k) {
                        $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>

                            <td>
                                <a href="<?php echo base_url('public/uploaded_documents/' . $k->document_path); ?>" download>
                                    View File
                                </a>
                            </td>

                            <td><?php echo $k->document_name; ?></td>

                            <td>
    <a href="javascript:void(0);" 
       onclick="delete_doc(<?php echo $k->doc_id; ?>)" 
       class="btn btn-danger btn-sm">
        <i class="fa fa-trash"></i>
    </a>
</td>
                           
                        </tr>
                <?php }
                } ?>

                <!-- New file row -->
                <tr id="addr0">
                    <td>1</td>

                    <td>
                        <input class="form-control" type="file" name="documents_res[]" accept=".jpeg,.jpg,.png,.doc,.pdf">
                    </td>

                    <td>
                        <select class="form-control" name="document_types[]">
                            <option value="">Select Type</option>
                            <option>Resignation Letter</option>
                            <option>Resignation Form</option>
                            <option>MOHRE Cancellation Paper</option>
                            <option>Clearance Paper</option>
                            <option>Final Settlement Letter</option>
                            <option>Labor Cancellation</option>
                            <option>Visa Cancellation</option>
                            <option>Other</option>
                        </select>
                    </td>

                    <td>
                        <button type="button" id="add_row" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                        </button>

                        <button type="button" id="delete_row" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr id="addr1"></tr>

            </tbody>
        </table>

    </div>
</div>
        <!-- Submit -->
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <input type="hidden" name="id" value="<?php echo $row->resig_id; ?>">
            <button type="submit" class="btn btn-success">Update Resignation</button>
            <a href="<?php echo base_url('index.php/Hr/view_emp_resignation_list'); ?>" class="btn btn-secondary">Cancel</a>
          </div>
        </div>

      </div>
    </form>
    
  </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1;
        $("#add_row").click(function() {
            $('#addr' + i).html("<td>" + (i + 1) + "</td><td><div class='col-sm-8'><input class='form-control' id='documents" + i + "' name='documents[]' type='file'></div></td><td><div class='col-sm-10'><select class='form-select form-control-sm' name='document_types[]' id='document_types'><option value='' selected disabled>Please select document type</option><option value='Resignation Letter'>Resignation Letter</option><option value='Resignation Form'>Resignation Form</option><option value='MOHRE Cancellation Paper'>MOHRE Cancellation Paper</option><option value='Clearance Paper'>Clearance Paper</option><option value='Final Settlement Letter'>Final Settlement Letter</option><option value='Labor Cancellation'>Labor Cancellation</option><option value='Visa Cancellation'>Visa Cancellation</option><option value='Other'>Other</option></select></div></td><td></td>");
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

    function calculate_total_days() {
        var startDateStr = document.getElementById('start_date').value;
        var endDateStr = document.getElementById('end_date').value;

        // Parse start date and end date in d-m-Y format
        var startDateArr = startDateStr.split('-');
        var endDateArr = endDateStr.split('-');

        var startDate = new Date(startDateArr[2], startDateArr[1] - 1, startDateArr[0]);
        var endDate = new Date(endDateArr[2], endDateArr[1] - 1, endDateArr[0]);

        const time = Math.abs(endDate - startDate);

        const days = Math.ceil(time / (1000 * 60 * 60 * 24));

        document.getElementById("total_date").value = days;
    }

    // Call calculate_total_days() when there is a change in start_date or end_date fields
    document.getElementById('start_date').addEventListener('input', calculate_total_days);
    document.getElementById('end_date').addEventListener('input', calculate_total_days);

function delete_doc(doc_id) {

    if (confirm("Are you sure you want to delete this file?")) {

        $.ajax({
            url: "<?php echo base_url('index.php/Hr/delete_resignation_document'); ?>",
            type: "POST",
            data: { doc_id: doc_id },
            success: function (res) {
                location.reload();
            }
        });

    }
}
</script>