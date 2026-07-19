<style>
    th {
        background: #f5f5f5;
        font-weight: bold;
    }
    td, th {
        vertical-align: middle !important;
    }
    .title_right .btn-group{display:none;}
</style>

<div class="row">
<div class="col-md-12">
    <div class="text-right">
    <div class="dropdown">
        <button class="btn btn-secondary btn-sm border"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
           Action
        </button>

        <div class="dropdown-menu dropdown-menu-right">
           <a class="dropdown-item" href="<?= base_url('index.php/Project/assign_task/'.$project_id) ?>"><i class="fa fa-plus text-success"></i> Assign/Add Task</a>
           <a class="dropdown-item" href="<?= base_url('index.php/Project/update_projecttask/'.$project_id) ?>"><i class="fa fa-edit text-warning"></i> Edit Task</a>
           <a class="dropdown-item view-team" href="<?= base_url('index.php/Project/get_project_list') ?>">
                <i class="fa fa-users text-primary"></i>Back to Projects
            </a>
           
            <div class="dropdown-divider"></div>
       </div>
    </div>
</div>
<div class="x_panel">
<!-- <div class="x_title">
    <div class="text-end">
        <a href="<?= base_url('index.php/Project/create_material_request') ?>" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Create MR
        </a>
    </div>
    <div class="clearfix"></div>
</div> -->

<div class="x_content">

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>
<!--
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    
    <th>Description</th>
    <th width="12%">Approved By</th>
</tr>
</thead>
<tbody>
<td><?php echo $project_task['task_description'];?></td>
<td><?php echo $project_task['Approved_by'];?></td>
</tbody>
</table>-->
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Task Name</th>
    <th>Employee</th>
    <th>Designation</th>
    <th>Start date</th>
    <th>End date</th>
    <th>status</th>
    <th>Priority</th>
    <th>Mile stone</th>
    
    
    <th>Description</th>
    <!-- <th>Status</th> -->
    <th width="12%">Action</th>
</tr>
</thead>

<tbody>
<?php if (!empty($project_task)): ?>
<?php 

    foreach($project_task['tasks'] as $i=> $pt):
    ?>
<tr>
    <td><?= $i+1 ?></td>
    <td><?= $pt['task_name'] ?></td>
    <td><?= $pt['employee_name'] ?></td>
    <td><?= $pt['designation_name'] ?></td>
    <td><?= date('d-m-Y', strtotime($pt['start_date'])) ?></td>
    <td><?= date('d-m-Y', strtotime($pt['end_date'])) ?></td>
    <td><?= $pt['status'] ?></td>
    <td><?= $pt['priority'] ?></td>
    <td><?= $pt['milestone_name'] ?></td>
    <td><?= $pt['task_description'] ?></td>
    <!-- <td>
        <?php
            $status = $mr['status'] ?? 'Pending';
            $badgeClass = 'bg-secondary';

            switch(strtolower($status)){
                case 'pending':
                    $badgeClass = 'bg-warning';
                    break;
                case 'approved':
                    $badgeClass = 'bg-success';
                    break;
                case 'rejected':
                    $badgeClass = 'bg-danger';
                    break;
            }
        ?>
        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
    </td> -->
    <td class="text-center">

    <div class="dropdown">
        <button class="btn btn-light btn-sm border"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-right">

            <!--<a class="dropdown-item view-item"
            href="#" data-id="<?= $pt['id'] ?>">
                <i class="fa fa-eye text-info"></i> View Materials
            </a>-->

            <a class="dropdown-item text-danger"
            href="<?= base_url('index.php/Project/delete_task_item/'.$project_id."/".$pt['id']) ?>"
            onclick="return confirm('Delete this task item?')">
                <i class="fa fa-trash"></i> Delete
            </a>

        </div>
    </div>
</td>

</tr>
<?php endforeach; ?>

<?php else: ?>
<tr>
    <td colspan="10" class="text-center">No material requests found</td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>
</div>
</div>
<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#datatable-responsive')) {
        $('#datatable-responsive').DataTable({
            responsive: true
        });
    }
});
</script>
