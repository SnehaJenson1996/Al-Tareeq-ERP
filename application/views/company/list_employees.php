<?php 
    $page_name = 'Company/add_employee';
	$user = $this->session->userdata('user_id');
?>
<style>
#filter_list li {
  padding: 5px;
  cursor: pointer;
}
#filter_list li:hover {
  background: #f0f0f0;
}
</style>
<div class="x_panel">
    <div class="x_title">

        <?php if (has_access($user, $page_name, 'A')) { ?>
            <a href="<?= base_url('index.php/Company/add_employee') ?>"
            class="btn btn-primary pull-right">
                <span class="glyphicon glyphicon-plus"></span> Add New
            </a>
        <?php } ?>
        
        <div class="clearfix">
        <form action="<?= base_url()?>index.php/Company/list_employee" method="post">
            <div class="col-md-4"  >
                <input type="text" id="smart_filter" name="filter" class="form-control" placeholder="Type filter..." style="width: 400px;">
                <ul id="filter_list" style="border: 1px solid #ccc; list-style: none; margin: 0; padding: 5px; position: absolute; display: none; background: #fff; width: 400px; z-index: 10;">
                        <li data-key="employee_name">Employee Name</li>
                        <li data-key="employee_code">Employee Code</li>
                        <li data-key="designation">Designation</li>
                </ul>
            </div>
            <div class="col-md-3" style="float: right;" >
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </form>
        </div>
    </div>
    <div class="x_content">
        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php endif; ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <!-- <th>Designation</th> -->
                    <th>Mobile</th>
                    <th>Joining Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($employee_list as $emp): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if ($emp->employee_photo): ?>
                                <img src="<?= base_url('public/employee/' . $emp->employee_photo) ?>" width="50" height="50" style="object-fit: cover;">
                            <?php else: ?>
                                <span>No Photo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $emp->employee_name ?></td>
                        <td><?= $emp->branch_name ?></td>
                        <!-- <td><?= $emp->designation_name ?></td> -->
                        <td><?= $emp->mobile ?></td>
                        <td><?= date('d-M-Y', strtotime($emp->joining_date)) ?></td>
                        <td>
                            <?php if (has_access($user, $page_name, 'E')): ?>
                                <a href="<?= base_url('index.php/Company/edit_employee/' . $emp->employee_id ); ?>" title="Edit">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </a>
                            <?php endif; ?>

                            &nbsp;&nbsp;&nbsp;

                            <?php if (has_access($user, $page_name, 'D')): ?>
                                <a href="javascript:void(0);" onclick="confirmDeleteemployee(<?= $emp->employee_id  ?>)" title="Delete">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>

function confirmDeleteemployee(tid) {
     if (event) event.preventDefault();
    if (confirm("Are you sure you want to delete this branch?")) {
        $.ajax({
            url: "<?= base_url('index.php/Company/delete_employee') ?>",
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


const input = document.getElementById('smart_filter');
const list = document.getElementById('filter_list');

input.addEventListener('focus', () => {
  list.style.display = 'block';
});

document.addEventListener('click', function(e) {
  if (!input.contains(e.target) && !list.contains(e.target)) {
    list.style.display = 'none';
  }
});

Array.from(list.children).forEach(item => {
  item.addEventListener('click', () => {
    input.value = item.innerText + ': ';
    input.focus();
    list.style.display = 'none';
  });
});
</script>