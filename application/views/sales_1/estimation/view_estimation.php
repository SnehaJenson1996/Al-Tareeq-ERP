<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
        <div class="text-end mb-3">
            <button type="button" 
                    class="btn btn-primary" 
                    onclick="window.open('<?= base_url('index.php/Sales/print_estimation/'.$estimation_id.'/'.$enquiry_data['enquiry_id']) ?>', '_blank');">
                🖨 Print
            </button>
        </div>

		<div class="x_panel">
			<div class="x_content">
                <!-- Enquiry Details Table -->
    <?php if (isset($enquiry_data)): ?>
    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
        <tr>
            <th style="width:20%;">Enquiry No</th>
            <td style="width:30%;"><?= $enquiry_data['enquiry_code'] ?? '' ?></td>
            <th style="width:20%;">Enquiry Date</th>
            <td style="width:30%;"><?= $enquiry_data['enquiry_date'] ?? '' ?></td>
        </tr>
        <tr>
            <th>Branch</th>
            <td><?= $enquiry_data['branch_name'] ?? '' ?></td>
            <th>Customer</th>
            <td><?= $enquiry_data['customer_name'] ?? '' ?></td>
            
        </tr>
        <tr>
            <th>Project / Reference</th>
            <td><?= $enquiry_data['project_name'] ?? '' ?></td>
            <th>Sales Person</th>
            <td><?= $enquiry_data['user_name'] ?? '' ?></td>
        </tr>
    </table>
    <?php endif; ?>
                <!-- Estimation Table -->
    <?php if (isset($estimation)): $i=0; ?>
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
        <?php foreach ($estimation as $main): ?>
            
            <!-- Main Heading Row -->
            <tr style="background:#f2f2f2; font-weight:bold;">
                <td><?= $main['main_heading'] ?></td>
                <td colspan="6"><?= $main['main_details'] ?></td>
            </tr>

            <?php $j=0; foreach ($main['sub_headings'] as $sub): ?>
                <!-- Sub Heading Row -->
                <?php 
                    $subHeading = isset($sub['sub_heading']) ? $sub['sub_heading'] : "Sub Heading ".($j+1);
                ?>
                <tr style="background:#e8f1ff; font-style:italic;">
                    <td></td>
                    <td colspan="6"><?= $subHeading ?></td>
                </tr>

                <!-- Product Rows -->
                <?php $k=0; foreach ($sub['products'] as $prod): ?>
                <tr>
                    <td></td> <!-- Empty (main heading column) -->
                    <td></td> <!-- Empty (sub heading column) -->
                    <td>
                        <?= $prod['product_description'] ?>
                    </td>
                    <td>
                        <?php
                                                       echo $prod['unit_name'];
                        ?>
                    </td>
                    <td><?= $prod['quantity'] ?></td>
                    <td><?= number_format($prod['unit_price'], 2) ?></td>
                    <td><?= number_format($prod['amount'], 2) ?></td>
                </tr>
                <?php $k++; endforeach; ?>
            <?php $j++; endforeach; ?>
        <?php $i++; endforeach; ?>
        </tbody>

        <!-- Footer Summary -->
        <tfoot>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Sub total</td>
                <td>
                    <?= isset($master['sub_total'])?$master['sub_total']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Freight Amount</td>
                <td>
                    <?= isset($master['freight_amount'])?$master['freight_amount']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Travel Expense</td>
                <td>
                    <?= isset($master['travel_expense'])?$master['travel_expense']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Inspection Cost</td>
                <td>
                    <?= isset($master['inspection_cost'])?$master['inspection_cost']:"" ?>
                </td>
            </tr>
             <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Margin</td>
                <td>
                    <?= isset($master['margin_amount'])?$master['margin_amount']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Bank Charge</td>
                <td>
                    <?= isset($master['bank_charge'])?$master['bank_charge']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Other Expense</td>
                <td>
                    <?= isset($master['other_expense'])?$master['other_expense']:"" ?>
                </td>
            </tr>
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Total Amount</td>
                <td>
                    <?= isset($master['grand_total'])?$master['grand_total']:"" ?>
                   
                </td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>

</div>
		</div>
	</div>
</div>
