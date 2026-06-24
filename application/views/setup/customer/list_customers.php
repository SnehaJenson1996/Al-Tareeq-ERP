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
                          <th>Customer Name</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=1;foreach($all_customers as $customer){ ?>
                        <tr>
                          <th scope="row"><?php echo $i; ?></th>
                          <td><?php echo $customer->customer_name; ?></td>
                          <td>
                            <?php if(has_access($user,$page_name,'E')){ ?>
                              <a href='<?php echo base_url().'index.php/Setup/edit_customer/'.$customer->customer_id; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            <?php } ?>
                             &nbsp;&nbsp;&nbsp;&nbsp;
                              <?php if(has_access($user,$page_name,'D')){ ?>
                                <a href='<?php echo base_url().'index.php/Setup/delete_customer/'.$customer->customer_id; ?>' title='Delete'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
