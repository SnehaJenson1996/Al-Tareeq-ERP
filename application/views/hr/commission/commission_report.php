<div class="card">

    <div class="card-header">
        <h4>Commission Report</h4>
    </div>

    <div class="card-body">
        <form method="post"
            action="<?= base_url(); ?>index.php/Hr/view_commission_report">
            <div class="row">

                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date"
                        name="from_date"
                        class="form-control"
                        value="<?= set_value('from_date'); ?>">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date"
                        name="to_date"
                        class="form-control"
                        value="<?= set_value('to_date'); ?>">
                </div>

                <div class="col-md-3">
                    <label>Sales Representative</label>

                    <select name="sales_rep_id"
                        class="form-control">

                        <option value="">All Sales Representatives</option>
                        <?php
                        if (!empty($sales_rep)) {
                            foreach ($sales_rep as $row) {
                        ?>
                            <option value="<?= $row->sales_rep_id; ?>">
                                <?= $row->sales_rep_name; ?>
                            </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Status</label>

                    <select name="status"
                        class="form-control">
                        <option value="">All</option>
                        <option value="Pending">Pending</option>
                        <option value="Eligible">Eligible</option>
                        <option value="Approved">Approved</option>
                        <option value="Paid">Paid</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <br>

            <button type="submit"
                class="btn btn-success">
                <i class="fa fa-search"></i>
                Search
            </button>

            <a href="<?= base_url(); ?>index.php/Hr/view_commission_report"
                class="btn btn-secondary">
                Reset
            </a>

            <a href="<?=base_url()?>index.php/Hr/print_commission_report?<?=http_build_query($_POST);?>"
                target="_blank"
                class="btn btn-primary">
                <i class="fa fa-print"></i>
                Print
            </a>

            <a href="<?=base_url()?>index.php/Hr/export_commission_report?<?=http_build_query($_POST);?>"
                class="btn btn-success">
                <i class="fa fa-file-excel-o"></i>
                Excel
            </a>

        </form>

        <hr>

        <div class="table-responsive">

            <table class="table table-bordered table-striped"
                id="datatable">
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
                    ?>

                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->invoice_code; ?></td>
                            <td>
                                <?= date('d-M-Y', strtotime($row->invoice_date)); ?>
                            </td>
                            <td><?= $row->customer_name; ?></td>
                            <td><?= $row->sales_rep_name; ?></td>
                            <td>
                                <?= number_format($row->invoice_amount, 2); ?>
                            </td>
                            <td>
                                <?= $row->commission_percent; ?> %
                            </td>
                            <td>
                                <?= number_format($row->commission_amount, 2); ?>
                            </td>
                            <td>
                                <?= date('d-M-Y', strtotime($row->eligible_date)); ?>
                            </td>
                            <td>
                                <?php
                                if ($row->status == "Pending") {
                                    echo '<span class="badge badge-warning">Pending</span>';
                                } elseif ($row->status == "Eligible") {
                                    echo '<span class="badge badge-info">Eligible</span>';
                                } elseif ($row->status == "Approved") {
                                    echo '<span class="badge badge-success">Approved</span>';
                                } elseif ($row->status == "Paid") {
                                    echo '<span class="badge badge-primary">Paid</span>';
                                } elseif ($row->status == "Rejected") {
                                    echo '<span class="badge badge-danger">Rejected</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($row->approved_date)) {
                                    echo date(
                                        'd-M-Y H:i',
                                        strtotime($row->approved_date)
                                    );
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($row->payment_date)) {
                                    echo date(
                                        'd-M-Y',
                                        strtotime($row->payment_date)
                                    );
                                }
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
                </tbody>
            </table>
        </div>
    </div>
</div>