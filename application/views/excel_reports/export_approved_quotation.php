<?php
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename=Invoice_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<html>
<body>
	<table width="100%" border=0 cellspacing="0" colspacing="0">
		<tr align="center">
			<td>
				<p style="font-size:16px; font-weight:bold;">Export Invoice Report</p>
			</td>
			
		</tr>
	</table>
       <table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr align="center">
			<th>Todays Date : </th>
			<th> From Date : </th>
			<th> To Date :  </th>
		</tr>
	</table>
	<br>
       <table width='100%' border=1 cellspacing="0" colspacing="0">
		<thead>
			<tr>
				<th>Sr.no</th>
				<th>Invoice</th>
				<th>Quotation</th>
				<th>Customer</th>
				<th>Grand Total</th>
				</thead>
                <tbody>
                <tr>
                    <td>
                        </td>
                    <td>
                       <br>
                        
                    </td>
                    <td>
                        <br>
                        
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            <
        </tbody>
	</table>
</body>

</html>
