<div class="card-body">
    <div class="dt-responsive table-responsive">
        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Resign Code</th>
                    <th>Employee Name</th>
                    <th>Resignation Date:</th>
                    <th>Last Working Date</th>
                    <th>Notice Period</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($records as $row) { ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td><?php echo $row->resign_code; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->resignation_date)); ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->last_working_date)); ?></td>
                        <td><?php echo $row->notice_days; ?></td>

                        <td>

                            <a href="<?php echo base_url() . 'index.php/Hr/edit_emp_regignation_corner/' . $row->resig_id; ?>" title="Edit"><?php echo $this->session->userdata('edit_icon'); ?></a>
                            <a href="<?php echo base_url() . 'index.php/Hr/delete_resignation_application/' . $row->resig_id; ?>" title="Delete" onclick="return confirmcancel(<?php echo $row->resig_id; ?>);"><?php echo $this->session->userdata('delete_icon'); ?></a>
                            <a href="<?php echo base_url() . 'index.php/Hr/print_resignation_application/' . $row->resig_id; ?>" title="Print" target="_blank"><i class="fa fa-print" style="font-size:18px"></i></a>

                        </td>
                    </tr>
                <?php  } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Static Table End -->



<script>
    function confirmcancel(tid) {
        var r = confirm("Are you sure you want to Delete Record?");
        if (r == true) {
            $.ajax({
                url: "<?php echo base_url() ?>index.php/Ajax/delete_record",
                type: "POST",
                data: {
                    table_name: 'employee_resignation',
                    where_key: 'resig_id',
                    where_val: tid
                },
                success: function(msg) {
                    if (msg == 1) {

                        window.location.href = "<?php echo $_SERVER['PHP_SELF'] ?>";
                    } else {
                        alert("Can't Delete record. Data already exist!!!");
                    }
                },
            });
            return true;
        } else
            return false;

    }
</script>