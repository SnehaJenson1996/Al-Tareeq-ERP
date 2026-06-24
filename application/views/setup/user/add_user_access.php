
<div class="clearfix"></div>
<div class="x_content">
        <form action="<?php echo base_url().'index.php/'; ?>Setup/add_user_access" method="post" >
          <?php if($user_id==1){ ?>
          <div class="item form-group">
            <label class="col-form-label col-md-1 col-sm-1 label-align" >Select User <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" name="user_id" id="user_id" onchange='this.form.submit()'>
                            <option value="">Select User</option>
                            <?php foreach($users as $user) { ?>
                              <option value="<?php echo $user->user_id;?>" <?php if(isset($_POST['user_id'])&&$_POST['user_id']== $user->user_id ) echo "selected"; ?>><?php echo $user->user_login;?></option>
                            <?php } ?>
              </select>
            </div>
		      </div>
          <?php } ?>
        </form>
</div>
<div class="clearfix"></div>
<div class="x_content">
  <?php if(!empty($menu)){ ?>
    <form class="form-horizontal" method="post" action="<?php echo base_url().'index.php/'; ?>Setup/add_user_access_data" >
      <input type="hidden" name="user_id" id="user_id " value="<?php echo $user_id;?>" />
      <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>Menu Name</td>
              <td>View</td>
              <td>Add</td>
              <td>Edit</td>
              <td>Delete</td>
            </tr>
          </thead>
          <tbody>
            <?php foreach($menu as $row1):$xx=get_check_item_status_checked($user_id,$row1->menu_id);?>
            <tr>
              <td><li><span>* &nbsp&nbsp<?php echo $row1->menu_name ;?></span></td>
              <td><input title="<?php echo $row1->menu_name;?>" type="checkbox" class="case" <?php if($xx==1) echo "checked";?>  name="check[]"  value="<?php echo $row1->menu_id ;?>" onclick="select_sub_childs(<?php echo $row1->menu_id;?>);" id="parent<?php echo $row1->menu_id ;?>"  ></td>
              <td></td><td></td><td></td>
            </tr>
            <?php $submenu = accesscontrol($row1->menu_pid);
            foreach($submenu as $row2): $yy=get_check_item_status_checked($user_id,$row2->menu_id);?>
            <tr>
                <td><li style='padding-left:50px;'><span>&nbsp&nbsp<?php echo $row2->menu_name;?></span></td>
                <td><input title="<?php echo $row2->menu_name;?>" type="checkbox" <?php if($yy==1) echo "checked";?> class="case case<?php echo $row1->menu_id;?>"  name="check[]"  value="<?php echo $row2->menu_id ;?>" id="<?php echo $row2->menu_id ;?>"  ></td>
                <?php if($row2->menu_url!='blank') {?>
                <td><input title="<?php echo $row2->menu_name;?> Add" type="checkbox" name="check_add[]" value="<?php echo $row2->menu_id ;?>" class="case case<?php echo $row1->menu_id;?>" <?php if(check_pageaccess_status($user_id,$row2->menu_id,'A')==1) echo "checked";?>></td>
                <td><input title="<?php echo $row2->menu_name;?> Edit" type="checkbox" name="check_edit[]" value="<?php echo $row2->menu_id ;?>" class="case case<?php echo $row1->menu_id;?>" <?php if(check_pageaccess_status($user_id,$row2->menu_id,'E')==1) echo "checked";?>> </td>
                <td><input title="<?php echo $row2->menu_name;?> Delete" type="checkbox" name="check_delete[]" value="<?php echo $row2->menu_id ;?>" class="case case<?php echo $row1->menu_id;?>" <?php if(check_pageaccess_status($user_id,$row2->menu_id,'D')==1) echo "checked";?>></td>
                <?php } else echo "<td></td><td></td><td></td><td></td>";?></li>
            </tr>
            <?php endforeach ?>
            <?php endforeach ?>
          </tbody>
      </table>
      <div class="col-md-12">
          <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </form>
  <?php } ?>
</div>
                              
<div class="clearfix"></div>


          
             
       
       
<script>
 

  $(document).ready(function () {
    $('.select2').select2();
  });

function select_sub_childs(a)
{
	if ($("#parent"+a).is(':checked'))
	{
        $(".case" + a).prop("checked", true).change();
	}
	else
	{
		$(".case" + a).prop("checked", false).change();
	}
}
</script>
       