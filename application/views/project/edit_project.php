<style>
label, h5 {
    color: #000;
    font-weight: bold;
}
table th, table td {
    vertical-align: middle !important;
}
</style>

<div class="row">
<div class="col-md-12">
<div class="x_panel">
<div class="x_content">

<form id="project_form" action="<?= base_url('index.php/Project/update_project') ?>" method="post">

<input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">

<!-- ================= PROJECT BASIC INFO ================= -->

<h5>Project Details</h5>
<table class="table table-bordered">
<tr>
    <th width="20%">Sales Order</th>
    <td width="30%">
        <input type="text" class="form-control" value="<?= $project['so_code'] ?? '' ?>" readonly>
        <input type="hidden" name="so_id" value="<?= $project['so_id'] ?>">
    </td>
    <!-- <th width="20%">Customer</th>
    <td width="30%">
       <input type="text" class="form-control" value="<?= $project['customer_name'] ?>" readonly>
        <input type="hidden" name="customer_name" value="<?= $project['customer_name'] ?>">
        <input type="hidden" name="branch_name" value="<?= $project['branch_name'] ?>">
    </td> -->
</tr>

<tr>
    <th width="20%">Customer</th>
    <td width="25%">
       <input type="text" class="form-control" value="<?= $project['customer_name'] ?>" readonly>
       <input type="hidden" name="customer_name" value="<?= $project['customer_name'] ?>">
    </td>
    <th width="20%">Branch</th>
    <td width="25%">
       <input type="text" class="form-control" value="<?= $project['branch_name'] ?>" readonly>
       <input type="hidden" name="branch_name" value="<?= $project['branch_name'] ?>">
    </td>
</tr>


<tr>
    <th>Project Name</th>
    <td>
        <input type="text" name="project_name" class="form-control" value="<?= $project['project_name'] ?>" required>
    </td>
    <th>Location</th>
    <td>
        <input type="text" name="project_location" class="form-control" value="<?= $project['project_location'] ?>">
    </td>
</tr>

<tr>
    <th>Start Date</th>
    <td>
        <input type="date" name="start_date" id="start_date" class="form-control"
               value="<?= $project['start_date'] ?>">
    </td>
    <th>End Date</th>
    <td>
        <input type="date" name="end_date" id="end_date" class="form-control"
               value="<?= $project['end_date'] ?>">
    </td>
</tr>

<tr>
    <th>Duration (Days)</th>
    <td colspan="3">
        <input type="text" name="duration" id="duration" class="form-control"
               value="<?= $project['duration'] ?>" readonly>
    </td>
</tr>
</table>

<!-- ================= PROJECT ITEMS ================= -->

<h5>Project Items</h5>
<table class="table table-bordered" id="project_items_table">
<thead>
<tr>
    <th>#</th>
    <th>Item</th>
    <th class="text-end">Qty</th>
    <th class="text-end">Unit Price</th>
    <th class="text-end">Total</th>
</tr>
</thead>
<tbody>

<?php foreach ($project_items as $i => $item): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td>
        <?= $item['product_name'] ?>
        <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
    </td>
    <td>
        <input type="number" name="quantity[]" class="form-control qty_input text-end"
               value="<?= $item['quantity'] ?>" readonly>
    </td>
    <td>
        <input type="number" name="unit_price[]" class="form-control price_input text-end"
               value="<?= $item['unit_price'] ?>" readonly>
    </td>
    <td class="text-end total"><?= number_format($item['total'],2) ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<!-- ================= FINANCIAL SUMMARY ================= -->

<h5>Financial Summary</h5>
<table class="table table-bordered" style="width:50%">
<tr>
    <th>Subtotal</th>
    <td>
        <input type="text" name="subtotal" id="subtotal" class="form-control"
               value="<?= $project['subtotal'] ?>" readonly>
    </td>
</tr>
<tr>
    <th>VAT (%)</th>
    <td>
        <input type="number" name="vat_percentage" id="vat_percentage" class="form-control"
               value="<?= $project['vat_percentage'] ?>" readonly>
    </td>
