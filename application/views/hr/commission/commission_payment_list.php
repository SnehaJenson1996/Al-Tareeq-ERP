<style>
    .action-icons i {
        font-size:18px;
        margin:0 5px;
        vertical-align:middle;
    }
</style>

<div class="card-body">
    <!-- FLASH MESSAGE -->
    <?php if($this->session->flashdata('success')){ ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>

    <?php if($this->session->flashdata('warning')){ ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?= $this->session->flashdata('warning'); ?>
        </div>
    <?php } ?>

    <?php if($this->session->flashdata('error')){ ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php } ?>
    
    <div class="dt-responsive table-responsive">
        <table id="datatable"
               class="table table-striped"
               data-toggle="data-table">
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
                $i=1;
                foreach($records as $row)
                {
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row->invoice_code; ?></td>
                    <td><?= date('d-M-Y',strtotime($row->invoice_date)); ?></td>
                    <td><?= $row->customer_name; ?></td>
                    <td><?= $row->sales_rep_name; ?></td>
                    <td><?= number_format($row->invoice_amount,2); ?></td>
                    <td><?= $row->commission_percent; ?> %</td>
                    <td><?= number_format($row->commission_amount,2); ?></td>
                    <td>
                        <span class="badge badge-success">
                            Approved
                        </span>
                    </td>
                    <td>
                        <a href="<?=base_url()?>index.php/Hr/commission_payment/<?= $row->transaction_id;?>"
                           class="btn btn-primary btn-sm">
                            Pay
                        </a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>