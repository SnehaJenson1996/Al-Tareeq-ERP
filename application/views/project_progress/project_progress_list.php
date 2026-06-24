<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Project</th>
    <th>Customer</th>
    <th>Progress</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($projects as $i => $p): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td><?= $p['project_name'] ?> (<?= $p['project_code'] ?>)</td>
    <td><?= $p['customer_name'] ?></td>
    <td>
        <div class="progress">
            <div class="progress-bar bg-success"
                 style="width: <?= $p['progress_percentage'] ?? 0 ?>%">
                <?= $p['progress_percentage'] ?? 0 ?>%
            </div>
        </div>
    </td>
    <td>
        <span class="badge 
            <?= ($p['current_status'] ?? '')=='Completed' ? 'bg-success' : 
               (($p['current_status'] ?? '')=='In Progress' ? 'bg-warning' : 'bg-secondary') ?>">
            <?= $p['current_status'] ?? 'Not Started' ?>
        </span>
    </td>
    <td>
        <a href="<?= base_url('index.php/Project/project_progress/'.$p['project_id']) ?>"
           class="btn btn-info btn-sm">Update</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#datatable-responsive')) {
        $('#datatable-responsive').DataTable({
            responsive: true
        });
    }
});
</script>