</tr>
<tr>
    <th>VAT Amount</th>
    <td>
        <input type="text" name="vat_amount" id="vat_amount" class="form-control"
               value="<?= $project['vat_amount'] ?>" readonly>
    </td>
</tr>
<tr>
    <th>Grand Total</th>
    <td>
        <input type="text" name="grand_total" id="grand_total" class="form-control"
               value="<?= $project['grand_total'] ?>" readonly>
    </td>
</tr>
</table>

<!-- ================= TECHNICIAN ASSIGNMENT ================= -->

<h5>Technician & Resource Assignment</h5>

<table class="table table-bordered" id="technician_table">
<thead>
<tr>
    <th>#</th>
    <th>Technician</th>
    <th>Role</th>
    <th>Start</th>
    <th>End</th>
    <!-- <th>Availability</th> -->
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php if (!empty($project_technicians)): ?>
<?php foreach ($project_technicians as $i => $tech): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td>
    <select name="technician_id[]" class="form-control technician_select">
        <?php foreach ($employees as $emp): ?>
        <option value="<?= $emp['employee_id'] ?>"
            <?= $emp['employee_id'] == $tech['employee_id'] ? 'selected' : '' ?>>
            <?= $emp['employee_name'] ?>
        </option>
        <?php endforeach; ?>
    </select>
</td>
<td>
    <select name="designation_id[]" class="form-control designation_select">
        <?php foreach ($designations as $des): ?>
        <option value="<?= $des['id'] ?>"
            <?= $des['id'] == $tech['designation_id'] ? 'selected' : '' ?>>
            <?= $des['designation_name'] ?>
        </option>
        <?php endforeach; ?>
    </select>
</td>
<td><input type="date" name="assignment_start[]" class="form-control assignment_start"
           value="<?= $tech['assignment_start'] ?>"></td>
<td><input type="date" name="assignment_end[]" class="form-control assignment_end"
           value="<?= $tech['assignment_end'] ?>"></td>
<!-- <td class="availability_status">
    <span class="badge bg-secondary">Checking...</span>
</td> -->

    <td>
        <button type="button" class="btn btn-danger btn-sm remove_row">Remove</button>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

</tbody>
</table>

<button type="button" class="btn btn-primary btn-sm" id="add_technician">Add Technician</button>

<!-- ================= REMARKS ================= -->

<div class="form-group mt-3">
<label>Remarks</label>
<textarea name="remarks" class="form-control"><?= $project['remarks'] ?></textarea>
</div>

