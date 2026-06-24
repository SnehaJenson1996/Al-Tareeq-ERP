<div class="container-fluid">

    <div class="card">
        
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Invoice No</th>
                        <th>Customer Name</th>
                        <th>Site Location</th>
                        <th>Installation Date</th>
                        <th>Warranty Period</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1; foreach($records as $row){ ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $row->invoice_code ?></td>
                            <td><?= $row->customer_name ?></td>
                            <td><?= $row->site_location ?></td>
                            <td><?= date('d-m-Y', strtotime($row->installation_date)) ?></td>
                            <td><?= $row->warranty_period ?></td>

                            <td>
                                <a href="<?= base_url('index.php/Sales/print_warranty/'.$row->warranty_id) ?>"
                                   class="btn btn-info btn-sm">
                                    Print
                                </a>

                                <!-- <a href="<?= base_url('Warranty/edit/'.$row->warranty_id) ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a> -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
    </div>

</div>