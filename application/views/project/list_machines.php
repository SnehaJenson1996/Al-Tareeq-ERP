<div class="x_panel">
    <div class="x_title">
        <h2>Machine List</h2>


                 <div class="pull-right">
                    <a href="<?= base_url('index.php/Project/add_machine') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Machine
                    </a>
                </div>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <!-- FLASH MESSAGE -->
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
                    <th>Machine ode</th>
                    <th>Machine</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_machines)) { 
                    $i = 1;
                    foreach ($all_machines as $row) { 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->machine_code; ?></td>
                        <td><?= $row->machine_name; ?></td>
                        <td><?= $row->description; ?></td>
                        <td><?= $row->created_at; ?></td>

                        <td>
                            <a href="<?= base_url('index.php/Project/edit_machine/'.$row->machine_id) ?>" 
                               class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= base_url('index.php/Project/delete_machine/'.$row->machine_id) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure to delete this machine?')">Delete</a>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">No Data Found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>