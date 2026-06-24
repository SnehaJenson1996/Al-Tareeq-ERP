<!-- page content -->
<div class="right_col" role="main">
  <div class="page-title">
    
  </div>
  <div class="clearfix"></div>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Add Account Group</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
          </ul>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <br />

          <form id="account" method="post" 
                action="<?php echo base_url() . 'index.php/Accounts/add_account_group_records'; ?>" 
                class="form-horizontal form-label-left">

            <!-- Account Group Name -->
            <div class="form-group row">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ac_group">
                Account Group Name <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="ac_group" name="ac_group" required="required" 
                       placeholder="Enter Account Group Name"
                       class="form-control col-md-7 col-xs-12">
              </div>
            </div>

            <!-- Parent Group -->
            <div class="form-group row">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="p_group">
                Parent Group
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="p_group" id="p_group" class="form-control select2">
                  <option value="0">Self</option>
                  <?php foreach($parent_records as $row) { ?>
                    <option value="<?php echo $row->group_no; ?>"><?php echo $row->group_name; ?></option>
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
                    <option value="<?php echo $row->group_no; ?>"><?php echo $row->group_name; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <!-- Buttons -->
            <div class="ln_solid"></div>
            <div class="form-group row">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" name="add" class="btn btn-success">Submit</button>
                <a target="_blank" href="<?php echo base_url().'index.php/Accounts/view_general_ledger_account_form'; ?>" class="btn btn-primary">
                  Create Ledger Account
                </a>
                <button type="reset" class="btn btn-danger">Reset</button>
              </div>
            </div>

          </form>
        </div> <!-- x_content -->
      </div> <!-- x_panel -->
    </div>
  </div>
</div>
<!-- /page content -->



<script>
$("#p_group").change(function()
{  	
  	var p_group =document.getElementById('p_group').value; 
  	 $.ajax({     	 	 	
        'type': "POST",
 	    'url':"<?php echo base_url()?>index.php/Ajax/get_parent_account_group",          
        'data':{group_no:p_group},
        'success': function(msg){   
       		$('#sec_in_account').val(msg);	
       	}
	});
});


$('#account').validate({
    rules : {

        ac_group: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
            pattern: /\S+/
        },

        p_group: {
            required: true
        }

    },

    messages : {

        ac_group : {
            required : "Please enter Account group",
            pattern: "Spaces only are not allowed"
        },

        p_group: {
            required : "Please Select Parent group"
        }

    },

    highlight : function(element) {
        $(element).closest('.form-group')
            .removeClass('has-success')
            .addClass('has-error');
    },

    unhighlight : function(element) {
        $(element).closest('.form-group')
            .removeClass('has-error')
            .addClass('has-success');
    },

    errorElement : 'span',
    errorClass : 'help-block',

    errorPlacement : function(error, element) {
        error.insertAfter(element);
    }
});

</script>
