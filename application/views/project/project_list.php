<style>
    th {
        background: #f5f5f5;
        font-weight: bold;
    }
    td, th {
        vertical-align: middle !important;
    }
</style>

<div class="row">
<div class="col-md-12">
<div class="x_panel">
<!-- <div class="x_title">
    <h4>Project List</h4>
    <div class="text-end">
        <a href="<?= base_url('index.php/Project/add_project') ?>" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Add Project
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

<!-- <table class="table table-bordered table-striped" id="projectTable"> -->
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Project Code</th>
    <th>Project Name</th>
    <th>Customer</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Duration</th>
    <th>Grand Total</th>
    <th>Status</th>
    <th width="15%">Action</th>
</tr>
</thead>

<tbody>
<?php if (!empty($projects)): ?>
<?php foreach ($projects as $i => $row): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><?= $row['project_code'] ?></td>
    <td><?= $row['project_name'] ?></td>
    <td><?= $row['customer_name'] ?></td>
    <td><?= date('d-m-Y', strtotime($row['start_date'])) ?></td>
    <td><?= date('d-m-Y', strtotime($row['end_date'])) ?></td>
    <td><?= $row['duration'] ?> Days</td>
    <td class="text-end"><?= number_format($row['grand_total'], 2) ?></td>
    <td>
    <?php
        $status = $row['status'] ?? 'Open';
        $badgeClass = 'bg-secondary'; // Default

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
            case 'open':
                $badgeClass = 'bg-info';
                break;
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
</td>

    <td>
        <a href="<?= base_url('index.php/Project/edit_project/'.$row['project_id']) ?>"
           class="btn btn-warning btn-sm">
            Edit
        </a>
        <!-- <a href="<?= base_url('index.php/Project/view/'.$row['project_id']) ?>"
           class="btn btn-info btn-sm">
            View
        </a> -->
        <a href="<?= base_url('index.php/Project/delete/'.$row['project_id']) ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure you want to delete this project?')">
            Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="10" class="text-center">No projects found</td>
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
