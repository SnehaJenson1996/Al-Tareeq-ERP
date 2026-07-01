<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Agent List</h2>


                <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_agent') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Agent
                    </a>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Agent Code</th>
                            <th>Agent Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <!-- <th>Status</th> -->
                            <th width="15%">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $i = 1;
                    foreach($agents as $agent){
                    ?>

                        <tr>
                            <td><?= $i++; ?></td>

                            <td><?= $agent->agent_code; ?></td>

                            <td><?= htmlspecialchars($agent->agent_name); ?></td>

                            <td><?= htmlspecialchars($agent->phone); ?></td>

                            <td><?= htmlspecialchars($agent->email); ?></td>

                            <td><?= htmlspecialchars($agent->address); ?></td>

                            <!-- <td>
                                <?php if($agent->is_active){ ?>
                                    <span class="badge badge-success">Active</span>
                                <?php } else { ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php } ?>
                            </td> -->

                            <td>

                                <a href="<?= base_url('index.php/Setup/edit_agent/'.$agent->agent_id); ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="<?= base_url('index.php/Setup/delete_agent/'.$agent->agent_id); ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this agent?');">
                                    Delete
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