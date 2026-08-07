<div class="container">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <table id="datatable-responsive"
        class="table table-striped table-bordered dt-responsive nowrap"
        cellspacing="0"
        width="100%">

        <thead>
            <tr>

                <th>Sl. No</th>
                <th>Date</th>
                <th>Product Code</th>
                <th>Product</th>
                <th>Warehouse</th>
                <th>Store</th>
                <th>Project</th>
                <th>Transaction</th>
                <th>Qty</th>
                <th>Balance Qty</th>
                <th>Unit Price</th>
                <th>Stock Value</th>
                <th>Reference</th>
                <th>Created By</th>

            </tr>
        </thead>

        <tbody>

            <?php if (!empty($records)): ?>

                <?php $i = 1; ?>

                <?php foreach ($records as $row): ?>

                    <tr>

                        <td><?= $i++; ?></td>

                        <td><?= date('d-m-Y', strtotime($row->stock_date)); ?></td>

                        <td><?= $row->product_code; ?></td>

                        <td><?= $row->product_name; ?></td>

                        <td><?= $row->warehouse_name; ?></td>

                        <td><?= $row->store_name; ?></td>

                        <td><?= $row->project ?: '-'; ?></td>

                        <?php
                            switch ($row->stock_type) {

                                case 'IN':
                                    $badge = 'success';
                                    break;

                                case 'OUT':
                                    $badge = 'danger';
                                    break;

                                case 'RESERVE':
                                    $badge = 'warning';
                                    break;

                                case 'ADJUSTMENT':
                                    $badge = 'primary';
                                    break;

                                default:
                                    $badge = 'secondary';
                            }
                        ?>
                        <td>
                            <span class="badge badge-<?= $badge ?>">
                                <?= $row->stock_type ?>
                            </span>
                        </td>


                        <td><?= number_format($row->quantity, 2); ?></td>

                        <td><?= number_format($row->balance_qty, 2); ?></td>

                        <td><?= number_format($row->price, 2); ?></td>

                        <td><?= number_format($row->stock_value, 2); ?></td>

                        <td><?= $row->remark; ?></td>

                        <td><?= $row->user_name; ?></td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="11" class="text-center">
                        No Stock Transactions Found
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    $(document).ready(function() {

        if (!$.fn.DataTable.isDataTable('#datatable-responsive')) {

            $('#datatable-responsive').DataTable({

                responsive: true,

                pageLength: 25,

                order: [
                    [1, 'desc']
                ]

            });

        }

    });
</script>