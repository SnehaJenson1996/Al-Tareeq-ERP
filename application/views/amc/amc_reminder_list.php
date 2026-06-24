
<div class="card-body">
<div class="dt-responsive table-responsive">
	<h6><u>PPM Reminders</u></h6>
			<table id="datatable" class="table table-striped table-bordered" data-toggle="data-table">
    <thead style="background:#2A3F54; color:white;">
        <tr>
            <th>Sr.no</th>
            <th>PPM Code</th>
            <th>Project</th>
            <th>PP No</th>
            <th>PPM Schedule Date</th>
            <th>Status</th>
            <th>Action</th>
			
        </tr>
    </thead>

    <tbody>
    <?php $i=1; foreach($ppmrecords as $row) :?>

       <?php
$bg = '';

$today = date('Y-m-d');

if($row->ppm_status == 'Completed' || $row->ppm_status == 'Finished'){
    $bg = 'style="background:#d4edda;"'; // green
}
elseif($row->ppm_sch_date < $today){
    $bg = 'style="background:#f8d7da;"'; // red overdue
}
else{
    $bg = 'style="background:#fff3cd;"'; // yellow upcoming
}
?>

        <tr <?= $bg; ?>>
            <td><?php echo $i; $i++; ?></td>

            <td>
                <a href="<?php echo base_url(); ?>index.php/AMC/edit_ppm/<?php echo $row->ppm_id; ?>/reminder">
    <b><?php echo $row->ppm_code; ?></b>
</a>
            </td>

            <td><?php echo $row->project_name; ?></td>

            <td>
                <span class="badge badge-info">
                    <?php echo $row->ppm_num; ?>
                </span>
            </td>

            <td>
                <?php echo date('d-M-Y',strtotime($row->ppm_sch_date));?>
            </td>

           <td>
    <?php if($row->ppm_status == 'Completed' || $row->ppm_status == 'Finished'){ ?>

        <span class="badge badge-success">
            Completed
        </span>

        <?php if(!empty($row->completed_date)){ ?>
            <br>
            <small style="color:green;">
                <?= date('d-M-Y', strtotime($row->completed_date)); ?>
            </small>
        <?php } ?>

    <?php } elseif($row->ppm_sch_date < date('Y-m-d')){ ?>

        <span class="badge badge-danger">
            Overdue
        </span>

    <?php } else { ?>

        <span class="badge badge-warning">
            Scheduled
        </span>

    <?php } ?>
</td>

<td id="action-<?= $row->ppm_summary_id; ?>">

<?php if(
    ($row->ppm_status == 'Completed' || $row->ppm_status == 'Finished')
    && $row->invoice_generated == 0
){ ?>

    <button class="btn btn-primary btn-sm generateInvoice"
            data-id="<?= $row->ppm_summary_id; ?>">
        Generate Invoice
    </button>

<?php } elseif($row->invoice_generated == 1){ ?>

    <a href="<?= base_url('index.php/AMC/print_ppm_invoice/'.$row->invoice_id); ?>"
       target="_blank"
       class="btn btn-success btn-sm">
        Print Invoice
    </a>

<?php } ?>

</td>
 

        </tr>

    <?php endforeach; ?>
    </tbody>

</table>
        </div>
		<div class="dt-responsive table-responsive">
		<h6><u>AMC Renewal Reminders</u></h6>
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
							<th>Sr.no</th>
                            <th>AMC Code</th>
							<th>Customer</th>
							<th>Project</th>
							<th>AMC Start Date</th>
							<th>AMC End Date</th>
							<th>Amount</th>
							<th>Action</th>
						</tr>
					</thead>

				<tbody>
<?php 
$i=1; 
$today = new DateTime();

foreach($records as $row) :

$endDate = new DateTime($row->amc_end_date);
$diff = $today->diff($endDate)->days;
$isExpired = $endDate < $today;

if ($isExpired) {
    $rowClass = "table-danger";
} elseif ($diff <= 30) {
    $rowClass = "table-warning";
} else {
    $rowClass = "table-success";
}
?>
<tr class="<?php echo $rowClass; ?>">
    <td><?php echo $i++; ?></td>
    <td><?php echo $row->invoice_code; ?></td>
    <td><?php echo $row->customer_id."-".$row->customer_name; ?></td>
    <td><?php echo $row->project_name; ?></td>
    <td><?php echo date('d-M-Y',strtotime($row->amc_start_date)); ?></td>
    <td style="color:red">
        <?php echo date('d-M-Y',strtotime($row->amc_end_date)); ?>
    </td>
    <td><?php echo $row->grand_total; ?></td>
    <td>
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $row->customer_email; ?>" target="_blank">
            Send Email
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
            </div>
        </div>
        <!-- Static Table End -->

<script>

        $(document).on('click', '.generateInvoice', function(){

    var id = $(this).data('id');
    var actionBox = $('#action-' + id);

    $.ajax({
        url: "<?= base_url('index.php/AMC/generate_ppm_invoice'); ?>",
        type: "POST",
        data: { ppm_summary_id: id },
        dataType: "json",
        success: function(res){

            if(res.status){

                actionBox.html(`
                    <span class="badge badge-success">Invoice Created</span><br><br>
                    <a href="<?= base_url('index.php/AMC/print_ppm_invoice/'); ?>${res.invoice_id}"
           target="_blank"
           class="btn btn-success btn-sm">
           Print Invoice
        </a>
                `);

            } else {

                actionBox.html(`
                    <span class="badge badge-danger">${res.message}</span>
                `);
            }
        }
    });

});
</script>
        
