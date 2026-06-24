<?php  $this->load->helper('menu_helper.php');
foreach($comapny_records as $row) {
	$company_name=$row->company_name;
	$company_address=$row->company_address;
	$company_city= $row->company_city;
	$company_country = $row->company_country;

	$company_pincode= $row->company_pincode;
	$company_country= $row->company_country;
	$company_email_id= $row->company_email_id;
	$company_telephone= $row->company_telephone;
	$company_website= $row->company_website;
	$company_TRN= $row->company_TRN;
	//$company_id=$row->company_id;
}

$mis_cnt=0;
foreach($records1 as $row) {	
	$enquiry_code=$row->amc_enq_code;
	$enquiry_date=date('d-M-Y',strtotime($row->enq_date));
	$revision=$row->revision;
	$quotation_date=date('d-M-Y',strtotime($row->invoice_date));
	$project_name = $row->project_name;
	$scope_work = $row->scope_work;
	$service_scheme = $row->service_scheme;
	$quotation_code=$row->invoice_code;
	$customer_id=$row->customer_id;
	$customer_name=$row->cust_name;
	$cp_name=$row->cp_name;
	$cp_mobile=$row->cp_mobile;
	$cp_email=$row->cp_email;
	$sub_total=$row->sub_total;
	$discount_amt = $row->discount_amt;
	$vat_percent=$row->vat_percent;
	$vat_amt=$row->vat_amt;
	$discount_percent=$row->discount_percent;
	$amc_start_date1 = $row->amc_start_date;; // Example date in YYYY-MM-DD format
	$date = DateTime::createFromFormat('Y-m-d', $amc_start_date1);
	$amc_start_date = $date->format('d-m-Y'); // Convert to DD-MM-YYYY format
	$company_id=$row->company_id;
	$amc_end_date = $row->amc_end_date;
	$conditions = nl2br($row->conditions);
	$exclusions = nl2br($row->exclusions);
	$grand_total=$row->grand_total;

		
	$billing_addr=$row->billing_addr;
	$billing_city=$row->billing_city;
	$billing_state=$row->billing_state;
	$billing_pincode=$row->billing_pincode;
	$billing_country= $row->billing_country;	
	$shipping_addr= $row->shipping_addr;
	$shipping_city= $row->shipping_city;
	$shipping_pincode= $row->shipping_pincode;
	$shipping_country= $row->shipping_country;	
	$remark=$row->remark;
	
	$company_bank_name= $row->bank_name;
	$company_bank_account= $row->bank_account;
	$company_bank_branch= $row->bank_branch;
	$cust_trn = $row->trn_no;
	$bank_iban= $row->bank_iban;
	$bank_swift= $row->bank_swift;	
	$sales_person=$row->user_name;
	$sales_person_mob=$row->contact_no;
	$currabrev=$row->currabrev;
	$currency_rate=$row->currency_rate;
	
	
}
if($revision>0)
{
$revtext= 'Rev -'.$revision;
}
else
{ $revtext="";
 }
 $tot_dis=0;
?>
<style>
@media print {
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    header {
        position: fixed;
        width: 100%;
        top: 0;
        height: 300px; /* Height of the header */
        z-index: 1000; /* Ensure header is above content */
    }

    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        background-color: #f0f0f0;
        padding: 10px 0;
        z-index: 1000; /* Ensure footer is above content */
        margin-top: 50px; /* Adjust to prevent overlap */
    }

    .main-content {
        margin-top: 10% auto; /* Adjust according to header height + some spacing */
        margin-bottom: 120px; /* Space for footer */
        page-break-after: avoid;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
        font-size: 12px;
    }

    h3, h4, h5 {
        page-break-after: avoid; /* Prevent breaking headers */
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .page-break {
        page-break-before: always; /* Use this class for new pages */

    }
}
</style>


