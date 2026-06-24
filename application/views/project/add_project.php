<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }

    table th,
    table td {
        vertical-align: middle !important;
    }
</style>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div >
                <!-- <h4><?= !empty($project) ? "Edit Project" : "Add Project" ?></h4> -->
                <div class="clearfix"></div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="x_content">
                <form action="<?= base_url('index.php/Project/save_project') ?>" method="post">
                    <input type="hidden" name="project_id" value="<?= $project['project_id'] ?? '' ?>">

                    <!-- Select Sales Order -->
                    <div class="form-group mb-3">
                        <label>Select Sales Order</label>
                        <select name="so_id" id="so_select" class="form-control" required>
    <option value="">-- Select --</option>
    <?php foreach ($sales_orders as $so): ?>
        <option value="<?= $so['so_id'] ?>" 
            <?= (!empty($selected_so_id) && $selected_so_id == $so['so_id']) ? 'selected' : '' ?>>
            <?= $so['so_code'] ?> (SO ID: <?= $so['so_id'] ?>)
        </option>
    <?php endforeach; ?>
</select>

                    </div>

                    <!-- Project & Customer Details -->
                    <table class="table table-bordered" style="width:100%; font-size:13px; margin-bottom:20px;">
                        <tr>
                            <th>Project Name</th>
                            <td><input type="text" name="project_name" id="project_name" class="form-control" value="<?= $project['project_name'] ?? '' ?>" required></td>
                            <th>Project Location</th>
                            <td><input type="text" name="project_location" class="form-control" value="<?= $project['project_location'] ?? '' ?>"></td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td><input type="text" name="customer_name" id="customer_name" class="form-control" value="<?= $project['customer_name'] ?? '' ?>" readonly></td>
                            <th>Branch</th>
                            <td><input type="text" name="branch_name" id="branch_name" class="form-control" value="<?= $project['branch_name'] ?? '' ?>" readonly></td>
                        </tr>

                        <tr>
    <th>Start Date</th>
    <td><input type="date" name="start_date" id="start_date" class="form-control" value="<?= $project['start_date'] ?? '' ?>"></td>
    <th>End Date</th>
    <td><input type="date" name="end_date" id="end_date" class="form-control" value="<?= $project['end_date'] ?? '' ?>"></td>
</tr>
<tr>
    <th>Project Duration (Days)</th>
    <td colspan="3"><input type="text" name="duration" id="duration" class="form-control" value="<?= $project['duration'] ?? '' ?>" readonly></td>
