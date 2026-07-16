<style>
    .daybook-container {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
    }

    .daybook-header {
        background: #2c3e50;
        color: #fff;
        padding: 10px;
        text-align: center;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .daybook-header h4 {
        margin: 0;
    }

    .table-daybook th {
        background: #f1f1f1;
        font-size: 13px;
    }

    .table-daybook td {
        font-size: 13px;
        vertical-align: middle;
    }

    .amount {
        text-align: right;
        white-space: nowrap;
    }

    .voucher-link {
        color: #007bff;
        text-decoration: none;
    }

    .voucher-link:hover {
        text-decoration: underline;
    }

    .total-row {
        background: #e9ecef;
        font-weight: bold;
    }

    @media print {

        .btn,
        form {
            display: none !important;
        }

        body {
            background: #fff;
        }

        .daybook-container {
            border: none;
        }
    }
</style>

<div class="card-body">

    <!-- ================= FILTER ================= -->
    <form method="post" action="<?= base_url('index.php/Accounts/daily_transaction_report') ?>">
        <div class="row mb-3">

            <div class="col-md-2">
                <label>From</label>
                <input type="date" name="from_date" value="<?= $from_date ?>" class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <label>To</label>
                <input type="date" name="to_date" value="<?= $to_date ?>" class="form-control form-control-sm">
            </div>

            <div class="col-md-3" style="padding-top:24px;">
                <button type="submit" class="btn btn-primary btn-sm" style="margin-right:10px;">
                    <i class="fa fa-search" style="margin-right:5px;"></i>Go
                </button>
                <button type="button" onclick="printDayBook()" class="btn btn-warning btn-sm">
                    <i class="fa fa-print" style="color:#000; margin-right:5px;"></i>
                    <span style="color:#000;">Print</span>
                </button>
            </div>

        </div>
    </form>

    <!-- ================= REPORT ================= -->
    <div id="printArea">

        <div class="daybook-container">

            <div class="daybook-header">
                <h4>DAY BOOK</h4>
                <small>
                    <?= date('d-M-Y', strtotime($from_date)) ?>
                    To
                    <?= date('d-M-Y', strtotime($to_date)) ?>
                </small>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-sm table-daybook">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Voucher No</th>
                            <th>Type</th>
                            <th>Account</th>
                            <th class="amount">Debit</th>
                            <th class="amount">Credit</th>
                            <th>Narration</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $total_dr = 0;
                        $total_cr = 0;
                        ?>

                        <?php if (!empty($daybook_records)) { ?>

                            <?php foreach ($daybook_records as $row) {

                                // FIXED DR/CR LOGIC
                                $type = strtolower($row->drcr_type);

                                $dr = ($type == 'dr' || $type == 'debit') ? $row->amount : 0;
                                $cr = ($type == 'cr' || $type == 'credit') ? $row->amount : 0;

                                $total_dr += $dr;
                                $total_cr += $cr;
                            ?>

                                <tr>
                                    <td><?= date('d-m-Y', strtotime($row->trx_date)) ?></td>

                                    <td><?= $row->voucher_code ?? '-' ?></td>

                                    <td><?= ucfirst($row->voucher_type ?? '-') ?></td>

                                    <td><?= $row->account_name ?? '-' ?></td>

                                    <td class="amount text-danger">
                                        <?= number_format($dr, 2) ?>
                                    </td>

                                    <td class="amount text-success">
                                        <?= number_format($cr, 2) ?>
                                    </td>

                                    <td><?= $row->narration ?? '-' ?></td>
                                </tr>

                            <?php } ?>

                            <tr class="total-row">
                                <td colspan="4" class="text-right">TOTAL</td>
                                <td class="amount text-danger">
                                    <?= number_format($total_dr, 2) ?>
                                </td>
                                <td class="amount text-success">
                                    <?= number_format($total_cr, 2) ?>
                                </td>
                                <td></td>
                            </tr>

                        <?php } else { ?>

                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    No transactions found
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- ================= PRINT SCRIPT ================= -->
<script>
    function printDayBook() {

        let content = document.getElementById('printArea').innerHTML;

        let from = document.querySelector('input[name="from_date"]').value;
        let to = document.querySelector('input[name="to_date"]').value;

        let win = window.open('', '', 'width=1200,height=800');

        win.document.write(`
        <html>
        <head>
            <title>Day Book</title>
            <style>
                body{
                    font-family: Arial;
                    font-size: 12px;
                    margin:20px;
                }

                h2{
                    text-align:center;
                }

                .date{
                    text-align:center;
                    margin-bottom:10px;
                }

                table{
                    width:100%;
                    border-collapse:collapse;
                }

                th, td{
                    border:1px solid #000;
                    padding:6px;
                }

                th{
                    background:#eee;
                }

                .amount{
                    text-align:right;
                }

                .total-row{
                    font-weight:bold;
                    background:#f5f5f5;
                }

                @page{
                    size:A4 landscape;
                    margin:10mm;
                }
            </style>
        </head>
        <body>

            <h2>DAY BOOK</h2>
            <div class="date">${from} TO ${to}</div>

            ${content}

        </body>
        </html>
    `);

        win.document.close();

        win.onload = function() {
            win.print();
            win.close();
        };
    }
</script>