<html>
	<head>
		<title>
			Annual Maintenance Contract
		</title>
			
	</head>

	<body>
    <header>
        <?php if($company_id == 1): ?>
            <img src="<?php echo base_url() ?>public/logo/Header.jpg" alt="Header Image" width='100%'>
        <?php else: ?>
            <img src="<?php echo base_url() ?>public/logo/Header2.jpg" alt="Header Image" width='100%'>
        <?php endif; ?>
    </header>
	<div class="main-content">
    <br/><br/>
	<table border='1'  width='95%' style='border-collapse: collapse;font-family: Helvetica, sans-serif;'>
				<tr>
					<td bgcolor="#160D9F" align="center" colspan='4'>
						<b><font color="#ffffff" style="font-size:30px;">ANNUAL MAINTENANCE CONTRACT</font></b>
					</td>
				</tr>
			</table>
			
			<table align="center" width='90%' cellpadding="4" style='font-family: Helvetica, sans-serif;font-size:13px;'>
			<tr>
				<td >Ref Number</td><td ></td><td  width='75%'><?php echo $quotation_code;?></td>
				</tr>
				<tr>
				<td >Date</td><td ></td><td  width='75%'><?php echo $quotation_date;?></td>
				</tr>
				<tr>
					<td colspan ="3"><h4 align="left">This Agreement is made on <?php echo $quotation_date;?> between : </h4></td>
				</tr>
				<tr>
					<td width="25%"><b>First Party</b></td><td width="1%">:</td><td ><?php echo $company_name;?></td>
				</tr>
				<tr>
					<td></td><td></td><td  width='75%'><?php echo $company_address;?></td>
				</tr>
				<tr>
					<td></td><td></td><td  width='75%'><?php echo $company_telephone;?></td>
				</tr>
				<tr>
				<td></td><td></td><td  width='75%'><?php echo $company_email_id;?></td>
				</tr>
				<tr>
				<td></td><td></td><td  width='75%'><?php echo $company_city;?></td>
				</tr>
				
				
				</tr>
				<tr>
					<td width='15%' ><b>Second Party</b></td><td width="1%">:</td><td  width='75%'><?php echo $customer_name;?></td>
				</tr>
				<tr>
					<td ></td><td ></td><td  width='75%'><?php echo $cp_name;?></td>
				</tr>
				<tr>
					<td></td><td ></td><td  width='75%'><?php echo $cust_trn;?></td>
				</tr>
				
				<tr>
					<td width='15%' ><b>Project</b></td><td width="1%">:</td><td  width='75%'><?php echo $project_name;?></td>
				</tr>
				<tr>
					<td width='15%' ><b>Scope of Work</b></td><td width="1%">:</td><td  width='75%'>MAINTENANCE OF HVAC SYSTEMS</td>
				</tr>
				<tr>
					<td width='15%' ><b>CONTRACT</b></td><td width="1%">:</td>
					<td  width='75%'>
					Contract Starts On <?php echo $amc_start_date;?> and ends on <?php echo $amc_end_date;?> extendable upon agreement of both the parties.

					</td>
				</tr>
				
				</table>
				
				<table style="margin-top:5%; font-size:12px;">
					<tr>
						<td>WHEREAS</td>
					</tr>
					<tr>
						<td>The first party is an MEP Maintenance & Contracting Company duly appointed by the second party to carry out the maintenance of Air conditioning systems of LuLu Hypermarket LLC at Mall of UAQ, UAE.as Scope of work attached.
						</td>
					</tr>
					<tr>
						<td>And</td>
					</tr>
					
					<tr>
						<td>Second Party is <?php echo $customer_name;?>, UAE and has agreed to accept the service offer of Air conditioning by the first party.</td>
					</tr>
				</table>
				<h4 align="left">Therefore, the two parties have agreed on following:</h4>
				
				<h5 align="left">LIST OF EQUIPMENT COVERED UNDER THE CONTRACT</h5>

				<?php 			
				$maxItemsPerPage = 7; 
				$currentItemCount = 0;

				echo '<table border="1" width="100%" cellpadding="10" style="font-size: 12px; border-collapse: collapse; font-family: Arial, sans-serif;">';
				echo '<thead><tr><th bgcolor="#BFC9CA">#</th><th bgcolor="#BFC9CA">Description</th><th bgcolor="#BFC9CA">Brand</th><th bgcolor="#BFC9CA">Capacity</th><th bgcolor="#BFC9CA">Quantity</th></tr></thead><tbody>';

				foreach ($records2 as $tr) {
					// If item count exceeds max, add a page break
					if ($currentItemCount % $maxItemsPerPage == 0 && $currentItemCount > 0) {
						echo '</tbody></table>';
						echo '<div class="page-break"></div>'; // Add page break
						echo '<table border="1" width="100%" cellpadding="10" style="font-size: 12px; border-collapse: collapse; font-family: Arial, sans-serif;">';
						echo '<thead><tr><th bgcolor="#BFC9CA">#</th><th bgcolor="#BFC9CA">Description</th><th bgcolor="#BFC9CA">Brand</th><th bgcolor="#BFC9CA">Capacity</th><th bgcolor="#BFC9CA">Quantity</th></tr></thead><tbody>';
					}

					// Output the row
					echo '<tr>';
					echo '<td>' . ++$currentItemCount . '</td>';
					echo '<td>' . $tr->product_id . '</td>';
					echo '<td>' . $tr->brand . '</td>';
					echo '<td>' . $tr->capacity . '</td>';
					echo '<td>' . round($tr->quantity) . '</td>';
					echo '</tr>';
				}

				echo '</tbody></table>'; ?>

				
					<br/><br/><br/><br/>
					<table   width='100%' style='font-size: 12px; border-collapse: collapse;page-break-inside: avoid;'>
							<tbody>
								<tr><td colspan="2"><h3 align="left" colspan='2'>SERVICE SCHEME PLAN:</h3></td></tr>
								<tr>
									<td colspan="2">
									<?php $i=1;
									foreach($records5 as $tr) :?>
									<tr><td><?php echo $tr->service_details; ?></td></tr>

									<?php $i++; 
							endforeach; ?>	
									</td>
								</tr>
					</tbody>	
						</table>
						<br/><br/><br/><br/><br/><br/>
