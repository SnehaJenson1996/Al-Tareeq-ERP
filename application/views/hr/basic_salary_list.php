<div class="card-body">
    <div class="dt-responsive table-responsive">
        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Employee Name</th>
                    <th>Gross Salary</th>
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
                        <td><?php echo $row->gross_salary; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->effective_date)); ?></td>

                        <td>
                            <a href="<?php echo base_url() . 'index.php/Hr/edit_salary_structure/' . $row->sid; ?>" title="Edit">Edit<?php echo $this->session->userdata('edit_icon'); ?></a> &nbsp;&nbsp;
<a href="<?php echo base_url() . 'index.php/Hr/delete_basic_salary/' . $row->sid; ?>" 
   title="Delete" 
   onclick="return confirmcancel(<?php echo $row->sid; ?>);">
    <?php 
        $icon = $this->session->userdata('delete_icon');
        echo !empty($icon) ? $icon : '<i class="fa fa-trash text-danger"></i>';
    ?>
</a>                        </td>
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
                    table_name: 'salary_structure',
                    where_key: 'sid',
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