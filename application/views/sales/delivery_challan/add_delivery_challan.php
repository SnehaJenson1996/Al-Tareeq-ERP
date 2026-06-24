<form action="<?= base_url() ?>index.php/Sales/create_delivery_challan" method="post">

    <div class="x_content well" style="overflow: auto">
        <!-- Invoice and Delivery Date -->
        <div class="row">
            <div class="col-md-6">
                <label class="control-label col-md-3">Select Order</label>
                <div class="col-md-9">
                    <select class="form-control" name="sales_order_del" id="sales_order_del">
                        <option value="">--Select sales order--</option>
                        <?php if (!empty($sales_order_list)): ?>
                            <?php foreach ($sales_order_list as $so):
                                $so_id = is_array($so) ? $so['so_id'] : $so->so_id;
                                $so_code = is_array($so) ? $so['so_code'] : $so->so_code;
                            ?>
                                <option value="<?= $so_id ?>"><?= htmlspecialchars($so_code) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="control-label col-md-3">Delivery Date</label>
                <div class="col-md-9">
                    <input type="date" class="form-control" name="dc_date" id="dc_date">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label class="control-label col-md-3">Delivery Code</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="delivery_code" id="delivery_code" value="<?= isset($delivery_code) ? $delivery_code : "" ?>" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <label class="control-label col-md-3">Delivery Mode</label>
                <div class="col-md-9">
                    <select class="form-control" id="delivery_mode" name="delivery_mode">
                        <option value="">--Select--</option>
                        <option value="Road">Road</option>
                        <option value="Air">Air</option>
                        <option value="Sea">Sea</option>
                        <option value="Courier">Courier</option>
                        <option value="Rail">Rail</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label class="control-label col-md-3">Delivered By</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="deliverd_by" id="deliverd_by">
                </div>
            </div>

            <div class="col-md-6">
                <label class="control-label col-md-3">Customer</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="del_customer" id="del_customer" value="" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="x_content mt-3" style="overflow-x:auto;">
        <table id="del_products_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filled dynamically via JS or PHP -->
            </tbody>
        </table>
    </div>

    <!-- Shipping Address Section -->
    <div class="x_content well mt-3">
        <div class="row">
            <label class="control-label col-md-2">Shipping Address</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="del_shipping_address" id="del_shipping_address">
            </div>

            <label class="control-label col-md-2">Shipping City</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="del_shipping_city" id="del_shipping_city">
            </div>
        </div>

        <div class="row mt-3">
            <label class="control-label col-md-2">Contact No</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="del_shipping_contact" id="del_shipping_contact">
            </div>

            <label class="control-label col-md-2">Email</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="del_shipping_email" id="del_shipping_email">
            </div>
        </div>

        <div class="row mt-3">
            <label class="control-label col-md-2">Remark</label>
            <div class="col-md-6">
                <textarea name="del_remark" id="del_remark" class="form-control"></textarea>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row mt-3">       
        <div class="row justify-content-center mt-3">
            <button type="submit" class="btn btn-success" name="action" value="save">Create new Delivery Note</button>
            <button type="submit" class="btn btn-primary ml-2" name="action" value="create_invoice">Save & Create Invoice</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#sales_order_del").on("change", function() {
            let so_id = $(this).val();


            $.ajax({
                url: "<?= base_url('index.php/Sales/get_sales_order_partial_del') ?>",
                type: "POST",
                data: {
                    so_id: so_id
                },
                dataType: "json",
                success: function(res) {
                    console.log("Full response:", res);

                    if (!res || res.error) {
                        alert("No data found for this Sales Order.");
                        return;
                    }
                    // alert(r?es.so_master.customer_name);
                    if (res.source === "sales_order") {
                        $('#del_products_table').html(res.products_html);

                        $('input[name="del_customer"]').val(res.so_master.customer_name || '');

                        $('input[name="del_shipping_address"]').val(res.so_address.shipping_address || '');
                        $('input[name="del_shipping_city"]').val(res.so_address.shipping_emirate || '');
                        $('input[name="del_shipping_contact"]').val(res.so_address.shipping_contact || '');
                        $('input[name="del_shipping_email"]').val(res.so_address.shipping_email || '');

                        $('.submit_div').show();

                    } else if (res.source === "delivery") {

                        $('#print_del').html('<a href="<?= base_url("index.php/Document_controller/print_delivery_challan/") ?>' + res.so_master.del_id + '/' + enq_id + '" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>');

                        $('#del_products_table').html(res.products_html);

                        $('input[name="dc_date"]').val(res.so_date || '');
                        $('#delivery_mode').val(res.so_master.delivery_mode || '');
                        $('input[name="deliverd_by"]').val(res.so_master.deliverd_by || '');

                        $('input[name="del_shipping_address"]').val(res.so_master.shipping_address || '');
                        $('input[name="del_shipping_city"]').val(res.so_master.shipping_city || '');
                        $('input[name="del_shipping_contact"]').val(res.so_master.contact || '');
                        $('input[name="del_shipping_email"]').val(res.so_master.email || '');
                        $('.del_remark').val(res.so_master.remark || '');

                        $('.submit_div').hide(); // ✅ optional if you don’t want editing
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("Failed to fetch Sales Order / Delivery details.");
                }
            });
        });
        var selected_so = <?= json_encode(isset($selected_so) ? $selected_so : ''); ?>;
        if (selected_so) {
            $('#sales_order_del').val(selected_so);

            // Trigger the change event to auto-populate fields
            if ($('#sales_order_del').hasClass('select2-hidden-accessible')) {
                $('#sales_order_del').trigger('change.select2');
            } else {
                $('#sales_order_del').trigger('change');
            }
        }
    });
</script>