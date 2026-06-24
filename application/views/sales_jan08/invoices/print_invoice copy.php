<?php  $this->load->helper('menu_helper.php');?>




<html>
	<head>
		<title>
			Tax Invoice
		</title>
        <link rel="stylesheet" href="public/assests/quotation_print_styles.css" />
        <style>
             body {
                            font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
                            font-size:13px;
            }
            td{
                font-style:italic;
            }
            .top-line{
            
                border-top: 2px solid black; 
                border-bottom: 2px solid black; 
                border-left: none; 
                border-right: none;"
            }
        .gray-row{
                background-color:#e8e8e8;
            }
        
        </style>
	</head>
	<body style="margin-left: 5px; margin-top:5px; font-family:Arial;">
		<center>
			<main>	
				
			<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 10px;'>
				<thead>
				
				</thead>
				<tbody>
                    <tr style='text-align:center;'>
                        <td colspan=3>
                            Tax Invoice
                        </td>
                    </tr>
					<tr>
						<td rowspan=3 width=50%>
							<img src="<?= $headerPath ?>" alt="Company Logo" style="width:100%; height:auto;">
						</td>
						<td>invoice Code<br><?php echo $invoice_master['invoice_code']; ?></td>
						<td>Dated<br><?php echo $invoice_master['invoice_date']; ?></td>
					</tr>
					<tr>
						<td>Delivery Mode<br><?php //echo $delivery_challan_data['delivery_mode']; ?></td>
						<td>DO Date<br><?php //echo $sales_order['pi_date']; ?></td>
					</tr>
					<tr>
						<td>Supplier's Ref<br><?php //echo $invoice['supplier_ref']; ?></td>
						<td>Other Ref<br><?php //echo $invoice['other_ref'];?></td>
					</tr>
					<tr>
                        <td rowspan=4>
                            Buyer:<br>
                            M/s: <?php echo $customer_name; ?><br>
                            <?php echo $customer_address; ?><br>
                            Emirate :<?php echo $emirate; ?><br>
                            Email:<?php echo $customer_email; ?><br>
                            <!-- Country :<?php echo $invoice['customer_country']??''; ?><br> -->
                            TRN :<?php echo $customer_trn; ?>
                        </td>
						<td>Buyers Order No<br><?php //echo $sales_order['supplier_ref']; ?></td>
						<td>Dated<br><?php //echo $sales_order['other_ref']; ?></td>
					</tr>
					<tr>
						<td>Dispatch documnet No<br><?php //echo $invoice['dispatch_document_number']; ?></td>
                        <td>Mode/Terms of Payment<br><?php //echo $invoice['payment_terms']; ?></td>
					</tr>
                    <tr>
                        <td>Dispatched through<br><?php echo $delivery_challan_data['delivery_mode']; ?></td>
                        <td>Dispatched By<br><?php echo $delivery_challan_data['deliverd_by']; ?></td>
                    </tr>
                    <tr colspan=2>
                        <td>Terms of Delivery<br><?php echo $invoice_master['delivery_term']; ?></td>
                    </tr>
					
										
				</table>
				<table border=1 width=100% style='border-collapse: collapse;font-size: 10px'>
					<thead>
                        <tr  style='text-align:center'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:21%'>Product</td>
                                    <td style='width:5.3%'>Qty</td>
                                     <td style='width:5.3%'>Unit</td>
                                    <td style='width:8.3%'>Unit price</td>
                                    <td style='width:8.3%'>Amount</td>
                                    <td style='width:8.3%'>Discount Amount</td>
                                    <td style='width:8.3%'>Taxable Amount</td>
                                    <!-- <td style='width:8.3%'>Availability</td> -->
                            </tr>
						
					</thead>
					<tbody>

						<?php $i=1;
                        foreach($invoice_products as $detail){?>
                           
							    <tr class='' valign='middle' style='height:50px'>
                                    <td style='text-align:center'><?php echo $i; ?></td>
                                    <td style="font-weight:bold;font-style:normal;text-align:center"><?php echo $detail['product_id']; ?></td>
                                    <td style='text-align:center'><?php echo $detail['deliver_quantity']; ?></td>
                                    <td style='text-align:right'><?php echo $detail['unit_id']; ?></td>
                                    <td style='text-align:right'><?php echo $detail['unit_price']; ?></td>
                                    <td style='text-align:center'><?php echo $detail['total_amount']; ?></td>
                                    <td style='text-align:right'><?php echo $detail['discount_amount']; ?></td>
                                    <td style='text-align:right'><?php echo $detail['taxable_amount']; ?></td>
                                    <!-- <td style='text-align:right'><?php if($detail->allocated_quantity>0){if($detail->allocated_quantity >= $detail->pi_quantity) echo 'Ex-Stock';else if($detail->allocated_quantity < $detail->pi_quantity) echo 'Partial';}else { echo 'No-Stock';} ?></td> -->
                                    
                                </tr>
						<?php $i++;} ?>
                        
                        
					</tbody>
			
				</table>
				<table width=100% border=1 style='border-collapse:collapse;font-size:10px;'>
					<tr>
					<td>
                        <table cellpadding=5 border=0 width=100% style='border-collapse: collapse;border:0;'>
                            <tr>
                                <td width='50%'>
                                    <table width=100%>
                                        <tr>
                                            <td>Amount chargeable in words:<br><b><?php echo convert_number_to_words($invoice_master['grand_total']); ?></b></td>
                                        </tr>
                                        <tr>
                                           <td>Vat Amount in words:<br><b><?php echo convert_number_to_words($invoice_master['vat_amount']); ?></b></td>
                                        </tr>
                                        <tr>
                                           <td></td>
                                        </tr>	
                                         <tr height=120px>
                                           <td></td>
                                        </tr>					
                                    </table>
                                </td>
                                
                                <td width='50%'>
                                    <table width=100%>
                                        
                                        
                                        <tr>
                                            <td width=20%></td><td colspan=3><br>Company's bank detail:<br>
                                                Bank Name:<?= $invoice_bank_data->bank_name ?><br>
                                                Account Name:<br>
                                                Account No:<?= $invoice_bank_data->bank_account ?><br>
                                                IBAN:<?= $invoice_bank_data->bank_iban?><br>
                                                Swift Code:<?=$invoice_bank_data->bank_swift ?>
                                            </td></td>
                                        </tr>
                                       
                                            
                                    </table>
                                </td>
                            </tr>
                        </table>						
					</td>
				</tr>
				</table>
			</main>
		</center>
	</body>
</html>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    window.print();
});
</script>






