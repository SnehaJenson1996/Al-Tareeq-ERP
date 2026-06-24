<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
<table id="brandTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Brand Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1;foreach($all_brands as $brand){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $brand->brand_name; ?></td>
                    <td>
                      <?php if(has_access($user,$page_name,'E')){ ?>
                        <a href='<?php echo base_url().'index.php/Item/edit_brand/'.$brand->brand_id; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                      <?php } ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <?php if(has_access($user,$page_name,'D')){ ?>
<a href='<?php echo base_url().'index.php/Item/delete_brand/'.$brand->brand_id; ?>' 
   title='Delete' 
   onclick="return confirm('Are you sure you want to delete this brand?');">
   <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
</a>                      <?php } ?>
                    </td>
                </tr>
              <?php $i++;} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#brandTable').DataTable({
        "pageLength": 10
    });
});
</script>