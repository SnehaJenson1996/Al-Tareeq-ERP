<?php  $this->load->helper('menu_helper.php');?>
<?php
$filled_rows = count($request_details);
$empty_rows = $total_rows - $filled_rows;
?>
<style>

 
 
</style>


<html>
	<head>
		<title>
			Sample Request Form
		</title>
	</head>
	<body style="margin-left: 5px; margin-top:5px; font-family:Arial;">
		<center>
			<main>	
				
			<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 10px;'>
				<thead>
				
				</thead>
				<tbody>
                    <tr style='text-align:center;'>
                        <td colspan=4>
                           Sample Request Form
                        </td>
                    </tr>
					<tr>
						<td colspan=4>
							<img src="<?php echo base_url().'public/header/pi_header.png'?>" alt='logo.png'>
						</td>
					</tr>
                    <tr><td colspan=4></td></tr>
					<tr>
						<td width=25%>Document Number</td><td width=25%><?php echo $request['request_code']; ?></td>
						<td width=25%>Document Date</td><td width=25%><?php echo date('d-m-Y',strtotime($request['request_date'])); ?></td>
					</tr>
					<tr>
						<td width=25%>Customer Name</td><td width=25%><?php echo $request['customer_name']; ?></td>
						<td width=25%>Sample status</td><td width=25%><?php //echo $sales_order['pi_date']; ?></td>
					</tr>
                    <tr>
						<td width=25%>Customer Contact</td><td width=25%><?php //echo $sales_order['pi_code']; ?></td>
						<td width=25%>Agreed Return date</td><td width=25%><?php echo date('d-m-Y',strtotime($request['return_date'])); ?></td>
					</tr>				
				</table>
				<table border=1 width=100% style='border-collapse: collapse;font-size: 15px;text-align:center'>
					<thead>
                         <tr  style='text-align:center'>
                                    <td style='width:10%'>Sl No</td>
                                     <td style='width:20%'>Brand</td>
                                    <td style='width:50%'>Description of Goods and Services</td>
                                    <td style='width:10%'>Qty</td>
                                    <td style='width:20%'>Item Code/Serial No</td>
                            </tr>
						
					</thead>
					<tbody>
                        <?php $sl_no=1;$i=1;
                        foreach($request_details as $detail){
                            if($detail->issued_qty > 0){?>
							    <tr valign='top'>
                                    <td><?php echo $sl_no; ?></td>
                                    <td><?php echo $detail->brand_name; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model.'-'.$detail->item_description; ?></td>
                                    <td><?php echo $detail->issued_qty; ?></td>
                                    <td><?php echo $detail->stock_ids; ?></td>
                                </tr>
						<?php $sl_no++;}} ?>
                        <?php for ($j = 0; $j < $empty_rows; $j++): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endfor; ?>
						<tr>
                            <td colspan=2>REQUESTED BY</td><td rowspan=5></td><td colspan=2>DIVISION MANAGER APPROVAL</td>
                        </tr>
                        <tr>
                            <td>Name : </td><td><?php echo $request['requested_by']; ?></td><td>Name : </td><td><?php echo $request['approved_by']; ?></td>
                        </tr>
                         <tr>
                            <td>Date : </td><td><?php echo date('d-m-Y',strtotime($request['created_at'])); ?></td><td>Date : </td><td><?php echo date('d-m-Y',strtotime($request['approval_date'])); ?></td>
                        </tr>
                         <tr>
                            <td>Sign : </td><td></td><td>Sign : </td><td></td>
                        </tr>
                         <tr>
                            <td>&nbsp;</td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                            <td colspan=2>ISSUED BY</td><td rowspan=5></td><td colspan=2>AUTHORIZATION FROM OPERATIONS</td>
                        </tr>
                        <tr>
                            <td>Name : </td><td><?php echo $request['issued_by']; ?></td><td>Name : </td><td></td>
                        </tr>
                         <tr>
                            <td>Date : </td><td><?php echo date('d-m-Y',strtotime($request['issue_date'])); ?></td><td>Date : </td><td></td>
                        </tr>
                         <tr>
                            <td>Sign : </td><td></td><td>Sign : </td><td></td>
                        </tr>
                         <tr>
                            <td>&nbsp;</td><td></td><td></td><td></td>
                        </tr>
                        
					</tbody>
			
				</table>
				
				</table>
			</main>
		</center>
	</body>
</html>






