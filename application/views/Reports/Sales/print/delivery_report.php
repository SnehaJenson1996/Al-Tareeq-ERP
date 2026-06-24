<html>

<head>
	<title>
		Delivery Note Report
	</title>
	 <style>
        @page {
            margin: 10mm 10mm 25mm 10mm;

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }

            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Avenger Electronics LLC, Designed and developed by Concepts 360 Plus";
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
								<img src="<?php echo base_url() ?>public/header/header.jpg" alt="Header Image" width='100%' >										
							</th>
						</thead>
						<tr height='22px'>
							<td width=100% style="color:e8b41a">Delivery Report</td>
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
										<td align="center">Delivery Report from <?php echo $_GET["from_date"]; ?> to <?php echo $_GET["to_date"]; ?></td>
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
								<th style="border: 1px solid black;">Delivery Note Code</th>
								<th style="border: 1px solid black;">Date</th>
								<th style="border: 1px solid black;">Customer</th>
								<th style="border: 1px solid black;">Created By</th>
								<!-- <th style="border: 1px solid black;">Last Updated By</th> -->
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							foreach ($records as $row): ?>
								<tr style="border: 1px solid black;">
									<td style="border: 1px solid black; text-align: center;"><?php echo $i++; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->dn_code; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->dn_date; ?></td>
									<td style="border: 1px solid black;"><?php echo $row->customer_name; ?></td>

									<td style="border: 1px solid black;"><?php echo $row->created; ?></td>

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
