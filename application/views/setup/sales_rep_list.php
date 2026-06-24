<div class="x_panel">
    <div class="x_title">
        <h2>Sales Rep List</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Employee</th>
                    <th>Group</th>
                    <th>Target</th>
                    <th>Commission %</th>
                    <th>Discount %</th>
                    <th>Blocked</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_sales_rep)) {
                    $i = 1;
                    foreach ($all_sales_rep as $row) {
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->sales_rep_code ?></td>
                        <td><?= $row->sales_rep_name ?></td>
                        <td><?= $row->employee_name ?></td>
                        <td><?= $row->commission_group_name ?></td>
                        <td><?= $row->target_amount ?></td>
                        <td><?= $row->commission_percent ?></td>
                        <td><?= $row->sales_discount_percent ?></td>
                        <td>
                            <?= ($row->is_blocked == 1) ? 'Yes' : 'No' ?>
                        </td>

                        <td>
                            <a href="<?= base_url('index.php/Setup/edit_sales_rep/'.$row->sales_rep_id) ?>"
                               class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= base_url('index.php/Setup/delete_sales_rep/'.$row->sales_rep_id) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr>
                        <td colspan="10" class="text-center">No Data Found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>