<div class="x_panel">
	<div class="x_title">
		<h2>Individual Ledger Details</h2>
		<ul class="nav navbar-right panel_toolbox">
			<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
		</ul>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form class="form-horizontal" action="<?php echo base_url() . 'index.php/accounts/search_individual_ledger_details/' . $account_id; ?>" id="receipt" method="post" name="receipt">
			<!-- Date Fields -->
			<div class="form-group row">
				<label class="col-md-2 col-sm-3 col-form-label">From</label>
				<div class="col-md-3 col-sm-4">
					<div class="input-group date">
						<input type="text" class="form-control form-control-sm" id="from_date" name="from_date"
							value="<?php echo date('d-M-Y', strtotime($from_date)); ?>" required tabindex="3">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>

				<label class="col-md-2 col-sm-3 col-form-label">To Date</label>
				<div class="col-md-3 col-sm-4">
					<div class="input-group date">
						<input type="text" class="form-control form-control-sm" id="to_date" name="to_date"
							value="<?php echo date('d-M-Y', strtotime($to_date)); ?>" required tabindex="3">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Ledger Account + Buttons -->
			<div class="form-group row align-items-center">
				<label class="col-md-2 col-sm-3 col-form-label">Ledger Account</label>
				<div class="col-md-3 col-sm-4">
					<select tabindex="1" class="form-control form-control-sm select2" id="account_id" name="account_id" required>
						<option value="">Select Code</option>
						<?php foreach ($account_ledgers as $s) { ?>
							<option value="<?php echo $s->account_id; ?>" <?php if ($s->account_id == $account_id) echo 'selected'; ?>>
								<?php echo $s->account_name; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<label class="col-md-2 col-sm-3 col-form-label">Branch</label>
				<div class="col-md-3 col-sm-4">
					<select class="form-control select2" id="branch_id" name="branch_id">
						<option value="">All Branches</option>
						<?php foreach ($branch_list as $branch) { ?>
							<option value="<?php echo $branch->branch_id; ?>"
								<?php if ($branch_id == $branch->branch_id) echo 'selected'; ?>>
								<?php echo $branch->branch_name; ?>
							</option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-5 col-sm-12 d-flex flex-wrap align-items-center mt-2 mt-md-2">
					<!-- Go Button -->
					<button type="submit" id="view" name="go" class="btn btn-primary btn-sm mr-2">
						<i class="fa fa-search"></i> Go
					</button>
		</form>

		<!-- Print Form -->
		<form target="_blank" action="<?php echo base_url() . 'index.php/'; ?>Accounts/print_individual_ledger_account_details" method="post" class="mr-2">
			<input type="hidden" name="from_date" value="<?php echo $from_date; ?>" />
			<input type="hidden" name="to_date" value="<?php echo $to_date; ?>" />
			<input type="hidden" name="account_id" value="<?php echo $account_id; ?>" />
			<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>" />
			<button type="submit" id="print" class="btn btn-warning btn-sm">
				<i class="fa fa-print"></i> Print
			</button>
		</form>

		<!-- Export Form -->
		<form action="<?php echo base_url() . 'index.php/'; ?>Accounts/export_individual_ledger_account_details" method="post">
			<input type="hidden" name="from_date" value="<?php echo $from_date; ?>" />
			<input type="hidden" name="to_date" value="<?php echo $to_date; ?>" />
			<input type="hidden" name="account_id" value="<?php echo $account_id; ?>" />
			<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>" />
			<button type="submit" id="export" class="btn btn-success btn-sm">
				<i class="fa fa-file-excel-o"></i> Export
			</button>
		</form>
	</div>
</div>

<!-- Ledger Table -->
<div class="table-responsive mt-3">
	<table id="example1" class="table table-bordered table-striped table-sm" width="100%" style="font-size:12px;">
		<thead class="thead-light">
			<tr>
				<th>Sr.No</th>
				<th>Txn Date</th>
				<th>Particulars</th>
				<th>Voucher Code</th>
				<th>Txn Type</th>
				<th>Debit</th>
				<th>Credit</th>
			</tr>
		</thead>

		<?php
		$count = 0;
		$total = 0;
		$totalc = 0;
		$credit_amount = 0;
		$debit_amount = 0;
		$display_total_row = 0;
		$tamount = 0;
		$opening_balance = 0;
		$opening_bal = 0;
		$for_loop = 0;
		$closing_row = 0;
		$display_total_closing = 0;
		$i = 0;
		$j = 1;
		$val_from = date('d-m-Y', strtotime($from_date));
		$debit_amount_new = 0;
		$credit_amount_new = 0;
		$display_total_row = 0;
		$this->load->helper('myopeningbalance');
		$opening_bal = calculate_opening_bal($from_date, $account_id, $branch_id);

		if ($for_loop == 0) {
			if ($opening_bal > 0) : ?>
				<tr>
					<td colspan="5">Dr. Opening Balance</td>
					<td align="right"><?php echo sprintf("%0.2f", $opening_bal) . " Dr"; ?></td>
					<?php $opening_balance = $total ?>
					<td></td>
				</tr>
			<?php else : ?>
				<tr bgcolor="#ccccc">
					<td colspan="5">Cr. Opening Balance</td>
					<td></td>
					<td align="right"><?php echo sprintf("%0.2f", ($opening_bal) * (-1)) . " Cr"; ?></td>

				</tr>
			<?php
			endif;
		}
		if (!empty($ledger_transaction_records)) :
			foreach ($ledger_transaction_records as $row): ?>
				<?php
				$close_bal = $opening_bal;
				$for_loop++; ?>
				<tr>
					<?php if ($row->voucher_date != '' && $row->amount > 0) {
						$time = strtotime($row->voucher_date);
						$new_date = date('d-M-Y', $time); ?>
						<td><?php echo $j; ?></td>
						<td><?php echo $new_date; ?></td>
						<td>
							<?php
							if ($row->voucher_type == 'S')
								echo 'Ref No: ' . $row->ref_no . '<br>Invoice Date: ' .
									(isset($row->invoice_date) ? date('d-M-Y', strtotime($row->invoice_date)) : "") . '<br> 
			   					        Client PO: ' . $row->po_code;
							else if ($row->voucher_type == 'G')
								echo 'Invoice No: ' . $row->ref_no . '<br>
			   					      Invoice Date: ' . date('d-M-Y', strtotime($row->invoice_date)) . '<br> 
			   					      Ref No: ' . $row->po_code;
							//else if($row->voucher_type == 'P') 
							//echo 'Invoice No: '.$row->ref_no.'<br>
							// Invoice Date: '.date('d-M-Y',strtotime($row->invoice_date ?? '')).'<br> 
							// Ref No: '.$row->po_code.'<br>'.$row->narration;
							else
								echo $row->narration; ?>
						</td>
						<td><?php echo $row->voucher_code; ?></td>
						<td>
							<?php
							if ($row->voucher_type == 'S')
								echo 'Sales Invoice';
							if ($row->voucher_type == 'G')
								echo 'PO GRN Invoice';
							if ($row->voucher_type == 'R')
								echo 'Receipt';
							if ($row->voucher_type == 'P')
								echo 'Payment';
							if ($row->voucher_type == 'C')
								echo 'Credit Note';
							if ($row->voucher_type == 'D')
								echo 'Debit Note';
							if ($row->voucher_type == 'J')
								echo 'Journal';
							if ($row->voucher_type == 'N')
								echo 'Contra Entry';
							if ($row->voucher_type == 'EX')
								echo 'Expense Entry';
							?>
						</td>

						<?php $tamount = $row->amount; ?>
						<?php if (strtoupper($row->drcr_type) == "DR") {
							$display_total = number_format((float)$tamount, 2, '.', ''); ?>
							<td align="right"><?php echo sprintf("%0.2f", $display_total); ?></td>
						<?php $debit_amount = $debit_amount + $tamount;
						} else echo "<td></td>"; ?>

						<?php

						if (strtoupper($row->drcr_type) == "CR") { ?>
							<?php $display_total = number_format((float)$tamount, 2, '.', ''); ?>
							<td align="right"><?php echo sprintf("%0.2f", $display_total); ?></td>
					<?php $credit_amount = $credit_amount + $tamount;
						} else echo "<td></td>";

						$j++;
					} ?>
				</tr>
		<?php
				$time = strtotime($to_date);
				$val_to = date('y-m-d', $time);
				$val_to = strtotime($to_date);
				$time2 = strtotime($row->voucher_date);
				if ($time2 < $val_to):
					if (strtoupper($row->drcr_type) == "DR"):
						$total = $total + $tamount;
					endif;
					if (strtoupper($row->drcr_type) == "CR"):
						$total = $total - $tamount;
					endif;
				endif;
			endforeach;
		endif; ?>
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="font-weight: bold">Trans Total:</td>
				<?php $display_total_cr = 0;
				if ($opening_bal > 0) {
					$display_total_db = $debit_amount + $opening_bal;
					$display_total_cr = $credit_amount;
				} else {
					$opening_bal = $opening_bal * -1;
					$display_total_cr = $credit_amount + $opening_bal;
					$display_total_db = $debit_amount;
				} ?>

				<?php $display_total = number_format((float)($display_total_db), 2, '.', ''); ?>
				<td align="right" style="font-weight: bold"><?php echo sprintf("%0.2f", $debit_amount); ?></td>
				<?php
				if ($display_total_cr < 0)
					$display_total = $display_total_cr * -1;
				else
					$display_total = $display_total_cr;
				?>
				<td align="right" style="font-weight: bold"><?php echo sprintf("%0.2f", $credit_amount); ?></td>
			</tr>
			<?php

			if ($display_total_cr < 0)
				$bal = $display_total_db - ($display_total_cr * -1);
			else {
				$bal = $display_total_db - ($display_total_cr);
			}
			?>
			<?php
			if ($bal > 0): ?>
				<tr bgcolor="#ccccc">
					<td colspan="5">Dr. Closing Balance</td>
					<?php $display_total = $bal; ?>
					<td align="right" style="font-weight: bold"><?php echo sprintf("%0.2f", ($display_total)) . " Dr"; ?></td>
					<td></td>
				</tr>
			<?php
			else :
			?>
				<tr class="last" bgcolor="#ccccc">
					<td colspan="5">Cr. Closing Balance</td>
					<?php $display_total = $bal * -1; ?>
					<td></td>
					<td align="right" style="font-weight: bold"><?php echo sprintf("%0.2f", ($display_total)) . " Cr"; ?></td>
				</tr>
			<?php
			endif;

			?>
		</tfoot>
	</table>
</div>
</div>
</form>

</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>

<script>
	$(document).ready(function() {


		var i = 1;
		$("#dr_add_row").click(function() {
			$('#dr_addr' + i).html(
				`<td>
                <select class="form-select form-control-sm select2 select2Width" id="debtor${i}" name="debtor[]" onchange="get_account_balance(${i}, 'dr')" required>
                    <option value="">Select Code</option>
                    <?php if (!empty($sundry_detors_records)) { ?>
                        <?php foreach ($sundry_detors_records as $s) { ?>
                            <option value="<?= $s->account_id; ?>"><?= $s->account_name; ?></option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="">No Records Found</option>
                    <?php } ?>
                </select>
                <br>
                <label id="set_balancedr${i}">Balance</label>
            </td>
            <td>
                <input type="number" step="0.01" name="dr_amount[]" id="dr_amount${i}" class="form-control form-control-sm debit_sum" min="0" required onkeyup="calculate_grand_total()">
            </td>
            <td>
                <a onclick="remove_row_dr(${i});" title="Delete" class="btn btn-xs bg-orange remove1">
                    <span class="fa fa-trash"></span>
                </a>
            </td>`
			);
			$('#dr_body tr:last').after(`<tr id="dr_addr${i + 1}"></tr>`);
			i++;
			$('.select2').select2({
				width: "220px"
			});
		});

		$("#delete_row1").click(function() {
			if (i > 1) {
				$("#dr_addr" + (i - 1)).html('');
				i--;
			}
		});

		var k = 1;
		$("#cr_add_row").click(function() {
			$('#cr_addr' + k).html(
				`<td>
                <select class="form-select form-control-sm select2 select2Width" id="debtor${k}" name="debtor[]" onchange="get_account_balance(${k}, 'cr')" required>
                    <option value="">Select Code</option>
                    <?php if (!empty($sundry_detors_records)) { ?>
                        <?php foreach ($sundry_detors_records as $s) { ?>
                            <option value="<?= $s->account_id; ?>"><?= $s->account_name; ?></option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="">No Records Found</option>
                    <?php } ?>
                </select>
                <br>
                <label id="set_balancecr${k}">Balance</label>
            </td>
            <td>
                <input type="number" step="0.01" name="dr_amount[]" id="dr_amount${k}" class="form-control form-control-sm credit_sum" min="0" required onkeyup="calculate_grand_total()">
            </td>
            <td>
                <a onclick="remove_row_cr(${k});" title="Delete" class="btn btn-xs bg-orange remove1">
                    <span class="fa fa-trash"></span>
                </a>
            </td>`
			);
			$('#cr_body tr:last').after(`<tr id="cr_addr${k + 1}"></tr>`);
			k++;
			$('.select2').select2({
				width: "220px"
			});
		});

		$("#delete_row2").click(function() {
			if (k > 1) {
				$("#cr_addr" + (k - 1)).html('');
				k--;
			}
		});
	});

	// Example remove_row_cr / remove_row_dr functions
	function remove_row_cr(id) {
		$('#cr_addr' + id).remove();
	}

	function remove_row_dr(id) {
		$('#dr_addr' + id).remove();
	}
	$('#from_date, #to_date').datepicker({
		format: 'dd-M-yyyy',
		autoclose: true,
		todayHighlight: true
	});


	function remove_row_cr(append_id) {
		$('#cr_addr' + append_id).attr("id", "cr_addr" + append_id + "x");
		$('#cr_addr' + append_id + "x").remove();
	}

	function get_account_balance(append_id, type) {
		var account_id = document.getElementById("debtor" + append_id).value;
		var today = "<?php echo date('Y-m-d') ?>";
		$.ajax({
			url: "<?php echo site_url('Accounts/get_account_balance'); ?>",
			type: 'POST',
			data: {
				account_id: account_id,
				today: today
			},
			success: function(msg) {
				if (msg) {
					//alert(msg);
					document.getElementById('set_balance' + type + append_id).innerHTML = 'Balance: ' + msg;

				}
			}
		});
	}

	function calculate_grand_total() {
		var i_value = 0;
		i_total = 0;
		$('.debit_sum').each(function() {
			i_value = $(this).val();
			if (i_value == '')
				i_value = 0;
			else
				i_total += parseFloat(i_value);
		});
		if (isNaN(i_total)) var dr_total = 0;

		var k_value = 0;
		k_total = 0;
		$('.credit_sum').each(function() {
			k_value = $(this).val();
			if (k_value == '')
				k_value = 0;
			else
				k_total += parseFloat(k_value);
		});
		if (isNaN(k_total)) var cr_total = 0;

		document.getElementById("debit_total").value = parseFloat(i_total).toFixed(2);
		document.getElementById("credit_total").value = parseFloat(k_total).toFixed(2);
		//check_total();
	}

	function check_total() {
		var dr_total = $('#debit_total').val();
		var cr_total = $('#credit_total').val();

		if (parseFloat(cr_total) != parseFloat(dr_total)) {
			alert("Both debit total and credit total must match");
			return false;
		}
	}

	$(document).ready(function() {
		$('.select2').select2({
			width: '100%',
			placeholder: "Select",
			allowClear: true
		});
	});
</script>