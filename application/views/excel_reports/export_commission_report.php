<?php

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Commission_Report.xls");
header("Pragma: no-cache");
header("Expires: 0");

$total_invoice = 0;
$total_commission = 0;

?>

<style>
    table {

        border-collapse: collapse;
        width: 100%;
        font-family: Arial;
        font-size: 13px;

    }

    th,
    td {

        border: 1px solid #000;
        padding: 7px;

    }

    th {

        background: #E8E8E8;

    }

    .section-header {

        background: #8AB645;
        color: #fff;
        font-size: 20px;
        font-weight: bold;
        text-align: center;

    }

    .text-right {

        text-align: right;

    }

    .text-center {

        text-align: center;

    }
</style>

<html>

<body>

    <table width="100%" border="0">
        <tr>
            <td align="center">
                <h2>AL TAREEQ ERP</h2>
                <h3>Commission Report</h3>
            </td>
        </tr>
    </table>

    <br><br>

    <table>
        <tr>
            <td colspan="13">
                Generated On :
                <b><?= date('d-M-Y H:i'); ?></b>
            </td>
        </tr>
    </table>

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer</th>
                <th>Sales Rep</th>
                <th>Invoice Amount</th>
                <th>Commission %</th>
                <th>Commission Amount</th>
                <th>Eligible Date</th>
                <th>Status</th>
                <th>Approved Date</th>
                <th>Payment Date</th>
                <th>Payment Mode</th>

            </tr>

        </thead>

        <tbody>

            <?php

            $i = 1;

            if (!empty($records)) {

                foreach ($records as $row) {

                    $total_invoice += $row->invoice_amount;
                    $total_commission += $row->commission_amount;

            ?>

                    <tr>

                        <td><?= $i++; ?></td>

                        <td><?= $row->invoice_code; ?></td>

                        <td>

                            <?= (!empty($row->invoice_date)) ?
                                date('d-M-Y', strtotime($row->invoice_date)) : ""; ?>

                        </td>

                        <td><?= $row->customer_name; ?></td>

                        <td><?= $row->sales_rep_name; ?></td>

                        <td class="text-right">

                            <?= number_format($row->invoice_amount, 2); ?>

                        </td>

                        <td>

                            <?= $row->commission_percent; ?> %

                        </td>

                        <td class="text-right">

                            <?= number_format($row->commission_amount, 2); ?>

                        </td>

                        <td>

                            <?php

                            if (!empty($row->eligible_date) && $row->eligible_date != '0000-00-00')
                                echo date('d-M-Y', strtotime($row->eligible_date));

                            ?>

                        </td>

                        <td>

                            <?= $row->status; ?>

                        </td>

                        <td>

                            <?php

                            if (!empty($row->approved_date))
                                echo date('d-M-Y', strtotime($row->approved_date));

                            ?>

                        </td>

                        <td>

                            <?php

                            if (!empty($row->payment_date))
                                echo date('d-M-Y', strtotime($row->payment_date));

                            ?>

                        </td>

                        <td>

                            <?= $row->payment_mode; ?>

                        </td>

                    </tr>

            <?php

                }
            }

            ?>

            <tr>

                <th colspan="5">

                    Grand Total

                </th>

                <th class="text-right">

                    <?= number_format($total_invoice, 2); ?>

                </th>

                <th></th>

                <th class="text-right">

                    <?= number_format($total_commission, 2); ?>

                </th>

                <th colspan="5"></th>

            </tr>

        </tbody>

    </table>

</body>

</html>