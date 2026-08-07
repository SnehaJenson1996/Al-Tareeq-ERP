<form method="post"
    action="<?= base_url('index.php/Inventory/save_stock_transfer'); ?>"
    id="transfer_form">

    <div class="x_panel">

        <div class="x_content">
            <table class="table table-bordered">
                <tr>
                    <th width="25%">Transfer Date <span class="text-danger">*</span></th>
                    <td width="25%">
                        <input
                            type="date"
                            name="transfer_date"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required>
                    </td>

                    <th width="25%">Status</th>
                    <td width="25%">
                        <input
                            type="text"
                            class="form-control"
                            value="Completed"
                            readonly>
                    </td>
                </tr>
            </table>

            <hr>

            <h4><b>From Location</b></h4>

            <table class="table table-bordered">
                <tr>
                    <th width="25%">Branch <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="from_branch_id"
                            id="from_branch_id"
                            class="form-control"
                            required>
                            <option value="">-- Select Branch --</option>
                            <?php foreach ($branch_records as $row) { ?>
                                <option value="<?= $row->branch_id; ?>">
                                    <?= $row->branch_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Warehouse <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="from_warehouse_id"
                            id="from_warehouse_id"
                            class="form-control"
                            required
                            onchange="loadFromStores()">
                            <option value="">-- Select Warehouse --</option>
                            <?php foreach ($warehouse_list as $row) { ?>
                                <option value="<?= $row->warehouse_id; ?>">
                                    <?= $row->warehouse_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Store <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="from_store_id"
                            id="from_store_id"
                            class="form-control"
                            required>
                            <option value="">-- Select Store --</option>
                        </select>
                    </td>
                </tr>
            </table>

            <hr>

            <h4><b>To Location</b></h4>

            <table class="table table-bordered">
                <tr>
                    <th width="25%">Branch <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="to_branch_id"
                            id="to_branch_id"
                            class="form-control"
                            required>
                            <option value="">-- Select Branch --</option>
                            <?php foreach ($branch_records as $row) { ?>
                                <option value="<?= $row->branch_id; ?>">
                                    <?= $row->branch_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Warehouse <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="to_warehouse_id"
                            id="to_warehouse_id"
                            class="form-control"
                            required
                            onchange="loadToStores()">
                            <option value="">-- Select Warehouse --</option>
                            <?php foreach ($warehouse_list as $row) { ?>
                                <option value="<?= $row->warehouse_id; ?>">
                                    <?= $row->warehouse_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Store <span class="text-danger">*</span></th>
                    <td>
                        <select
                            name="to_store_id"
                            id="to_store_id"
                            class="form-control"
                            required>
                            <option value="">-- Select Store --</option>
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered">
                <tr>
                    <th width="25%">Remarks</th>
                    <td>
                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"></textarea>

                    </td>
                </tr>
            </table>

            <hr>

            <div class="row">

                <div class="col-md-12">

                    <div class="text-right" style="margin-bottom:15px;">

                        <button
                            type="button"
                            class="btn btn-primary"
                            id="addRow">

                            <i class="fa fa-plus"></i>

                            Add Item

                        </button>

                    </div>

                    <table
                        class="table table-bordered"
                        id="items_table">

                        <thead>

                            <tr>

                                <th width="5%">Sl.No</th>

                                <th width="30%">Product</th>

                                <th width="10%">Unit</th>

                                <th width="12%">Available Qty</th>

                                <th width="12%">Transfer Qty</th>

                                <th>Remarks</th>

                                <th width="5%">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="text-center">

                <button
                    type="submit"
                    class="btn btn-success btn-lg"
                    id="saveBtn">

                    <i class="fa fa-save"></i>

                    Save Stock Transfer

                </button>

                <button
                    type="reset"
                    class="btn btn-default btn-lg">

                    Reset

                </button>

            </div>

</form>
</div>
</div>

<script>
    const products = <?= json_encode($products); ?>;
    const units = <?= json_encode($units); ?>;
    let rowIndex = 0;
    $('#addRow').click(function() {
        rowIndex++;
        let productOption = '<option value="">-- Select Product --</option>';
        products.forEach(function(product) {
            productOption += `
                <option value="${product.product_id}">
                    ${product.product_code} - ${product.product_name}
                </option>
            `;
        });

        let unitOption = '<option value="">-- Select Unit --</option>';
        units.forEach(function(unit) {
            unitOption += `
                <option value="${unit.unit_id}">
                    ${unit.unit_name}
                </option>
            `;
        });

        let html = `
            <tr>
                <td class="slno"></td>

                <td>
                    <select
                    class="form-control product_id select2"
                    name="product_id[]"
                    required>
                    ${productOption}
                    </select>
                </td>

                <td>
                    <select
                    class="form-control unit_id"
                    name="unit_id[]"
                    required>
                    ${unitOption}
                    </select>
                </td>

                <td>
                    <input
                    type="text"
                    class="form-control available_qty"
                    readonly>
                </td>

                <td>
                    <input
                    type="number"
                    class="form-control transfer_qty"
                    name="transfer_qty[]"
                    min="1"
                    value="1"
                    required>
                </td>

                <td>
                    <input
                    type="text"
                    class="form-control"
                    name="item_remark[]">
                </td>

                <td class="text-center">
                    <button
                    type="button"
                    class="btn btn-danger removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        $('#items_table tbody').append(html);
        $('#items_table tbody tr:last .product_id').select2({
            width: '100%',
            placeholder: 'Search Product',
            allowClear: true
        });
        updateSlNo();
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

    $(document).on('change', '.product_id', function() {
        var row = $(this).closest('tr');
        var product_id = $(this).val();
        var warehouse_id = $('#from_warehouse_id').val();
        var store_id = $('#from_store_id').val();

        if (warehouse_id == '') {
            alert('Please select From Warehouse first.');
            $(this).val('');
            return;
        }

        if (store_id == '') {
            alert('Please select From Store first.');
            $(this).val('');
            return;
        }
        getProductDetails(row, product_id);
    });

    function getProductDetails(row, product_id) {
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_direct_issue_product_details'); ?>",
            type: "POST",
            data: {
                product_id: product_id
            },
            dataType: "json",
            success: function(res) {
                row.find('.unit_id').val(res.unit_id);
                getAvailableStock(row, product_id);
            }
        });
    }

    function loadFromStores() {
        var warehouse_id = $('#from_warehouse_id').val();
        $('#from_store_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_store_by_warehouse'); ?>",
            type: "POST",
            data: {
                warehouse_id: warehouse_id
            },
            dataType: "json",
            success: function(res) {
                var html = '<option value="">-- Select Store --</option>';
                $.each(res, function(i, row) {
                    html += '<option value="' + row.store_id + '">' + row.store_name + '</option>';
                });
                $('#from_store_id').html(html);
            }
        });
    }

    function loadToStores() {
        var warehouse_id = $('#to_warehouse_id').val();
        $('#to_store_id').html('<option value="">Loading...</option>');
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_store_by_warehouse'); ?>",
            type: "POST",
            data: {
                warehouse_id: warehouse_id
            },
            dataType: "json",
            success: function(res) {
                var html = '<option value="">-- Select Store --</option>';
                $.each(res, function(i, row) {
                    html += '<option value="' + row.store_id + '">' + row.store_name + '</option>';
                });
                $('#to_store_id').html(html);
            }
        });
    }

    function getAvailableStock(row, product_id) {
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_available_stock_ajax'); ?>",
            type: "POST",
            data: {
                warehouse_id: $('#from_warehouse_id').val(),
                store_id: $('#from_store_id').val(),
                product_ids: [product_id]
            },
            dataType: "json",
            success: function(res) {
                row.find('.available_qty').val(res[product_id]);
            }
        });
    }

    $(document).on('change', '.product_id', function() {
        var current = $(this).val();
        var count = 0;

        $('.product_id').each(function() {
            if ($(this).val() == current && current != '') {
                count++;
            }
        });

        if (count > 1) {
            alert('This product is already added.');
            $(this).val('');
            $(this).closest('tr').find('.unit_id').val('');
            $(this).closest('tr').find('.available_qty').val('');
            return false;
        }
    });

    $(document).on('keyup change', '.transfer_qty', function() {
        var row = $(this).closest('tr');
        var available = parseFloat(row.find('.available_qty').val()) || 0;
        var qty = parseFloat($(this).val()) || 0;

        if (qty <= 0) {
            qty = 1;
        }

        if (qty > available) {
            alert('Transfer Qty cannot exceed Available Qty.');
            qty = available;
        }

        $(this).val(qty);
    });

    $('#to_store_id').change(function() {
        var fromWarehouse = $('#from_warehouse_id').val();
        var toWarehouse = $('#to_warehouse_id').val();
        var fromStore = $('#from_store_id').val();
        var toStore = $('#to_store_id').val();

        if (fromWarehouse == toWarehouse && fromStore == toStore) {
            alert('Source and Destination cannot be same.');
            $(this).val('');
        }
    });

    $('#transfer_form').submit(function(e) {
        if ($('#items_table tbody tr').length == 0) {
            alert('Please add at least one item.');
            e.preventDefault();
            return;
        }

        var valid = true;
        $('.transfer_qty').each(function() {
            if (parseFloat($(this).val()) <= 0) {
                valid = false;
            }
        });

        if (!valid) {
            alert('Transfer Qty should be greater than zero.');
            e.preventDefault();
            return;
        }

        $('#saveBtn').prop('disabled', true);
        $('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    });
</script>