 <!DOCTYPE html>
<html>
<head>
    <title>Quotation</title>
    <style>
        body {
            font-family: "Franklin Gothic Book", Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

      .header {
    display: flex;
    justify-content: space-between;
    align-items: center; /* ✅ Keeps logo and quotation aligned vertically */
    padding: 5px 20px;
}

.header-left .logo {
    height: 70px;   /* Adjust as per your logo */
    display: block;
}

.header-right {
    text-align: right;
    margin-top: 0;  /* ✅ Removes extra space */
}

.quotation-title {
    font-size: 20px;
    font-weight: bold;
    line-height: 1.2; /* ✅ Keeps spacing tight */
}

.quotation-number {
    font-size: 12px;
    margin-top: 2px;
}
        /* Title */
        .title {
            text-align: center;
            margin: 20px 0 10px 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Supplier/Buyer Section */
        .party-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .party-table td {
            vertical-align: top;
            padding: 10px;
        }
        .party-table .section-title {
            background-color: #656c69ff;
            color:#e8b41a;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .party-table .info {
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Quotation Details Table */
        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.products th {
            background-color: #656b68;
            color: #e8b41a;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #000;
        }
        table.products td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        table.products td img {
            display: block;
            margin: auto;
        }

        /* Totals */
        table.totals {
            width: 40%;
            float: right;
            border-collapse: collapse;
            font-size: 12px;
        }
        table.totals td {
            border: 1px solid #000;
            padding: 5px;
            text-align: right;
        }
        table.totals td.label {
            font-weight: bold;
        }
        table.totals td.total {
            background-color: #5e6462ff;
            color: #e8b41a;
            font-weight: bold;
        }

        /* Terms */
        .terms {
            margin-top: 20px;
            font-size: 12px;
        }

        .footer {
    text-align: center;
    font-size: 12px;
    position: fixed;
    bottom: 0;          /* Stick to bottom of page */
    left: 0;
    right: 0;
    width: 100%;
}
.footer img {
    max-width: 100%;    /* Prevent image from stretching */
    height: auto;
    display: block;
    margin: 0 auto;
}
        /* Print */
        @media print {
            .header, .footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
<!-- 
<table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
       Left side: Company Logo -->
        <!-- <td style="width:100%; vertical-align:middle;">
<img src="<?= $headerPath ?>" alt="Company Logo" style="width:100%; height:auto;">
        </td> -->

       
    <!-- </tr> -->
<!-- </table> --> 


<!-- <hr style="margin:5px 0; border:1px solid #000;"> -->
    <!-- Title -->
    <div class="title"></div>

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
                        <?= $prod['item_name'] ?> <br>
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
   <tr style=" font-weight:bold;"> <!-- light blue -->
    <td colspan="6" style="text-align:right;">Sub total</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['sub_total']) ? number_format($master['sub_total'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Margin %:</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['margin_percentage']) ? number_format($master['margin_percentage'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Margin Amount:</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['margin_amount']) ? number_format($master['margin_amount'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Freight %:</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['freight_percentage']) ? number_format($master['freight_percentage'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Freight Amount</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['freight_amount']) ? number_format($master['freight_amount'], 2) : "" ?>
    </td>
</tr>
<tr style="font-weight:bold;">
    <td colspan="6" style="text-align:right;">Bank Charge</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['bank_charge']) ? number_format($master['bank_charge'], 2) : "" ?>
    </td>
</tr>
<tr style="font-weight:bold;">
    <td colspan="6" style="text-align:right;">Travel Expense</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['travel_expense']) ? number_format($master['travel_expense'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Inspection Cost</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['inspection_cost']) ? number_format($master['inspection_cost'], 2) : "" ?>
    </td>
</tr>
<tr style=" font-weight:bold;">
    <td colspan="6" style="text-align:right;">Other Expense</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['other_expense']) ? number_format($master['other_expense'], 2) : "" ?>
    </td>
</tr>
<tr style="background:#cce0ff; font-weight:bold; color:#003366;"> <!-- darker blue for total -->
    <td colspan="6" style="text-align:right;">Total Amount</td>
    <td colspan="6" style="text-align:right;">
        <?= isset($master['grand_total']) ? number_format($master['grand_total'], 2) : "" ?>
    </td>
</tr>

</tfoot>

    </table>
    <?php endif; ?>
    <div style="clear: both;"></div>

 <!-- Footer -->
    <!-- <div class="footer">
        <img src="<?= $footerPath ?>" alt="Logo" width="100%">
        Thank you for giving us the opportunity to quote you.<br>
       
    </div> -->

