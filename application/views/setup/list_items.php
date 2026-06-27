<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_item') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Item
                    </a>
                </div>
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
                <td><?php echo $item->product_code; ?></td>
                <td><?php echo $item->product_name; ?></td>
                <td><?php echo $item->unit_name; ?></td>
                <td><?php echo $item->retail_price; ?></td>
                <td><?php echo $item->description; ?></td>

                <td>
                  <?php if (!empty($item->product_image) && file_exists('./public/items/' . $item->product_image)) { ?>
                    <img src="<?php echo base_url('public/items/' . $item->product_image); ?>" alt="Item Image" style="width: 60px; height: auto;"/>
                  <?php } else { ?>
                    <span>No Image</span>
                  <?php } ?>
                </td>
                <td>
                    <a href='<?php echo base_url().'index.php/Setup/edit_item/'.$item->product_id; ?>' title='Edit' class="btn btn-primary btn-sm">Edit</a>
                    
                    
                  &nbsp;&nbsp;&nbsp;&nbsp;
                   

                    <a href='<?php echo base_url().'index.php/Setup/delete_item/'.$item->product_id; ?>' 
   title='Delete' 
   onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm">
   Delete
</a>
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