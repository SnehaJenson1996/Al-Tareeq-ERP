
<form id="mr_form" action="<?= base_url('index.php/Project/save_material_request') ?>" method="post">

<!-- Select Approved Project -->
<div class="mb-3">
    <label for="project_id" class="form-label">Select Approved Project</label>
    <select name="project_id" id="project_id" class="form-control" required>
    <option value="">-- Select Project --</option>
    <?php foreach ($approved_projects as $proj): ?>
        <option value="<?= $proj['project_id'] ?>"
            <?= (isset($selected_project_id) && $selected_project_id == $proj['project_id']) ? 'selected' : '' ?>>
            <?= $proj['project_name'] ?> (<?= $proj['project_code'] ?>)
        </option>
    <?php endforeach; ?>
</select>

</div>

<!-- Initiated By -->
<div class="mb-3">
    <label for="initiated_by" class="form-label">Initiated By</label>
    <select name="initiated_by" id="initiated_by" class="form-control" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['user_id'] ?>"
                <?= ($user['user_id'] == $this->session->userdata('user_id')) ? 'selected' : '' ?>>
                <?= $user['user_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Auto-filled Project Info -->
<table class="table table-bordered">
<tr>
    <th>Project</th>
    <td id="project_name">-</td>
</tr>
<tr>
    <th>Customer</th>
    <td id="customer_name">-</td>
</tr>
<tr>
    <th>Branch</th>
    <td id="branch_name">-</td>
</tr>
<tr>
    <th>Requested Date</th>
    <td>
        <input type="date" name="requested_date" class="form-control" 
               value="<?= date('Y-m-d') ?>" required>
    </td>
</tr>
<tr>
    <th>Required Date</th>
    <td>
        <input type="date" name="required_date" class="form-control" required>
    </td>
</tr>
</table>

<h5>Items</h5>
<table class="table table-bordered" id="items_table">
<thead>
<tr>
    <th>#</th>
    <th>Item</th>
    <th>Unit</th>
    <th>Required Quantity</th>
</tr>
</thead>
<tbody>
<tr>
    <td colspan="3">Select a project to load items</td>
</tr>
</tbody>
</table>

<input type="hidden" name="project_code" id="project_code">
<input type="hidden" name="customer_name" id="hidden_customer_name">
<input type="hidden" name="branch_name" id="hidden_branch_name">

<div class="text-end mt-3">
    <button type="submit"  id="saveBtn" class="btn btn-success">Create MR</button>
    <a href="<?= base_url('index.php/Project/list_material_request') ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>
<script>
$(document).ready(function(){
    $('#project_id').change(function(){
        var project_id = $(this).val();
        var units = <?php echo json_encode($units); ?>;
        if(!project_id){
            // Clear fields
            $('#project_name').text('-');
            $('#customer_name').text('-');
            $('#branch_name').text('-');
            $('#items_table tbody').html('<tr><td colspan="3">Select a project to load items</td></tr>');
            return;
        }

        $.ajax({
            url: '<?= base_url("index.php/Project/get_project_details_ajax") ?>',
            type: 'POST',
            data: { project_id: project_id },
            dataType: 'json',
            success: function(res){
                // Fill project info
                $('#project_name').text(res.project.project_name);
                $('#customer_name').text(res.project.customer_name);
                $('#branch_name').text(res.project.branch_name);

                  // Fill hidden inputs for form submission
    $('#project_code').val(res.project.project_code);
    $('#hidden_customer_name').val(res.project.customer_name);
    $('#hidden_branch_name').val(res.project.branch_name);

                // Fill items table
                var rows = '';
                res.items.forEach(function(item, i){
                    rows += `<tr>
                        <td>${i+1}</td>
                        <td>${item.product_name}</td>
                        <td>
                <select name="unit_id[]" class="form-control" required>
                    <option value="">-- Select Unit --</option>
                    ${units.map(u => `<option value="${u.unit_id}" ${u.unit_id == item.item_unit ? 'selected' : ''}>${u.unit_name}</option>`).join('')}
                </select>
            </td>
                        <td>
                            <input type="hidden" name="product_id[]" value="${item.product_id}">
                            <input type="number" name="quantity[]" value="${item.quantity}" class="form-control" min="1">
                        </td>
                    </tr>`;
                });

                $('#items_table tbody').html(rows);
            }
        });
    });
});

$(document).ready(function(){
    var selectedProject = $('#project_id').val();
    if(selectedProject){
        $('#project_id').trigger('change'); // Auto-fill details
    }
});

document.getElementById("mr_form").addEventListener("submit", function (e) {
    var requestedDate = document.querySelector('input[name="requested_date"]').value;
    var requiredDate  = document.querySelector('input[name="required_date"]').value;
    var btn = document.getElementById("saveBtn");

    // Convert to Date objects
    if(requestedDate && requiredDate){
        var reqDate = new Date(requestedDate);
        var matDate = new Date(requiredDate);

        if(matDate < reqDate){
            alert("Material Required Date cannot be earlier than Requested Date.");
            document.querySelector('input[name="required_date"]').focus();
            e.preventDefault(); // Stop form submission
            btn.disabled = false;
            btn.innerHTML = "Create MR";
            return false;
        }
    }

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    btn.disabled = true;
    btn.innerHTML = "Processing...";
});
</script>
