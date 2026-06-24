<?php
if (!empty($company_records)) {
    $row = $company_records[0]; 
    $company_name = htmlspecialchars($row->company_name);
    $company_address = htmlspecialchars($row->company_address);
    $company_city = htmlspecialchars($row->company_city);
    // $company_pincode = htmlspecialchars($row->company_pincode);
    $company_country = htmlspecialchars($row->company_country);
    $company_email_id = htmlspecialchars($row->company_email_id);
    $company_telephone = htmlspecialchars($row->company_telephone);
    $company_website = htmlspecialchars($row->company_website);
    // $company_TRN = htmlspecialchars($row->company_TRN);
} else {
    $company_name = $company_address = $company_city = $company_pincode = '';
    $company_country = $company_email_id = $company_telephone = $company_website = $company_TRN = '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .header-table td { border: none; vertical-align: middle; }
        .company-name { font-size: 18px; font-weight: bold; text-align: center; }
        .report-title { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 10px; }
        .date-range { margin-bottom: 20px; }
        .footer {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .no-border { border: none !important; }
        img.logo {
            width: 60px;
            height: auto;
        }
    </style>
</head>
<body>

    <!-- Company Header -->
    <!-- <table class="header-table no-border">
        <tr>
            <td width="15%" align="left">
                <img src="<?php echo base_url('public/logo/logo192x192.png'); ?>" alt="Company Logo" class="logo">
            </td>
            <td align="center">
                <div class="company-name"><?= $company_name ?></div>
                <div class="company-contact">
                    TRN: <?= $company_TRN ?> | 
                    Tel: <?= $company_telephone ?> | 
                    Email: <?= $company_email_id ?> | 
                    Website: <?= $company_website ?>
                </div>
            </td>
        </tr>
    </table> -->

    <!-- Report Title and Date -->
    <table class="no-border" style="width:100%; margin-bottom: 15px;">
        <tr>
            <td align="center" colspan="4">
                <p class="report-title">Cashflow Report</p>
            </td>
        </tr>
        <!-- <tr>
            <td align="left"><strong>Date:</strong> <?= date('d-M-Y') ?></td>
            <td align="right" colspan="3"><strong>Company:</strong> <?= $company_name ?></td>
        </tr> -->
    </table>

    <?php if (!empty($cashflow_summary)) : ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Account Group</th>
                <th>Account Name</th>
                <th>Cash Inflows (Cr)</th>
                <th>Cash Outflows (Dr)</th>
                <th>Net Cashflow</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cashflow_summary as $row): ?>
               <tr>
    <td width="15%">
        <?= !empty($row->trx_date) ? date('d-m-Y', strtotime($row->trx_date)) : '' ?>
    </td>

    <td width="15%">
        <?= htmlspecialchars($row->account_group ?? '') ?>
    </td>

    <td>
        <?= htmlspecialchars($row->account_name ?? '') ?>
    </td>

    <td class="text-success">
        <?= number_format((float)($row->cash_inflows ?? 0), 2) ?>
    </td>

    <td class="text-danger">
        <?= number_format((float)($row->cash_outflows ?? 0), 2) ?>
    </td>

    <td>
        <?= number_format((float)($row->net_cashflow ?? 0), 2) ?>
    </td>
</tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No transactions found for selected filters.</p>
<?php endif; ?>

    <!-- <div class="footer">
        &copy; <?= date('Y') ?> For <?= $company_name ?>, Designed and developed by Concepts 360 Plus
    </div> -->

    <script>
        // Auto print on page load
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
