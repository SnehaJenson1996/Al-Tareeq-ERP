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
	<title>Compliant Report</title>
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

	<table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr>
			<th align="center">Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th> From Date : <?php echo $from; ?> </th>
			<th> To Date : <?php echo $to; ?> </th>
		</tr>
	</table>
	<br>

	<?php if ($rpt_type == 'Summary') { ?>
            
		<table width='100%' border=1 cellspacing="0" colspacing="0">
                <thead>
                  <tr>
                    <th>Sr. No</th>
              			<th>Complaint Code</th>
                    	<th>Project</th>
              			<th>Dates</th>
              			<th>Status</th>
              			<th>Total Cost</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=1;$totalCost = 0; foreach($records as $row) : $totalCost += ($row->mat_cost + $row->lab_cost);?>
                <tr>
                  <td><?php echo  $i; $i++;?></td>
                  <td><a href="<?php echo base_url().'index.php/AMC/edit_complaint/'.$row->cmp_id.'';?>"><?php echo $row->cmp_code;?></a></td>
                  <td><?php echo $row->project_name; ?><br/><?php echo $row->description; ?></td>
                  <td>
                    Create Date: <?php echo date('d-M-Y',strtotime($row->cmp_date)); ?><br/>
                    Receive Date: <?php echo date('d-M-Y',strtotime($row->receive_date)); ?><br/>
                    Closing Date: <?php echo date('d-M-Y',strtotime($row->close_date)); ?><br/>
                  </td>
                  <td><?php echo $row->cmp_status; ?></td>
                  <td><?php echo number_format($row->mat_cost+$row->lab_cost,2); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                <tr>
      <th colspan="5" style="text-align: right;">Total Cost</th>
      <th><?php echo number_format($totalCost,2); ?></th>
    </tr>
                </tfoot>
              </table>
            
            <?php } else { ?>
              
				<table width='100%' border=1 cellspacing="0" colspacing="0">
                <thead>
                  <tr>
                    <th>Sr. No</th>
              			<th>Complaint Code</th>
                    <th>Project</th>
              			<th>Details</th>
              			<th>Labour Cost</th>
              			<th>Materials</th>
                    <th>Material Cost</th>
                    <th>Total Cost</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  $i = 1; 
                  $totalLabourCost = 0; 
                  $totalMaterialCost = 0; 
                  $totalCost = 0; 
                  foreach($records as $row) : 
                    $totalLabourCost += $row->expense; 
                    $totalMaterialCost += $row->total; 
                    $totalCost += $row->expense+$row->total;
                  ?>
                <tr>
                  <td><?php echo  $i; $i++;?></td>
                  <td><a href="<?php echo base_url().'index.php/AMC/edit_complaint/'.$row->cmp_id.'';?>"><?php echo $row->cmp_code;?></a></td>
                  <td><?php echo $row->project_name; ?><br/><?php echo $row->description; ?></td>
                  <td>
                    Flat: <?php echo $row->flat_no; ?><br/>
                    Technician: <?php echo $row->user_name; ?><br/>
                    Visit Date: <?php echo date('d-M-Y',strtotime($row->visit_date)); ?><br/>
                    Hours: <?php echo $row->hours; ?><br/>
                  </td>
                  <td><?php echo number_format($row->expense,2); ?></td>
                  <td><?php echo $row->product_code; ?></td>
                  <td><?php echo number_format($row->total,2); ?></td>
                  <td><?php echo number_format($row->expense+$row->total,2); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                <tr>
                  <th colspan="4" style="text-align: right;">Total</th>
                  <th><?php echo number_format($totalLabourCost,2); ?></th>
                  <th></th> <!-- No total for materials column here -->
                  <th><?php echo number_format($totalMaterialCost,2); ?></th>
                  <th><?php echo number_format($totalCost,2);?></th>
                </tr>
              </tfoot>
              </table>
           
            <?php } ?>


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
