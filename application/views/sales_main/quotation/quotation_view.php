<div class="text-end mb-3">
    <button type="button" 
            class="btn btn-primary" 
            onclick="window.open('<?= base_url('index.php/Sales/print_quotation/'.$quotation['qtn_id'].'/'.$quotation['enquiry_id']) ?>', '_blank');">
        🖨 Print
    </button>
</div>

<div class="x_panel">
  <div class="x_content">

    <!-- Quotation Header -->
    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
        <tr>
            <th style="width:20%;">Quotation No</th>
            <td style="width:30%;"><?= $quotation['quotation_code'] ?> (<?= date('d-m-Y', strtotime($quotation['quotation_date'])) ?>)</td>
            <th style="width:20%;">Enquiry</th>
            <td style="width:30%;"><?= $quotation['enquiry_code'] ?></td>
        </tr>
        <tr>
            <th>Estimation</th>
            <td><?= $quotation['estimation_code'] ?></td>
            <th>Branch</th>
            <td><?= $quotation['branch_name'] ?></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td><?= $quotation['customer_name'] ?></td>
            <th>Prepared By</th>
            <td><?= $quotation['preparedby'] ?></td>
        </tr>
        <tr>
            <th>Approved By</th>
            <td></td>
            <th>Status</th>
            <td><?= $quotation['quotation_status'] ?></td>
        </tr>
        <tr>
            <th>Revision</th>
            <td><?= $quotation['quotation_revision'] ?></td>
            <td colspan="2"></td>
        </tr>
    </table>

    <!-- Quotation Items -->
    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#0070C0; color:#fff; text-align:center;">
                <th style="width:20%;">Main Heading</th>
                <th style="width:20%;">Sub Heading</th>
                <th style="width:25%;">Product</th>
                <th style="width:10%;">Unit</th>
                <th style="width:10%;">Qty</th>
                <th style="width:10%;">Unit Price</th>
                <th style="width:15%;">Amount</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($quotation['main_headings'] as $main): ?>
            <!-- Main Heading -->
            <tr style="background:#f2f2f2; font-weight:bold;">
                <td><?= $main['main_heading'] ?></td>
                <td colspan="6"><?= $main['description'] ?></td>
            </tr>

            <?php foreach($main['sub_headings'] as $sub): ?>
                <!-- Sub Heading -->
                <tr style="background:#e8f1ff; font-style:italic;">
                    <td></td>
                    <td colspan="6"><?= $sub['sub_heading'] ?></td>
                </tr>

                <!-- Products -->
                <?php foreach($sub['products'] as $prd): ?>
                <tr>
                    <td></td>
                    <td><?= $prd['product_name'] ?></td>
                    <td><?= $prd['product_description'] ?></td>
                    <td><?= $prd['unit_name'] ?></td>
                    <td><?= $prd['quantity'] ?></td>
                    <td><?= number_format($prd['unit_price'],2) ?></td>
                    <td><?= number_format($prd['amount'],2) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>

        <!-- Totals -->
        <tfoot>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Sub Total</td>
                <td><?= number_format($quotation['totals']['sub_total'],2) ?></td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Discount</td>
                <td>
                    <?= $quotation['totals']['discount_percentage'] ?>% 
                    (<?= number_format($quotation['totals']['discount_amount'],2) ?>)
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Total Before VAT</td>
                <td><?= number_format($quotation['totals']['total_before_vat'],2) ?></td>
            </tr>
            <?php if($quotation['totals']['vat_required']): ?>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">VAT (<?= $quotation['totals']['vat_percentage'] ?>%)</td>
                <td><?= number_format($quotation['totals']['vat_amount'],2) ?></td>
            </tr>
            <?php endif; ?>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Grand Total</td>
                <td><strong><?= number_format($quotation['totals']['grand_total'],2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Terms -->
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <tr>
        <th colspan="2" style="background:#0070C0; color:#fff; text-align:center;">Terms & Conditions</th>
    </tr>
     <tr>
        <td style="width:30%;"><strong>Payment Term</strong></td>
        <td style="width:70%;"><?= $quotation['terms']['payment_term'] ?></td>
    </tr>
    <tr>
        <td style="width:30%;"><strong>Delivery Term</strong></td>
        <td style="width:70%;"><?= $quotation['terms']['delivery_term'] ?></td>
    </tr>
    <tr>
        <td style="width:30%;"><strong>Validity</strong></td>
        <td style="width:70%;"><?= $quotation['terms']['validity'] ?></td>
    </tr>
    <tr>
        <td style="width:30%;"><strong>Other Conditions</strong></td>
        <td style="width:70%;"><?= nl2br($quotation['terms']['terms_condition']) ?></td>
    </tr>
</table>


  </div>
</div>
