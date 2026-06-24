<div class="x_panel">
    <div class="x_title">
        <h2>Cash Flow Report</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <?php
        // Default dates if empty
        if (empty($from_date)) {
            $from_date = date('d-m-Y', strtotime('first day of this month'));
        }
        if (empty($to_date)) {
            $to_date = date('d-m-Y', strtotime('last day of this month'));
        }
        $transaction_type = 'cash';
        ?>

        <form class="form-horizontal form-label-left" action="<?php echo base_url('index.php/Accounts/view_cash_flow'); ?>" method="post" autocomplete="off">

            <!-- Row: Date Range and Transaction Type -->
            <div class="form-group row">
                <label class="col-sm-1 col-form-label">From <span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm datepicker1" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <label class="col-sm-1 col-form-label">To <span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm datepicker1" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

           

            <!-- Branch -->
<label class="col-sm-1 col-form-label">Branch</label>
<div class="col-sm-3">
    <select name="branch_id" id="branch_id" class="form-control form-control-sm">
        <option value="">All Branches</option>
        <?php foreach ($branch_list as $branch) { ?>
            <option value="<?= $branch->branch_id ?>"
                <?= (isset($branch_id) && $branch_id == $branch->branch_id) ? 'selected' : '' ?>>
                <?= $branch->branch_name ?>
            </option>
        <?php } ?>
    </select>
</div>
 </div>
            <!-- Submit and Print Buttons -->
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" id="view" class="btn btn-primary btn-sm">Go</button>
                    <?php
                   $print_url = base_url('index.php/Accounts/print_cashflow_report') .
             '?from_date=' . urlencode($from_date) .
             '&to_date=' . urlencode($to_date) .
             '&branch_id=' . urlencode($branch_id ?? '');
?>
                    <a href="<?= $print_url ?>" target="_blank" class="btn btn-warning btn-sm">Print</a>
                </div>
            </div>
        </form>

        <!-- Cash Flow Table -->
        <?php if (!empty($cashflow_summary)) : ?>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="bg-primary text-white">
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
    <td>
        <?= !empty($row->trx_date) ? date('d-m-Y', strtotime($row->trx_date)) : '' ?>
    </td>

    <td>
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
                        <tr class="font-weight-bold bg-light">
                            <td colspan="3">Total</td>
                            <td class="text-success"><?= number_format($totals['inflows'], 2) ?></td>
                            <td class="text-danger"><?= number_format($totals['outflows'], 2) ?></td>
                            <td><?= number_format($totals['net'], 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">No cash transactions found for the selected date range.</div>
        <?php endif; ?>
    </div>
</div>

<!-- jQuery (already required by Bootstrap Datepicker) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (v3 or v4 is fine, depending on your setup) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>

<!-- Bootstrap Datepicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<script>
$('.datepicker1').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
</script>