<tr>
    <th>Approver</th>
    <td colspan="3">
        <select name="approver_id" class="form-control" required>
            <option value="">-- Select Approver --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['user_id'] ?>"
                    <?= ($project['approver_id'] == $user['user_id']) ? 'selected' : '' ?>>
                    <?= $user['user_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>


<div class="text-end mt-4">
    <button type="submit" class="btn btn-success">Update Project</button>
    <a href="<?= base_url('index.php/Project/get_project_list') ?>" class="btn btn-secondary">Cancel</a>

    <?php if ($project['approver_id'] == $logged_in_user_id && $project['status'] != 'Approved'): ?>
        <button type="button" id="approve_project" class="btn btn-primary">
            Approve
        </button>

        <?php if ($project['status'] != 'Rejected'): ?>
            <button type="button" id="reject_project" class="btn btn-danger">
                Reject
            </button>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($project['status'] === 'Approved'): ?>
<a href="<?= base_url('index.php/Project/create_material_request/'.$project['project_id']) ?>"
   class="btn btn-primary btn-sm">
   Create MR
</a>
<?php endif; ?>


</div>



</form>

</div>
</div>
</div>
</div>

<!-- ================= JS ================= -->

<script>
var employees = <?= json_encode($employees); ?>;
var designations = <?= json_encode($designations); ?>;
var rowCount = $('#technician_table tbody tr').length;

// Generate dropdown options
function getTechnicianOptions() {
    var options = '<option value="">-- Select Technician --</option>';
    employees.forEach(function(emp){
        options += `<option value="${emp.employee_id}">${emp.employee_name}</option>`;
    });
    return options;
}

function getDesignationOptions() {
    var options = '<option value="">-- Select Role --</option>';
    designations.forEach(function(des){
        options += `<option value="${des.id}">${des.designation_name}</option>`;
    });
    return options;
}

// Add new technician row
$('#add_technician').click(function(){
    rowCount++;
    var newRow = `<tr>
        <td>${rowCount}</td>
        <td>
            <select name="technician_id[]" class="form-control technician_select">
                ${getTechnicianOptions()}
            </select>
        </td>
        <td>
            <select name="designation_id[]" class="form-control designation_select">
                ${getDesignationOptions()}
            </select>
        </td>
        <td><input type="date" name="assignment_start[]" class="form-control assignment_start"></td>
        <td><input type="date" name="assignment_end[]" class="form-control assignment_end"></td>
        <td class="availability_status"><span class="badge bg-secondary">Checking...</span></td>
        <td><button type="button" class="btn btn-danger btn-sm remove_row">Remove</button></td>
    </tr>`;
    $('#technician_table tbody').append(newRow);
});

// Remove row and re-number
$(document).on('click', '.remove_row', function () {
    $(this).closest('tr').remove();
    $('#technician_table tbody tr').each(function (i) {
        $(this).find('td:first').text(i + 1);
    });
    rowCount = $('#technician_table tbody tr').length;
});

// Check availability function
function checkAvailability(row) {
    var technician_id = row.find('.technician_select').val();
    var start_date = row.find('.assignment_start').val();
    var end_date = row.find('.assignment_end').val();
    var project_id = $('input[name="project_id"]').val();

    if (!technician_id || !start_date || !end_date) return;

    $.ajax({
        url: '<?= base_url("index.php/Project/check_technician_availability") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            technician_id: technician_id,
            start_date: start_date,
            end_date: end_date,
            project_id: project_id
        },
        success: function(res) {
            var badge = row.find('.availability_status span');
            if (res.status === 'Available') {
                badge.removeClass().addClass('badge bg-success').text('Available');
            } else {
                badge.removeClass().addClass('badge bg-danger').text('Not Available');
            }
        }
    });
}

// Trigger availability check
$(document).on('change', '.technician_select, .assignment_start, .assignment_end', function() {
    var row = $(this).closest('tr');
    checkAvailability(row);
});

// Initial check on page load for existing rows
$('#technician_table tbody tr').each(function(){
    checkAvailability($(this));
});

$('#project_form').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res){
            if(res.status === 'error'){
                alert(res.message); // Show pop-up alert
            } else {
                alert(res.message); // Success message
                window.location.href = '<?= base_url("index.php/Project/get_project_list") ?>';
            }
        },
        error: function(){
            alert('Something went wrong. Please try again.');
        }
    });
});


$('#approve_project').click(function(){
    if(confirm('Are you sure you want to approve this project?')){
        $.ajax({
            url: '<?= base_url("index.php/Project/approve_project") ?>',
            type: 'POST',
            data: { project_id: $('input[name="project_id"]').val() },
            dataType: 'json',
            success: function(res){
                if(res.status === 'success'){
                    alert(res.message);
                    window.location.href = '<?= base_url("index.php/Project/get_project_list") ?>';
                } else {
                    alert(res.message);
                }
            },
            error: function(){
                alert('Something went wrong while approving the project.');
            }
        });
    }
});
$('#reject_project').click(function(){
    if(confirm('Are you sure you want to reject this project?')){
        $.ajax({
            url: '<?= base_url("index.php/Project/reject_project") ?>',
            type: 'POST',
            data: { project_id: $('input[name="project_id"]').val() },
            dataType: 'json',
            success: function(res){
                if(res.status === 'success'){
                    alert(res.message);
                    window.location.href = '<?= base_url("index.php/Project/get_project_list") ?>';
                } else {
                    alert(res.message);
                }
            },
            error: function(){
                alert('Something went wrong while rejecting the project.');
            }
        });
    }
});

</script>

