<div class="x_panel">
  <div class="x_title">
    <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
          <i class="fa fa-wrench"></i>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Settings</a></li>
        </ul>
      </li>
      <li><a class="close-link"><i class="fa fa-close"></i></a></li>
    </ul>
    <div class="clearfix"></div>
  </div>

  <div class="dt-responsive table-responsive">
		<table id="datatable" class="table table-striped" data-toggle="data-table">
      <thead>
        <tr>
					<th>Sr.no</th>
          <th>Account Code</th>
          <th>Group Name</th>
          <th>Account Type</th>
          <th>Parent Group</th>
          <!-- <th>Action</th> -->
        </tr>
      </thead>

      <tbody>
        <?php $i=1; foreach ($account_records as $row): ?>
          <tr>
						<td><?php echo $i;$i++;?></td>
            <td><?php echo $row->group_code; ?></td>
            <td><?php echo $row->group_name; ?></td>
            <td><?php echo ($row->pandl == 0) ? 'Balance Sheet' : 'Profit and Loss'; ?></td>
            <td><?php echo $row->parent; ?></td>
           	<!-- <td>
		                 	<a href="<?php echo base_url() . 'index.php/Accounts/edit_account_group_form/'.$row->group_no;?>" title="Edit" class="btn btn-sm bg-red"><span class="fa fa-edit"></span></a>
		                  	<a title="Delete" class="btn btn-sm bg-red" href="javascript:delete_area(<?php echo $row->group_no;?>)"><span class="fa fa-trash"></span></a> 
						</td> -->
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
        <script>
function delete_area(id) {

    if (confirm("Are you sure you want to delete this record?")) {

        window.location.href = "<?php echo base_url(); ?>index.php/Accounts/delete_account_group/" + id;

    }

}
</script>