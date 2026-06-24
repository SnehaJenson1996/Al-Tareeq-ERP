<div class="x_panel">

    <div class="x_content">
        <div class="row">

            <!-- LEFT : ITEM DETAILS -->
            <div class="col-md-8">
                <div class="row">

                    <div class="col-md-6 form-group">
                        <label>Item Name</label>
                        <input type="text" class="form-control" value="<?= $item['item_name']; ?>" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Item Code</label>
                        <input type="text" class="form-control" value="<?= $item['item_code']; ?>" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Brand</label>
                        <input type="text" class="form-control" value="<?= $item['brand_name']; ?>" readonly>
                    </div>

                    <!-- <div class="col-md-6 form-group">
                        <label>Model</label>
                        <input type="text" class="form-control" value="<?= $item['item_model']; ?>" readonly>
                    </div> -->

                    <div class="col-md-6 form-group">
                        <label>Unit</label>
                        <input type="text" class="form-control" value="<?= $item['unit_name']; ?>" readonly>
                    </div>

                    <!-- <div class="col-md-6 form-group">
                        <label>HS Code</label>
                        <input type="text" class="form-control" value="<?= $item['hs_code']; ?>" readonly>
                    </div> -->

                </div>
            </div>

            <!-- RIGHT : ITEM IMAGE -->
            <div class="col-md-4 text-center">
                <label>Item Image</label><br>

                <?php if (!empty($item['item_image'])): ?>
                    <img src="<?= base_url('uploads/items/' . $item['item_image']); ?>"
                        class="img-thumbnail"
                        style="max-height: 200px;">
                <?php else: ?>
                    <div class="text-muted">No Image Available</div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>
<div class="x_panel"><br>
    <div class="x_title">
        <h2>Reservation Details (Priority Wise)</h2>
    </div>

    <div class="x_content">
        <table class="table table-bordered table-striped">
            <thead style="background-color:#d9edf7;">
                <tr>
                    <th>Priority</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>SO No</th>
                    <th>Reserved Qty</th>
                    <th>Pending Qty</th>
                    <th>Reserved Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php foreach ($reservations as $row): ?>
                <tr>
                    <td>
                        <input type="number"
                            class="form-control priority-input"
                            value="<?= $row['reserve_priority']; ?>"
                            min="1"
                            data-stock-id="<?= $row['stock_id']; ?>">
                    </td>

                    <td><?= $row['customer_name']; ?></td>
                    <td><?= $row['branch_name']; ?></td>
                    <td><?= $row['so_code']; ?></td>

                    <td><?= $row['reserved_quantity']; ?></td>

                    <td><?= $row['pending_quantity']; ?></td>
                    <td>
                        <?= !empty($row['reserved_date']) ? date('d-m-Y H:i', strtotime($row['reserved_date'])) : '-'; ?>
                    </td>

                    <td>
                        <span class="badge badge-info"><?= $row['stock_status']; ?></span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm release-btn"
                            data-stock-id="<?= $row['stock_id']; ?>"
                            data-reserved="<?= $row['reserved_quantity']; ?>">
                            Release
                        </button>


                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>
<div class="modal fade" id="releaseModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Release Reserved Quantity</h5>
            </div>

            <div class="modal-body">
                <input type="hidden" id="release_stock_id">

                <div class="form-group">
                    <label>Reserved Qty</label>
                    <input type="text" id="reserved_qty"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Release Qty</label>
                    <input type="number" id="release_qty"
                        class="form-control"
                        min="1">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger"
                    id="confirmRelease">Release</button>
            </div>

        </div>
    </div>
</div>

<script>
    $('.priority-input').on('change', function() {
        let stock_id = $(this).data('stock-id');
        let new_priority = $(this).val();
        $.ajax({
            url: "<?= base_url('index.php/Inventory/update_stock_priority'); ?>",
            type: "POST",
            data: {
                stock_id: stock_id,
                priority: new_priority
            },
            success: function(res) {
                if (res.status === 'error') {
                    alert(res.message);
                    //  location.reload();
                } else {
                    //location.reload();
                }
            }
        });
    });
    $('.release-btn').on('click', function() {

        $('#release_stock_id').val($(this).data('stock-id'));
        $('#reserved_qty').val($(this).data('reserved'));
        $('#release_qty').val('');

        $('#releaseModal').modal('show');
    });


    $('#confirmRelease').on('click', function() {

        let stock_id = $('#release_stock_id').val();
        let reserved = parseInt($('#reserved_qty').val());
        let releaseQty = parseInt($('#release_qty').val());

        if (!releaseQty || releaseQty <= 0) {
            alert('Enter valid release quantity');
            return;
        }

        if (releaseQty > reserved) {
            alert('Release quantity cannot exceed reserved quantity');
            return;
        }

        $.ajax({
            url: "<?= base_url('index.php/Inventory/release_partial'); ?>",
            type: "POST",
            data: {
                stock_id: stock_id,
                release_qty: releaseQty
            },
            success: function() {
                location.reload();
            }
        });
    });
</script>