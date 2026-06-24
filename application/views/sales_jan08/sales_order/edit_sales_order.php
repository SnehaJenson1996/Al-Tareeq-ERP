<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }

    table th,
    table td {
        vertical-align: middle !important;
    }
</style>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="clearfix"></div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="x_content">
                <form action="<?= base_url('index.php/Sales/update_sales_order') ?>" method="post">
                    <input type="hidden" name="so_id" value="<?= $sales_order_master['so_id'] ?>">
                    <input type="hidden" name="enquiry_id" value="<?= $sales_order_master['enquiry_id'] ?>">
                    <input type="hidden" name="estimation_id" value="<?= $sales_order_master['estimation_id'] ?>">

                    <!-- Quotation & Header Details -->
                    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">

                        <tr>
                            <th>Sales Order Date<?= $sales_order_master['so_date'] ?></th>
                            <td>
                                <input type="date" name="so_date" class="form-control" value="<?= date('Y-m-d', strtotime($sales_order_master['so_date'])) ?>">
                            </td>
                            <th>Sales Order No</th>
                            <td><input type="text" name="so_code" class="form-control" value="<?= $sales_order_master['so_code'] ?>" readonly></td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <td><input type="text" name="branch_name" class="form-control" value="<?= $sales_order_master['branch_name'] ?>" readonly></td>
                            <th>Prepared By</th>
                            <td><input type="text" name="prepared_by" class="form-control" value="<?= $sales_order_master['prepared_by'] ?>" readonly></td>
                        </tr>
                        <tr>
                            <th>Project Name</th>
                            <td><input type="text" name="project_name" class="form-control" value="<?= $sales_order_master['project_name'] ?>" readonly></td>
                            <th>Customer</th>
                            <td><input type="text" name="customer_name" class="form-control" value="<?= $sales_order_master['customer_name'] ?>" readonly></td>
                        </tr>
                    </table>

                    <hr>

                    <!-- Product List -->
                    <div class="row">
                        <div class="col-12 table-responsive_sales_order quotation_product_table">
                            <!-- Populate products table like Add page -->
                            <?php if (!empty($so_products)): ?>
                                <table class="table table-bordered table-striped" style="font-size:13px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <!-- <th>Description</th> -->
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sub_total = 0;
                                        $i = 1;
                                        foreach ($so_products as $prod):
                                            $total = $prod['quantity'] * $prod['unit_price'];
                                            $sub_total += $total;
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= htmlspecialchars($prod['product_name']) ?></td>
                                                <!-- <td><?= htmlspecialchars($prod['description']) ?></td> -->
                                                <td class="text-end"><?= number_format($prod['quantity'], 2) ?></td>
                                                <td class="text-end"><?= number_format($prod['unit_price'], 2) ?></td>
                                                <td class="text-end"><?= number_format($total, 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Subtotal:</th>
                                    <td><input type="text" name="so_subtotal" id="so_subtotal" value="<?= $sales_order_master['sub_total'] ?>" class="form-control so_subtotal" readonly></td>
                                </tr>
                                <tr>
                                    <th>Discount (%)</th>
                                    <td>
                                        <input type="hidden" name="so_add_discount_percentage" id="so_add_discount_percentage" value="<?= $sales_order_master['discount_percentage'] ?>">
                                        <input type="text" name="so_add_discount_amount" id="so_add_discount_amount" value="<?= $sales_order_master['discount_amount'] ?>" class="form-control so_discount_amount" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Before VAT</th>
                                    <td><input type="text" name="so_totalbefore_vat_amount" id="so_totalbefore_vat_amount" value="<?= $sales_order_master['total_before_vat'] ?>" class="form-control so_totalbefore_vat_amount" readonly></td>
                                </tr>
                                <tr>
                                    <th>VAT (%)</th>
                                    <td>
                                        <input type="hidden" name="so_vat_percentage" id="so_vat_percentage" value="<?= $sales_order_master['vat_percentage'] ?>">
                                        <input type="text" name="so_vat_amount" id="so_vat_amount" value="<?= $sales_order_master['vat_amount'] ?>" class="form-control so_vat_amount" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Grand Total:</th>
                                    <td><input type="text" name="so_grand_total" id="so_grand_total" value="<?= $sales_order_master['grand_total'] ?>" class="form-control so_grand_total" readonly></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Billing & Shipping Address -->
                    <table class="table" style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <div class="checkbox">
                                        <label><input type="checkbox" id="copyAddress" class="flat"> Shipping address is same as Billing address</label>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th style="width:50%;">Billing Address</th>
                                <th style="width:50%;">Shipping Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group"><label>Name</label>
                                        <input type="text" name="billing_name" value="<?= $sales_order_address['billing_customer_name'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Address</label>
                                        <input type="text" name="billing_address" value="<?= $sales_order_address['billing_customer_address'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Emirate</label>
                                        <input type="text" name="billing_city" value="<?= $sales_order_address['billing_emirates'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Phone</label>
                                        <input type="text" name="billing_phone" value="<?= $sales_order_address['billing_contact'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Email</label>
                                        <input type="text" name="billing_email" value="<?= $sales_order_address['billing_email'] ?? '' ?>" class="form-control">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group"><label>Name</label>
                                        <input type="text" name="shipping_name" value="<?= $sales_order_address['shipping_customer'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Address</label>
                                        <input type="text" name="shipping_address" value="<?= $sales_order_address['shipping_address'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Emirate</label>
                                        <input type="text" name="shipping_city" value="<?= $sales_order_address['shipping_emirate'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Phone</label>
                                        <input type="text" name="shipping_phone" value="<?= $sales_order_address['shipping_contact'] ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Email</label>
                                        <input type="text" name="shipping_email" value="<?= $sales_order_address['shipping_email'] ?? '' ?>" class="form-control">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Terms & Remarks -->
                    <h5>Terms & Conditions</h5>
                    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px;">
                        <tbody>
                            <tr>
                                <th>Payment Term</th>
                                <td><input type="text" name="so_payment_term" value="<?= $sales_order_master['payment_term'] ?? '' ?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <th>Validity</th>
                                <td><input type="text" name="so_validity" value="<?= $sales_order_master['validity'] ?? '' ?>" class="form-control"></td>
                            </tr>
                            <tr>
                                <th>Delivery Term</th>
                                <td><textarea name="so_delivery_term" class="form-control" rows="3"><?= $sales_order_master['delivery_term'] ?? '' ?></textarea></td>
                            </tr>
                            <tr>
                                <th>General Terms & Conditions</th>
                                <td><textarea name="so_terms_condition" class="form-control" rows="4"><?= $sales_order_master['terms_and_condition'] ?? '' ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td><textarea name="so_remarks" class="form-control" rows="2"><?= $sales_order_master['remarks'] ?? '' ?></textarea></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">Update Sales Order</button>
                        <a href="<?= base_url('index.php/Sales/sales_order_list') ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>