<div class="x_panel">
  <div class="x_title">
    <h2>General Ledger <small>Create / Add Account</small></h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <br />
    <form id="gen_ledger" method="post" 
          action="<?php echo base_url().'index.php/Accounts/add_general_ledger_records'; ?>" 
          class="form-horizontal form-label-left">
          <div class="form-group row">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Branch<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">

        <select name="branch_id" id="branch_id" class="form-control" required>
            <option value="">Select</option>
            <?php foreach ($branch_list as $br) { ?>
                <option value="<?php echo $br->branch_id; ?>"
                    <?php echo (!empty($branch_id) && $branch_id == $br->branch_id) ? 'selected' : ''; ?>>
                    <?php echo $br->branch_name; ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>

      <!-- Account Type -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_type">
          Account Type <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select class="form-control" id="account_type" name="account_type" onchange="show_list_div(this.value);">
            <option value="">Please select account type</option>
            <option value="CUS">Customer / Sundry Debtors</option>
            <option value="SUPP">Vendor / Supplier / Sundry Creditors</option>
            <option value="OTHER">Others</option>
          </select>
        </div>
      </div>

      <!-- Account Name (Others) -->
      <div class="form-group row" id="show_other" style="display: none;">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ac_name">
          Account Name
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id="ac_name" name="ac_name" placeholder="Account Name"
                 class="form-control col-md-7 col-xs-12">
        </div>
      </div>

      <!-- Select Customer -->
      <div class="form-group row" id="show_visitor" style="display: none; margin-top: 10px;">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="inward_from_cus">
          Select Customer
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select name="CUS" id="inward_from_cus" class="form-control select2" onchange="check_account_name_exist();">
            <option value="">Select</option>
            <?php foreach($customer_records as $row) { ?>
              <option value="<?php echo $row->occu_name.','.$row->occupier_id; ?>">
                <?php echo $row->occu_name; ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>

    <!-- Select Vendor / Supplier -->
    <div class="form-group row" id="show_vendor" style="display: none; margin-top: 10px;">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="inward_from_supp">
        Select Vendor / Supplier
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="SUPP" id="inward_from_supp" class="form-control select2" onchange="check_account_name_exist();">
          <option value="">Select</option>
          <?php foreach($supplier_records as $row) { ?>
            <option value="<?php echo $row->supp_name.','.$row->supp_id; ?>">
              <?php echo $row->supp_name; ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>

      <!-- Account Group -->
       <br> <br>
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ac_group">
          Account Group
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select name="ac_group" id="ac_group" class="form-control select2">
            <option value="">Select</option>
            <?php foreach($account_records as $row) { ?>
              <option value="<?php echo $row->group_no; ?>">
                <?php echo $row->group_name; ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Opening Balance -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="opening_bal">
          Opening Balance
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" id="opening_bal" name="opening_bal" placeholder="Opening Balance"
                 class="form-control col-md-7 col-xs-12">
        </div>
      </div>

      <!-- Dr/Cr Type -->
      <div class="form-group row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="dr_cr_type">
          Opening Balance Type
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <select id="dr_cr_type" name="dr_cr_type" class="form-control">
            <option value="">Select Dr/Cr Type</option>
            <option value="Cr">Cr</option>
            <option value="Dr">Dr</option>
          </select>
        </div>
      </div>

      <div class="ln_solid"></div>

      <!-- Buttons -->
      <div class="form-group row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <button type="submit" name="add" class="btn btn-success">Submit</button>
          <a target="_blank" href="<?php echo base_url().'index.php/Accounts/view_account_group_form'; ?>" class="btn btn-primary">
            Create Group
          </a>
          <button type="reset" class="btn btn-danger">Reset</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- jQuery (only once, from template or CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Validation -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

function check_acc_code_exist(post_value, table_name, column_name, input_name)
{
	//var ac_code = document.getElementById("ac_code").value;
	//alert(ac_code);
	if(post_value!='')
	{
		$.ajax({     	 	 	
	        'type': "POST",
	 	    'url':"<?php echo base_url()?>index.php/Ajax/check_record_exist",          
	        'data':{code:post_value, tb_name:table_name, column: column_name},
	        //'dataType':"json",
	        'success': function(msg)
	        { 
	        	if(msg==1)
	        	{
	        	 alert('Account Code already exist');
	        	 document.getElementById(input_name).value='';
	        	}
	       	}
		});
	}
}

function show_list_div(acc_type) {

    $("#show_visitor, #show_vendor, #show_other").hide();

    if (acc_type == 'CUS') {
        $("#show_visitor").show();
    } 
    else if (acc_type == 'SUPP') {
        $("#show_vendor").show();
    } 
    else if (acc_type == 'OTHER') {
        $("#show_other").show();
    }

    
}
$('#gen_ledger').validate({
		rules : {
		  account_type: {
				required : true,
		  },
		  ac_code: {
				required : true,
				maxlength: 9,
		  },
		  ac_name: {
			required : true,
		  },
		  ac_group: {
				required : true,
		  },
		  opening_bal: {
				required : true,
				number : true,
				
			},
		 dr_cr_type: {
				required : true,
			},
		},

		messages : {

			account_type : {
				required : "Please Select Account type",
			},

			ac_code : {
				required : "Please enter Account Code",
			},
			 
			 ac_name: {
				required : "Please enter Account Name",
			},
			
			ac_group : {
				required : "Please Select Account Group ",
			},
			
			Opening_bal : {
				required : "Please enter Opening Balance ",
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
	
	function check_account_name_exist()
	{
       var inward_from=document.getElementById('inward_from').value;
	   inward_from_name=$("#inward_from option:selected").text();
	  
       $.ajax
       ({
			url: "<?php echo site_url('ajax_validation/check_account_name_exist'); ?>",
			type: 'POST',
			dataType: "json",
			data: {ac_name: inward_from_name},
			success: function(msg) {
				//alert(msg);
				if(msg !=0)
				{
					alert("Account Name Already Exits");
					$('#inward_from').val('');
				}
				
		}
		});
	}
$(document).ready(function () {
    $('#ac_group').select2({
        width: '100%'
    });
});
</script>
