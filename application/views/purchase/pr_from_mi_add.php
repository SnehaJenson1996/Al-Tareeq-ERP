<!-- page content -->
<form id="main" method="post" action="<?php echo base_url('index.php/Purchase/save_pr_from_mi'); ?>" autocomplete="off" enctype="multipart/form-data">
    <div class="form-group" role="main">
        <div class="page-title"></div>
        <div class="clearfix"></div>

        <div class="x_content">
            <div class="well" style="overflow: auto">

                <!-- PR Code -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">PR Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pr_code" readonly value="<?php echo $Code; ?>">  
                    </div>
                </div>

                <!-- PR Date -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">PR Date</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" name="pr_date" id="pr_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <br><br><br>

                

                <!-- Material Issue -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Select Material Issue</label>
                    <div class="col-md-9">
                        <select name="mi_id" id="mi_id" class="form-control select2" required>
                            <option value="">-- Select MI --</option>
                            <?php foreach($material_issues as $mi) { ?>
                                <option value="<?= $mi->mi_id ?>"><?= $mi->mi_code ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Supplier -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Select Supplier</label>
                    <div class="col-md-9">
                        <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                            <option value="">Please select supplier</option>
                            <?php foreach($supplier_records as $s) { ?>
                                <option value="<?php echo $s->supplier_id; ?>"><?php echo $s->supplier_name.' ('.$s->supplier_code.')'; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <br><br><br>
<!-- Branch -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Select Branch</label>
                    <div class="col-md-9">
                        <select name="branch_id" id="branch_id" class="form-control select2" required>
                            <option value="">Please select branch</option>
                            <?php foreach($branch_records as $b) { ?>
                                <option value="<?php echo $b->branch_id; ?>"><?php echo $b->branch_name; ?></option>
                            <?php } ?>
                        </select>  
                    </div>
                </div>
                

                <!-- Subject -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Subject</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="subject" id="subject">
                    </div>
                </div>
                <br><br><br>

                <!-- Project Name -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Project Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="project" id="project" readonly>

                    </div>
                </div>

                <!-- Reference -->
                <div class="col-md-6">
                    <label class="control-label col-md-3">Reference</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="ref" id="ref">
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="row col-md-12" style="overflow-x:auto; margin-top:20px;">
            <div class="x_content">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap">
                   <thead>
    <tr>
        <th>Product</th>
        <th>Brand</th>
        <th>Description</th>
        <th>Unit</th>
        <th>Pending Quantity</th>
        <th>Actions</th>
    </tr>
</thead>

                    <tbody id="mi_items_body">
                        <!-- Items will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks -->
        <div class="x_content well" style="margin-top:20px;">
            <div class="row col-md-12">
                <label class="control-label col-md-2">Remarks</label>
                <div class="col-md-10">
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row col-md-12" style="margin-top:20px;">
                <button type="submit" id="saveBtn" name="submit_action" value="save" class="btn btn-success">Save</button>
                <!-- <button type="submit" name="submit_action" value="save_and_create_po" class="btn btn-primary">Save &amp; Create Supplier quote</button> -->
            </div>
        </div>
    </div>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    $('#mi_id').change(function() {
        var mi_id = $(this).val();

        if (mi_id) {

            /* =============================
               1️⃣ Load MI Items
            ============================= */
            $.ajax({
                url: '<?= base_url("index.php/Ajax/get_mi_items_ajax") ?>',
                type: 'POST',
                data: { mi_id: mi_id },
                dataType: 'json',
                success: function(items) {

                    var tbody = '';
                    $.each(items, function(index, item) {
                        tbody += `
                        <tr>
                            <td>
                                <input type="hidden" name="product_id[]" value="${item.product_id}">
                                ${item.item_name}
                            </td>
                            <td>${item.brand_name}</td>
                            <td>${item.item_description}</td>
                            <td>
                                <select class="form-control" name="unit[]" required>
                                    <option value="${item.unit_id}" selected>${item.unit_name}</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="quantity[]"
                                    value="${item.pending_qty}" min="0" required>
                            </td>
                            <td>
                                <button class="deleteRow btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });

                    $('#mi_items_body').html(tbody);
                }
            });

            /* =============================
               2️⃣ Load Branch + Project Name
            ============================= */
            $.ajax({
                url: '<?= base_url("index.php/Ajax/get_mi_details_ajax") ?>',
                type: 'POST',
                data: { mi_id: mi_id },
                dataType: 'json',
                success: function(data) {

                    if (data) {
                        // Set project name
                        $('#project').val(data.project_name);

                        // Auto-select branch
                        $('#branch_id option').each(function() {
                            if ($(this).text().trim() === data.branch_name.trim()) {
                                $(this).prop('selected', true);
                            }
                        });

                        $('#branch_id').trigger('change');
                    }
                }
            });

        } else {
            $('#mi_items_body').html('');
            $('#project').val('');
            $('#branch_id').val('').trigger('change');
        }
    });

    /* =============================
       Delete Row
    ============================= */
    $(document).on('click', '.deleteRow', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    /* =============================
       Quantity Validation
    ============================= */
    $(document).on('input', 'input[name="quantity[]"]', function() {
        if ($(this).val() <= 0) {
            $(this).css('border', '1px solid red');
        } else {
            $(this).css('border', '');
        }
    });

    $('.select2').select2({
    width: '100%',
    placeholder: "Select an option",
    allowClear: true
});

});

 document.getElementById("main").addEventListener("submit", function (e) {

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

