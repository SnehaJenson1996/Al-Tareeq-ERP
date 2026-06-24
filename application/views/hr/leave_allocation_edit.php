<?php $logged_user_id = $this->session->userdata('user_id'); ?>

<div class="card">
  <div class="card-body">

<?php $row = $leave; ?>

<!-- ====================== LEAVE APPLICATION FORM ====================== -->
<form onsubmit="return check_duplicate_exist();"
      id="leave_application_form"
      method="post"
      action="<?= base_url('index.php/Hr/update_leave_application'); ?>"
      autocomplete="off"
      enctype="multipart/form-data">

<!-- Employee -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Employee Name:</label>
  <div class="col-md-5">

    <select name="employee_id" id="employee_id" class="form-control select2" required>
      <option value="">Select Employee</option>

      <?php foreach($records as $emp): ?>
       <option value="<?= $emp->employee_id ?>"
                                <?= ($emp->employee_id == $row->employee_id) ? 'selected' : '' ?>>
                                <?= $emp->user_code . ' - ' . $emp->employee_name ?>
                            </option>
      <?php endforeach; ?>

    </select>

  </div>
</div>

<!-- Leave Code -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Leave Code:</label>
  <div class="col-md-5">
    <input type="text" name="lv_code" id="lv_code"
           class="form-control"
           value="<?= $row->leave_code ?? ''; ?>" readonly>
  </div>
</div>

<!-- Application Date -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Application Date:</label>
  <div class="col-md-5">
    <input type="text" class="form-control"
           value="<?= !empty($row->application_date) ? date('d-m-Y', strtotime($row->application_date)) : ''; ?>"
           readonly>
  </div>
</div>

<!-- Leave Type -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Leave Type:</label>
  <div class="col-md-5">

    <?php
      $leave_types = [
        'Personal Leave','Annual Leave','Sick Leave',
        'Maternity Leave','Compensatory Leave',
        'Sick/Casual Leave','Emergency Leave','Other'
      ];
    ?>

    <select class="form-control select2" name="leave_type" required>
      <option value="">Select Type</option>

      <?php foreach($leave_types as $lt): ?>
        <option value="<?= $lt ?>"
          <?= ($row->leave_type == $lt) ? 'selected' : '' ?>>
          <?= $lt ?>
        </option>
      <?php endforeach; ?>

    </select>

  </div>
</div>

<!-- Dates -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Leave From - To:</label>

  <div class="col-md-2">
    <input type="date" name="start_date" id="start_date"
           class="form-control"
           value="<?= $row->start_date ?? ''; ?>">
  </div>

  <div class="col-md-2">
    <input type="date" name="end_date" id="end_date"
           class="form-control"
           value="<?= $row->end_date ?? ''; ?>">
  </div>
</div>

<!-- Total Days -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Total Days:</label>
  <div class="col-md-2">
    <?php
      $total_days = 0;
      if (!empty($row->start_date) && !empty($row->end_date)) {
        $diff = (new DateTime($row->start_date))
              ->diff(new DateTime($row->end_date));
        $total_days = $diff->days + 1;
      }
    ?>
    <input type="text" id="total_date" class="form-control"
           value="<?= $total_days; ?>" readonly>
  </div>
</div>

<!-- Contact -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Contact & Address:</label>
  <div class="col-md-5">
    <textarea name="outside_contact" class="form-control"><?= $row->outside_contact ?? ''; ?></textarea>
  </div>
</div>

<!-- Reason -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Reason:</label>
  <div class="col-md-5">
    <textarea name="reason" class="form-control"><?= $row->reason ?? ''; ?></textarea>
  </div>
</div>

<!-- Joining Date -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Joining Date:</label>
  <div class="col-md-5">
    <input type="date" name="joindate_fromlastLeave"
           class="form-control"
           value="<?= $row->joindate_fromlastLeave ?? ''; ?>">
  </div>
</div>

<!-- Replacement -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Charge Handed To:</label>
  <div class="col-md-5">

    <select name="replcement" class="form-control select2">
      <option value="">Select</option>

      <?php foreach($records as $emp): ?>
        <option value="<?= $emp->employee_id ?>"
          <?= ($row->replcement == $emp->employee_id) ? 'selected' : '' ?>>
          <?= $emp->employee_name ?>
        </option>
      <?php endforeach; ?>

    </select>

  </div>
</div>

<!-- Files -->
<div class="form-group row">
  <label class="col-md-3 col-form-label">Upload Files:</label>
  <div class="col-md-6">

    <table class="table table-bordered table-sm">
      <tr>
        <td>1</td>
        <td><input type="file" name="documents[]" class="form-control"></td>
      </tr>

      <?php if (!empty($file_records)): ?>
        <?php $i=1; foreach($file_records as $f): ?>
          <tr>
            <td><?= $i++; ?></td>
            <td>
              <a href="<?= base_url('public/uploaded_documents/'.$f->document_path); ?>" target="_blank">
                View File
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>

    </table>

  </div>
</div>

<!-- Submit -->
<div class="form-group row">
  <div class="col-md-10 text-end">
    <input type="hidden" name="id" value="<?= $row->leave_id; ?>">
    <button type="submit" class="btn btn-primary btn-sm">Update</button>
  </div>
</div>


</form>

<!-- ====================== APPROVAL FORM ====================== -->
<hr>

<form method="post" action="<?= base_url('index.php/Hr/add_leave_approval'); ?>">
<?php if($logged_user_id != $row->employee_id): ?>
<div class="form-group row">
  <label class="col-md-3">Approve Date:</label>
  <div class="col-md-5">
    <input type="date" name="approve_date"
           class="form-control"
           value="<?= date('Y-m-d'); ?>">
  </div>
</div>
<?php endif; ?>

<div class="form-group row">
  <label class="col-md-3">Status:</label>
  <div class="col-md-5">
    <select name="leave_status" class="form-control select2">
      <option value="1">Approved</option>
      <option value="2">Rejected</option>
    </select>
  </div>
</div>

<!-- <div class="form-group row">
  <div class="col-md-10 text-end">
    <input type="hidden" name="hide_leave_id" value="<?= $row->leave_id; ?>">
    <button class="btn btn-success btn-sm">Submit Approval</button>
  </div>
</div> -->

<?php $status = $leave_status->leave_status ?? 0; ?>
<?php if($status == 0): ?>

<div class="form-group row">
  <div class="col-md-10 text-end">
    <input type="hidden" name="hide_leave_id" value="<?= $row->leave_id; ?>">
    <button type="submit" class="btn btn-success btn-sm">
      Submit Approval
    </button>
  </div>
</div>

<?php else: ?>

<div class="form-group row">
  <div class="col-md-10 text-end">
   <span class="badge badge-success">
  Already Approved / Processed
</span>
  </div>
</div>

<?php endif; ?>
</form>

  </div>
</div>

<!-- ====================== SCRIPT ====================== -->
<script>
function calculate_total_days() {
    let s = document.getElementById('start_date').value;
    let e = document.getElementById('end_date').value;

    if (!s || !e) return;

    let start = new Date(s);
    let end = new Date(e);

    let diff = Math.ceil((end - start) / (1000*60*60*24)) + 1;

    document.getElementById('total_date').value = diff;
}

document.getElementById('start_date').addEventListener('change', calculate_total_days);
document.getElementById('end_date').addEventListener('change', calculate_total_days);
</script>