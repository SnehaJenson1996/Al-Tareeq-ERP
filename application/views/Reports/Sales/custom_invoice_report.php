<?php
$user = $this->session->userdata('user_id');
?>
<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Reports/custom_invoice_report" autocomplete="off" enctype="multipart/form-data">

	<!-- page content -->
	<div class="form-group" role="main">
		<div class="">
			<div class="page-title"></div>
			<div class="clearfix"></div>

			<div class="x_content">



				<div class="well" style="overflow: auto">
					<div class="row">
						<div class="col-md-10">
							<div class="item form-group">
								<label class="control-label col-md-1 col-sm-3 col-xs-3">Date From:</label>
								<div class="col-md-2">
									<input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>" />
								</div>
								<label class="control-label col-md-1 col-sm-3 col-xs-3">Date To:</label>
								<div class="col-md-2">
									<input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" />
								</div>
								
    <!-- <div class="col-md-2">
        <select name="sales_person" id="sales_person" class="form-control select2" tabindex="2">
            <option value="">Select Salesperson</option>
            <?php foreach ($all_users as $user) { ?>
                <option value='<?= $user->user_id ?>' <?php if ($user->user_id == $sales_person) echo 'selected'; ?>>
                    <?= $user->user_name ?>
                </option>
            <?php } ?>
        </select>
    </div> -->

    <div class="col-md-2">
        <select name="customer" id="customer" class="form-control select2" tabindex="2">
            <option value="">Select Customer</option>
            <?php foreach ($all_customers as $customer) { ?>
                <option value='<?= $customer->customer_id ?>' <?php if ($customer->customer_id == $customer_id) echo 'selected'; ?>>
                    <?= $customer->customer_name ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>

								<div class="col-md-2">
									<button type="submit" class="btn btn-success">Go</button>
<button type="button" class="btn btn-primary" onclick="printCustomInvoiceReport()">Print</button>
								</div>
							</div>

						</div>
					



					</div>


				</div>
			</div>


			<?php if (isset($records)) { ?>
	<div class="dt-responsive table-responsive table-scroll">
    <table class="table table-bordered table-hover">
        <thead>
            <tr style="background:#f2f2f2; font-weight:bold; text-align:center;">
                <th>Sl.no</th>
                <th>Invoice Date</th>
                <th>Invoice Code</th>
                <th>Customer</th>
                <th>Grand Total</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody>
           <?php 
$i = 1;
$grand_total_sum = 0;

foreach ($records as $row): 
    $grand_total_sum += $row['grand_total']; // use DB value directly
?>
<tr class="bg-light">
    <td class="text-center"><?= $i ?></td>
    <td class="text-center"><?= date('d-M-Y', strtotime($row['invoice_date'])) ?></td>
    <td>
        <a target="_blank" href="<?= base_url('index.php/Sales/print_invoice/'.$row['invoice_id']) ?>">
            <?= $row['invoice_code'] ?>
        </a>
    </td>
    <td><?= $row['customer_name'] ?></td>
    <td class="text-right"><?= number_format($row['grand_total'], 2) ?></td>
</tr>
<?php 
$i++; 
endforeach; 
?>

<!-- Total Row -->
<tr>
    <td colspan="4" class="text-right"><strong>Total:</strong></td>
    <td class="text-right"><strong><?= number_format($grand_total_sum, 2) ?></strong></td>
</tr>

        </tbody>
    </table>
</div>



			<?php } ?>
			<!--  -->
		</div>
	</div>

	</div>
	</div>



	<!-- /page content -->
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%' // make it full width like form-control
    });
});



function printCustomInvoiceReport() {
    const fromDate    = document.querySelector('input[name="from_date"]').value;
    const toDate      = document.querySelector('input[name="to_date"]').value;
    // const salesPerson = document.querySelector('select[name="sales_person"]').value;
    const customer    = document.querySelector('select[name="customer"]').value;

    const baseUrl = "<?php echo base_url('index.php/Reports/print_custom_invoice_report'); ?>";

    const params = new URLSearchParams({
        from_date: fromDate,
        to_date: toDate,
        // sales_person: salesPerson,
        customer: customer
    });

    window.open(baseUrl + '?' + params.toString(), '_blank');
}

</script>



	

