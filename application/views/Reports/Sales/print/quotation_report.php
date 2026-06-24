<html>

<head>
    <title>Quotation Report</title>

    <style>
        @page {
            margin: 10mm 10mm 25mm 10mm;

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }

            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Al Adel Automatic Doors TR. LLC, Designed and developed by Concepts 360 Plus";
            }
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        body {
            margin-left: 5px;
            margin-top: 5px;
            font-family: Arial;
            font-size: 12px;
            text-align: center;
        }

        table {
            width: 100%;
        }
    </style>
</head>

<body>

    <table border="0" width="100%">
        <thead>
            <tr>
                <td>
                    <table width="100%" cellpadding="5" style="text-align:center; border:0;">
                        <tr>
                            <td>
                                   <img src="<?= $headerPath ?>" alt="Company Logo" class="header-img">

                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:20px; color:#e8b41a;">
                                Quotation Report
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;">
                                Quotation Report from <?php echo $_GET["from_date"]; ?> to <?php echo $_GET["to_date"]; ?>
                            </td>
                        </tr>

                        <tr height="5" style="background-color:#525453;">
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <table cellpadding="8" width="100%"
                        style="font-size:12px; border-collapse:collapse; border:1px solid black;">

                        <tr style="background-color:#f2f2f2; border:1px solid black; font-weight:bold;">
                            <th style="border:1px solid black;">Sl. No</th>
                            <th style="border:1px solid black;">Quotation Code</th>
                            <th style="border:1px solid black;">Date</th>
                            <th style="border:1px solid black;">Customer</th>
                            <th style="border:1px solid black;">Grand Total</th>
                            <th style="border:1px solid black;">Created By</th>
                            <th style="border:1px solid black;">Last Updated By</th>
                        </tr>

                      <?php $i = 1; ?>
<?php if(!empty($records)): ?>
    <?php foreach ($records as $row): ?>
    <tr style="border:1px solid black;">
        <td style="border:1px solid black; text-align:center;"><?php echo $i++; ?></td>
        <td style="border:1px solid black;"><?php echo $row->quotation_code; ?></td>
        <td style="border:1px solid black;"><?php echo $row->quotation_date; ?></td>
        <td style="border:1px solid black;"><?php echo $row->customer_name ?: '-'; ?></td>
        <td style="border:1px solid black;"><?php echo $row->grand_total ?: '-'; ?></td>
        <td style="border:1px solid black;"><?php echo $row->created ?: '-'; ?></td>
        <td style="border:1px solid black;"><?php echo $row->last_updated ?: '-'; ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align:center;">No records found</td>
    </tr>
<?php endif; ?>

                    </table>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr><td></td></tr>
        </tfoot>
    </table>

</body>
</html>
