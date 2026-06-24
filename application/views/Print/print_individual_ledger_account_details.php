<?php
$this->load->helper('myopeningbalance');


?>

<style>
    header,
    footer {
        position: fixed;
        width: 100%;
        left: 0;
        right: 0;
    }

    header {
        top: 0;
        text-align: center;
		
    }
	 header img {
        max-width: 100%;
        height: 120px;   /* control height for view */
        object-fit: contain;
    }
    footer {
        bottom: 0;
        text-align: center;
    }

    main {
        margin-top: 120px; /* push below header */
        margin-bottom: 120px; /* push above footer */
    }
	 @media screen {
        main {
            margin-top: 120px; /* enough gap below header */
        }
    }

    @media print {
        header,
        footer {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
        }

        header {
            top: 0;
            text-align: center;
        }

        footer {
            bottom: 0;
            text-align: center;
        }

        main {
            margin-top: 180px !important;
            margin-bottom: 100px !important;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            padding: 5px;
        }
    }
</style>


<header style="margin-bottom: 10px;width:100%">


	<img src="<?= base_url('public/header/2.png'); ?> " width="100%" />

</header>
<!-- <br><br> -->
<main>
	<p style="font-size:16px; font-weight:bold;">Individual Ledger Details</p>
	<table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr>
			<td>Report Date : <?php echo date('d-M-Y'); ?></td>
			<td></td>
		</tr>
		<tr>
			<td>Ledger Account : <?php echo get_accountname_by_id($account_id); ?></td>
			<td>Period From : <?php echo date('d-M-Y', strtotime($from_date)) . ' ' . date('d-M-Y', strtotime($to_date)); ?></td>
		</tr>
	</table>
	<br>

	<table width='100%' border=1 cellspacing="0" colspacing="0">
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
					<?php if ($row->voucher_date > 0 && $row->amount > 0) {
						$time = strtotime($row->voucher_date);
						$new_date = date('d-M-Y', $time); ?>
						<td><?php echo $j; ?></td>
						<td><?php echo $new_date; ?></td>
						<td><?php echo $row->narration; ?></td>
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


</main>
<footer style="margin-top:30px; text-align:left; width:100%;">
	<img src="<?= base_url('public/footer/2.png'); ?> " />
</footer>