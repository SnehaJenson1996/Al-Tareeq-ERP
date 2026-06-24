<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
<table id="itemTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Item Code</th>
              <th>Item name</th>
               <th>Item Unit</th>
              <th>Unit Price</th>
              <th>Item Description</th>
              <th>Image</th>  <!-- New column -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; foreach($all_items as $item){ ?>
              <tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><?php echo $item->item_code; ?></td>
                <td><?php echo $item->item_name; ?></td>
                <td><?php echo $item->unit_name; ?></td>
                <td><?php echo $item->unit_price; ?></td>
                <td><?php echo $item->item_description; ?></td>

                <td>
                  <?php if (!empty($item->item_image) && file_exists('./public/items/' . $item->item_image)) { ?>
                    <img src="<?php echo base_url('public/items/' . $item->item_image); ?>" alt="Item Image" style="width: 60px; height: auto;"/>
                  <?php } else { ?>
                    <span>No Image</span>
                  <?php } ?>
                </td>
                <td>
                  <?php if(has_access($user, $page_name, 'E')){ ?>
                    <a href='<?php echo base_url().'index.php/Item/edit_item/'.$item->item_id; ?>' title='Edit'>
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>
                  <?php } ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if(has_access($user,$page_name,'D')){ ?>
                   

                    <a href='<?php echo base_url().'index.php/Item/delete_item/'.$item->item_id; ?>' 
   title='Delete' 
   onclick="return confirm('Are you sure you want to delete this item?');">
   <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
</a>
                  <?php } ?>
                </td>
              </tr>
            <?php $i++; } ?>
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
    $('#itemTable').DataTable({
        "pageLength": 10
    });
});
</script>