<?php  
	foreach ($gen_ledger_records as $row ) {
		$account_id=$row->account_id;
   		$ac_no = $row->account_name;
		$ac_grp = $row->group_no;		
		$op_bal =$row->opening_balance;
		$opening_balance = $row->opening_balance;
		$opening_bal_type = $row->opening_bal_type;
	}
	
?>   
<div class="x_panel">
    <div class="x_title">
        <h2>Update General Ledger Records</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <!-- <li><a href="<?php echo base_url(); ?>index.php/Accounts/general_ledger_list" class="btn btn-sm btn-secondary">List</a></li> -->
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="gen_ledger" method="post" action="<?php echo base_url() . 'index.php/'; ?>Accounts/update_general_ledger_records" class="form-horizontal form-label-left" autocomplete="off">

            <div class="row">
                <!-- Account Name -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Account Name</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <input type="text" id="ac_name" name="ac_name" class="form-control" value="<?php echo $ac_no; ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Account Group -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Account Group</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <select name="ac_group" id="ac_group" class="form-control select2" disabled>
                                <option value="">Select</option>
                                <?php foreach ($account_records as $row) { ?>
                                    <option <?php if ($row->group_no == $ac_grp) echo 'selected'; ?> value="<?php echo $row->group_no; ?>">
                                        <?php echo $row->group_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Opening Balance -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Opening Balance</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <input type="text" id="opening_bal" name="opening_bal" class="form-control" value="<?php echo $opening_balance; ?>">
                        </div>
                    </div>
                </div>

                <!-- Opening Balance Type -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Opening Balance Type</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <select id="dr_cr_type" name="dr_cr_type" class="form-control">
                                <option value="">Select Dr/Cr Type</option>
                                <option value="Dr" <?php if ($opening_bal_type == 'Dr') echo 'selected'; ?>>DR</option>
                                <option value="Cr" <?php if ($opening_bal_type == 'Cr') echo 'selected'; ?>>CR</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="account_id" name="account_id" value="<?php echo $account_id; ?>">

            <!-- Buttons -->
            <div class="ln_solid"></div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </div>
        </form>
    </div>
</div>

<script>

$('#gen_ledger').validate({
		rules : {

			opening_bal: {
				required : true,
				number : true,
				
			},

			 dr_cr_type: {
				required : true,

			},
			 
		},

		messages : {

			opening_bal : {
				required : "Please enter Opening Balance",
			},
			 
			 dr_cr_type: {
				required : "Please Select DR/CR Type",
			},
			
		},

		highlight : function(element) {
			var id_attr = "#" + $(element).attr("id") + "1";
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			$(id_attr).removeClass('glyphicon glyphicon-ok').addClass('glyphicon glyphicon-remove');
		},
		unhighlight : function(element) {
			var id_attr = "#" + $(element).attr("id") + "1";
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			$(id_attr).removeClass('glyphicon glyphicon-remove').addClass('glyphicon glyphicon-ok');
		},
		errorElement : 'span',
		errorClass : 'help-block',
		errorPlacement : function(error, element) {
			if (element.length) {
				error.insertAfter(element);
			} else {
				error.insertAfter(element);
			}
		}
	});

</script>
