<?php
foreach ($comapny_records as $row) {
	$company_name = $row->company_name;
	$company_address = $row->company_address;
	$company_city = $row->company_city;

	// $company_pincode = $row->company_pincode;
	$company_country = $row->company_country;
	$company_email_id = $row->company_email_id;
	$company_telephone = $row->company_telephone;
	$company_website = $row->company_website;
	// $company_TRN = $row->company_TRN;
}
?>
<html>
<head>
	<title>Enquiry Report</title>
	<style>
		body {
			font-family: Arial;
			margin: 0;
			padding: 0;
		}
		main {
			padding: 10px 20px;
		}
		@media print {
			body {
				margin-bottom: 120px;
			}
			footer {
			position: fixed;
					bottom: 0;
					width: 100%;
			}
			* {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }	
		}
		table {
			border-collapse: collapse;
			width: 100%;
			font-size: 14px;
		}
		th, td {
			padding: 8px;
			border: 1px solid #ccc;
		}
		th {
			background-color: #f0f0f0;
		}
	</style>
</head>

<body>
	<!-- Header -->
	<!-- <header>
		<center>
			<img src="<?php echo base_url(); ?>public/logo/print_header.png" alt="Company Header" style="width: 95%;">
		</center>
	</header> -->

	<main>


	<table width="100%" border=0 cellspacing="0" colspacing="0">
		<tr>
			<td align="center">
				<p style="font-size:16px; font-weight:bold;">AMC report</p>
			</td>
			
		</tr>
	</table>

	<table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr>
			<th align="center">Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th> From Date : <?php echo $from; ?> </th>
			<th> To Date : <?php echo $to; ?> </th>
		</tr>
	</table>
	<br>

	<table width='100%' border=1 cellspacing="0" colspacing="0">
		<thead>
		<tr>
                    <th>Sr. No</th>
                    <th>Invoice Date</th>
                    <th>AMC Code</th>
                    <th>AMC Start Date</th>
                    <th>AMC End Date</th>
                    <th>Customer Name</th>
					<!-- <th>Instalment</th>
                    <th>Payment Date</th> -->
                    <th>Amount</th>
                  </tr>
		</thead>
		<tbody>
                  <?php $i=1; if(!empty($amc_list)){foreach($amc_list as $row) :?>
                    <tr>
                    <td><?php echo  $i; $i++;?></td>
                    <td><?php echo date('d-M-Y',strtotime($row->invoice_date)); ?></td>
                    <td><?php echo $row->invoice_code; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($row->amc_start_date)); ?></td>
                    <td><?php echo date('d-M-Y',strtotime($row->amc_end_date)); ?></td>
                    
                    <td><b><?php echo $row->customer_name; ?></b> </td>
                    			
                    <!-- <td><?php echo $row->ins_name; ?></td>
                    <td><?php echo date('d-M-Y',strtotime($row->pay_date)); ?></td> -->
                    <td><?php echo $row->grand_total; ?></td>
                    
			
		            </tr>
                    <?php endforeach; }?>
                  </tbody>
	</table>
	</main>

	<!-- Footer -->
	<!-- <footer>
		<div style="width: 100%; font-size: 10px; color: #000;">
			<table style="width: 100%; border-top: 1px solid #ccc; padding-top: 5px; font-family: Arial;">
				<tr>
					<td style="width: 50%;">
					<img src="<?php echo base_url().'public/logo/print_footer_new.jpg'?>" alt='logo.png' width='100px' style="float:left">
					</td>
					<td style="width: 50%; text-align: right;">
						<div style="font-size: 9px; line-height: 1.4;">
							Behind Abu hail metro station, NMC hospital building, Office Number 171 (First Floor) Dubai, UAE<br>
							P.O. Box 39599| Landline: 043312175  | <a href="https://www.dexionkitchen.com" style="color:#000;">www.dexionkitchen.com</a>
						</div>
						<div style="margin-top: 5px;">
							<span style="background-color: #8AB645; color: white; padding: 3px 12px; font-size: 10px;">www.dexionkitchen.com</span>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</footer> -->
</body>

</html>
