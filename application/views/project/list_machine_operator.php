<div class="x_panel">
    <div class="x_title">
        <h2>Machine Operator Mapping</h2>
                <div class="pull-right">
                    <a href="<?= base_url('index.php/Project/add_machineop_map') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Mapping
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
                    <th>Map Code</th>
                    <th>Machine</th>
                    <th>Employee</th>
                    <th>Skill Level</th>
                    <th width="25%">Remarks</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_map)) { 
                    $i = 1;
                    foreach ($all_map as $row) { 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->map_code; ?></td>
                        <td><?= $row->machine_name; ?></td>
                        <td><?= $row->employee_name; ?></td>
                        <td><?= $row->skill_level; ?></td>
                        <td><?= $row->remarks; ?></td>
                        <td><?php if($row->bit_active==1) echo "Active"; else echo "In active"; ?></td>

                        <td>
                            <a href="<?= base_url('index.php/Project/edit_machineop_map/'.$row->mapping_id) ?>" 
                               class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= base_url('index.php/Project/delete_machineop_map/'.$row->mapping_id) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure to delete this mapping?')">Delete</a>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr>
                        <td colspan="8" class="text-center">No Data Found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>