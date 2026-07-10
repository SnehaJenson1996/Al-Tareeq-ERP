<style>
    .action-icons i {
        font-size: 18px;
        margin: 0 5px;
        vertical-align: middle;
    }
</style>

<div class="card-body">
    <div class="dt-responsive table-responsive">

        <!-- FLASH MESSAGE -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>

        <?php if($this->session->flashdata('warning')){ ?>
            <div class="alert alert-warning">
                <?= $this->session->flashdata('warning'); ?>
            </div>
        <?php } ?>

        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>

        <table id="datatable" class="table table-striped" data-toggle="data-table">
            <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Customer</th>
                    <th>Sales Rep</th>
                    <th>Invoice Amount</th>
                    <th>Commission %</th>
                    <th>Commission Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($records as $row) {
                ?>

                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row->invoice_code; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->invoice_date)); ?></td>
                        <td><?php echo $row->customer_name; ?></td>
                        <td><?php echo $row->sales_rep_name; ?></td>
                        <td><?php echo number_format($row->invoice_amount, 2); ?></td>
                        <td><?php echo $row->commission_percent; ?> %</td>
                        <td><?php echo number_format($row->commission_amount, 2); ?></td>
                        <td>
                            <?php
                            $status = $row->status;
                            if ($status == "Pending") {
                                echo '<span class="badge badge-warning">Pending</span>';
                            } elseif ($status == "Approved") {
                                echo '<span class="badge badge-success">Approved</span>';
                            } elseif ($status == "Paid") {
                                echo '<span class="badge badge-primary">Paid</span>';
                            } elseif ($status == "Rejected") {
                                echo '<span class="badge badge-danger">Rejected</span>';
                            }elseif($status=="Eligible") {
                                echo '<span class="badge badge-info">Eligible</span>';
                            } else {
                                echo $status;
                            }
                            ?>
                        </td>

                        <td class="action-icons">
                            <?php if($row->status=="Pending" || $row->status=="Eligible"){ ?>

                            <a href="<?php echo base_url(); ?>index.php/Hr/edit_commission_transaction/<?php echo $row->transaction_id; ?>">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="<?php echo base_url(); ?>index.php/Hr/delete_commission_transaction/<?php echo $row->transaction_id; ?>"
                                onclick="return confirmcancel(<?php echo $row->transaction_id; ?>);">
                                <i class="fa fa-trash"></i>
                            </a>
                            <?php }else{ ?>

                                <span class="badge badge-success">
                                    Locked
                                </span>

                            <?php } ?>
                        </td>

                    </tr>

                <?php

                }

                ?>

            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmcancel(id) {

        return confirm("Delete this Commission Transaction?");

    }
</script>