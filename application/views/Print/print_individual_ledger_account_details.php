<?php
$this->load->helper('myopeningbalance');
if (!empty($company_records) && is_array($company_records)) {
	foreach ($company_records as $row) {
		$company_name = $row->company_name;
		$company_address = $row->company_address;
		$company_city = $row->company_city;

		$company_pincode = $row->company_pincode;
		$company_country = $row->company_country;
		$company_email_id = $row->company_email_id;
		$company_telephone = $row->company_telephone;
		$company_website = $row->company_website;
		$company_TRN = $row->company_TRN;
	}
}
$printed_by = $this->session->userdata('user_name') ?? $this->session->userdata('email');

?>
<!DOCTYPE html>
<html>

<head>
	<title>Individual Ledger Report</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}

		main {
			padding: 10px 20px;
		}

		/* Scoped styles specifically for your data tables */
		table.data-table {
			border-collapse: collapse;
			width: 100%;
			font-size: 14px;
		}

		table.data-table th,
		table.data-table td {
			padding: 8px;
			border: 1px solid #ccc;
		}

		table.data-table th {
			background-color: #f0f0f0;
		}

		/* Styles for the invisible print wrapper */
		table.print-wrapper {
			width: 100%;
			border: none;
		}

		table.print-wrapper>thead>tr>td,
		table.print-wrapper>tbody>tr>td,
		table.print-wrapper>tfoot>tr>td {
			border: none;
			padding: 0;
		}

		/* Spacer height must match your fixed footer height */
		.footer-space {
			height: 140px;
		}

		@media print {
			@page {
				margin-top: 10mm;
				margin-right: 10mm;
				margin-left: 10mm;
				margin-bottom: 35mm;
			}

			html,
			body {
				height: auto;
			}

			footer {
				position: fixed;
				bottom: 0 !important;
				left: 0;
				width: 100%;
				height: 100px;
				background-color: white;
			}

			.pagenum:before {
				content: counter(page);
			}

			* {
				-webkit-print-color-adjust: exact !important;
				print-color-adjust: exact !important;
			}
		}
	</style>
</head>

