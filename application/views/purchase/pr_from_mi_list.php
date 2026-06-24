<div class="x_panel">
    <div class="x_content">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PR Code</th>
                    <th>PR Date</th>
                    <th>Material Issue</th>
                    <th>Branch</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($pr_list)) { 
                    $i = 1;
                    foreach ($pr_list as $pr) { ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $pr->pr_code ?></td>
                        <td><?= date('d-m-Y', strtotime($pr->pr_date)) ?></td>
                        <td><?= $pr->mi_code ?></td>
                        <td><?= $pr->branch_name ?></td>
                        <td><?= $pr->supplier_name ?></td>
                        <td>
                            <!-- View Button -->
                            <!-- <a href="<?= base_url('index.php/Purchase/view_pr/'.$pr->pr_id) ?>"
                               class="btn btn-info btn-xs" title="View">
                                <i class="fa fa-eye"></i>
                            </a> -->

                            <!-- Edit Button -->
                            <a href="<?= base_url('index.php/Purchase/edit_pr_from_mi/'.$pr->pr_id) ?>"
                               class="btn btn-warning btn-xs" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            <!-- Delete Button -->
                            <a href="<?= base_url('index.php/Purchase/delete_pr/'.$pr->pr_id) ?>"
                               class="btn btn-danger btn-xs"
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this PR?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
