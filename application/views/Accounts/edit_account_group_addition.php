
<?php
$account_grp = '';
$p_grp = 0;
$ag_id = '';
$section_id = '';

if(!empty($acc_grp_records)) {
    $row = $acc_grp_records[0]; // get the first record
    $account_grp = $row->group_name;
    $p_grp = $row->parent_group;
    $ag_id = $row->group_no;
    $section_id = $row->sno;
}
?>

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Edit Account Group</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
          <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <br />

        <form id="account" method="post" 
              action="<?php echo base_url() . 'index.php/Accounts/update_account_grp_records'; ?>" 
              class="form-horizontal form-label-left">

          <!-- Account Group Name -->
          <div class="form-group row">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ac_group">
              Account Group Name <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id="ac_group" name="ac_group" required="required" 
                     placeholder="Enter Account Group Name"
                     class="form-control col-md-7 col-xs-12"
                     value="<?php echo $account_grp; ?>">
            </div>
          </div>

          <!-- Parent Group -->
          <div class="form-group row">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="p_group">
              Parent Group
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="p_group" id="p_group" class="form-control select2">
                <option value="0">Top Level Group</option>
                <?php foreach($parent_records as $row) { ?>
                  <option value="<?php echo $row->group_no; ?>" <?php if($row->group_no == $p_grp) echo 'selected'; ?>>
                    <?php echo $row->group_name; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>

          <!-- Section In Accounts -->
          <div class="form-group row">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sec_in_account">
              Section In Accounts
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="sec_in_account" id="sec_in_account" class="form-control">
                <option value="">Select</option>
                <?php foreach($section_records as $row) { ?>
                  <option value="<?php echo $row->group_no; ?>" <?php if($row->group_no == $section_id) echo 'selected'; ?>>
                    <?php echo $row->group_name; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>

          <input type="hidden" id="ag_id" name="ag_id" value="<?php echo $ag_id;?>">

          <!-- Buttons -->
          <div class="ln_solid"></div>
          <div class="form-group row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="submit" name="add" class="btn btn-success">Update</button>
              <button type="reset" class="btn btn-danger">Reset</button>
            </div>
          </div>

        </form> <!-- End form -->

      </div> <!-- x_content -->
    </div> <!-- x_panel -->
  </div> <!-- col-md -->
</div> <!-- row -->

<script>
$("#p_group").change(function() {  	
    var p_group = $(this).val(); 
    $.ajax({     	 	 	
        type: "POST",
        url: "<?php echo base_url()?>index.php/Ajax/get_parent_account_group",          
        data: {group_no: p_group},
        success: function(msg){   
            $('#sec_in_account').val(msg);	
        }
    });
});

$('#account').validate({
    rules : {
        ac_group: { required : true },
        p_group: { required : true },
        sec_in_account: { required : true },
    },
    messages : {
        ac_group : { required : "Please enter Account group" },
        p_group: { required : "Please select Parent group" },
        sec_in_account : { required : "Please select Section" },
    },
    highlight : function(element) {
        $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
    },
    unhighlight : function(element) {
        $(element).closest('.form-group').addClass('has-success').removeClass('has-error');
    },
    errorElement : 'span',
    errorClass : 'help-block',
    errorPlacement : function(error, element) {
        error.insertAfter(element);
    }
});
</script>