<body>

	<table class="print-wrapper">

		<thead>
			<tr>
				<td>
					<header>
						<center>
							<img src="<?php echo base_url(); ?>public/assets/images/altariq_logo.jpeg" alt="Company Header"
								style="width: 18%;">
						</center>
					</header>
				</td>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>
					<main class="content-wrapper">
						<h2 style="text-align: center; margin-bottom: 5px;">Individual Ledger Report</h2>

						<table class="data-table">
							<tr>

								<th align="left">Today's Date: <?php echo date('d-M-Y'); ?></th>
							</tr>
							<tr>
								<th align="left">Period From : <?php echo date('d-M-Y', strtotime($from_date)) . ' ' . date('d-M-Y', strtotime($to_date)); ?></th>
							</tr>
							<!-- <tr><th align="left">Ledger Account : <?php echo get_accountname_by_id($account_id); ?></th> -->
							<tr>
								<th align="left">
									<?php
									if (!empty($account_id)) {
										echo 'Ledger Account : ' . get_accountname_by_id($account_id);
									} elseif (!empty($parent_customer_id)) {
										echo 'Parent Group : ' . $parent_group_name;
									} else {
										echo 'All Records';
									}
									?>
								</th>
							</tr>
			</tr>
	</table>
	<br>

	<table class="data-table">
		<thead>
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
		<tbody>
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

			// $opening_bal=calculate_opening_bal($from_date,$account_id);
			$show_balance = !empty($account_id);

			if ($show_balance) {
				$opening_bal = calculate_opening_bal($from_date, $account_id);
			} else {
				$opening_bal = 0;
			}

			if ($show_balance) {
				if ($for_loop == 0) {
					if ($opening_bal > 0) :
			?>
						<tr>
							<td colspan="5">Dr. Opening Balance</td>
							<td align="right"><?php echo sprintf("%0.2f", $opening_bal) . " Dr"; ?></td>
							<td></td>
						</tr>
					<?php
					else :
					?>
						<tr bgcolor="#ccccc">
							<td colspan="5">Cr. Opening Balance</td>
							<td></td>
							<td align="right"><?php echo sprintf("%0.2f", ($opening_bal) * (-1)) . " Cr"; ?></td>
						</tr>
					<?php
					endif;
				}
			}
			if (!empty($ledger_transaction_records)) :
				foreach ($ledger_transaction_records as $row): ?>
					<?php
					$close_bal = $opening_bal;
					$for_loop++; ?>
					<tr>
						<?php if ($row->voucher_date > 0 && $row->amount > 0) {
							$time = strtotime($row->voucher_date);
							$new_date = date('d-M-Y', $time); ?>
							<td><?php echo $j; ?></td>
							<td><?php echo $new_date; ?></td>
							<td>
								<?php
								if ($row->voucher_type == 'S') {

									$invoice_date = !empty($row->invoice_date) ? date('d-M-Y', strtotime($row->invoice_date)) : '';

									echo 'Ref No: ' . ($row->ref_no ?? '') . '<br>
          Invoice Date: ' . $invoice_date . '<br> 
          Client PO: ' . ($row->po_code ?? '');
								} else if ($row->voucher_type == 'G') {

									$invoice_date = !empty($row->invoice_date) ? date('d-M-Y', strtotime($row->invoice_date)) : '';

									echo 'Invoice No: ' . ($row->ref_no ?? '') . '<br>
          Invoice Date: ' . $invoice_date . '<br> 
          Ref No: ' . ($row->po_code ?? '');
								} else {
									// Petty Cash Entries
									if (!empty($row->payment_type) && $row->payment_type == 'PETTY') {
										// if (!empty($row->particulars))
										// {
										//     echo html_escape($row->particulars) . '<br>';
										// }

										if (!empty($row->parent_name)) {
											echo 'Parent Group: ' . html_escape($row->parent_name) . '<br>';
										}

										if (!empty($row->voucher_code)) {
											echo 'Voucher No: ' . html_escape($row->voucher_code) . '<br>';
										}

										if (!empty($row->narration)) {
											echo '<div class="narration-text">'
												. html_escape($row->narration) .
												'</div>';
										}
									} else {
										echo '<div class="narration-text">'
											. html_escape($row->narration ?? '') .
											'</div>';
									}
								}
								?>
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
				<td style="font-weight:bold">Trans Total:</td>

				<td align="right" style="font-weight:bold">
					<?php echo sprintf("%0.2f", $debit_amount); ?>
				</td>

				<td align="right" style="font-weight:bold">
					<?php echo sprintf("%0.2f", $credit_amount); ?>
				</td>
			</tr>

			<?php if (!empty($account_id)) { ?>

				<?php

				if ($opening_bal > 0) {
					$display_total_db = $debit_amount + $opening_bal;
					$display_total_cr = $credit_amount;
				} else {
					$opening_bal = $opening_bal * (-1);
					$display_total_cr = $credit_amount + $opening_bal;
					$display_total_db = $debit_amount;
				}

				if ($display_total_cr < 0) {
					$bal = $display_total_db - ($display_total_cr * -1);
				} else {
					$bal = $display_total_db - $display_total_cr;
				}

				?>

				<?php if ($bal > 0) { ?>

					<tr bgcolor="#ccccc">
						<td colspan="5">Dr. Closing Balance</td>
						<td align="right" style="font-weight:bold">
							<?php echo sprintf("%0.2f", $bal) . " Dr"; ?>
						</td>
						<td></td>
					</tr>

				<?php } else { ?>

					<tr bgcolor="#ccccc">
						<td colspan="5">Cr. Closing Balance</td>
						<td></td>
						<td align="right" style="font-weight:bold">
							<?php echo sprintf("%0.2f", ($bal * -1)) . " Cr"; ?>
						</td>
					</tr>

				<?php } ?>

			<?php } ?>

		</tfoot>
		</tbody>
	</table>
	</main>
	</td>
	</tr>
	</tbody>

	<tfoot>
		<tr>
			<td>
				<div class="footer-space">&nbsp;</div>
			</td>
		</tr>
	</tfoot>
	</table>

	<footer>
		<div style="width: 100%; font-size: 10px; color: #000;">
			<table
				style="width: 100%; border-top: 1px solid #ccc; padding-top: 5px; font-family: Arial; border-collapse: collapse;">
				<tr>
					<td style="width: 33%; border: none;">
						<img src="<?php echo base_url() . 'public/assets/images/altariq_logo.jpeg' ?>" alt='logo.png'
							width='100px' style="float:left">
					</td>
					<td style="width: 34%; text-align: center; border: none;">
						Printed by: <?php echo $printed_by; ?><br>
						Page <span class="pagenum"></span>
					</td>
					<td style="width: 33%; text-align: right; border: none;">
						<div style="font-size: 9px; line-height: 1.4;">
							Industrial Area 11 - Industrial Area - Sharjah,
							UAE<br>
							P.O. Box 39599 | Landline: 050 545 8602 | <a href="https://www.altareeqkitchen.com/"
								style="color:#000;">www.altareeqkitchen.com</a>
						</div>
						<div style="margin-top: 5px;">
							<span
								style="background-color: #8AB645; color: white; padding: 3px 12px; font-size: 10px;">www.altareeqkitchen.com</span>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</footer>

	<script>
		window.onload = function() {
			window.print();
		};
	</script>
</body>

</html>