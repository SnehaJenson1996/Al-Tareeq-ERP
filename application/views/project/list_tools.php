<div class="x_panel">
    <div class="x_title">
        <h2>Tool List</h2>


                 <div class="pull-right">
                    <a href="<?= base_url('index.php/Project/add_tool') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Tool
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
                    <th>Tool Code</th>
                    <th>Tool Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_tools)) { 
                    $i = 1;
                    foreach ($all_tools as $row) { 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->tool_code; ?></td>
                        <td><?= $row->tool_name; ?></td>
                        <td><?= $row->description; ?></td>
                        <td><?= $row->created_at; ?></td>

                        <td>
                            <a href="<?= base_url('index.php/Project/edit_tool/'.$row->tool_id) ?>" 
                               class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= base_url('index.php/Project/delete_tool/'.$row->tool_id) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure to delete this tool?')">Delete</a>
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