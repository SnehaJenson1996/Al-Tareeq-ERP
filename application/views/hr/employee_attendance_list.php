<div class="card-body">
    <div class="dt-responsive table-responsive">
        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Employee Name</th>
                    <th>Attendance</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($records as $row) { ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->attendence; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->Attendance_date)); ?></td>

                        <td>
                            <a href="<?php echo base_url() . 'index.php/Hr/edit_emp_attendance/' . $row->emp_aId; ?>" title="Edit">Edit<?php echo $this->session->userdata('edit_icon'); ?></a>
                            <a href="<?php echo base_url() . 'index.php/Hr/delete_attendance_emp/' . $row->emp_aId; ?>" title="Delete" onclick="return confirmcancel(<?php echo $row->emp_aId; ?>);">Delete<?php echo $this->session->userdata('delete_icon'); ?></a>
							<!-- <a href="<?php echo base_url() . 'index.php/Hr/delete_leave_application/' . $row->leave_id; ?>" title="Delete" onclick="return confirmcancel(<?php echo $row->leave_id; ?>);"><?php echo $this->session->userdata('delete_icon'); ?></a> -->

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
                    table_name: 'employee_attendance',
                    where_key: 'emp_aId',
                    where_val: tid
                },
                success: function(msg) {
                    if (msg == 1) {
                        // alert("Record deleted");
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