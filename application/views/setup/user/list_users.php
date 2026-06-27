<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$logged_user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
    <div class="x_content">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>User Name</th>
                          <th>Login ID</th>
                          <th>DOB</th>
                          <th>Action</th>
                          
                        </tr>
                        </tr>

                      </thead>
                      <tbody>
                        <?php $i=1;foreach($all_users as $user){ ?>
                        <tr>
                          <th scope="row"><?php echo $i; ?></th>
                          <td><?php echo $user->user_name; ?></td>
                          <td><?php echo $user->user_login; ?></td>
                          <td><?php echo $user->dob;  ?></td>
                          <td>
                            <?php if(has_access($logged_user,$page_name,'E')){ ?>
                              <a href='<?php echo base_url().'index.php/Setup/edit_user/'.$user->user_id; ?>' title='Edit' class="btn btn-primary btn-sm"></a>
                            <?php } ?>
                             &nbsp;&nbsp;&nbsp;&nbsp;
                             <?php if(has_access($logged_user,$page_name,'D')){ ?>
                              <a href='<?php echo base_url().'index.php/Setup/delete_user/'.$user->user_id; ?>' title='Edit'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
