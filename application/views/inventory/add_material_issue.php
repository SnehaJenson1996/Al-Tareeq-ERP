<form id="mi_form" action="<?= base_url('index.php/Inventory/save_material_issue') ?>" method="post">
<div class="container">

    <!-- Material Request Selection -->
    <div class="mb-3">
        <label>Select Material Request</label>
        <select name="mr_id" id="mr_id" class="form-control" required>
            <option value="">-- Select MR --</option>
            <?php foreach($material_requests as $mr): ?>
                <option value="<?= $mr['mr_id'] ?>">
                    <?= $mr['mr_code'] ?> (<?= $mr['project_name'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- MR Details -->
    <table class="table table-bordered">
        <tr><th>Project</th><td id="project_name">-</td></tr>
        <tr><th>Customer</th><td id="customer_name">-</td></tr>
        <tr><th>Branch</th><td id="branch_name">-</td></tr>
    </table>

    <!-- Items Table -->
    <table class="table table-bordered" id="items_table">
        <thead>
            <tr>
                <th>#</th>
                <th>Select</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Requested qty</th>
                <!-- <th>Reserved</th> -->
                <th>Available Stock</th>
                <th>Previously Issued</th>
                <th>Issue qty</th>
                <th>Pending stock</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="8">Select MR</td></tr>
        </tbody>
    </table>


<!-- Hidden Fields -->
<input type="hidden" name="project_id" id="project_id">
<input type="hidden" name="project_code" id="project_code">
<input type="hidden" name="customer_name" id="customer_name_input">
<input type="hidden" name="branch_name" id="branch_name_input">

    

    <button id="saveBtn" class="btn btn-success">Material Issue / Purchase request</button>
</div>
</form>

<script>
$(document).ready(function(){

    const units = <?= json_encode($units); ?>; // array of units from PHP

    // On MR selection
    $('#mr_id').change(function(){
        let mr_id = $(this).val();
        if(!mr_id) return;

        $.post('<?= base_url("index.php/Inventory/get_mr_details_ajax") ?>',
        {mr_id: mr_id}, function(res){

               // Update visible table
        $('#project_name').text(res.mr.project_name);
        $('#customer_name').text(res.mr.customer_name);
        $('#branch_name').text(res.mr.branch_name);

        // Update hidden inputs
        $('#project_id').val(res.mr.project_id);
        $('#project_code').val(res.mr.project_code);
        $('#customer_name_input').val(res.mr.customer_name);
        $('#branch_name_input').val(res.mr.branch_name);
            // Build table rows
            let rows = '';
            res.items.forEach((item, i) => {

                let default_issue = Math.min(item.available_qty, item.requested_qty);
                let pending = item.requested_qty - default_issue;
                if(pending < 0) pending = 0;

                rows += `
                <tr>
                    <td>${i+1}</td>

                     <td class="text-center">
        <input type="checkbox"
               class="item_check"
               name="item_check[${i}]"
               value="1"
               checked>
    </td>
                    <td>${item.product_name}</td>

                    <!-- Unit -->
                    <td>
                        <select name="unit_id[]" class="form-control">
                            ${units.map(u => `
                                <option value="${u.unit_id}" ${u.unit_id==item.item_unit?'selected':''}>
                                    ${u.unit_name}
                                </option>`).join('')}
                        </select>
                        <!-- Hidden field always submitted -->
    <input type="hidden" name="unit_id[]" value="${item.item_unit}">
                    </td>

                    <!-- Requested -->
                    <td>
                        <input type="number" class="form-control requested_qty"
                               name="requested_qty[]"
                               value="${item.requested_qty}" readonly>
                    </td>

                    

                    <!-- Available -->
                    <td>
                        <input type="number" class="form-control available"
                               value="${item.available_qty}" readonly>
                    </td>

                      <!-- Previously Issued -->
    <td>
        <input type="number" class="form-control prev_issued"
               name="previously_issued[]"
               value="${item.issued_qty_total || 0}" readonly>
    </td>

                    <!-- Issue -->
                    <td>
                        <input type="number" class="form-control issue_qty"
                               name="issue_qty[]"
                               max="${item.available_qty}"
                               value="${default_issue}">
                    </td>

                    <!-- Pending -->
                    <td>
                        <input type="number" class="form-control pending_qty"
                               name="pending_qty[]"
                               value="${pending}" readonly>
                    </td>

                    <!-- Hidden Product ID -->
                    <input type="hidden" name="product_id[]" value="${item.product_id}">
                </tr>`;
            });

            $('#items_table tbody').html(rows);

        }, 'json');
    });

    // Recalculate Pending live when Issue qty changes
    $(document).on('input', '.issue_qty', function(){
        let $row = $(this).closest('tr');
        let max = parseFloat($(this).attr('max')) || 0;
        let val = parseFloat($(this).val()) || 0;

        if(val > max) val = max;
        if(val < 0) val = 0;
        $(this).val(val);

        // Pending = Requested - Issue
        let requested = parseFloat($row.find('.requested_qty').val()) || 0;
        let pending = requested - val;
        if(pending < 0) pending = 0;
        $row.find('.pending_qty').val(pending);
    });

});

// Enable/Disable row inputs based on checkbox
$(document).on('change', '.item_check', function () {
    let $row = $(this).closest('tr');
    let isChecked = $(this).is(':checked');

    $row.find('select, input.issue_qty').prop('disabled', !isChecked);
});

document.getElementById("mi_form").addEventListener("submit", function (e) {

    var btn = document.getElementById("saveBtn");

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    // Disable immediately
    btn.disabled = true;
    btn.innerHTML = "Processing...";

});

</script>



<!-- Reserved -->
                    <!-- <td>
                        <input type="number" class="form-control reserved_qty"
                               value="${item.reserved_qty}" readonly>
                    </td> -->