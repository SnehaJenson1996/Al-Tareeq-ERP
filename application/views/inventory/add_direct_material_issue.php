<form id="mi_form" action="<?= base_url('index.php/Inventory/save_direct_material_issue') ?>" method="post">
    <div class="container">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>Error!</strong>
                <?= $this->session->flashdata('error'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>Success!</strong>
                <?= $this->session->flashdata('success'); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <!-- Branch Details -->
            <tr>
                <th>Branch</th>
                <td>
                    <select name="branch_id" id="branch_id" class="form-control select2" required>
                        <option value="">-- Select Branch --</option>
                        <?php foreach ($branch_records as $b) { ?>
                            <option value="<?php echo $b->branch_id; ?>"><?php echo $b->branch_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <!-- Warehouse Details -->
            <tr>
                <th>Warehouse <span class="text-danger">*</span></th>
                <td>
                    <select
                        class="form-control"
                        name="warehouse_id"
                        id="warehouse_id"
                        onchange="loadStores()"
                        required>

                        <option value="">-- Select Warehouse --</option>
                        <?php foreach ($warehouse_list as $warehouse) { ?>
                            <option value="<?= $warehouse->warehouse_id; ?>">
                                <?= $warehouse->warehouse_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <!-- Store Details -->
            <tr>
                <th>Store <span class="text-danger">*</span></th>
                <td>
                    <select
                        class="form-control"
                        name="store_id"
                        id="store_id"
                        required>

                        <option value="">-- Select Store --</option>

                    </select>
                </td>
            </tr>

            <!-- Project Details -->
            <tr>
                <th width="25%">Project <span class="text-danger">*</span></th>
                <td>
                    <select name="project_id" id="project_id" class="form-control" required>
                        <option value="">-- Select Project --</option>
                        <?php foreach ($approved_projects as $proj): ?>
                            <option value="<?= $proj['project_id'] ?>"
                                <?= (isset($selected_project_id) && $selected_project_id == $proj['project_id']) ? 'selected' : '' ?>>
                                <?= $proj['project_name'] ?> (<?= $proj['project_code'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- Customer Details -->
            <tr>
                <th>Customer</th>
                <td id="customer_name">-</td>
            </tr>

        </table>

        <div class="text-right mb-2">
            <button type="button" class="btn btn-primary" id="addRow">
                <i class="fa fa-plus"></i> Add Item
            </button>
        </div>
        <!-- Items Table -->
        <table class="table table-bordered table-striped" id="items_table">
            <thead>
                <tr>
                    <th width="5%">Sl.No</th>
                    <th width="28%">Product <span class="text-danger">*</span></th>
                    <th width="10%">Unit</th>
                    <th width="12%">Requested Qty</th>
                    <th width="12%">Available Stock</th>
                    <th width="12%">Previously Issued</th>
                    <th width="12%">Issue Qty</th>
                    <th width="12%">Pending Qty</th>
                    <th width="7%">Action</th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>

        <!-- Hidden Fields -->
        <input type="hidden" name="project_code" id="project_code">
        <input type="hidden" name="customer_name" id="customer_name_input">
        <input type="hidden" name="branch_name" id="branch_name_input">

        <button id="saveBtn" class="btn btn-success">Material Issue / Purchase request</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const products = <?= json_encode($products); ?>;
        const units = <?= json_encode($units); ?>;
        let rowIndex = 0;

        $('#addRow').click(function() {
            rowIndex++;
            let productOption = '<option value="">-- Select Product --</option>';
                products.forEach(function(item) {
                    productOption += `
                    <option value="${item.product_id}">
                        ${item.product_name}
                    </option>
                    `;
            });

            let unitOption = `
                <option value="">-- Select Unit --</option>
                    `;
                units.forEach(function(unit) {
                    unitOption += `
                    <option value="${unit.unit_id}">
                        ${unit.unit_name}
                    </option>
                `;

            });

            let html = `<tr>
                <td class="slno"></td>
                <td>
                    <select
                        class="form-control product_id select2"
                        name="product_id[]"
                        id="product${rowIndex}"
                        onchange="get_product_details(${rowIndex})"
                        required>
                        ${productOption}
                    </select>

                </td>

                <td>
                    <select
                    class="form-control"
                    name="unit_id[]"
                    id="unit${rowIndex}"
                    required>
                    ${unitOption}
                    </select>
                </td>

                <td>
                    <input
                    type="number"
                    class="form-control requested_qty"
                    name="requested_qty[]"
                    id="requested${rowIndex}"
                    min="1"
                    value="1">
                </td>

                <td>
                    <input
                    type="number"
                    class="form-control available_stock"
                    id="available${rowIndex}"
                    readonly>
                </td>

                <td>
                    <input
                    type="number"
                    class="form-control prev_issued"
                    id="previous${rowIndex}"
                    value="0"
                    readonly>
                </td>

                <td>
                    <input
                    type="number"
                    class="form-control issue_qty"
                    name="issue_qty[]"
                    id="issue${rowIndex}"
                    value="0"
                    min="0">
                </td>

                <td><input
                    type="number"
                    class="form-control pending_qty"
                    name="pending_qty[]"
                    id="pending${rowIndex}"
                    readonly>
                </td>

                <td>
                    <button
                        type="button"
                        class="btn btn-danger removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;

            $('#items_table tbody').append(html);
            $('#product' + rowIndex).select2({
                width: '100%',
                placeholder: 'Search Product'
            });
            updateSlNo();
        });

        // Recalculate Pending live when Issue qty changes
        $(document).on('input', '.requested_qty,.issue_qty', function() {
            let row = $(this).closest('tr');
            let requested = parseFloat(row.find('.requested_qty').val()) || 0;
            let issue = parseFloat(row.find('.issue_qty').val()) || 0;
            let available = parseFloat(row.find('.available_stock').val()) || 0;

            if (issue > available) {
                issue = available;
                row.find('.issue_qty').val(issue);
            }

            let pending = requested - issue;
            if (pending < 0)
                pending = 0;

            row.find('.pending_qty').val(pending);
        });

    });

    function updateSlNo() {
        $('#items_table tbody tr').each(function(index) {
            $(this).find('.slno').html(index + 1);
        });
    }

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        updateSlNo();
    });

    document.getElementById("mi_form").addEventListener("submit", function(e) {
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

    function loadStores() {
        var warehouse_id = $("#warehouse_id").val();
        if (warehouse_id == "") {
            $("#store_id").html('<option value="">-- Select Store --</option>');
            return;
        }

        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_store_by_warehouse'); ?>",
            type: "POST",
            data: {
                warehouse_id: warehouse_id
            },
            dataType: "json",
            success: function(result) {
                var html = '<option value="">-- Select Store --</option>';
                $.each(result, function(i, row) {
                    html += '<option value="' + row.store_id + '">' + row.store_name + '</option>';
                });
                $("#store_id").html(html);
            }
        });
    }

    $('#store_id').change(function() {
        refreshAvailableStock();
    });

    function refreshAvailableStock() {
        let warehouse_id = $('#warehouse_id').val();
        let store_id = $('#store_id').val();

        if (warehouse_id == '' || store_id == '')
            return;

        let product_ids = [];
        $('.product_id').each(function() {
            product_ids.push($(this).val());
        });

        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_available_stock_ajax'); ?>",
            type: "POST",
            data: {
                warehouse_id: warehouse_id,
                store_id: store_id,
                product_ids: product_ids
            },
            dataType: "json",
            success: function(stock) {
                $('#items_table tbody tr').each(function() {
                    let product_id = $(this).find('.product_id').val();
                    let available = parseFloat(stock[product_id]) || 0;

                    $(this).find('.available_stock').val(available);

                    let requested = parseFloat($(this).find('.requested_qty').val()) || 0;
                    let issue = Math.min(requested, available);

                    $(this).find('.issue_qty').val(issue);
                    $(this).find('.issue_qty').attr('max', available);
                    $(this).find('.pending_qty').val(requested - issue);
                });
            }
        });
    }

    function get_product_details(row) {
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_direct_issue_product_details'); ?>",
            type: "POST",
            data: {
                product_id: $('#product' + row).val(),
                warehouse_id: $('#warehouse_id').val(),
                store_id: $('#store_id').val(),
                project_id: $('#project_id').val()
            },
            dataType: "json",
            success: function(res) {
                $('#unit' + row).val(res.unit_id);
                $('#available' + row).val(res.available_stock);
                $('#previous' + row).val(res.previously_issued);
            }
        });
    }

    $('#project_id').change(function() {
        var project_id = $(this).val();
        if (project_id == '') {
            $('#customer_name').text('-');
            $('#customer_name_input').val('');
            $('#project_code').val('');
            $('#branch_name_input').val('');
            return;
        }

        $.ajax({
            url: "<?= base_url('index.php/Project/get_project_details_ajax'); ?>",
            type: "POST",
            data: {
                project_id: project_id
            },
            dataType: "json",
            success: function(res) {
                $('#customer_name').text(res.project.customer_name);
                $('#customer_name_input').val(res.project.customer_name);
                $('#project_code').val(res.project.project_code);
                $('#branch_name_input').val(res.project.branch_name);
            }
        });

    });
</script>