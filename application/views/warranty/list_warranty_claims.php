<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
        <table id='datatable' class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Warranty Code</th>
              <th>Warranty Date</th>
              <th></th>
              <th></th>
             
            </tr>
          </thead>
          <tbody>
            <?php $i=1;foreach($warraty_claims as $warranty){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $warranty->warranty_code; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($warranty->warranty_date)); ?></td>
                    <?php if($warranty->warranty_status == 0){ ?>
                      <td>
                        <?php if(has_access($user,$page_name,'E')){ ?>
                          <a href='<?php echo base_url().'index.php/Warranty/edit_warranty_claim/'.$warranty->warranty_id; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if(has_access($user,$page_name,'D')){ ?>
                          <a href='<?php echo base_url().'index.php/Sales/delete_enquiry/'.$warranty->warranty_id; ?>' title='Delete'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                        <?php } ?>
                      </td>
                    <?php } else { ?>
                       <td>
                        <?php if(has_view_access($user,$page_name)){ ?>
                          <a href='<?php echo base_url().'index.php/Warranty/print_warranty_claim/'.$warranty->warranty_id; ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                        <?php } ?>
                      </td>
                      <td></td>
                    <?php } ?>
                </tr>
              <?php $i++;} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>