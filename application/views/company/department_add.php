<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Company/save_department" id="addform" autocomplete="off" >

	    <div class="form-group row">
	      <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Department Name <span style="color: red;"> * </span></label>
	      <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<input tabindex="1" type="text" name="dept_name" id="dept_name" onblur="check_dept_exist();" class="form-control" placeholder="" required>
	      </div>
	      <label id="dept_exits" style="color: red;"></label>
	    </div>


	    <div class="form-group row">
		<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Remarks</label>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<textarea tabindex="2" name="remark" id="remark" class="form-control" rows="5" cols="3" placeholder="Enter remark"></textarea>
		</div>
	    </div>

	    <div class="form-group row">
	      <label class="col-sm-2"></label>
	      <div class="col-sm-10">
		<button tabindex="3" type="submit" id="add" class="btn btn-primary m-b-0">Submit</button>
	      </div>
	    </div>
	  </form>


        </div>
    </div>
</div>
</div>
</div>
</div>

<script>

function call_help()
{
  $('#help-Modal').modal('show');
}

function check_dept_exist()
{
	var dname= $('#dept_name').val();
	$.ajax
	({
		url: "<?php echo site_url('Ajax/check_duplicate_exist'); ?>",
		type: 'POST',
		data: {table_name:'department_master', column_name:'dept_name', post_id: dname },
		success: function(msg) {
			if(msg!=0)
			{
				$('#dept_exits').html("Department name already exits");
				$('#dept_name').val('');
			}
			else
			{
				$('#dept_exits').html("");
			}
		}
	});
}
</script>
