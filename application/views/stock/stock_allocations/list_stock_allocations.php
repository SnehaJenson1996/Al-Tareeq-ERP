<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Code </th>
              <th>Allocation Date </th>
              <th>Sales Order</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1;foreach($all_stock_allocations as $allocation){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $allocation->allocation_code; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($allocation->allocation_date)); ?></td>
                    <td><?php echo $allocation->pi_code; ?></td>
                    <td>
                      <?php if(has_access($user,$page_name,'E')){ ?>
                        <a href='<?php echo base_url().'index.php/Stock/edit_stock_allocation/'.$allocation->allocation_id ; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                      <?php } ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <?php if(has_access($user,$page_name,'D')){ ?>
                        <a href='<?php //echo base_url().'index.php/Item/delete_item/'.$item->item_id; ?>' title='Delete'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                      <?php } ?>
                    </td>
                </tr>
              <?php $i++;} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
