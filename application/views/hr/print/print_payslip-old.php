<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 40px;
            font-size: 16px;
        }

        table {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 1px;
        }
        @media print {
    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
    }
}
    </style>


</head>

<body onload="window.print();">
    <?php $j = 0; if (!empty($records)) :
        foreach ($records as $row) :
    ?>
            <div class="border-all">
                <table border="0px"  width="100%">
                    <tr>
			    <td align='centre'>			<img src="<?php echo base_url(); ?>public/logo/print_header.png" alt="Company Header" style="width: 95%;">
</td>
                
                    </tr>
            	</table>
		<br><br>
                <table border="0px" style="font-size: 18px;" width="100%" >
                    <tr style=" background-color:  #8AB645;">
                                <td align="center" style="font-size: 25px;">Payslip</td>
                    </tr>
            	</table>

                <table border="0px" style="font-size: 18px;" width="100%">
                    <tr>
                        <td colspan="2">
                            <div class="border-all">
                                <table cellspacing="0px" cellpadding="0px" width="100%" class="text_font">
                                    <tr>
                                        <th>General Information</th>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <!-- Proper nesting of the last table within the structure -->
                            <div class="border-all">
                                <table cellspacing="4px" cellpadding="0px" width="100%" height="80px" class="table-bordered" style="border: 1px solid black;">
                                    <tbody>

                                        <tr>
                                            <th>Employee Name:</th>
                                            <td><?php echo $row->user_name; ?></td>
                                            <th>Joining Date:</th>
                                            <td><?php echo date('d-M-Y', strtotime($row->joining_date)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Employee Number:</th>
                                            <td><?php echo $row->user_code; ?></td>
                                            <th>Payment month:</th>
                                            <td><b><?php echo date('M-Y',strtotime($row->salary_month)); ?></b></td>
                                        </tr>
                                        <tr>
                                            <th>Designation:</th>
                                            <td><?php echo $row->designation_name; ?></td>
                                            <th>Department:</th>
                                            <td><?php echo $row->dept_name; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mobile No:</th>
                                            <td><?php echo $row->contact_no; ?></td>
                                            <th>Email Id:</th>
                                            <td><?php echo ''; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
		    
                    <tr>
                        <td>
                            <div class="border-all">
                                <table cellspacing="0px" cellpadding="0px" width="100%" class="">
                                    <tr style="background-color:gray;">
                                        <td align="center"><b>Salary Details</b></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <table width='100%' border=1 cellspacing="4px" cellpadding="0px">
                	<tr>
                		<td>Working Days</td>
                		<td><?php echo $row->working_days; ?></td>
                		<td>Leaves</td>
                		<td><?php echo $row->leave_days; ?></td>
                	</tr>
                	<tr>
                		<td>Present Days</td>
                		<td><?php echo $row->present_days; ?></td>
                		<td>Paid Leaves</td>
                		<td><?php echo $row->paid_leave; ?></td>
                	</tr>
                	<tr>
                		<td>Paid Days</td>
                		<td><?php echo $row->payment_days; ?></td>
                		<td>Overtime Amount</td>
                		<td><?php echo $row->overtime_amt; ?></td>
                	</tr>
                	<tr>
                		<td>Basic Salary</td>
                		<td><?php echo $row->basic_salary; ?></td>
                		<td></td>
                		<td></td>
                	</tr>
                </table>
                <br>
               

		 <div class="form-group row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                         <table border='1' width='100%' cellspacing="4px" cellpadding="0px">
                            <thead>
                                <tr style="background-color:gray;">
                                    <th scope="col">Allowance Type</th>
                                    <th scope="col">Allowance Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            <?php if (isset($record2)) {
foreach($record2 as $r)
			    { 
			    	if($r->allowance_type=='A'){?>
                                <tr>
                                    <td><?php echo $r->allowance_name;?></td>
                                    <td align='right'><?php echo $r->amount;?> </td>
                                </tr>
                            <?php } } } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <table border='1' width='100%' cellspacing="4px" cellpadding="0px">
                            <thead>
                                <tr style="background-color:gray;">
                                    <th scope="col">Deduction Type</th>
                                    <th scope="col">Deduction Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php if (isset($record2)) {
foreach($record2 as $r)
			    	{ 
			    	if($r->allowance_type=='D'){?>
                                <tr>
                                    <td><?php echo $r->allowance_name;?></td>
                                    <td align='right'><?php echo $r->amount;?></td>
                                </tr>
                            <?php } } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <table width='100%' border=1 cellspacing="4px" cellpadding="0px">
                	<tr>
                		<td>Total Allowances</td>
                		<td align='right'><?php echo $row->total_allowance; ?></td>
                		<td>Total Deductions</td>
                		<td align='right'><?php echo $row->total_deduction; ?></td>
                	</tr>
                	<tr>
                		<td>Gross Amount</td>
                		<td align='right'><?php echo $row->gross_salary; ?></td>
                		<td>Net Salary</td>
                		<td align='right'><?php echo $row->net_salary; ?></td>
                	</tr>
                </table>
            </div>
    <?php $j++;
        endforeach;
    endif; ?>
    
</body>
<footer>
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
	</footer>
</html>


