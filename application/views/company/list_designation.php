<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <form action="<?= base_url()?>index.php/Company/list_designation" method="post">
            <div class="col-md-4"  >
                <input type="text" id="smart_filter" name="filter" class="form-control" placeholder="Type filter..." style="width: 400px;">
                <ul id="filter_list" style="border: 1px solid #ccc; list-style: none; margin: 0; padding: 5px; position: absolute; display: none; background: #fff; width: 400px; z-index: 10;">
                        <li data-key="designation_code ">Designation Code</li>
                        <li data-key="designation_name">Designation Name</li>
                        <li data-key="department">Department</li>
                        <li data-key="reporting_to">Reporting To</li>
                        <li data-key="level">Level</li>
                        <li data-key="employment_type">Type</li>
                        <li data-key="location">Location</li>
                        <li data-key="status">Status</li>
                </ul>
            </div>
            <div class="col-md-3" style="float: right;" >
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </form>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation Code</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Reporting To</th>
                            <th>Level</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th width="130">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($designation_list)) {
                            $i = 1;
                            foreach ($designation_list as $des): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $des->designation_code ?></td>
                                    <td><?= $des->designation_name ?></td>
                                    <td><?= $des->department ?></td>
                                    <td><?= $des->reporting_to ?></td>
                                    <td><?= $des->level ?></td>
                                    <td><?= $des->employment_type ?></td>
                                    <td><?= $des->location ?></td>
                                    <td><?= $des->status ?></td>
                                    <td>
                                        <?php if (has_access($user, $page_name, 'E')): ?>
                                            <a href="<?= base_url('index.php/Company/edit_designation/' . $des->id ); ?>" title="Edit">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            </a>
                                        <?php endif; ?>

                                        &nbsp;&nbsp;&nbsp;

                                        <?php if (has_access($user, $page_name, 'D')): ?>
                                            <a href="javascript:void(0);" 
                                            title="Delete" 
                                            onclick="confirmDeleteDesignation(<?= $des->id ?>)">
                                            <i class="fa fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>                                   
                                </tr>
                        <?php endforeach; } else { ?>
                            <tr>
                                <td colspan="10" class="text-center">No records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<script>
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

function confirmDeleteDesignation(id) {
    if (!confirm("Are you sure you want to delete this record?")) return false;

    $.ajax({
        url: "<?= base_url('index.php/Company/delete_designation') ?>",
        type: "POST",
        data: { id: id },
        success: function(response) {
            if (response == '1') {
                alert("Record deleted successfully.");
                location.reload();
            } else {
                alert("Cannot delete record. It may be in use.");
            }
        },
        error: function() {
            alert("Something went wrong. Please try again.");
        }
    });
}
</script>