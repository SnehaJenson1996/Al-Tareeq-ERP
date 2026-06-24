<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="x_panel">
      <div class="x_content">
        <!-- <form action="<?= base_url()?>index.php/Company/list_branch" method="post">
            <div class="col-md-4"  >
                <input type="text" id="smart_filter" name="filter" class="form-control" placeholder="Type filter..." style="width: 400px;">
                <ul id="filter_list" style="border: 1px solid #ccc; list-style: none; margin: 0; padding: 5px; position: absolute; display: none; background: #fff; width: 400px; z-index: 10;">
                        <li data-key="branch_name">Branch Name</li>
                        <li data-key="branch_code">Branch Code</li>
                        <li data-key="branch_manager">Branch Manager</li>
                        <li data-key="branch_trn">TRN</li>
                        <li data-key="branch_contact">Contact Number</li>
                </ul>
            </div>
            <div class="col-md-3" style="float: right;" >
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </form> -->
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
                <?php if (has_access($user, $page_name, 'E')): ?>
                  <a href="<?= base_url('index.php/Company/edit_branch/' . $branch_data->branch_id ); ?>" title="Edit">
                      <span class="glyphicon glyphicon-pencil"></span>
                  </a>
                <?php endif; ?>

                <?php if (has_access($user, $page_name, 'D')): ?>
                  <a href="javascript:void(0);" onclick="confirmcancel(<?= $branch_data->branch_id ?>)" title="Delete">
                      <span class="glyphicon glyphicon-trash"></span>
                  </a>
                <?php endif; ?>
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
            url: "<?= base_url('index.php/Company/delete_branch_record') ?>",
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


// const input = document.getElementById('smart_filter');
// const list = document.getElementById('filter_list');

// input.addEventListener('focus', () => {
//   list.style.display = 'block';
// });

// document.addEventListener('click', function(e) {
//   if (!input.contains(e.target) && !list.contains(e.target)) {
//     list.style.display = 'none';
//   }
// });

// Array.from(list.children).forEach(item => {
//   item.addEventListener('click', () => {
//     input.value = item.innerText + ': ';
//     input.focus();
//     list.style.display = 'none';
//   });
// });

$(document).ready(function() {
    $('#branchTable').DataTable({
        "pageLength": 10
    });
});
</script>