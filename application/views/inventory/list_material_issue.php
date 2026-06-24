<div class="container">
    <!-- <h3>Material Issues</h3> -->

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>MI Code</th>
                <th>Project</th>
                <th>Customer</th>
                <th>Branch</th>
                <th>Issue Date</th>
                <!-- <th>Issued By</th> -->
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php if (!empty($material_issues)): ?>
                <?php foreach($material_issues as $i => $mi): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= $mi['mi_code']; ?></td>
                        <td><?= $mi['project_name']; ?></td>
                        <td><?= $mi['customer_name']; ?></td>
                        <td><?= $mi['branch_name']; ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($mi['issue_date'])); ?></td>
                        <!-- <td>
                            <?php
                                // If you have issued_by_name from users table
                                echo isset($mi['issued_by_name']) ? $mi['issued_by_name'] : '-';
                            ?>
                        </td> -->
                        <td><?= $mi['status']; ?></td>
                        <td>
        <a href="<?= base_url('index.php/Inventory/view_material_issue/'.$mi['mi_id']); ?>"
           class="btn btn-sm btn-primary">
            View
        </a>
         <!-- Delete Button -->
    <a href="<?= base_url('index.php/Inventory/delete_material_issue/'.$mi['mi_id']); ?>"
       class="btn btn-sm btn-danger"
       onclick="return confirm('Are you sure you want to delete this Material Issue?');">
        Delete
    </a>
    </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No Material Issues found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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
