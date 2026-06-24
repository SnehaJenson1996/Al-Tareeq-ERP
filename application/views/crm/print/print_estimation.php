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

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 20px;
        }
        .header-left .logo {
            height: 70px;
            display: block;
        }
        .header-right {
            text-align: right;
            margin-top: 0;
        }
        .quotation-title {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.2;
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

        /* Party Section */
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
            color: #e8b41a;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .party-table .info {
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Products Table */
        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
            table-layout: fixed; /* ✅ Fix column widths */
        }
        table.products th, table.products td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            word-wrap: break-word;
        }
        table.products th:nth-child(3),
        table.products td:nth-child(3) {
            text-align: left; /* Product column left-aligned */
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

        /* Footer */
        .footer {
            text-align: center;
            font-size: 12px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
        .footer img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Print */
        @media print {
            .header, .footer { page-break-inside: avoid; }
        }

        /* Buttons for modal (if needed) */
        .print-buttons {
            margin: 20px 0;
        }
        .print-buttons button {
            padding: 8px 15px;
            font-size: 14px;
            margin-right: 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            color: #fff;
        }
        .btn-print { background-color: #28a745; }
        .btn-cancel { background-color: #dc3545; }
    </style>
</head>
<body>

<div id="printArea">

    <!-- Header (Logo + Quotation Info) -->
    <div class="header">
        <div class="header-left">
            <img src="<?= $headerPath ?>" alt="Company Logo" class="logo">
        </div>
        <div class="header-right">
            <div class="quotation-title">Quotation</div>
            <div class="quotation-number"><?= $enquiry_data['enquiry_code'] ?? '' ?></div>
        </div>
    </div>

    <!-- Enquiry / Customer Details -->
    <table class="party-table" style="width:100%; border-collapse: collapse;">
        <tr>
            <th style="width:20%; text-align:left;">Enquiry No</th>
            <td style="width:30%;"><?= $enquiry_data['enquiry_code'] ?? '' ?></td>
            <th style="width:20%; text-align:left;">Enquiry Date</th>
            <td style="width:30%;"><?= $enquiry_data['enquiry_date'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="text-align:left;">Branch</th>
            <td><?= $enquiry_data['branch_name'] ?? '' ?></td>
            <th style="text-align:left;">Customer</th>
            <td><?= $enquiry_data['customer_name'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="text-align:left;">Project / Reference</th>
            <td><?= $enquiry_data['project_name'] ?? '' ?></td>
            <th style="text-align:left;">Sales Person</th>
            <td><?= $enquiry_data['user_name'] ?? '' ?></td>
        </tr>
    </table>

    <!-- Estimation / Products -->
    <?php if (isset($estimation)): ?>
    <table class="products">
        <thead>
            <tr style="background:#0070C0; color:#fff;">
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
            <!-- Main Heading -->
            <tr style="background:#f2f2f2; font-weight:bold;">
                <td><?= $main['main_heading'] ?></td>
                <td colspan="6"><?= $main['main_details'] ?></td>
            </tr>

            <?php foreach ($main['sub_headings'] as $sub): ?>
            <!-- Sub Heading -->
            <tr style="background:#e8f1ff; font-style:italic;">
                <td></td>
                <td colspan="6"><?= $sub['sub_heading'] ?? '' ?></td>
            </tr>

            <!-- Products -->
            <?php foreach ($sub['products'] as $prod): ?>
            <tr>
                <td></td>
                <td></td>
                <td><?= $prod['item_name'] ?><br><?= $prod['product_description'] ?></td>
                <td><?= $prod['unit_name'] ?></td>
                <td><?= $prod['quantity'] ?></td>
                <td><?= number_format($prod['unit_price'], 2) ?></td>
                <td><?= number_format($prod['amount'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>

        <!-- Footer / Totals -->
        <tfoot>
            <?php 
            $totals = [
                'Sub total' => $master['sub_total'] ?? 0,
                'Margin %' => $master['margin_percentage'] ?? 0,
                'Margin Amount' => $master['margin_amount'] ?? 0,
                'Freight %' => $master['freight_percentage'] ?? 0,
                'Freight Amount' => $master['freight_amount'] ?? 0,
                'Bank Charge' => $master['bank_charge'] ?? 0,
                'Travel Expense' => $master['travel_expense'] ?? 0,
                'Inspection Cost' => $master['inspection_cost'] ?? 0,
                'Other Expense' => $master['other_expense'] ?? 0,
                'Total Amount' => $master['grand_total'] ?? 0
            ];
            foreach ($totals as $label => $value):
            ?>
            <tr style="font-weight:bold;<?= $label=='Total Amount'?'background:#cce0ff;color:#003366;':'' ?>">
                <td colspan="6" style="text-align:right;"><?= $label ?></td>
                <td style="text-align:right;"><?= number_format($value,2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tfoot>
    </table>
    <?php endif; ?>

</div>

<!-- Optional Print Buttons -->
<div class="print-buttons">
    <button class="btn-print" onclick="printQuotation()">Print</button>
    <button class="btn-cancel" onclick="window.close()">Cancel</button>
</div>

<script>
function printQuotation() {
    let printContents = document.getElementById('printArea').innerHTML;
    let printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Quotation</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body{font-family:Arial,sans-serif;font-size:13px;}');
    printWindow.document.write('table{border-collapse:collapse;width:100%;table-layout:fixed;}');
    printWindow.document.write('th,td{border:1px solid #000;padding:5px;text-align:center;word-wrap:break-word;}');
    printWindow.document.write('th:nth-child(3),td:nth-child(3){text-align:left;}');
    printWindow.document.write('</style></head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>

</body>
</html>