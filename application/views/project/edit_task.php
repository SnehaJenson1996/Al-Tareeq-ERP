<style>
.title_right .btn-secondary{
    display:none;
}
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

<form id="project_form" action="<?= base_url('index.php/Project/act_update_task') ?>" method="post">
<input type="hidden" name="project_id" value="<?= $project['project_id']; ?>">
<input type="hidden" name="project_task_id" value="<?= !empty($project_task) ? $project_task['id'] : ''; ?>">
<div class="project-info-card">
    <h5 class="card-title">Project Information</h5>

    <div class="row">

        <div class="col-md-4 mb-3">
            <label>Project</label>
            <input type="text" name="project_name" class="form-control" value="<?= $project['project_name'] ?>" required readonly>
        </div>

        <div class="col-md-4 mb-3">
            <label>Customer</label>
            <input type="text" class="form-control" value="<?= $project['customer_name'] ?>" readonly>
        </div>

        <div class="col-md-4 mb-3">
            <label>Project Approver</label>
             <select name="approver_id" class="form-control" readonly>
            <option value="">-- Select Approver --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['user_id'] ?>"
                    <?= ($project['approver_id'] == $user['user_id']) ? 'selected' : '' ?>>
                    <?= $user['user_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        </div>

        <div class="col-md-4 mb-3">
            <label>Project Start Date</label>
            <input type="text" class="form-control" value="<?= $project['start_date'] ?>" readonly>
        </div>

        <div class="col-md-4 mb-3">
            <label>Project End Date</label>
            <input type="text" class="form-control"  value="<?= $project['end_date'] ?>"  readonly>
        </div>

        <div class="col-md-4 mb-3">
            <label>Project Duration (Days)</label>
            <input type="text" class="form-control" value="<?= $project['duration'] ?>" readonly>
        </div>

    </div>
</div>
<input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">


<!-- ================= PROJECT BASIC INFO ================= -->
            </div>
    <div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Task Assignment & Timeline</h5>

        <button type="button" class="btn btn-primary btn-sm" id="add_task">
            <i class="fa fa-plus"></i> Add Task
        </button>

    </div>

    <div class="card-body">

        <div id="task_container">

        </div>

    </div>

</div>
         
    <!-- ================= Remarks ================= -->

    <div class="card mb-3">

        <div class="card-header bg-secondary text-white">

            <strong>Remarks</strong>

        </div>

        <div class="card-body">
            <textarea name="remarks" class="form-control"><?= $task['remarks']; ?></textarea>
        </div>

    </div>

    <!-- ================= Approval ================= -->

    <div class="card mb-3">

        <div class="card-header bg-info text-white">

            <strong>Approval</strong>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Assign Approver</label>
                        <select name="approver" class="form-control">
                        <?php foreach($users as $user){ ?>
                            <option value="<?= $user['user_id']; ?>"
                                <?= ($task['approved_by']==$user['user_id'])?'selected':''; ?>>
                                <?= $user['user_name']; ?>
                            </option>
                        <?php } ?>
                        </select>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ================= Buttons ================= -->

    <div class="text-right mb-4">

        <button class="btn btn-success">

            <i class="fa fa-save"></i> Save

        </button>

        <a href="#" class="btn btn-secondary">

            Cancel

        </a>

    </div>

</form>


<div class="row">







        <!-- ================= PROJECT ITEMS ================= -->


</div>



</form>

</div>
</div>
</div>
</div>
<?php
$task_category_options = '';
$task_category_options .= '<option value="">Select Task category</option>';
foreach ($task_categories as $row) {
    
    $task_category_options .= '<option value="'.$row['project_task_id'].'">'
                            .$row['project_task_name'].
                            '</option>';

}
$m_options = '';
$m_options .= '<option value="">Select Milestone</option>';
foreach ($milestones as $rowm) {
    
    $m_options .= '<option value="'.$rowm['milestone_id'].'">'
            .$rowm['milestone_name'].
    '</option>';

}
$designation_options = '';

foreach($designation_list as $row){

    $designation_options .= '<option value="'.$row['id'].'">'
                          .$row['designation_name'].
                          '</option>';

}


$emp_list = '';

foreach($employees as $row){

    $emp_list .= '<option value="'.$row['employee_id'].'">'
                          .$row['employee_name'].
                          '</option>';

}
?>

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
<script>
$(document).ready(function(){

    $('#se_select').change(function(){
        var enquiry_id = $(this).val();

        if(enquiry_id != ''){

            $.ajax({
                url: "<?php echo base_url()?>index.php/Project/getQuotationByEnquiry",
                type: "POST",
                data: {enquiry_id: enquiry_id},
                dataType: "json",
                success: function(response){

                    $('#quotation_select').html('<option value="">-- Select Quotation --</option>');

                    $.each(response, function(index, row){

                        $('#quotation_select').append(
                            '<option value="'+row.qtn_id+'">'+row.quotation_code+'</option>'
                        );

                    });

                }
            });

        }else{

            $('#quotation_select').html('<option value="">-- Select Quotation --</option>');

        }

    });

});
$(document).ready(function () {

    $('#se_select').change(function () {
        var enquiry_id = $(this).val();
        if (enquiry_id != '') {

            $.ajax({
                url: "<?php echo base_url()?>index.php/Project/getProjectDetailsByEnquiry",
                type: "POST",
                data: { enquiry_id: enquiry_id },
                dataType: "json",
                success: function (response) {

                    // Auto fill project details
                    $('#project_name').val(response.project_name);
                    $('#project_location').val(response.project_location);

                }
            });

        } else {

            $('#project_name').val('');
            $('#project_location').val('');
            $('#quotation_select').html('<option value="">-- Select Quotation --</option>');

        }

    });

});

$(document).ready(function () {

    $('#quotation_select').change(function () {

        var quotation_id = $(this).val();

        if (quotation_id != '') {

            $.ajax({
                url: "<?php echo base_url()?>index.php/Project/getcustomerDetails",
                type: "POST",
                data: {
                    quotation_id: quotation_id
                },
                dataType: "json",
                success: function (response) {

                    $('#customer_name').val(response.customer);
                    $('#branch_name').val(response.branch);

                }
            });

        } else {

            $('#customer_name').val('');
            $('#branch_name').val('');

        }

    });

});


function fetchQuotation(q_id){
        if(!q_id) return;
        $.ajax({
            url: '<?= base_url("index.php/Project/fetch_quotation_details") ?>',
            type: 'POST',
            data: {q_id: q_id},
            dataType: 'json',
            success: function(data){
                var html = '';
                $.each(data.q_products, function(i, prod){
                    html += '<tr>'+
                        '<td>'+(i+1)+'</td>'+
                        '<td><input type="hidden" name="product_id[]" value="'+prod.prd_id+'">'+prod.product_name+'</td>'+
                        '<td class="text-end"><input type="text" name="quantity[]" value="'+prod.qty+'" class="form-control qty_input text-end" readonly></td>'+
                        //'<td class="text-end"><input type="text" name="unit_price[]" value="'+prod.unit_price+'" class="form-control price_input text-end" readonly></td>'+
                        //'<td class="text-end total">'+(prod.qty * prod.unit_price).toFixed(2)+'</td>'+
                    '</tr>';
                });
                $('#project_items_table tbody').html(html);

                calculateTotals();
            }
        });
    }

    // Trigger when user manually selects quotation
    $('#quotation_select').change(function(){
        var q_id = $(this).val();
        fetchQuotation(q_id);
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
var designationOptions = `<?= $designation_options ?>`;
var emp_list = `<?= $emp_list ?>`; 
</script>
<script>

var taskData = <?= json_encode($task_items); ?>;
$(function(){

    if(taskData.length > 0){

        $.each(taskData,function(i,row){

            addTask(row);

        });

    }else{

        addTask();

    }

});
         
</script>
<script>
//old
$(document).ready(function(){

    

    $("#add_task").click(function(){

        addTask();

    });

    $(document).on("click",".remove_task",function(){

        $(this).closest(".task-card").remove();

        renumberTask();

    });

});
var taskCategoryOptions = `<?= $task_category_options ?>`;
var m_options = `<?= $m_options ?>`;
function addTask(data = null){
     console.log(data);
    var row=$("#task_container .task-card").length+1;

    var html='';

    html+='<div class="task-card">';

    html+='<div class="task-title">';
    html+='Task '+row;
    html+='<button type="button" class="btn btn-danger btn-sm remove_task remove-task">';
    html+='<i class="fa fa-trash"></i>';
    html+='</button>';
    html+='</div>';

    html+='<div class="row">';

    html += '<div class="col-md-3">';
    html += '<label>Task Category</label>';
    html += '<select name="task_category[]" class="form-control" required>';
    html += '<option value="">-- Select Category --</option>';
    html += taskCategoryOptions;
    html += '</select>';
    html += '</div>';
  
    html+='<div class="col-md-3">';
    html+='<label>Task Name</label>';
    html+='<input type="text" name="task_name[]" class="form-control" required>';
    html+='</div>';

    html+='<div class="col-md-3">';
    html+='<label>Milestone</label>';
    html+='<select name="milestone[]" class="form-control" required>';
    html += m_options;
    html+='</select>';
    html+='</div>';

   // Designation

    html+='<div class="col-md-3">';
    html+='<label>Designation</label>';
    html+='<select name="designation_id[]" class="form-control designation_select" required>';
    html+='<option value="">-- Select Designation --</option>';
    html+=designationOptions;
    html+='</select>';
    html+='</div>';

    // Employee


    //html+='</div>';

    //html+='<div class="col-md-12">';

    
    html+='<div class="col-md-3">';
    html+='<label>Employee</label>';
    html+='<select name="employee_id[]" class="form-control employee_select" required>';
    html+='<option value="">-- Select Employee --</option>';
    html+=emp_list;
    html+='</select>';
    html+='</div>';

    html+='<div class="col-md-3">';
    html+='<label>Priority</label>';
    html+='<select name="priority[]" class="form-control">';
    html+='<option value="Low">Low</option>';
    html+='<option value="Medium">Medium</option>';
    html+='<option value="High">High</option>';
    html+='<option value="Critical">Critical</option>';
    html+='</select>';
    html+='</div>';

    html+='<div class="col-md-3">';
    html+='<label>Start Date</label>';
    html+='<input type="date" name="start_date[]" class="form-control">';
    html+='</div>';

    html+='<div class="col-md-3">';
    html+='<label>End Date</label>';
    html+='<input type="date" name="end_date[]" class="form-control">';
    html+='</div>';

    html+='<div class="col-md-3">';
    html+='<label>Status</label>';
    html+='<select name="status[]" class="form-control">';
    html+='<option value="not_started">Not Started</option>';
    html+='<option value="in_progress">In Progress</option>';
    html+='<option value="completed">Completed</option>';
    html+='<option value="hold">On Hold</option>';
    html+='</select>';
    html+='</div>';

    //html+='</div>';

    //html+='<div class="row mt-3">';

    html+='<div class="col-md-9">';
    html+='<label>Task Description</label>';
    html+='<textarea name="task_description[]" class="form-control" rows="4"></textarea>';
    html+='</div>';

    //html+='</div>';

    html+='</div>';

    var card = $(html);
      if(data){
        card.find('[name="task_category[]"]').val(data.task_category_id);

        card.find('[name="task_name[]"]').val(data.task_name);

        card.find('[name="milestone[]"]').val(data.milestone_id);

        card.find('[name="designation_id[]"]').val(data.designation_id);
        card.find('.employee_select').attr('data-selected', data.employee_id);
        card.find('[name="employee_id[]"]').val(data.employee_id);
        card.find('[name="priority[]"]').val(data.priority);

        card.find('[name="start_date[]"]').val(data.start_date);

        card.find('[name="end_date[]"]').val(data.end_date);

        card.find('[name="status[]"]').val(data.status);

        card.find('[name="task_description[]"]').val(data.task_description);

    }
    $("#task_container").append(card);

    // Trigger designation change AFTER appending
    if (data) {
        card.find('.designation_select').trigger('change');
    }
   

}
$(document).on('change','.designation_select',function(){

    var designation_id=$(this).val();

    var employeeDropdown=$(this)
            .closest('.row')
            .find('.employee_select');

    employeeDropdown.html('<option>Loading...</option>');

    $.ajax({

        url:"<?= base_url('index.php/Project/getEmployeesByDesignation'); ?>",

        type:"POST",

        data:{
            designation_id:designation_id
        },

        dataType:"json",

       success:function(res){

            var selectedEmployee = employeeDropdown.attr('data-selected');

            employeeDropdown.html('<option value="">-- Select Employee --</option>');

            $.each(res, function(i, row){

                employeeDropdown.append(
                    '<option value="'+row.employee_id+'">'+row.employee_name+'</option>'
                );

            });

            var selectedEmployee = employeeDropdown.attr('data-selected');
            console.log(selectedEmployee);
            employeeDropdown.val(String(selectedEmployee));

        }

    });

});
function renumberTask(){

    $("#task_container .task-card").each(function(index){

        $(this).find(".task-title").html(
            'Task '+(index+1)+
            '<button type="button" class="btn btn-danger btn-sm remove_task remove-task"><i class="fa fa-trash"></i></button>'
        );

    });

}

  
</script>

