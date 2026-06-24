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

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>MR Code</th>
    <th>Project</th>
    <th>Customer</th>
    <th>Branch</th>
    <th>Requested Date</th>
    <th>Required Date</th>
    <th>Initiated By</th>
    <!-- <th>Status</th> -->
    <th width="12%">Action</th>
</tr>
</thead>

<tbody>
<?php if (!empty($material_requests)): ?>
<?php foreach ($material_requests as $i => $mr): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td><?= $mr['mr_code'] ?></td>
    <td><?= $mr['project_name'] ?> (<?= $mr['project_code'] ?>)</td>
    <td><?= $mr['customer_name'] ?></td>
    <td><?= $mr['branch_name'] ?></td>
    <td><?= date('d-m-Y', strtotime($mr['requested_date'])) ?></td>
    <td><?= date('d-m-Y', strtotime($mr['required_date'])) ?></td>
    <td><?= $mr['initiated_by_name'] ?></td>
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
    <td>
    <a href="<?= base_url('index.php/Project/edit_material_request/'.$mr['mr_id']) ?>" 
       class="btn btn-info btn-sm">Edit</a>
    
    <a href="<?= base_url('index.php/Project/delete_material_request/'.$mr['mr_id']) ?>" 
       class="btn btn-danger btn-sm" 
       onclick="return confirm('Are you sure you want to delete this Material Request?');">
       Delete
    </a>
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