<div class="page-break"></div>						

						<table border='1'  width='100%' style='font-size: 12px; border-collapse: collapse;page-break-inside: avoid;float:left;margin-top:15%;'>
							<thead>
								<tr><td colspan="2"><h3 align="left" colspan='2'>SCOPE OF WORK:</h3></td></tr>
								
								<tr>
									<th>S.No</th><th>Details</th>
								</tr>
							</thead>
							<tbody>
						<?php $i=1;
						foreach($records3 as $tr) :?>
							<tr><td><?php echo $i; ?></td><td><?php echo $tr->scope_of_work; ?></td></tr>

							<?php $i++; 
						endforeach;
						
						?>	
						</tbody>	
						</table>
						<br/><br/><br/>
						<table border='1'  width='100%' style='font-size: 12px; border-collapse: collapse;page-break-inside: avoid;float:left; margin-bottom:50px;'>
							<tr><th align="left" colspan ="2">PRICE: -</th></tr>
							<tr border="0">
								<td border="0">Total price for the above scope of Work  :</td><td align="right"><b><?php echo number_format($sub_total-$tot_dis,2);?></b></td>
							</tr>
							<tr>
								<td>Discount:</td><td align="right"><b><?php echo $discount_amt > 0 ? $discount_amt : 0.00;?></b></td>
							</tr>
							<tr>
								<td>TAXABLE AMOUNT:</td><td align="right"><b><?php echo sprintf("%.2f", $sub_total-$discount_amt); ?></b></td>
							</tr>
							<tr>
								<td>VAT @ 5%:</td><td align="right"><b><?php echo $vat_amt;?></b></td>
							</tr>
							<tr>
								<td>GRAND TOTAL :</td><td align="right"><b><?php echo $grand_total;?></b></td>
							</tr>
							<tr style="height:50px">
								<th colspan ="2" align="left">Total Price for the above scope of work will be Dhs. <?php echo $grand_total;?> /-. (<?php echo 'AED'.convert_number_to_words($grand_total); ?>)
								</th>
							</tr>
						</table>
						
						<br/><br/><br/>
						<table border='1'  width='100%' style='font-size: 12px; border-collapse: collapse;page-break-inside: avoid;float:left;'>
						<tr>
							<th colspan="4" align="left">TERMS OF PAYMENT: -</th>
						</tr>
							<tr>
								<th width="25%">Installment</th>
								<th width="25%">Period</th>
								<th width="25%">Payment Date</th>
								<th width="25%">Amount</th>
							</tr>
						<?php
						foreach($records4 as $pr) :?>
							<tr><td><?php echo $pr->ins_name; ?></td><td><?php echo  date('d-M-Y',strtotime($pr->from_date))." to ".date('d-M-Y',strtotime($pr->to_date)); ?></td><td><?php echo date('d-M-Y',strtotime($pr->pay_date)); ?></td><td><?php echo $pr->installment_amount; ?></td></tr>

							<?php 
						endforeach;
						?>	
						<tr style="height:10%"><td colspan="4"><h5 align="left">Please note that all the equipments and units should be normal working condition at the time of take over for maintenance contract.</h5>
						</td></tr>	
						
						</table>
						<br/><br/>
						
						<table   width='100%' style='font-size: 12px; border-collapse: collapse;page-break-inside: avoid;'>
							<tr><th colspan ="2"><h3 align="left">CUSTOMER SUPPORT CONTACT NUMBERS:</h3></th></tr>
							<tr style="border:1px solid #ccc">
								<td>CUSTOMER SUPPORT EXECUTIVE</td><td>050 1197589 / 065636589<br/>E-mail : service@catalystgroup.ae</td>
							</tr>
							<tr  style="border:1px solid #ccc">
								<td>FACILITY ENGINEER</td><td>052 6421672</td>
							</tr>
							<tr  style="border:1px solid #ccc">
								<td>MANAGER</td><td>050 8999725</td>
							</tr>
							
						</table>
					
					
						<table style='font-size: 12px;page-break-inside: avoid;'>
							<tr><th colspan ="3"><h3 align="left">Duration:-</h3></th></tr>
							<tr><td>
						The duration of this agreement shall commence <?php echo $amc_start_date;?> and continue until <?php echo $amc_end_date;?>. Subject to the terms and conditions of the agreement with the
