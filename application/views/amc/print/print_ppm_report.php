<?php
foreach ($comapny_records as $row) {
	$company_name = $row->company_name;
	$company_address = $row->company_address;
	$company_city = $row->company_city;
	$company_country = $row->company_country;
	$company_email_id = $row->company_email_id;
	$company_telephone = $row->company_telephone;
	$company_website = $row->company_website;
}
?>

<html>
<head>
	<title>PPM Report</title>

	<style>

		/* PAGE SETUP */
		  @page {
    margin: 1mm 12mm 0px 12mm; /* top right bottom left */
}

		body {
			font-family: "Franklin Gothic Book", Arial, sans-serif;
			font-size: 13px;
			 margin: 5px 5px 5px 5px;
			padding: 0;
			color: #333;
		}

		/* HEADER */
		/* .header {
			position: fixed;
			top: -25mm;
			left: 0;
			right: 0;
			height: 25mm;
		} */

	 .header-img {
    margin-top: -2mm;
}

		/* FOOTER */
		 .footer {
      text-align: center;
      font-size: 12px;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100%;
    }

    .footer img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
    }

		/* TABLE */
		table {
			width: 100%;
			border-collapse: collapse;
			font-size: 12px;
		}

		th, td {
			border: 1px solid #ccc;
			padding: 6px;
			vertical-align: top;
		}

		th {
			background: #f0f0f0;
		}

		.title {
			text-align: center;
			font-weight: bold;
			font-size: 16px;
			margin-bottom: 10px;
		}

	</style>
</head>

<body>

<!-- HEADER -->
<div style="width:60%; padding:5px 5px 5px 0;">
      <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
</div>



<!-- CONTENT -->
<main>

	<div class="title">PPM REPORT</div>

	<!-- DATE INFO -->
	<table>
		<tr>
			<th>Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th>From Date : <?php echo date('d-M-Y', strtotime($from)); ?></th>
			<th>To Date : <?php echo date('d-M-Y', strtotime($to)); ?></th>
		</tr>
	</table>

	<br>

	<!-- DATA TABLE -->
	<table>
		<thead>
			<tr>
				<th>Sr</th>
				<th>PPM Code</th>
				<th>Customer</th>
				<th>Project</th>
				<th>Location</th>
				<th>Subject</th>
				<th>Date</th>
				<th>No of Schedules</th>
				<th>Remarks</th>
			</tr>
		</thead>

		<tbody>

		<?php $i = 1; foreach ($records as $r) { ?>

			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $r->ppm_code; ?></td>
				<td><?php echo $r->customer_name; ?></td>
				<td><?php echo $r->project_name; ?></td>
				<td><?php echo $r->project_location; ?></td>
				<td><?php echo $r->subject; ?></td>
				<td><?php echo date('d-M-Y', strtotime($r->ppm_date)); ?></td>
				<td><?php echo $r->no_of_sch; ?></td>
				<td><?php echo $r->remarks; ?></td>
			</tr>

		<?php } ?>

		</tbody>
	</table>

</main>
 <div class="footer">
    <img src="<?= $footerPath ?>" alt="Footer Logo">
    
  </div>

<script>
window.onload = function () {
	setTimeout(function () {
		window.print();
	}, 800);
};
</script>

</body>
</html>