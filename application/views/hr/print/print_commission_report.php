<?php
$printed_by = $this->session->userdata('user_name') ?? $this->session->userdata('email');

$total_invoice = 0;
$total_commission = 0;
?>

<html>

<head>

    <title>Commission Report</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
        }

        main {
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 7px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        td {
            text-align: left;
        }

        .section-header {
            background: #8AB645;
            color: #fff;
            font-size: 22px;
            text-align: center;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .sub-header {
            background: #f3f3f3;
            padding: 6px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        @media print {

            @page {
                margin: 15mm;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

        }
    </style>

</head>

<body onload="window.print();">

    <main>

        <div class="section-header">

            Commission Report

        </div>

        <div class="sub-header">

            Report Date :
            <?= date('d-M-Y H:i'); ?>

        </div>

        <table>

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

                            <td class="text-center"><?= $i++; ?></td>

                            <td><?= $row->invoice_code; ?></td>

                            <td>

                                <?php

                                if (!empty($row->invoice_date))
                                    echo date('d-M-Y', strtotime($row->invoice_date));

                                ?>

                            </td>

                            <td><?= $row->customer_name; ?></td>

                            <td><?= $row->sales_rep_name; ?></td>

                            <td class="text-right">

                                <?= number_format($row->invoice_amount, 2); ?>

                            </td>

                            <td class="text-center">

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

                            <td><?= $row->status; ?></td>

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

                            <td><?= $row->payment_mode; ?></td>

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

        <br><br>

        <table border="0">

            <tr>

                <td width="50%">

                    Printed By :
                    <b><?= $printed_by; ?></b>

                </td>

                <td align="right">

                    Printed On :
                    <b><?= date('d-M-Y H:i'); ?></b>

                </td>

            </tr>

        </table>

    </main>

</body>

</html>