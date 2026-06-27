<div class="x_panel">
    <div class="x_title">
        <h2>Sales Area List</h2>


                 <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_sales_area') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Sales Area
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
                    <th>Sales Area Code</th>
                    <th>Sales Area Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($all_sales_area)) { 
                    $i = 1;
                    foreach ($all_sales_area as $row) { 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row->sales_area_code; ?></td>
                        <td><?= $row->sales_area_name; ?></td>
                        <td><?= $row->description; ?></td>
                        <td><?= $row->created_at; ?></td>

                        <td>
                            <a href="<?= base_url('index.php/Setup/edit_sales_area/'.$row->sales_area_id) ?>" 
                               class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= base_url('index.php/Setup/delete_sales_area/'.$row->sales_area_id) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure?')">Delete</a>
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