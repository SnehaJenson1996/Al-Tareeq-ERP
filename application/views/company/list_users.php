<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$logged_user = $this->session->userdata('user_id');
?>

<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">

    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

      
    <div class="x_content">
<table id="userTable" class="table table-hover">
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
                        <a href='<?php echo base_url().'index.php/Company/edit_user/'.$user->user_id; ?>' title='Edit' class="btn btn-primary btn-sm">Edit</a>
                      <?php } ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php if(has_access($logged_user,$page_name,'D')){ ?>
                        <a href="javascript:void(0)"
   onclick="confirmDeleteUser(<?= $user->user_id ?>)"
   title="Delete"  class="btn btn-danger btn-sm">Delete
   
</a>

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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
function confirmDeleteUser(tid) {
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            url: "<?= base_url('index.php/Company/delete_user') ?>",
            type: "POST",
            data: { id: tid },
            dataType: "json",
            success: function(response) {
                if (response.status == 1) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("An unexpected error occurred.");
            }
        });
    }
}
$(document).ready(function() {
    $('#userTable').DataTable({
        "pageLength": 10
    });
});
</script>
