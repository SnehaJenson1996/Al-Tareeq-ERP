<div class="x_panel">
    <!-- <div class="x_title">
        <h2>Approved Quotation List</h2>
        <div class="clearfix"></div>
    </div> -->

    <div class="x_content">

       <table class="table table-bordered table-striped" id="example">
    <thead>
        <tr>
            <th>#</th>
            <th>Quotation No</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Project</th>
            <th>Amount</th>
            <th>Client PO</th>
            <th>Status</th>
            <th>Approved On</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    <?php
    $i=1;
    foreach($records as $row){
    ?>

    <tr>

        <td><?= $i++ ?></td>

        <td><?= $row->quotation_code ?></td>

        <td><?= date('d-m-Y', strtotime($row->quotation_date)) ?></td>

        <td><?= $row->customer_name ?></td>

        <td><?= $row->project_name ?></td>

        <td class="text-right">
            <?= number_format($row->grand_total,2) ?>
        </td>
       <td>

<?php if(!empty($row->lpo_number)) { ?>

    <strong><?= $row->lpo_number ?></strong><br>

<?php } ?>

<?php if(!empty($row->po_file)) { ?>

 <a href="<?= base_url('uploads/quotation_po/'.$row->po_file) ?>" target="_blank">
    View File
</a>

<?php } else { ?>

    -

<?php } ?>

</td>

        <td>
            <?php
            if($row->aproval==1){
                echo '<span class="badge badge-success">Approved</span>';
            }else{
                echo '<span class="badge badge-danger">Rejected</span>';
            }
            ?>
        </td>

        <td>
            <?= !empty($row->approved_on) ? date('d-m-Y H:i', strtotime($row->approved_on)) : '' ?>
        </td>

        <td><?= $row->approval_remarks ?></td>

        <td>
            <a href="<?= base_url('index.php/Sales/view_quotation/'.$row->qtn_id) ?>"
               class="btn btn-info btn-sm">
                <i class="fa fa-eye"></i>
            </a>
        </td>

    </tr>

    <?php } ?>

    </tbody>

</table>

    </div>
</div>