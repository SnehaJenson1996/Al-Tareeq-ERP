<div class="form-group" role="main">

    <div class="page-title">
        <div class="clearfix"></div>

        <div class="x_content">

            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade in">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade in">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="well">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Transfer Code</th>
                                <th>Date</th>
                                <th>From Warehouse</th>
                                <th>From Store</th>
                                <th>To Warehouse</th>
                                <th>To Store</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->transfer_code; ?></td>
                                    <td><?= date('d-M-Y', strtotime($row->transfer_date)); ?></td>
                                    <td><?= $row->from_warehouse; ?></td>
                                    <td><?= $row->from_store; ?></td>
                                    <td><?= $row->to_warehouse; ?></td>
                                    <td><?= $row->to_store; ?></td>
                                    <td>
                                        <?php
                                        if ($row->status == 'Completed') {
                                            echo '<span class="label label-success">Completed</span>';
                                        } elseif ($row->status == 'Draft') {
                                            echo '<span class="label label-warning">Draft</span>';
                                        } else {
                                            echo '<span class="label label-danger">' . $row->status . '</span>';
                                        }
                                        ?>

                                    </td>

                                    <td>
                                        <a href="<?= base_url('index.php/Inventory/view_stock_transfer/' . $row->transfer_id); ?>"
                                            title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        &nbsp;

                                        <a href="<?= base_url('index.php/Inventory/edit_stock_transfer/' . $row->transfer_id); ?>"
                                            title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        &nbsp;

                                        <a href="#"
                                            class="delete-transfer"
                                            data-id="<?= $row->transfer_id; ?>"
                                            title="Delete">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.delete-transfer').click(function(e) {
            e.preventDefault();
            var transfer_id = $(this).data('id');
            if (confirm('Delete this Stock Transfer ?')) {
                $.ajax({
                    url: "<?= base_url('index.php/Inventory/delete_stock_transfer'); ?>",
                    type: "POST",
                    data: {
                        transfer_id: transfer_id
                    },
                    success: function(res) {
                        var result = JSON.parse(res);
                        if (result.success) {
                            location.reload();
                        } else {
                            alert(result.message);
                        }
                    }
                });
            }
        });
    });
</script>