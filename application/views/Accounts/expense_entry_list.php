<style>
	.action-icons i {
		font-size: 18px;
		margin: 0 5px;
		vertical-align: middle;
	}
</style>

<div class="card-body">
	<div class="dt-responsive table-responsive">
		<table id="datatable" class="table table-striped" data-toggle="data-table">
			<thead>
				<tr>
					<th>Sr.no</th>
					<th>Trans Code</th>
					<th>Date</th>
					<th style="text-align:right;">Expense</th>
					<th style="text-align:right;">VAT</th>
					<th style="text-align:right;">Total</th>
					<th>Narration</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php $i = 1;
				foreach ($records as $row) : ?>
					<tr <?php if ($row->cancel == 1) {
							echo "class='bg-soft-danger'";
						} ?>>
						<td><?php echo $i;
							$i++; ?></td>
						<td>
							<a target='_blank' href="<?php echo base_url() . 'index.php/Accounts/view_account_transaction_details/' . $row->voucher_id; ?>" title="details">
								<?php echo $row->voucher_code; ?>
							</a>
						</td>
						<td>
							<?php echo date('d-M-Y', strtotime($row->voucher_date)); ?><br>

						</td>
						<td align="right">
							<?= number_format($row->expense, 2); ?>
						</td>

						<td align="right">
							<?= number_format($row->vat, 2); ?>
						</td>

						<td align="right">
							<?= number_format($row->total, 2); ?>
						</td>
						<td>
							<?php echo $row->narration; ?>
						</td>
						<td class="action-icons">
							<?php if ($row->cancel == 0) { ?>
								<a href="javascript:confirmcancel('<?php echo $row->voucher_code; ?>')" title="Delete" class='delete' id='delete'><i class="fa fa-trash" style="color:red; cursor:pointer;" title="Delete"></i></a>
							<?php } else { ?>
								<span class="text-danger" title="Cancelled">
									<i class="fa fa-ban"></i>
								</span>
							<?php } ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>

		</table>
	</div>
</div>

<!-- Static Table End -->



<script>
	function confirmcancel(voucher_code) {
		var r = confirm("Are you sure you want to Cancel Record?");
		if (r == true) {
			$.ajax({
				url: "<?php echo base_url() ?>index.php/Accounts/delete_trans_entry",
				type: "POST",
				data: {
					voucher_code: voucher_code
				},
				success: function(msg) {
					if (msg == 1) {
						alert("Record Cancelled");
						window.location.href = "<?php echo $_SERVER['PHP_SELF'] ?>";
					} else {
						alert("Can't Cancel record. Data already exist!!!");
					}
				},
			});
			return true;
		} else
			return false;

	}
</script>