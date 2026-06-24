<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-money"></i> Monthly Salary Generation</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <!-- FILTER FORM (ONLY MONTH) -->
        <form method="post" action="<?php echo base_url('index.php/Hr/add_monthly_salary'); ?>">

            <div class="form-group row">

                <label class="col-md-2">Select Month <span class="text-danger">*</span></label>

                <div class="col-md-3">
                    <input type="month"
                           class="form-control form-control-sm"
                           name="effective_date"
                           value="<?php echo isset($effective_date) ? date('Y-m', strtotime($effective_date)) : date('Y-m'); ?>">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i> Go
                    </button>
                </div>

            </div>

        </form>

        <hr>

        <!-- SALARY TABLE -->
        <?php if (!empty($employee_salary_data)) { ?>

        <form method="post" action="<?php echo base_url('index.php/Hr/add_monthly_salary_data'); ?>">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead style="background:#2e6da4; color:#fff;">
                        <tr>
                            <th>
                                <input type="checkbox" onclick="$('.emp').prop('checked', this.checked)">
                            </th>
                            <th>Employee</th>
                            <th>Working Days</th>
                            <th>Present</th>
                            <th>Leave</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Overtime</th>
                            <th>Gross</th>
                            <th>Net Pay</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($employee_salary_data as $row) { ?>

                        <tr>
                            <td>
                                <input type="checkbox"
                                       class="emp"
                                       name="employee_ids[]"
                                       value="<?= $row->employee_id ?>">
                            </td>

                            <td><?= $row->employee_name ?></td>
                            <td><?= $row->working_days ?></td>
                            <td><?= $row->present_days ?></td>
                            <td><?= $row->leave_days ?></td>

                            <td><?= number_format($row->basic_salary, 2) ?></td>
                            <td><?= number_format($row->allowances, 2) ?></td>
                            <td><?= number_format($row->deductions, 2) ?></td>
                            <td><?= number_format($row->overtime, 2) ?></td>

                            <td><b><?= number_format($row->gross_salary, 2) ?></b></td>
                            <td><b><?= number_format($row->net_pay, 2) ?></b></td>
                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-check-circle"></i> Generate Salary
                </button>
            </div>

        </form>

        <?php } else { ?>
            <div class="alert alert-info">
                Select a month and click Go to load employee salary list.
            </div>
        <?php } ?>

    </div>
</div>
