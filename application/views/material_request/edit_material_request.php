<form id="mr_form" action="<?= base_url('index.php/Project/update_material_request') ?>" method="post">

<input type="hidden" name="mr_id" value="<?= $mr['mr_id'] ?>">

<!-- Select Approved Project -->
<div class="mb-3">
    <label for="project_id" class="form-label">Select Approved Project</label>
    <select name="project_id" id="project_id" class="form-control" required>
        <option value="">-- Select Project --</option>
        <?php foreach ($approved_projects as $proj): ?>
            <option value="<?= $proj['project_id'] ?>"
                <?= ($proj['project_id'] == $mr['project_id']) ? 'selected' : '' ?>>
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
                <?= ($user['user_id'] == $mr['initiated_by']) ? 'selected' : '' ?>>
                <?= $user['user_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Auto-filled Project Info -->
<table class="table table-bordered">
<tr>
    <th>Project</th>
    <td id="project_name"><?= $mr['project_name'] ?></td>
</tr>
<tr>
    <th>Customer</th>
    <td id="customer_name"><?= $mr['customer_name'] ?></td>
</tr>
<tr>
    <th>Branch</th>
    <td id="branch_name"><?= $mr['branch_name'] ?></td>
</tr>
<tr>
    <th>Requested Date</th>
    <td>
        <input type="date" name="requested_date" class="form-control"
               value="<?= $mr['requested_date'] ?>" required>
    </td>
</tr>
<tr>
    <th>Required Date</th>
    <td>
        <input type="date" name="required_date" class="form-control"
               value="<?= $mr['required_date'] ?>" required>
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
<?php if(!empty($mr_items)): ?>
    <?php foreach($mr_items as $i => $item): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= $item['product_name'] ?></td>
        <td>
            <select name="unit_id[]" class="form-control" required>
                <option value="">-- Select Unit --</option>
                <?php foreach($units as $u): ?>
                    <option value="<?= $u['unit_id'] ?>" 
                        <?= ($u['unit_id'] == $item['unit']) ? 'selected' : '' ?>>
                        <?= $u['unit_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
            <input type="number" name="quantity[]" value="<?= $item['quantity'] ?>" class="form-control">
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4">Select a project to load items</td>
    </tr>
<?php endif; ?>
</tbody>
</table>


<input type="hidden" name="project_code" id="project_code" value="<?= $mr['project_code'] ?>">
<input type="hidden" name="customer_name" id="hidden_customer_name" value="<?= $mr['customer_name'] ?>">
<input type="hidden" name="branch_name" id="hidden_branch_name" value="<?= $mr['branch_name'] ?>">

<div class="text-end mt-3">
    <button type="submit" class="btn btn-success">Update MR</button>
    <a href="<?= base_url('index.php/Project/list_material_request') ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<script>
$(document).ready(function(){
    $('#project_id').change(function(){
        var project_id = $(this).val();
        if(!project_id){
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
                $('#project_name').text(res.project.project_name);
                $('#customer_name').text(res.project.customer_name);
                $('#branch_name').text(res.project.branch_name);

                $('#project_code').val(res.project.project_code);
                $('#hidden_customer_name').val(res.project.customer_name);
                $('#hidden_branch_name').val(res.project.branch_name);

                var rows = '';
                res.items.forEach(function(item, i){
                    rows += `<tr>
                        <td>${i+1}</td>
                        <td>${item.product_name}</td>
                        <td>
                            <input type="hidden" name="product_id[]" value="${item.product_id}">
                            <input type="number" name="quantity[]" value="${item.quantity}" class="form-control">
                        </td>
                    </tr>`;
                });

                $('#items_table tbody').html(rows);
            }
        });
    });
});
</script>
