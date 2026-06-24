<html>
<head>
    <title>Invoice Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; }
        th { background: #f2f2f2; }
        .header { text-align: center; font-size: 18px; font-weight: bold; margin-top: 10px; }
        .sub-header { margin-top: 10px; font-size: 13px; }
        .no-border td { border: none !important; }
        @media print {
            @page { size: A4; margin: 10mm; }
        }
        @page {
            margin: 10mm 10mm 25mm 10mm;
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }
            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Al Adel Automatic Doors TR. LLC, Designed and developed by Concepts 360 Plus";
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
                                   <img src="<?= $headerPath ?>" alt="Company Logo" class="header-img">
        <div style="margin-top:10px; font-size:16px; font-weight:bold;">Invoice Report</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sl.no</th>
                <th>Invoice Date</th>
                <th>Invoice Code</th>
                <th>Customer</th>
                <th>Grand Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $grand_total_sum = 0;

            foreach ($records as $row):
                $rate = $row['grand_total'] ?? 0; // Assuming grand_total is already in DB
                $grand_total_sum += $rate;
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= date('d-M-Y', strtotime($row['invoice_date'])) ?></td>
                <td><?= $row['invoice_code'] ?></td>
                <td><?= $row['customer_name'] ?></td>
                <td align="right"><?= number_format($rate, 2) ?></td>
            </tr>
            <?php endforeach; ?>

            <!-- TOTAL ROW -->
            <tr style="font-weight:bold; background:#f0f0f0;">
                <td colspan="4" align="right">Total:</td>
                <td align="right"><?= number_format($grand_total_sum, 2) ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
