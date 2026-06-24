<div class="x_panel">
    <div class="x_title">
        <h2>Estimation Details</h2>
        <div class="clearfix">
            <div class="text-end mb-3">
            <button type="button" 
                    class="btn btn-primary" 
                    onclick="window.open('<?= base_url('index.php/Document_controller/print_estimation/'.$estimations['estimation_id'].'/'.$estimations['enquiry_id']) ?>', '_blank');">
                🖨 Print
            </button>
        </div>
        </div>
    </div>
    <div class="x_content">

        <!-- Styling for right-aligned numbers -->
        <style>
            /* Default: text left */
            td, th {
                text-align: left;
            }
            /* Numeric columns right */
            td.num, th.num {
                text-align: right;
            }
        </style>

        <!-- Heading -->
        <h4 class="panel-title">
            <?= $estimations['customer_name'] ?> - <?= $estimations['enquiry_code'] ?> 
            (<?= date('d-m-Y', strtotime($estimations['estimation_date'])) ?>)
        </h4>

        <!-- Basic Details Table -->
        <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
            <tr>
                <td><strong>Customer:</strong> <?= $estimations['customer_name'] ?></td>
                <td><strong>Branch:</strong> <?= $estimations['branch_name'] ?></td>
                <td><strong>Date:</strong> <?= date('d-m-Y', strtotime($estimations['estimation_date'])) ?></td>
            </tr>
            <tr>
                <td><strong>Enquiry Code:</strong> <?= $estimations['enquiry_code'] ?></td>
                <td><strong>Enquiry Date:</strong> <?= date('d-m-Y', strtotime($estimations['enquiry_date'])) ?></td>
                <td><strong>Prepared By:</strong> <?= $estimations['preparedby'] ?? '' ?></td>
            </tr>
        </table>

        <!-- Main / Sub / Products Table -->
        <?php foreach($estimations['main_headings'] as $main): ?>
            <h5><strong><?= $main['main_heading'] ?></strong> - <?= $main['main_details'] ?></h5>

            <?php foreach($main['sub_headings'] as $sub): ?>
                <h6><em><?= $sub['sub_heading'] ?></em></h6>

                <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th class="num">Qty</th>
                            <th class="num">Unit Price</th>
                            <th class="num">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sub['products'] as $pIndex => $prod): ?>
                            <tr>
                                <td><?= $pIndex + 1 ?></td>
                                <td><?= $prod['product_description'] ?></td>
                                <td><?= $prod['unit_name'] ?></td>
                                <td class="num"><?= $prod['quantity'] ?></td>
                                <td class="num"><?= number_format($prod['unit_price'], 2) ?></td>
                                <td class="num"><?= number_format($prod['amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <!-- Totals -->
        <table class="table table-bordered dt-responsive nowrap" width="40%" style="float:right;">
            <tr>
                <td><strong>Sub Total:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['sub_total'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Margin %:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['margin_percentage'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Margin Amount:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['margin_amount'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Freight %:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['freight_percentage'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Freight Amount:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['freight_amount'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Bank Charge:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['bank_charge'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Travel Expense:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['travel_expense'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Inspection Cost:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['inspection_cost'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Other Expense:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['other_expense'], 2) ?></td>
            </tr>
            <tr>
                <td><strong>Grand Total:</strong></td>
                <td class="num"><?= number_format($estimations['totals']['grand_total'], 2) ?></td>
            </tr>
        </table>
        <div style="clear:both;"></div>

    </div>
</div>
