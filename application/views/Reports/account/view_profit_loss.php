<style>
    .card-body {
        background: #f4f6f9;
    }

    .pnl-container {
        background: #fff;
        border: 1px solid #dcdcdc;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    .pnl-header {
        background: #34495e;
        color: #fff;
        padding: 12px;
        text-align: center;
    }

    .pnl-header h4 {
        margin: 0;
        font-weight: 600;
    }

    .pnl-header small {
        color: #dcdcdc;
    }

    .pnl-title {
        background: #ecf0f1;
        font-weight: 600;
        color: #2c3e50;
    }

    .pnl-filter {
        background: #fff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, .08);
    }

    .table td,
    .table th {
        padding: 6px 10px !important;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fbff;
    }

    .group-row {
        background: #f7f7f7;
        font-weight: 600;
        color: #2c3e50;
    }

    .amount {
        text-align: right;
        white-space: nowrap;
    }

    .account-link {
        color: #007bff;
        text-decoration: none;
    }

    .account-link:hover {
        text-decoration: underline;
    }

    .total-row {
        background: #34495e;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .gross-profit {
        color: #28a745;
        font-weight: 600;
    }

    .gross-loss {
        color: #dc3545;
        font-weight: 600;
    }

    .net-profit {
        color: #28a745;
        font-weight: 700;
        border-top: 2px solid #28a745;
    }

    .net-loss {
        color: #dc3545;
        font-weight: 700;
        border-top: 2px solid #dc3545;
    }

    .border-right {
        border-right: 2px solid #dee2e6 !important;
    }

    .report-section {
        padding: 10px;
    }

    @media print {

        .btn,
        form {
            display: none !important;
        }

        body {
            background: #fff;
        }

        .card-body {
            padding: 0;
            background: #fff;
        }

        .pnl-container {
            box-shadow: none;
            border: 1px solid #000;
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    }
</style>

<?php $this->load->helper('account_helper.php'); ?>
<div class="card-body">

    <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Accounts/view_profit_and_loss" class="form-horizontal" autocomplete="off" name="question" enctype="multipart/form-data">
        <div class="form-group row pnl-filter">

            <!-- From -->
            <label class="col-lg-1 col-form-label">
                From <span style="color:red">*</span>
            </label>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <input type="date" class="form-control form-control-sm" id="from" name="from" value="<?php echo $from; ?>">
            </div>

            <!-- To -->
            <label class="col-lg-1 col-form-label">
                To <span style="color:red">*</span>
            </label>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <input type="date" class="form-control form-control-sm" id="to" name="to" value="<?php echo $to; ?>">
            </div>

            <!-- Branch -->
            <label class="col-lg-1 col-form-label">
                Branch
            </label>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <select name="branch_id" id="branch_id" class="form-control form-control-sm">
                    <option value="">All Branches</option>
                    <?php foreach ($branch_list as $branch) { ?>
                        <option value="<?php echo $branch->branch_id; ?>"
                            <?php echo ($branch_id == $branch->branch_id) ? 'selected' : ''; ?>>
                            <?php echo $branch->branch_name; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Button -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <button type="submit"
                        id="view"
                        name="go"
                        class="btn btn-primary btn-sm"
                        style="margin-top:25px;margin-right:8px;">
                    <i class="fa fa-search"></i> Go
                </button>

                <button type="button"
                        class="btn btn-warning btn-sm"
                        onclick="printProfitLoss()"
                        style="margin-top:25px;color:#000;">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>

        </div>
    </form>

    <?php
    // --- TALLY LOGIC FOR TRADING ACCOUNT (Top Half) ---
    $trading_debits  = $total_purchase + $total_direct_expense;
    $trading_credits = $total_sales + $total_direct_income;

    $gross_profit = ($trading_credits >= $trading_debits) ? ($trading_credits - $trading_debits) : 0;
    $gross_loss   = ($trading_credits < $trading_debits)  ? ($trading_debits - $trading_credits) : 0;

    $trading_left_total  = $trading_debits + $gross_profit;
    $trading_right_total = $trading_credits + $gross_loss;

    // --- TALLY LOGIC FOR INCOME STATEMENT (Bottom Half) ---
    $p_and_l_debits  = $gross_loss + $total_indirect_expense;
    $p_and_l_credits = $gross_profit + $total_indirect_income;

    $net_profit = ($p_and_l_credits >= $p_and_l_debits) ? ($p_and_l_credits - $p_and_l_debits) : 0;
    $net_loss   = ($p_and_l_credits < $p_and_l_debits)  ? ($p_and_l_debits - $p_and_l_credits) : 0;

    $income_left_total  = $p_and_l_debits + $net_profit;
    $income_right_total = $p_and_l_credits + $net_loss;
    ?>

    <div class="pnl-container" id="printArea">

        <div class="pnl-header">
            <h4>PROFIT & LOSS ACCOUNT</h4>
            <small>
                <?php echo date('d-M-Y', strtotime($from)); ?>
                To
                <?php echo date('d-M-Y', strtotime($to)); ?>
            </small>
        </div>

        <div class="row no-gutters">

            <div class="col-md-6 border-right">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr class="border-bottom bg-light text-secondary">
                            <th class="pl-2">Particulars (Debit)</th>
                            <th class="text-right pr-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="group-row">
                            <td class="pl-2">Purchase Accounts</td>
                            <td align="right" class="pr-2"><?= number_format($total_purchase, 2) ?></td>
                        </tr>
                        <?php foreach ($purchase_accounts as $pa) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $pa->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $pa->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format(abs($pa->total), 2) ?></td>
                            </tr>
                        <?php } ?>

                        <tr class="font-weight-bold text-dark mt-2">
                            <td class="pl-2">Direct Expenses</td>
                            <td align="right" class="pr-2"><?= number_format($total_direct_expense, 2) ?></td>
                        </tr>
                        <?php foreach ($direct_expense as $de) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $de->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $de->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format(abs($de->total), 2) ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($gross_profit > 0) { ?>
                            <tr class="text-success font-weight-bold">
                                <td class="pl-2">Gross Profit c/o</td>
                                <td align="right" class="pr-2"><?= number_format($gross_profit, 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr class="border-bottom bg-light text-secondary">
                            <th class="pl-2">Particulars (Credit)</th>
                            <th class="text-right pr-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-weight-bold text-dark">
                            <td class="pl-2">Sales Accounts</td>
                            <td align="right" class="pr-2"><?= number_format($total_sales, 2) ?></td>
                        </tr>
                        <?php foreach ($sales_accounts as $sa) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $sa->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $sa->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format($sa->total, 2) ?></td>
                            </tr>
                        <?php } ?>

                        <tr class="font-weight-bold text-dark">
                            <td class="pl-2">Direct Income</td>
                            <td align="right" class="pr-2"><?= number_format($total_direct_income, 2) ?></td>
                        </tr>
                        <?php foreach ($direct_income as $di) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $di->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $di->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format($di->total, 2) ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($gross_loss > 0) { ?>
                            <tr class="text-danger font-weight-bold">
                                <td class="pl-2">Gross Loss c/o</td>
                                <td align="right" class="pr-2"><?= number_format($gross_loss, 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row no-gutters border-top border-bottom bg-light font-weight-bold text-secondary">
            <div class="col-md-6 border-right d-flex justify-content-between px-2 py-1">
                <span>Total</span>
                <span><?= number_format($trading_left_total, 2) ?></span>
            </div>
            <div class="col-md-6 d-flex justify-content-between px-2 py-1">
                <span>Total</span>
                <span><?= number_format($trading_right_total, 2) ?></span>
            </div>
        </div>


        <div class="row no-gutters mt-1">

            <div class="col-md-6 border-right">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <?php if ($gross_loss > 0) { ?>
                            <tr class="text-danger font-weight-bold">
                                <td class="pl-2">Gross Loss b/f</td>
                                <td align="right" class="pr-2"><?= number_format($gross_loss, 2) ?></td>
                            </tr>
                        <?php } ?>

                        <tr class="font-weight-bold text-dark">
                            <td class="pl-2">Indirect Expenses</td>
                            <td align="right" class="pr-2"><?= number_format($total_indirect_expense, 2) ?></td>
                        </tr>
                        <?php foreach ($indirect_expense as $ie) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $ie->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $ie->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format(abs($ie->total), 2) ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($net_profit > 0) { ?>
                            <tr class="text-success font-weight-bold border-top">
                                <td class="pl-2">Net Profit</td>
                                <td align="right" class="pr-2"><?= number_format($net_profit, 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <?php if ($gross_profit > 0) { ?>
                            <tr class="text-success font-weight-bold">
                                <td class="pl-2">Gross Profit b/f</td>
                                <td align="right" class="pr-2"><?= number_format($gross_profit, 2) ?></td>
                            </tr>
                        <?php } ?>

                        <tr class="font-weight-bold text-dark">
                            <td class="pl-2">Indirect Income</td>
                            <td align="right" class="pr-2"><?= number_format($total_indirect_income, 2) ?></td>
                        </tr>
                        <?php foreach ($indirect_income as $ii) { ?>
                            <tr>
                                <td class="pl-4 text-success">
                                    <a href="<?= base_url() . 'index.php/Accounts/drilldown?account_id=' . $ii->account_id . '&from=' . $from . '&to=' . $to; ?>">
                                        <?= $ii->account_name; ?>
                                    </a>
                                </td>
                                <td align="right" class="pr-2 text-muted"><?= number_format($ii->total, 2) ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($net_loss > 0) { ?>
                            <tr class="text-danger font-weight-bold border-top">
                                <td class="pl-2">Net Loss</td>
                                <td align="right" class="pr-2"><?= number_format($net_loss, 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row no-gutters border-top bg-light font-weight-bold text-secondary">
            <div class="col-md-6 border-right d-flex justify-content-between px-2 py-1">
                <span>Total</span>
                <span><?= number_format($income_left_total, 2) ?></span>
            </div>
            <div class="col-md-6 d-flex justify-content-between px-2 py-1">
                <span>Total</span>
                <span><?= number_format($income_right_total, 2) ?></span>
            </div>
        </div>

    </div>
</div>

<script>
function printProfitLoss()
{
    var printContents = document.getElementById("printArea").innerHTML;

    var from = document.getElementById("from").value;
    var to   = document.getElementById("to").value;

    var printWindow = window.open('', '', 'width=1200,height=800');

    printWindow.document.write(`
        <html>
        <head>

            <title>Profit & Loss Report</title>

            <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css');?>">

            <style>

                body{
                    font-family:Arial,sans-serif;
                    margin:20px;
                    font-size:12px;
                }

                table{
                    width:100%;
                    border-collapse:collapse;
                }

                th,
                td{
                    border:1px solid #ccc;
                    padding:6px;
                    font-size:12px;
                }

                th{
                    background:#f3f3f3;
                    text-align:center;
                }

                .amount{
                    text-align:right;
                }

                a{
                    color:#000;
                    text-decoration:none;
                }

                /* ---------- HEADER ---------- */

                .print-header{
                    text-align:center;
                    margin-bottom:15px;
                }

                .print-header img{
                    width:220px;
                    height:auto;
                    display:block;
                    margin:0 auto;
                }

                .pnl-header{
                    text-align:center;
                    margin-bottom:20px;
                }

                .pnl-header h2{
                    margin:5px 0;
                }

                /* ---------- PAGE ---------- */

                @page{

                    size:A4 landscape;

                    margin:15mm 10mm 25mm 10mm;

                    @top-center{
                        content:element(pageHeader);
                    }

                    @bottom-left{
                        content:"©<?= date('Y'); ?> For Al Tareeq Kitchen Equipment Industry LLC, Designed and developed by Concepts 360 Plus";
                        font-size:10px;
                    }

                    @bottom-right{
                        content:"Page " counter(page) " of " counter(pages);
                        font-size:10px;
                    }

                }

                @media print{

                    body{
                        margin:0;
                    }

                    .print-header{
                        position:running(pageHeader);
                    }

                    thead{
                        display:table-header-group;
                    }

                    tfoot{
                        display:table-footer-group;
                    }

                    tr{
                        page-break-inside:avoid;
                    }

                }

            </style>

        </head>

        <body>

            <!-- Company Logo -->
            <div class="print-header">
                <img src="<?php echo base_url('public/assets/images/altariq_logo.jpeg'); ?>" alt="Company Logo">
            </div>

            <!-- Report Title -->
            <div class="pnl-header">
                <h2>PROFIT & LOSS ACCOUNT</h2>
                <strong>Period :</strong> ${from} To ${to}
            </div>

            ${printContents}

        </body>

        </html>
    `);

    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}
</script>