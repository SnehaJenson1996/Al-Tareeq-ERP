<div class="card-body">

    <!-- FILTER ROW -->
    <form id="main" method="post"
      action="<?= base_url('index.php/Hr/view_emp_monthly_salary_list') ?>"
      class="form-horizontal">

    <div class="row align-items-center mb-2">

        <!-- LABEL -->
        <div class="col-md-2">
            <label>Select Month</label>
        </div>

        <!-- INPUT -->
        <div class="col-md-3">
            <input type="month"
                   class="form-control form-control-sm"
                   id="from"
                   name="from"
                   value="<?php echo $from; ?>">
        </div>

        <!-- BUTTONS (NO GAP) -->
        <div class="col-md-4">

            <button type="submit"
                    name="go"
                    class="btn btn-primary btn-sm">
                Go
            </button>

            <button type="submit"
                    formaction="<?= base_url('index.php/Hr/print_monthly_record/') ?>"
                    formtarget="_blank"
                    class="btn btn-warning btn-sm">
                Print
            </button>

            <!-- <button type="submit"
                    formaction="<?= base_url('index.php/Hr/export_monthly_record/') ?>"
                    class="btn btn-warning btn-sm">
                Export
            </button> -->

            <button type="submit"
        formaction="<?= base_url('index.php/Hr/export_monthly_record/') ?>"
        formtarget="_blank"
        class="btn btn-success btn-sm">
    Export
</button>

        </div>

    </div>

</form>


    <!-- TABLE WRAPPER -->
    <div class="table-responsive" style="width:100%; overflow-x:auto;">

        <table id="datatable" class="table table-striped table-bordered nowrap" style="width:100%;">

            <thead>
            <tr>
                <th>Sr No</th>
                <th>Employee Name</th>
                <th>Salary Month</th>
                <th>Working Days</th>
                <th>Total Leave</th>
                <th>Present Days</th>
                <th>Paid Leave</th>
                <th>Payment Days</th>
                <th>Total Overtime</th>
                <th>Overtime Amt</th>
                <th>Basic Salary</th>
                <th>Total Allowances</th>
                <th>Total Deduction</th>
                <th>Gross Pay</th>
                <th>Net Pay</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            <?php $i=1; foreach($records as $row){ ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row->employee_name; ?></td>
                    <td><?= !empty($row->salary_month) ? date('M-Y', strtotime($row->salary_month)) : ''; ?></td>
                    <td><?= $row->working_days; ?></td>
                    <td><?= $row->leave_days; ?></td>
                    <td><?= $row->present_days; ?></td>
                    <td><?= $row->paid_leave; ?></td>
                    <td><?= $row->payment_days; ?></td>
                    <td><?= $row->overtime; ?></td>
                    <td><?= $row->overtime_amt; ?></td>
                    <td><?= $row->basic_salary; ?></td>
                    <td><?= $row->total_allowance; ?></td>
                    <td><?= $row->total_deduction; ?></td>
                    <td><?= $row->gross_salary; ?></td>
                    <td><?= $row->net_salary; ?></td>
                    <td><?= $row->remark; ?></td>
                    <td>
                        <!-- <a href="<?= base_url('index.php/Hr/edit_emp_monthly_salary/'.$row->sid); ?>">
                            Edit
                        </a>
                        &nbsp; -->
                        <a href="<?= base_url('index.php/Hr/print_monthly_payslip/'.$row->sid); ?>">
                            Print
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>

    </div>
</div>

<script>
    function confirmcancel(tid) {
        if (!confirm("Are you sure you want to Delete Record?")) {
            return false;
        }
        $.ajax({
            url: "<?= base_url('index.php/Ajax/delete_record') ?>",
            type: "POST",
            data: {
                table_name: 'corporate_file',
                where_key: 'cop_id',
                where_val: tid
            },
            success: function(msg) {
                if (msg == 1) {
                    location.reload();
                } else {
                    alert("Can't Delete record. Data already exist!!!");
                }
            }
        });
        return false;
    }

    // // Datepicker initialization (remove if using input type="month" instead)
    // $('.datepicker1').datepicker({
    //     format: "dd-mm-yyyy",
    //     autoclose: true,
    //     todayBtn: "linked"
    // });

    // function validate() {
    //     var fromdate = document.getElementById('from').value;
    //     if (!fromdate) {
    //         alert("Please Enter Date");
    //         return false;
    //     }

    //     var fparts = fromdate.split('-');
    //     if (fparts.length !== 3 || fparts[2].length < 4) {
    //         alert("Please Enter Date in DD-MM-YYYY");
    //         return false;
    //     }
    //     return true;
    // }
   $('#datatable').DataTable({
    scrollX: true,
    autoWidth: false
});
</script>
