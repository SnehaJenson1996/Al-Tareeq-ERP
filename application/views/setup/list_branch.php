<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="x_panel">
      <div class="x_content">

       <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_branch') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Branch
                    </a>
                </div>
       
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

<table id="branchTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Branch Name</th>
              <th>Branch Code</th>
              <th>Branch Manager</th>
              <th>Branch Contact Number</th>
              <th>TRN NO</th>
              <th>Address</th>
              <th>Branch Logo</th>
              <th>Action</th>
            </tr>
          </thead>
         <tbody>
  <?php if (!empty($AllBranches)) { 
      $i = 1;
      foreach($AllBranches as $branch_data){ ?>
          <tr>
              <th scope="row"><?= $i; ?></th>
              <td><?= $branch_data->branch_name; ?></td>
              <td><?= $branch_data->branch_code; ?></td>
              <td><?= $branch_data->branch_manager; ?></td>
              <td><?= $branch_data->branch_contact; ?></td>
              <td><?= $branch_data->branch_trn; ?></td>
              <td><?= $branch_data->branch_address; ?></td>
              <td><img src="<?= base_url($branch_data->branch_logo); ?>" alt="Branch Logo" width="60" height="60"></td>
              <td>
                
                  <a href="<?= base_url('index.php/Setup/edit_branch/' . $branch_data->branch_id ); ?>" title="Edit"  class="btn btn-primary btn-sm">
                    Edit  
                  </a>
               

                  <a href="javascript:void(0);" onclick="confirmcancel(<?= $branch_data->branch_id ?>)" title="Delete" class="btn btn-danger btn-sm">
                       Delete
                  </a>
              </td>
          </tr>
      <?php $i++; }
  } else { ?>
      <tr>
          <td colspan="9" class="text-center">
              <strong>No data found</strong>
          </td>
      </tr>
  <?php } ?>
</tbody>

        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>

function confirmcancel(tid) {
     if (event) event.preventDefault();
    if (confirm("Are you sure you want to delete this branch?")) {
        $.ajax({
            url: "<?= base_url('index.php/Setup/delete_branch_record') ?>",
            type: "POST",
            data: { id: tid },
            dataType: "json",
            success: function(response) {
             console.log(response); 
                if (response.status == 1) {
                    alert(response.message);
                    location.reload(); // Reload only on successful deletion
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
                alert("An unexpected error occurred.");
            }
        });
    }
}


$(document).ready(function() {
    $('#branchTable').DataTable({
        "pageLength": 10
    });
});
</script>