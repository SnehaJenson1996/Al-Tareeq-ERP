<div class="card-body">
    <div class="dt-responsive table-responsive">
        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Leave Code</th>
                    <th>Employee Name</th>
                    <th>Leave Type</th>
                    <th>Leave From / To</th>
                    <th>Applied On</th>
                    <th>Leave Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($records as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row->leave_code; ?></td>
                        <td><?php echo $row->name; ?></td>

                        <td><?php echo $row->leave_type; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->start_date)) . ' <br> ' . date('d-M-Y', strtotime($row->end_date)); ?></td>


                        <td><?php echo date('d-M-Y', strtotime($row->application_date)); ?></td>
                        <td>
                            <?php
                            $latest_status = 'Pending'; // Default value
                            foreach ($record1 as $app) {
                                if ($row->leave_id == $app->leave_id) {
                                    if ($app->leave_status == 0) {
                                        $latest_status = 'Pending';
                                    } else if ($app->leave_status == 1) {
                                        $latest_status = 'Approved';
                                    } else if ($app->leave_status == 2) {
                                        $latest_status = 'Rejected';
                                    }
                                }
                            }
                            echo '<span style="color:';
                            echo ($latest_status == 'Pending') ? 'yellow' : (($latest_status == 'Approved') ? 'green' : 'red');
                            echo ';">' . $latest_status . '</span>';
                            ?>
                        </td>


                        <td>
                            <a href="<?php echo base_url() . 'index.php/Hr/edit_leave_corner_application/' . $row->leave_id; ?>" title="Edit"><?php echo $this->session->userdata('edit_icon'); ?></a>
                            <a href="<?php echo base_url() . 'index.php/Hr/delete_leave_corner_application/' . $row->leave_id; ?>" title="Delete" onclick="return confirmcancel(<?php echo $row->leave_id; ?>);"><?php echo $this->session->userdata('delete_icon'); ?></a>
                            <a href="<?php echo base_url() . 'index.php/Hr/print_leave_application/' . $row->leave_id; ?>" title="Print" target="_blank"><i class="fa fa-print" style="font-size:18px"></i></a>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
                    table_name: 'employee_leave',
                    where_key: 'leave_id',
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