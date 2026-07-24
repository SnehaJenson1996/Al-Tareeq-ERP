<div class="x_panel">
    <div class="x_content">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap">
            <thead>
                <tr>
                    <th>Sl. No</th>
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
                        <td class="action-icons">
                            <!-- View Button -->
                            <!-- <a href="<?= base_url('index.php/Purchase/view_pr/'.$pr->pr_id) ?>"
                               class="btn btn-info btn-xs" title="View">
                                <i class="fa fa-eye"></i>
                            </a> -->

                            <!-- Edit Button -->
                            <a href="<?= base_url('index.php/Purchase/edit_pr_from_mi/'.$pr->pr_id) ?>" title="Edit" style="margin-right:10px;">
                             <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>

                            <!-- Delete Button -->
                            <a href="<?= base_url('index.php/Purchase/delete_pr/'.$pr->pr_id) ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this PR?');">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
