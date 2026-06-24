<div>
    <div class="card-body">

        <form onsubmit="return check_duplicate_exist();" id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Hr/add_approve_data" autocomplete="off" enctype="multipart/form-data">

            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Approve Type :<span style="color: red;">*</span></label>
                <div class="col-sm-5">
                    <select class="form-select form-control-sm select2" name="approve_type" id="approve_type" tabindex=3 required onchange="check_dept_exist();">
                        <option value="" selected>Select Approve Type</option>
                        <option value="Leave">Leave</option>
                        <option value="Resignation">Resignation</option>
                    </select>
                    <span id="dept_exits" style="color: red;"></span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">HR:</label>
                <div class="col-xs-12 col-sm-9 col-md-5 col-lg-5">
                    <select tabindex="1" class="form-select form-control-sm select2" id="approve_hr" name="approve_hr">
                        <option value="">Select HR</option>
                        <?php foreach ($user_records as $hr) { ?>
                            <option <?php if ($this->session->userdata('user_id') == $hr->user_id) echo 'selected'; ?> value="<?php echo $hr->user_id ?>"><?php echo $hr->user_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>



            <div class="form-group row">
                <label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Admin / MD:</label>
                <div class="col-xs-12 col-sm-9 col-md-5 col-lg-5">
                    <select tabindex="1" class="form-select form-control-sm select2" id="approve_admin_md" name="approve_admin_md">
                        <option value="">Select Admin/DM</option>
                        <?php foreach ($user_records as $ad) { ?>
                            <option <?php if ($this->session->userdata('user_id') == $ad->user_id) echo 'selected'; ?> value="<?php echo $ad->user_id ?>"><?php echo $ad->user_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <div class="form-group row justify-content-center">
                <div class="col-sm-8 text-center">
                    <!-- <input type="hidden" name="id" value="<?php echo $row->approve_id; ?>"> -->
                    <button type="submit" tabindex="11" id="add" class="btn btn-primary m-b-0">Submit</button>
                </div>
            </div>

        </form>


    </div>
    <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table id="datatable" class="table table-striped" data-toggle="data-table">
                <thead>
                    <tr>

                        <th>Approve Type </th>
                        <th>HR</th>
                        <th>Admin / MD</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($records as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row->approve_type; ?></td>




                            <td><?php foreach ($user_records as $hr) { ?><?php if ($hr->user_id == $row->approve_hr) { ?><?php echo $hr->user_name; ?><?php } ?> <?php } ?></td>


                            <td><?php foreach ($user_records as $hr) { ?><?php if ($hr->user_id == $row->approve_admin_md) { ?><?php echo $hr->user_name; ?><?php } ?> <?php } ?></td>


                            <td>
                                <a href="#" title="Delete" onclick="return confirmCancel(<?php echo $row->approve_id; ?>);">
                                    <?php echo $this->session->userdata('delete_icon'); ?>
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


<script>
    function confirmCancel(tid) {
        // Show the confirmation prompt
        if (confirm("Are you sure you want to delete this record?")) {
            // If confirmed, send the AJAX request
            $.ajax({
                url: "<?php echo base_url('index.php/Ajax/delete_record'); ?>",
                type: "POST",
                data: {
                    table_name: 'approval_setup',
                    where_key: 'approve_id',
                    where_val: tid
                },
                success: function(response) {
                    if (response == 1) {
                        // If successful, reload the current page
                        window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>";
                    } else {
                        // If an error occurs, show an alert
                        alert("Can't delete record. Data already exists!");
                    }
                }
            });
        }
        // Return false to prevent the default anchor action
        return false;
    }
</script>

<script>
    function check_dept_exist() {
        var atype = $('#approve_type').val();

        $.ajax({
            url: "<?php echo site_url('Ajax/check_duplicate_exist3'); ?>",
            type: 'POST',
            data: {
                table_name: 'approval_setup',
                column_name1: 'approve_type',
                post_id1: atype
            },
            success: function(msg) {
                if (msg != 0) {
                    $('#dept_exits').html("Type already exits");
                    $('#approve_type').val('');
                } else {
                    $('#dept_exits').html("");
                }
            }
        });
    }
</script>