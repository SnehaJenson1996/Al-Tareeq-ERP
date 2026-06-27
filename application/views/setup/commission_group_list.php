<div class="x_panel">
    <div class="x_title">
        <h2>Commission Group List</h2>
        

                 <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_commission_group') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Add Commission Group
                    </a>
                </div>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>

        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Group Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_commission_group)) {
                    $i = 1;
                    foreach ($all_commission_group as $row) {
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->commission_group_code ?></td>
                        <td><?= $row->commission_group_name ?></td>
                        <td><?= $row->description ?></td>

                        <td>
                            <a href="<?= base_url('index.php/Setup/edit_commission_group/'.$row->commission_group_id) ?>"
                               class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            <a href="<?= base_url('index.php/Setup/delete_commission_group/'.$row->commission_group_id) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">No Data Found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>