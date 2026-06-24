<form method="post" action="<?= base_url('index.php/sales/update_invoice') ?>">

    <input type="hidden" name="invoice_id" value="<?= $invoice['invoice_id'] ?>">

    <div class="x_panel">
        <div class="x_title">
        </div>

        <div class="x_content">

            <!-- ================= BASIC INFO ================= -->
            <div class="row">
                <div class="col-md-4">
                    <label>Invoice Code</label>
                    <input type="text" class="form-control"
                        value="<?= $invoice['invoice_code'] ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>Invoice Date</label>
                    <input type="date" name="invoice_date"
                        value="<?= $invoice['invoice_date'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Customer</label>
                    <input type="text" class="form-control"
                        value="<?= $invoice['customer_name'] ?>" readonly>
                </div>
            </div>

            <hr>

            <!-- ================= PRODUCTS (VIEW ONLY) ================= -->
            <h5>Invoice Items</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($invoice_products as $p) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $p['item_name'] ?></td>
                            <td><?= $p['deliver_quantity'] ?></td>
                            <td><?= number_format($p['unit_price'], 2) ?></td>
                            <td><?= number_format($p['total_amount'], 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- ================= TOTALS (LOCKED) ================= -->
            <div class="row">
                <div class="col-md-4">
                    <label>Sub Total</label>
                    <input type="text" class="form-control"
                        value="<?= $invoice['sub_total'] ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>VAT Amount</label>
                    <input type="text" class="form-control"
                        value="<?= $invoice['vat_amount'] ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>Grand Total</label>
                    <input type="text" class="form-control"
                        value="<?= $invoice['grand_total'] ?>" readonly>
                </div>
            </div>

            <hr>

            <!-- ================= EDITABLE FIELDS ================= -->
            <div class="row">
                <div class="col-md-6">
                    <label>Payment Term</label>
                    <textarea name="payment_term" class="form-control"><?= $invoice['payment_term'] ?></textarea>
                </div>

                <div class="col-md-6">
                    <label>Delivery Term</label>
                    <textarea name="delivery_term" class="form-control"><?= $invoice['delivery_term'] ?></textarea>
                </div>
            </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-md-6">
                    <label>Validity</label>
                    <input type="text" name="validity"
                        value="<?= $invoice['validity'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Remarks</label>
                    <textarea name="remark" class="form-control"><?= $invoice['remark'] ?></textarea>
                </div>
            </div>

            <hr>
            <div class="row mt-3">
                <div class="col-md-6">
                    <!-- Employee Name -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Prepared By:</label>
                        <div class="col-md-6 col-sm-6 ">
                        <select class="form-control select2" 
                            id="employee_prepared" name="employee_prepared" required>
                            <option value="">Select</option>
                            <?php foreach ($employees as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>" <?= ($invoice['prepared_by'] == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Received By:</label>
                        <div class="col-md-6 col-sm-6 ">
                        <select class="form-control select2" 
                            id="employee_received" name="employee_received" required>
                            <option value="">Select</option>
                            <?php foreach ($employees as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>" <?= ($invoice['received_by'] == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </div>
                </div>       -->
            </div>
            <hr>
            <!-- ================= ACTION BUTTONS ================= -->
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    Update Invoice
                </button>

                <a href="<?= base_url('index.php/accounts/credit_note/' . $invoice['invoice_id'])?>"
                    class="btn btn-warning">
                    Create Credit Note
                </a>
            </div>

        </div>
    </div>

</form>