</tr>

                    </table>

                    <!-- Project Items Table -->
                    <h5>Project Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="project_items_table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($project_items)): ?>
                                    <?php foreach ($project_items as $i => $item): ?>
                                        <tr>
                                            <td><?= $i+1 ?></td>
                                            <td>
                                                <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </td>
                                            <td class="text-end"><input type="text" name="quantity[]" value="<?= $item['quantity'] ?>" class="form-control qty_input text-end" readonly></td>
                                            <td class="text-end"><input type="text" name="unit_price[]" value="<?= $item['unit_price'] ?>" class="form-control price_input text-end" readonly></td>
                                            <td class="text-end total"><?= number_format($item['total'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Select a Sales Order to populate items</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Financial Summary -->
                    <div class="col-md-6">
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Subtotal</th>
                                    <td><input type="text" name="subtotal" id="subtotal" class="form-control" value="<?= $project['subtotal'] ?? '0.00' ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>VAT (%)</th>
                                    <td><input type="text" name="vat_percentage" id="vat_percentage" class="form-control" value="<?= $project['vat_percentage'] ?? '5' ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>VAT Amount</th>
                                    <td><input type="text" name="vat_amount" id="vat_amount" class="form-control" value="<?= $project['vat_amount'] ?? '0.00' ?>" readonly></td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td><input type="text" name="grand_total" id="grand_total" class="form-control" value="<?= $project['grand_total'] ?? '0.00' ?>" readonly></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    

                    
<div class="table-responsive">
    <h5>Technician & Resource Assignment</h5>
    <table class="table table-bordered" id="technician_table">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Technician</th>
                <th>Role / Designation</th>
                <th>Assignment Start</th>
                <th>Assignment End</th>
                <!-- <th>Availability</th> -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <select name="technician_id[]" class="form-control technician_select" required>
    <option value="">-- Select Technician --</option>
    <?php foreach ($employees as $emp): ?>
        <option value="<?= $emp['employee_id'] ?>"><?= $emp['employee_name'] ?></option>
    <?php endforeach; ?>
</select>
                </td>
                <td>
                    <select name="designation_id[]" class="form-control designation_select" required>
    <option value="">-- Select Role --</option>
    <?php foreach ($designations as $des): ?>
        <option value="<?= $des['id'] ?>"><?= $des['designation_name'] ?></option>
    <?php endforeach; ?>
</select>
                </td>
                <td><input type="date" name="assignment_start[]" class="form-control assignment_start"></td>
                <td><input type="date" name="assignment_end[]" class="form-control assignment_end"></td>
                <!-- <td class="availability_status"><span class="badge bg-secondary">Checking...</span></td> -->

                <td><button type="button" class="btn btn-danger btn-sm remove_row">Remove</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm mt-2" id="add_technician">Add Technician</button>
</div>


                    <!-- Remarks -->
                    <div class="form-group mt-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"><?= $project['remarks'] ?? '' ?></textarea>
                    </div>
                    <div class="form-group mt-3">
    <label>Assign Approver</label>
    <select name="approver_id" class="form-control" required>
        <option value="">-- Select Approver --</option>
        <?php foreach($users as $user): ?>
            <option value="<?= $user['user_id'] ?>"
                <?= !empty($project) && $project['approver_id'] == $user['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($user['user_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success"><?= !empty($project) ? "Update Project" : "Save Project" ?></button>
                        <a href="<?= base_url('index.php/Project/add_project') ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS for fetching SO and calculating totals -->
<script>
$(document).ready(function(){

    // Function to fetch SO details
    function fetchSO(so_id){
        if(!so_id) return;
        $.ajax({
            url: '<?= base_url("index.php/Project/fetch_so_details") ?>',
            type: 'POST',
            data: {so_id: so_id},
            dataType: 'json',
            success: function(data){
                $('#project_name').val(data.so_master.project_name);
                $('#branch_name').val(data.so_master.branch_name);
                $('#customer_name').val(data.so_master.customer_name);
                $('#project_location').val(data.so_master.project_location);

                var html = '';
                $.each(data.so_products, function(i, prod){
                    html += '<tr>'+
                        '<td>'+(i+1)+'</td>'+
                        '<td><input type="hidden" name="product_id[]" value="'+prod.product_id+'">'+prod.item_name+'</td>'+
                        '<td class="text-end"><input type="text" name="quantity[]" value="'+prod.quantity+'" class="form-control qty_input text-end" readonly></td>'+
                        '<td class="text-end"><input type="text" name="unit_price[]" value="'+prod.unit_price+'" class="form-control price_input text-end" readonly></td>'+
                        '<td class="text-end total">'+(prod.quantity * prod.unit_price).toFixed(2)+'</td>'+
                    '</tr>';
                });
                $('#project_items_table tbody').html(html);

                calculateTotals();
            }
        });
    }

    // Trigger when user manually selects SO
    $('#so_select').change(function(){
        var so_id = $(this).val();
        fetchSO(so_id);
    });

    // ✅ Auto-fetch if SO is preselected via URL
    var preselected_so_id = '<?= $selected_so_id ?? '' ?>';
    if(preselected_so_id){
        fetchSO(preselected_so_id);
    }

    // Recalculate totals on quantity or price change
    $(document).on('keyup change', '.qty_input, .price_input', function(){
        calculateTotals();
    });

    function calculateTotals(){
        var subtotal = 0;
        $('#project_items_table tbody tr').each(function(){
            var qty = parseFloat($(this).find('.qty_input').val()) || 0;
            var price = parseFloat($(this).find('.price_input').val()) || 0;
            var total = qty * price;
            $(this).find('.total').text(total.toFixed(2));
            subtotal += total;
        });
        $('#subtotal').val(subtotal.toFixed(2));

        var vat_percentage = parseFloat($('#vat_percentage').val()) || 0;
        var vat_amount = subtotal * vat_percentage / 100;
        $('#vat_amount').val(vat_amount.toFixed(2));

        $('#grand_total').val((subtotal + vat_amount).toFixed(2));
    }

    // Recalculate VAT & grand total if VAT percentage changes
    $('#vat_percentage').on('keyup change', function(){
        calculateTotals();
    });

    // Initial calculation
    calculateTotals();
});



function calculateDuration() {
    var start = $('#start_date').val();
    var end = $('#end_date').val();

    if (start && end) {
        var startDate = new Date(start);
        var endDate = new Date(end);

        // Calculate difference in milliseconds
        var diffTime = endDate - startDate;
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end day

        $('#duration').val(diffDays >= 0 ? diffDays : 0);
    } else {
        $('#duration').val('');
    }
}

// Trigger on date change
$('#start_date, #end_date').on('change', calculateDuration);

// Optional: calculate on page load if dates are prefilled
calculateDuration();




 // ------------------------------
    // Technician & Resource Assignment
    // ------------------------------
    $(document).ready(function(){

    // Pass PHP arrays to JS
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
            <select name="technician_id[]" class="form-control technician_select" required>
                ${getTechnicianOptions()}
            </select>
        </td>
        <td>
            <select name="designation_id[]" class="form-control designation_select" required>
                ${getDesignationOptions()}
            </select>
        </td>
        <td><input type="date" name="assignment_start[]" class="form-control assignment_start"></td>
        <td><input type="date" name="assignment_end[]" class="form-control assignment_end"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove_row">Remove</button></td>
    </tr>`;
    
    $('#technician_table tbody').append(newRow);

    // ✅ Fill dates immediately after adding the row
    var lastRow = $('#technician_table tbody tr').last();
    lastRow.find('.assignment_start').val($('#start_date').val());
    lastRow.find('.assignment_end').val($('#end_date').val());

    // Optionally check availability immediately
    checkAvailability(lastRow);
});
    // Auto-fill assignment dates from project dates
    function fillAssignmentDates() {
        var projectStart = $('#start_date').val();
        var projectEnd = $('#end_date').val();
        $('.assignment_start').val(projectStart);
        $('.assignment_end').val(projectEnd);
    }

    $('#start_date, #end_date').on('change', fillAssignmentDates);
    fillAssignmentDates(); // fill on page load
});

function checkAvailability(row) {
    var technician_id = row.find('.technician_select').val();
    var start_date    = row.find('.assignment_start').val();
    var end_date      = row.find('.assignment_end').val();
    var project_id    = $('input[name="project_id"]').val();

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
        success: function (res) {
            var badge = row.find('.availability_status span');

            if (res.status === 'Available') {
                badge
                    .removeClass()
                    .addClass('badge bg-success')
                    .text('Available');
            } else if (res.status === 'Not Available') {
                badge
                    .removeClass()
                    .addClass('badge bg-danger')
                    .text('Not Available');
            }
        }
    });
}


$(document).on('change', '.technician_select, .assignment_start, .assignment_end', function () {
    var row = $(this).closest('tr');
    checkAvailability(row);
});

// Validate technician assignment dates before form submission
$('form').on('submit', function(e) {
    var valid = true;

    $('#technician_table tbody tr').each(function(index){
        var start = $(this).find('.assignment_start').val();
        var end = $(this).find('.assignment_end').val();

        // Remove previous error
        $(this).find('.text-danger').remove();

        if(start && end) {
            var startDate = new Date(start);
            var endDate = new Date(end);

            if(startDate > endDate){
                valid = false;
                $(this).find('td:last').before('<td class="text-danger">Start Date cannot be later than End Date</td>');
            }
        }
    });

    if(!valid){
        e.preventDefault();
        alert("Please correct technician assignment dates.");
        return false;
    }
});
function isTechnicianDuplicate(newTechId, newStart, newEnd, excludeRow=null) {
    var duplicate = false;

    $('#technician_table tbody tr').each(function(){
        if(excludeRow && $(this).is(excludeRow)) return; // skip current row when editing

        var techId = $(this).find('.technician_select').val();
        var start = $(this).find('.assignment_start').val();
        var end = $(this).find('.assignment_end').val();

        if(!techId || !start || !end) return;

        if(techId == newTechId) {
            // Check date overlap
            var s1 = new Date(newStart);
            var e1 = new Date(newEnd);
            var s2 = new Date(start);
            var e2 = new Date(end);

            if(s1 <= e2 && s2 <= e1) {
                duplicate = true;
                return false; // break loop
            }
        }
    });

    return duplicate;
}

$(document).on('change', '.technician_select, .assignment_start, .assignment_end', function () {
    var row = $(this).closest('tr');
    checkAvailability(row);

    var techId = row.find('.technician_select').val();
    var start = row.find('.assignment_start').val();
    var end = row.find('.assignment_end').val();

    if(techId && start && end) {
        if(isTechnicianDuplicate(techId, start, end, row)) {
            alert('This technician is already assigned during the selected dates!');
            // Optionally reset selection
            row.find('.technician_select').val('');
        }
    }
});

</script>
