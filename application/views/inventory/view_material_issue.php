<!-- File: application/views/inventory/view_material_issue.php -->

<div class="container">

    <!-- Material Request Info -->
    <h4>Material Issue Details (MI #: <?= $mi['mi_id'] ?>)</h4>
    <table class="table table-bordered">
        <tr><th>Material Request</th><td><?= $mi['mr_code'] ?> (<?= $mi['project_name'] ?>)</td></tr>
        <tr><th>Project</th><td><?= $mi['project_name'] ?></td></tr>
        <tr><th>Customer</th><td><?= $mi['customer_name'] ?></td></tr>
        <tr><th>Branch</th><td><?= $mi['branch_name'] ?></td></tr>
    </table>

    <!-- Items Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Requested qty</th>
                <!-- <th>Available Stock</th> -->
                <th>Previously Issued</th>
                <th>Issued qty</th>
                <th>Pending stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $i => $item): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= $item['product_name'] ?></td>
                <td>
                    <?php
                        $unit_name = '';
                        foreach($units as $u){
                            if($u['unit_id'] == $item['unit_id']){
                                $unit_name = $u['unit_name'];
                                break;
                            }
                        }
                        echo $unit_name;
                    ?>
                </td>
                <td><?= $item['requested_qty'] ?></td>
                <!-- <td><?= $item['available_qty'] ?? 0 ?></td> -->
                <td><?= $item['previously_issued_qty'] ?? 0 ?></td>
                <td><?= $item['issued_qty'] ?></td>
                <td><?= $item['pending_qty'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= base_url('index.php/Inventory/list_material_issue') ?>" class="btn btn-primary">Back to List</a>
</div>
