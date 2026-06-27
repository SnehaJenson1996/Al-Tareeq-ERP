<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">

            <div class="x_title">
                <h2>Item Category List</h2>

                <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_item_category') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Add Category
                    </a>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Category Code</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th width="10%">Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php $i = 1; foreach($categories as $row){ ?>

                        <tr>

                            <td><?= $i++; ?></td>

                            <td><?= $row->category_code; ?></td>

                            <td><?= $row->category_name; ?></td>

                            <td><?= $row->description; ?></td>

                            <td>
                                <?php if($row->is_active){ ?>
                                    <span class="label label-success">Active</span>
                                <?php } else { ?>
                                    <span class="label label-danger">Inactive</span>
                                <?php } ?>
                            </td>

                            <td>

                                <a href="<?= base_url('index.php/Setup/add_item_category/'.$row->category_id) ?>"
                                   class="btn btn-primary btn-sm"> Edit

                                </a>
                  &nbsp;&nbsp;&nbsp;

                                 <a href="<?= base_url('index.php/Setup/delete_category/'.$row->category_id) ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Are you sure you want to delete this category?');">Delete
    </a>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>