<?php ini_set('display_errors', 1);
error_reporting(E_ALL); ?>
<?php $this->load->helper('form'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>Trial Balance</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <!-- Filter Form -->
        <form class="form-horizontal form-label-left"
            action="<?= base_url('index.php/accounts/trial_balance') ?>"
            method="post" id="receipt" name="receipt" onsubmit="return goToUrlWithDates()">

            <div class="form-group row">

                <!-- From Date -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">From</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input type="date" class="form-control"
                        name="from_date" id="from_date"
                        value="<?= isset($from_date) ? $from_date : date('Y-m-d') ?>" required>
                </div>

                <!-- To Date -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">To</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <input type="date" class="form-control"
                        name="to_date" id="to_date"
                        value="<?= isset($to_date) ? $to_date : date('Y-m-d') ?>" required>
                </div>

                <!-- Branch -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">Branch</label>
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <select name="branch_id" id="branch_id" class="form-control">
                        <option value="">All Branches</option>
                        <?php foreach ($branch_list as $branch) { ?>
                            <option value="<?= $branch->branch_id ?>"
                                <?= (isset($branch_id) && $branch_id == $branch->branch_id) ? 'selected' : ''; ?>>
                                <?= $branch->branch_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- View, Print & Export Buttons -->
        <div class="form-group" style="margin-top:15px; margin-bottom:15px;">

            <div style="display:flex; align-items:center; gap:10px;">

                <!-- View -->
                <button type="submit"
                        form="receipt"
                        class="btn btn-primary btn-sm">
                    <i class="fa fa-search" style="margin-right:5px;"></i>View
                </button>

                <!-- Export -->
                <form method="post"
                    action="<?= base_url('index.php/Accounts/trial_balance_export') ?>"
                    style="margin:0;">

                    <input type="hidden" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
                    <input type="hidden" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
                    <input type="hidden" name="branch_id" value="<?= htmlspecialchars($branch_id) ?>">

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel-o" style="margin-right:5px;"></i>Export to Excel
                    </button>

                </form>

                <!-- Print -->
                <form method="post"
                    action="<?= base_url('index.php/Accounts/trial_balance_print') ?>"
                    target="_blank"
                    style="margin:0;">

                    <input type="hidden" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
                    <input type="hidden" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
                    <input type="hidden" name="branch_id" value="<?= htmlspecialchars($branch_id) ?>">

                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fa fa-print" style="margin-right:5px;"></i>Print
                    </button>

                </form>

            </div>

        </div>

        <!-- Trial Balance Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered table-sm" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>Group</th>
                        <th>Ledger</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($accounts)):
                        $current_group = null;
                        foreach ($accounts as $row):
                            if ($current_group !== $row['group_name']):
                                // Print group total if exists
                                if ($current_group !== null && isset($group_totals[$current_group])):
                                    $gt = $group_totals[$current_group]; ?>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="2">Total for <?= htmlspecialchars($current_group) ?></td>
                                        <td class="text-end"><?= number_format($gt['debit'], 2) ?></td>
                                        <td class="text-end"><?= number_format($gt['credit'], 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="table-primary">
                                    <td colspan="4"><strong><?= htmlspecialchars($row['group_name']) ?></strong></td>
                                </tr>
                            <?php $current_group = $row['group_name'];
                            endif; ?>
                            <tr>
                                <td></td>
                                <td><?= htmlspecialchars($row['account_name']) ?></td>
                                <td class="text-end"><?= number_format($row['debit'], 2) ?></td>
                                <td class="text-end"><?= number_format($row['credit'], 2) ?></td>
                            </tr>
                        <?php endforeach;
                        // Last group total
                        if ($current_group !== null && isset($group_totals[$current_group])):
                            $gt = $group_totals[$current_group]; ?>
                            <tr class="table-secondary fw-bold">
                                <td colspan="2">Total for <?= htmlspecialchars($current_group) ?></td>
                                <td class="text-end"><?= number_format($gt['debit'], 2) ?></td>
                                <td class="text-end"><?= number_format($gt['credit'], 2) ?></td>
                            </tr>
                        <?php endif;
                    else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery & jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    function goToUrlWithDates() {
        const fromDate = $("#from_date").datepicker("getDate");
        const toDate = $("#to_date").datepicker("getDate");

        if (!fromDate || !toDate) {
            alert('Please select both From and To dates.');
            return false;
        }

        if (fromDate > toDate) {
            alert('From date cannot be greater than To date.');
            return false;
        }

        function formatDate(d) {
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            return dd + '-' + mm + '-' + yyyy;
        }

        const baseUrl = '<?= base_url("index.php/accounts/trial_balance") ?>';
        window.location.href = `${baseUrl}/${formatDate(fromDate)}/${formatDate(toDate)}`;
        return false;
    }
</script>