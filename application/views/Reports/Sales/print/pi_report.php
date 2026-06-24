<html>

<head>
	<title>
		Sales Order Report
	</title>
	 <style>
        @page {
            margin: 10mm 10mm 25mm 10mm;

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }

            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Al Adel Automatic Doors TR. LLC, Designed and developed by Concepts 360 Plus";
            }
        }
    </style>
</head>

<body style="margin-left: 5px; margin-top:5px; font-family:Arial;font-size: 12px;text-align:center">
	<table width=100% style='border: 0'>

		<tbody id="table-body">
			<tr class='calc'>
				<td>
					<table cellpadding=5 width=95% style='font-size: 20px;border:0;text-align:center'>
						<thead>		
							<th>					
                                   <img src="<?= $headerPath ?>" alt="Company Logo" class="header-img">
							</th>
						</thead>
						<tr height='22px'>
							<td width=100% style="color:e8b41a">Sales order Report</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class='calc'>
				<td>
					<table cellpadding=5 border=0 width=95% style=' border-collapse: collapse;border:0'>
						<tr>
							<td width='60%'>
								<table width=100% style='font-size: 12px;'>
									<tr>
										<td align="center">Sales order Report from <?php echo $_GET["from_date"]; ?> to <?php echo $_GET["to_date"]; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class='calc' height=5px style="background-color: #525453;">
				<td></td>
			</tr>
			
			<tr>
				<td>
					<table cellpadding="8" width="100%"
						style="font-size: 12px; border-collapse: collapse; border: 1px solid black;">
						<thead>
							<tr style="background-color: #f2f2f2; border: 1px solid black;">
								<th style="border: 1px solid black;">Sl. No</th>
								<th style="border: 1px solid black;">Pi Code</th>
								<th style="border: 1px solid black;">Date</th>
								<th style="border: 1px solid black;">Quotation</th>
								<th style="border: 1px solid black;">Grand Total</th>


								<th style="border: 1px solid black;">Created By</th>
								<th style="border: 1px solid black;">Last Updated By</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							foreach ($records as $row): ?>
								<tr style="border: 1px solid black;">
									<td style="border: 1px solid black; text-align: center;"><?php echo $i++; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->so_code; ?></td>
									<td style="border: 1px solid black;"><?php echo date('d-m-Y', strtotime($row->so_date)); ?></td>
									<td style="border: 1px solid black;"><?php echo $row->quotation_code; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->grand_total; ?></td>

									<td style="border: 1px solid black;"><?php echo $row->created; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->last_updated; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				</td>
			</tr>





		</tbody>
		<tfoot class='footer'>

		</tfoot>
	</table>
</body>

</html>
