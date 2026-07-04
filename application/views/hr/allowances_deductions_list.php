<?php
    $page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $logged_user = $this->session->userdata('user_id');
?>
<style>
	.action-icons i {
		font-size: 18px;
		margin: 0 5px;
		vertical-align: middle;
	}
</style>

<div class="card-body">
    <div class="dt-responsive table-responsive">
        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Allowances Type</th>
                    <th>Allowance Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($records as $row) { ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td>
                            <?php echo ($row->allowance_type == 'A') ? "Allowances" : (($row->allowance_type == 'D') ? "Deductions" : ""); ?>
                        </td>

                        <td><?php echo $row->allowance_name; ?></td>

                        <td class="action-icons">
                            <a href="<?php echo base_url() . 'index.php/Hr/edit_allowances/' . $row->sno; ?>" title="Edit"><i class="fa fa-edit"></i><?php echo $this->session->userdata('edit_icon'); ?></a>

                            <a href="<?php echo base_url() . 'index.php/Hr/delete_Allowances/' . $row->sno; ?>"title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>

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
                    table_name: 'allowance_master',
                    where_key: 'sno',
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