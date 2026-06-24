<link rel="stylesheet" href="<?php echo base_url() ?>public/vendors/bootstrap-daterangepicker/daterangepicker.css">
<script src="<?php echo base_url() ?>public/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo base_url() ?>public/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<div class="x_panel">
    <div class="x_title">
        <h2>Outstanding Report</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form class="form-horizontal form-label-left" 
              action="<?php echo base_url('index.php/accounts/search_outstanding_report'); ?>" 
              method="post" id="receipt" name="receipt" autocomplete="off">

            <div class="form-group row">
                <!-- From Date -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">From <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="input-group">
                        <input type="text" class="form-control datepicker1" 
                               name="from" id="from"
                               value="<?php echo isset($from) ? $from : date('d-M-Y'); ?>" required>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <!-- To Date -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">To <span class="required">*</span></label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="input-group">
                        <input type="text" class="form-control datepicker1" 
                               name="to" id="to"
                               value="<?php echo isset($to) ? $to : date('d-M-Y'); ?>" required>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <!-- Type -->
                <label class="control-label col-md-1 col-sm-2 col-xs-12">Type</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control select2" name="request_type" id="request_type" onchange="handleRequestTypeChange()">
                        <option value="">Select Type</option>
                        <option value="Sundry Creditors" <?= ($request_type == 'Sundry Creditors') ? 'selected' : '' ?>>Sundry Creditors</option>
                        <option value="Sundry Debtors" <?= ($request_type == 'Sundry Debtors') ? 'selected' : '' ?>>Sundry Debtors</option>
                    </select>
                </div>
                 <label class="control-label col-md-1">Branch</label>
    <div class="col-md-2">
        <select class="form-control select2" name="branch_id" id="branch_id" onchange="submitForm()">
            <option value="">All Branches</option>
            <?php foreach ($branch_list as $branch) { ?>
                <option value="<?php echo $branch->branch_id; ?>"
                    <?= (isset($branch_id) && $branch_id == $branch->branch_id) ? 'selected' : '' ?>>
                    <?php echo $branch->branch_name; ?>
                </option>
            <?php } ?>
        </select>
    </div>

            </div>

            <!-- Ledger Dropdown -->
            <div class="form-group row" id="ledgerDropdownContainer" <?= empty($ledgers) ? 'style="display:none;"' : '' ?>>
                <label class="control-label col-md-1 col-sm-2 col-xs-12">Ledger</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php if (!empty($ledgers)) : ?>
                        <select class="form-control select2" name="ledger_id" id="ledger_id" onchange="submitForm()">
                            <option value="">Select Ledger</option>
                            <?php foreach ($ledgers as $ledger): ?>
                                <option value="<?= $ledger->account_id ?>" <?= ($ledger_id == $ledger->account_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ledger->account_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">Go</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="submitPrint()">Print</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="submitExport()">Export to Excel</button>
                </div>
            </div>
        </form>

        <!-- Report Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th><?php echo ($request_type == 'Sundry Creditors') ? 'Supplier Name' : 'Customer Name'; ?></th>
                        <th>Ref. No</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Outstanding</th>
                        <th>Due On</th>
                        <th>Overdue Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if (!empty($records)) {
                        foreach ($records as $row) { ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-M-Y', strtotime($row->voucher_date)); ?></td>
                                <td>
                                    <?php 
                                    echo ($request_type == 'Sundry Creditors') 
                                        ? (!empty($row->account_name) ? $row->account_name : 'N/A') 
                                        : (!empty($row->cust_name) ? $row->cust_name : 'N/A');
                                    ?>
                                </td>
                                <td><?php echo $row->voucher_code; ?></td>
                                <td class="text-right"><?php echo number_format($row->sum_amt, 2); ?></td>
                                <td class="text-right"><?php echo number_format($row->sum_paid_amt ?? $row->sum_received_amt ?? 0, 2); ?></td>
                                <td class="text-right"><?php echo number_format($row->sum_due_amt, 2); ?></td>
                                <td><?php echo date('d-M-Y', strtotime('+3 months', strtotime($row->voucher_date))); ?></td>
                                <td>
                                    <?php 
                                    $due_date = strtotime('+3 months', strtotime($row->voucher_date));
                                    $today = strtotime(date('Y-m-d'));
                                    echo ($today > $due_date) ? floor(($today - $due_date) / 86400) : '-';
                                    ?>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="9" class="text-center">No records found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
$(document).ready(function () {
    // Bootstrap Daterangepicker Single Date
    $('.datepicker1').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: { format: 'DD-MMM-YYYY' },
        autoApply: true
    });

    $('.select2').select2({
        width: '100%'
    });
});

function handleRequestTypeChange() {
    var requestType = $('#request_type').val();
    if (requestType === 'Sundry Creditors' || requestType === 'Sundry Debtors') {
        $('#ledgerDropdownContainer').show();
    } else {
        $('#ledgerDropdownContainer').hide();
        $('#ledger_id').val('');
    }
    submitForm();
}

function submitForm() {
    $('#receipt').submit();
}

function submitPrint() {
    const form = $('<form>', {
        method: 'post',
        action: "<?php echo base_url('index.php/Accounts/print_outstanding_report'); ?>",
        target: '_blank'
    }).append(
        $('<input>', { type: 'hidden', name: 'from', value: '<?php echo isset($from) ? $from : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'to', value: '<?php echo isset($to) ? $to : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'ledger_id', value: '<?php echo isset($ledger_id) ? $ledger_id : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'request_type', value: '<?php echo isset($request_type) ? $request_type : ''; ?>' }),
       $('<input>', { type: 'hidden', name: 'branch_id', value: '<?php echo isset($branch_id) ? $branch_id : ''; ?>' })

    );
    $('body').append(form);
    form.submit();
}

function submitExport() {
    const form = $('<form>', {
        method: 'post',
        action: "<?php echo base_url('index.php/Accounts/export_outstanding_report_details'); ?>"
    }).append(
        $('<input>', { type: 'hidden', name: 'from', value: '<?php echo isset($from) ? $from : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'to', value: '<?php echo isset($to) ? $to : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'request_type', value: '<?php echo isset($request_type) ? $request_type : ''; ?>' }),
        $('<input>', { type: 'hidden', name: 'branch_id', value: '<?php echo isset($branch_id) ? $branch_id : ''; ?>' })

    );
    $('body').append(form);
    form.submit();
}
</script>