mutual consent of both parties, the agreement may be renewed from year to year unless terminated under the termination clauses stated here after.
					</td></tr>
							<tr><th><h3 align="left">Conditions:-</h3></th></tr>
							<tr><td>
						

							<?php 
							$conditions_array = explode("\n", $row->conditions);
							echo '<ol>';
							foreach ($conditions_array as $condition) {
								
								$condition = trim($condition);
								if (!empty($condition)) {
									echo '<li>' . htmlspecialchars($condition) . '</li>';
								}
							}
							echo '</ol>';
							?>



					</td></tr>
						<tr><th><h3 align="left">Exclusions :-</h3></th></tr>
							<tr><td>
							<?php 
							$exclusions_array = explode("\n", $row->exclusions);
							echo '<ol>';
							foreach ($exclusions_array as $exclusion) {
								
								$exclusion = trim($exclusion);
								if (!empty($exclusion)) {
									echo '<li>' . htmlspecialchars($exclusion) . '</li>';
								}
							}
							echo '</ol>';
							?>
					</td></tr></table>

					<br><br>	
			
			<table border='0' width='97%' cellpadding='0' cellspacing=0>
				<thead>
					<tr height="50px" style="font-family:Arial; font-size: 11px; font-weight: bold; text-align: center; ">
						
							
							


						<td align='left' width="45%">
							<?php if($company_id==1){ ?>
								<b>For & on behalf of Catalyst A/C Units Fix Cont.</b>
							<?php }else{?> 
								<b>For & on behalf of Catalyst Technical Services L.L.C	</b>
							<?php } ?></td>
						<td></td>
						<td align="left" width="30%">For <?php echo $customer_name; ?></td>
					</tr>
					<tr height="50px">				
						<td align='left'></td><td></td>
					</tr>
					<tr height="50px" style="font-family:Arial; font-size: 11px; font-weight: bold; text-align: center; ">
						
						<td align='left'>Authorized Signatory</td>
						<td></td>
						<td align='left'>Authorized Signatory</td>
					</tr>
				</thead>
			</table>
    
							</div>
			<div class="page-break"></div>
			<footer>
				<img src="<?php echo base_url() ?>public/logo/Footer.jpg" alt='Footer Image' width='100%'>
			</footer>
</body>


	
</html>






