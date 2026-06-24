<?php
$user = $this->session->userdata('user_id');
?>
<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Reports/quotation_report" autocomplete="off" enctype="multipart/form-data">

	<!-- page content -->
	<div class="form-group" role="main">
		<div class="">
			<div class="page-title"></div>
			<div class="clearfix"></div>

			<div class="x_content">
				<div class="well" style="overflow: auto">
					<div class="item form-group">
						<label class="control-label col-md-1 col-sm-3 col-xs-3">Date From:</label>
						<div class="col-md-2">
							<input type="date" name="from_date" class="form-control" value="<?php echo $from_date; ?>" />
						</div>
						<label class="control-label col-md-1 col-sm-3 col-xs-3">Date To:</label>
						<div class="col-md-2">
							<input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" />
						</div>

					</div>

					<div class="item form-group">
						<label class="control-label col-md-1 col-sm-3 col-xs-3">Filter by:</label>

						<div class="col-md-2">
							<select name="status" id="status" class="form-control select2" tabindex="2">
								<option value='123'>All</option>
								<option value='1' <?php if ($status == 1) echo 'selected'; ?>>Approved</option>
								<option value='0' <?php if ($status == 0) echo 'selected'; ?>>Not Approved</option>

							</select>
						</div>
						<div class="col-md-2">
							<select name="sales_person" id="sales_person" class="form-control select2" tabindex="2"
								<?php if (!in_array($logged_in_user_id, $admin_users)) echo 'disabled'; ?>>
								<?php if (in_array($logged_in_user_id, $admin_users)) { ?>
									<option value="">Select Salesperson</option>
									<?php foreach ($all_users as $user) { ?>
										<option value="<?= $user->user_id ?>"
											<?= ($user->user_id == $sales_person) ? 'selected' : '' ?>>
											<?= htmlspecialchars($user->user_name) ?>
										</option>
									<?php } ?>
								<?php } else { ?>
									<?php 
									// Find and show only the logged-in user's name
									foreach ($all_users as $user) {
										if ($user->user_id == $logged_in_user_id) { ?>
											<option value="<?= $user->user_id ?>" selected>
												<?= htmlspecialchars($user->user_name) ?>
											</option>
									<?php break; } } ?>
								<?php } ?>
							</select>
							<?php if (!in_array($logged_in_user_id, $admin_users)) { ?>
								<!-- Hidden field to ensure correct user_id is submitted -->
								<input type="hidden" name="sales_person" value="<?= $logged_in_user_id ?>">
							<?php } ?>
						</div>
						<div class="col-md-2">
							<select name="customer" id="customer" class="form-control select2" tabindex="2">
								<option value="">Select Customer</option>
								<?php foreach ($all_customers as $customer) { ?>
									<option value='<?= $customer->customer_id ?>' <?php if ($customer->customer_id == $customer_id) echo 'selected'; ?>><?= $customer->customer_name ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-md-2">
							<button type="submit" class="btn btn-success">Go</button>
							<a href="#" class="btn btn-success" onclick="printQuoteReport()">Print</a>
						</div>
					</div>
				</div>
			</div>

			<?php if (isset($records)) { ?>
				<div class="dt-responsive table-responsive">
					<table id="basic-btn" class="table table-striped table-bordered nowrap">
						<thead>
							<tr>
								<th>Sl.no</th>
								<th>Quotation Code</th>
								<th>Date</th>
								<th>Customer</th>
								<th>Status</th>
								<th>Grand Total</th>
								<th>Created by</th>
								<th>Last updated by</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							foreach ($records as $row) : ?>
								<tr>
									<td><?php echo  $i;
											$i++; ?></td>
									<td>
										<a href='<?php echo base_url() . 'index.php/Sales/edit_quotation/' .$row->quotation_id. '/'.$row->quotation_revision; ?>' >
											<?php echo $row->quotation_code . '/Rev-' . $row->quotation_revision; ?>
										</a>
									</td>
									<td><?php echo $row->quotation_date; ?></td>
									<td><?php echo $row->customer_name; ?></td>
									<td><?php echo $row->aproval == 1 ? 'Approved' : 'Not Approved'; ?></td>
									<td><?php echo $row->grand_total; ?></td>
									<td><?php echo $row->created; ?></td>
									<td><?php echo $row->last_updated; ?></td>
								</tr>

							<?php endforeach; ?>

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
<script>
	function printQuoteReport() {
		const fromDate = document.querySelector('input[name="from_date"]').value;
		const toDate = document.querySelector('input[name="to_date"]').value;
		const status = document.querySelector('select[name="status"]').value;
		const sales_person = document.querySelector('select[name="sales_person"]').value;
		const customer = document.querySelector('select[name="customer"]').value;

		const baseUrl = "<?php echo base_url() . 'index.php/Reports/print_quotation_report'; ?>";
		const params = new URLSearchParams({
			from_date: fromDate,
			to_date: toDate,
			sales_person: sales_person,
			customer: customer,
			status: status
		});

		const printUrl = `${baseUrl}?${params.toString()}`;
		window.open(printUrl, '_blank');
	}

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "-select-",
        allowClear: true,
        width: '100%'
    });
});
	

</script>
