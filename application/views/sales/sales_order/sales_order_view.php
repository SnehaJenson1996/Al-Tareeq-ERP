<div class="x_panel">
    <div class="x_content">
        <div class="x_title">
            <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center">
                

                <!-- PRINT (LEFT) -->
              <button type="button"
    class="btn btn-primary btn-sm"
    onclick="window.open('<?= !empty($so['enquiry_id']) 
        ? base_url('index.php/Document_controller/print_sales_order/' . $so['so_id'] . '/' . $so['enquiry_id']) 
        : base_url('index.php/Document_controller/print_sales_order/' . $so['so_id']) ?>', '_blank');">
    <i class="fa fa-print"></i> Print
</button>
                <form method="post" action="<?= base_url('index.php/Sales/process_sales_action/' . $so['so_id']); ?>">
                    <!-- Buttons already here -->
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary btn-sm mr-2" name="action" value="delivery_challan">
                            Create Delivery Challan
                        </button>
                        <a href="<?= base_url('index.php/Project/add_project?so_id=' . $so['so_id']) ?>" 
   class="btn btn-success btn-sm mr-2 <?= ($project_created ? 'disabled' : '') ?>"
   <?= ($project_created ? 'onclick="return false;"' : '') ?>>
   Create Project
</a>
                        <?php if (empty($so['reserved_status'])) { ?>
                            <button type="submit" class="btn btn-primary btn-sm" name="action" value="reserve_products">
                                Reserve Products
                            </button>
                        <?php } ?>
                    </div>
                </form>

            </div>
        </div>
        <!-- ================= SALES ORDER HEADER ================= -->
        <table class="table table-bordered"
            style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
            <tr>
                <th style="width:20%;">Sales Order No</th>
                <td style="width:30%;"><?= $so['so_code'] ?> (<?= date('d-m-Y', strtotime($so['so_date'])) ?>)</td>
                <th style="width:20%;">Quotation</th>
                <td style="width:30%;"><?= $so['quotation_code'] ?></td>
            </tr>
            <tr>
                <th>Customer</th>
                <td><?= $so['customer_name'] ?></td>
                <th>Prepared By</th>
                <td><?= $so['prepared_by'] ?></td>
            </tr>
            <tr>
                <th>TRN</th>
                <td><?= $so['customer_TR_no'] ?></td>
                <th>Status</th>
                <td><?= $so['active'] ? 'Active' : 'Inactive' ?></td>
            </tr>
        </table>

        <!-- ================= PRODUCT DETAILS ================= -->
        <table class="table table-bordered"
            style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#0070C0; color:#fff; text-align:center;">
                    <th width="5%">Sl No</th>
                    <th>Description</th>
                    <th width="10%">Unit</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Unit Price</th>
                    <th width="10%">Discount</th>
                    <th width="15%">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($so_products as $item): ?>
                    <tr>
                        <td align="center"><?= $i++ ?></td>
                        <td><?= $item['product_name'] ?></td>
                        <td><?= $item['unit_name'] ?></td>
                        <td align="right"><?= number_format($item['quantity'], 2) ?></td>
                        <td align="right"><?= number_format($item['unit_price'], 2) ?></td>
                        <td align="right"><?= number_format($item['discount_amount'], 2) ?></td>
                        <td align="right"><?= number_format($item['amount'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <!-- ================= TOTALS ================= -->
            <tfoot>
                <tr style="background:#f9f9f9; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Sub Total</td>
                    <td><?= number_format($so['sub_total'], 2) ?></td>
                </tr>
                <tr style="background:#f9f9f9; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">
                        Discount (<?= $so['discount_percentage'] ?>%)
                    </td>
                    <td><?= number_format($so['discount_amount'], 2) ?></td>
                </tr>
                <tr style="background:#f9f9f9; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Total Before VAT</td>
                    <td><?= number_format($so['total_before_vat'], 2) ?></td>
                </tr>
                <?php if ($so['vat_required']): ?>
                    <tr style="background:#f9f9f9; font-weight:bold;">
                        <td colspan="6" style="text-align:right;">
                            VAT (<?= $so['vat_percentage'] ?>%)
                        </td>
                        <td><?= number_format($so['vat_amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <tr style="background:#f9f9f9; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Grand Total</td>
                    <td><strong><?= number_format($so['grand_total'], 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- ================= TERMS & CONDITIONS ================= -->
        <table class="table table-bordered" style="margin-top:20px;">
            <tr style="background:#0070C0; color:#fff;">
                <th colspan="2" style="text-align:center;">Terms & Conditions</th>
            </tr>
            <tr>
                <th width="30%">Payment Term</th>
                <td><?= nl2br($so['payment_term']) ?></td>
            </tr>
            <tr>
                <th>Validity</th>
                <td><?= nl2br($so['validity']) ?></td>
            </tr>
            <tr>
                <th>Delivery Term</th>
                <td><?= nl2br($so['delivery_term']) ?></td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><?= nl2br($so['remarks']) ?></td>
            </tr>
        </table>

    </div>
</div>