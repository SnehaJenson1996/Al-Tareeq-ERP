<?php  $this->load->helper('menu_helper.php');?>




<html>
	<head>
		<title>
			Proforma Invoice
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
                            height:50px;
                            
            }
        
        </style>
	</head>
	<body style="margin-left: 5px; margin-top:5px; font-family:Arial;">
		<center>
			<main>	
				
			<table cellpadding=5 width=100% border=1 style='border-collapse:collapse;font-size: 10px;'>
                    <tr style='text-align:center'>
                        <td colspan=3>
                            PROFORMA INVOICE
                        </td>
                    </tr>
					<tr>
						<td rowspan=3 width=50%>
							<img src="<?php echo base_url().'public/header/pi_header.png'?>" alt='logo.png'>
						</td>
						<td>PI No<br><?php echo $sales_order['pi_code']; ?></td>
						<td>Dated<br><?php echo $sales_order['pi_date']; ?></td>
					</tr>
					<tr>
						<td>Delivery Note<br><?php //echo $sales_order['pi_code']; ?></td>
						<td>DO Date<br><?php //echo $sales_order['pi_date']; ?></td>
					</tr>
					<tr>
						<td>Supplier's Ref<br><?php echo $sales_order['supplier_ref']; ?></td>
						<td>Other Ref<br><?php echo $sales_order['other_ref'];?></td>
					</tr>
					<tr>
                        <td rowspan=4>
                            Buyer:<br>
                            M/s: <?php echo $sales_order['customer_name']; ?><br>
                            <?php echo $sales_order['customer_address']??''; ?><br>
                            Emirate :<?php echo $sales_order['customer_emirate']??''; ?><br>
                            Email:<?php echo $sales_order['customer_email']??''; ?><br>
                            Country :<?php echo $sales_order['customer_country']??''; ?><br>
                            TRN :<?php echo $sales_order['customer_trn']??''; ?>
                        </td>
						<td>Buyers Order No<br><?php //echo $sales_order['supplier_ref']; ?></td>
						<td>Dated<br><?php //echo $sales_order['other_ref']; ?></td>
					</tr>
					<tr>
						<td>Dispatch documnet No<br><?php echo $sales_order['dispatch_document_number']; ?></td>
                        <td>Mode/Terms of Payment<br><?php echo $sales_order['payment_terms']; ?></td>
					</tr>
                    <tr>
                        <td>Dispatched through<br><?php echo $sales_order['dispatch_through']; ?></td>
                        <td>Destination<br><?php echo $sales_order['destination']; ?></td>
                    </tr>
                    <tr>
                        <td colspan=2>Terms of Delivery<br><?php echo $sales_order['delivery_terms']; ?></td>
                    </tr>
					
										
				</table>
				<table border=1 width=100% style='border-collapse: collapse;font-size: 10px'>
					<thead>
                         <tr  style='text-align:center'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:21%'>Description of Goods and Services</td>
                                    <td style='width:5.3%'>Qty</td>
                                    <td style='width:8.3%'>Rate(Incl VAT)</td>
                                    <td style='width:8.3%'>Rate</td>
                                    <td style='width:5.3%'>per</td>
                                    <td style='width:8.3%'>Amount</td>
                                    <td style='width:8.3%'>VAT%</td>
                                    <td style='width:8.3%'>Taxable Value (AED)</td>
                                    <td style='width:8.3%'>VAT (AED)</td>
                                    <td style='width:8.3%'>Total Incl VAT (AED)</td>
                                    <td style='width:8.3%'>Availability</td>
                            </tr>
					</thead>
					<tbody>
                        <?php $sl_no=1;$subtotal=0;$additional_discount=0;$total_vat=0;$total_qty=0;$total_taxable=0;$grand_total=0;?>
						<?php $i=1;foreach($pi_details as $detail){
                                $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                $vat_percent = $sales_order['vat_percent']/100;
                                $actual_price = $detail->actual_price;
                                $discount1_percent = $detail->discount1_percent/100;  
                                $discount2_percent = $detail->discount2_percent/100; 
                                $quantity = $detail->pi_quantity; $total_qty += $quantity;
                                if($discount1_percent > 0)
                                    $discount1_amount = ($actual_price * $discount1_percent) * $quantity;
                                if($discount2_percent > 0)
                                    $discount2_amount = (($actual_price * $quantity)-$discount1_amount) * $discount2_percent ;
                                $unit_price = $actual_price - ($actual_price*$discount1_percent);
                                $taxable_amount = $unit_price * $quantity;
                                $total_taxable +=  $taxable_amount;
                                $vat_amount = $taxable_amount * $vat_percent;
                                $total = $taxable_amount + $vat_amount;
                                $subtotal += $taxable_amount;
                                $additional_discount += $discount2_amount;
                                $total_vat+=$vat_amount;
                                $grand_total += $total;
                        ?>
							    <tr class='<?php if($sl_no %2 != 0) echo 'gray-row'; ?>' valign='middle' style='height:50px'>
                                    <td><?php echo $sl_no; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model.'-'.$detail->item_description; ?></td>
                                    <td><?php echo $quantity; ?></td>
                                    <td><?php echo $unit_price; ?></td>
                                    <td><?php echo $unit_price; ?></td>
                                    <td><?php echo $detail->unit_name; ?></td>
                                    <td><?php echo number_format($taxable_amount,2); ?></td>
                                    <td><?php echo $sales_order['vat_percent']; ?></td>
                                    <td><?php echo number_format($taxable_amount,2); ?></td>
                                    <td><?php echo number_format($vat_amount,2); ?></td>
                                    <td><?php echo number_format($total,2); ?></td>
                                    <td><?php if($detail->allocated_quantity>0){if($detail->allocated_quantity >= $detail->pi_quantity) echo 'Ex-Stock';else if($detail->allocated_quantity < $detail->pi_quantity) echo 'Partial';}else { echo 'No-Stock';} ?></td>
                                    
                                </tr>
						<?php $sl_no++;} ?>
                        <?php for($i=0;$i<$extra_rows;$i++){ ?>
                             <tr class='<?php if($sl_no %2 != 0) echo 'gray-row'; ?>' valign='middle' style='height:50px'>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                        <?php $sl_no++;} ?>
						<?php if($additional_discount>0){ ?>
                                    <?php $discount_vat = $additional_discount * $vat_percent; 
                                    $total_taxable -= $additional_discount;
                                    $total_vat -= $discount_vat;
                                    $grand_total -= ($additional_discount+$discount_vat);
                                    ?>
                                    <tr class='calc' style="font-weight:bold;height:50px" valign='middle'>
                                    <td></td>
                                    <td>SPECIAL DISCOUNT</td>
                                    <td>1</td>
                                    <td><?php echo '-'.($additional_discount+$discount_vat); ?></td>
                                    <td><?php echo '-'.$additional_discount; ?></td>
                                    <td></td>
                                    <td><?php echo '-'.$additional_discount; ?></td>
                                    <td><?php echo $sales_order['vat_percent']; ?></td>
                                    <td><?php echo '-'.$additional_discount; ?></td>
                                    <td><?php echo $discount_vat; ?></td>
                                    <td><?php echo '-'.($additional_discount+$discount_vat); ?></td>
                                    <td></td>
                                    
                                </tr>
                        <?php } ?>
                        <tr class='calc gray-row' style="font-weight:bold;height:50px" valign='middle'>
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo ($total_qty); ?></td>
                                     <td></td>
                                      <td></td>
                                    <td></td>
                                    <td><?php echo number_format($total_taxable,2); ?></td>
                                    <td></td>
                                    <td><?php echo number_format($total_taxable,2); ?></td>
                                    <td><?php echo number_format($total_vat,2); ?></td>
                                    <td><?php echo number_format($grand_total,2); ?></td>
                                    <td></td>
                                    
                        </tr>
					</tbody>
			
				</table>
				<table width=100% border=1 style='border-collapse:collapse;font-size:10px;'>
					<tr>
					<td>
                        <table cellpadding=5 border=0 width=100% style='border:0;'>
                            <tr>
                                <td width='50%'>
                                    <table width=100% valign='top'>
                                        <tr>
                                            <td>Amount chargeable in words:<br><b><?php echo convert_number_to_words($grand_total); ?></b></td>
                                        </tr>
                                        <tr>
                                           <td>Vat Amount in words:<br><b><?php echo convert_number_to_words($total_vat); ?></b></td>
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
                                            <td width=20%></td><td style='text-align:center' width=60%><br>Taxable Value(AED)</td>
                                            <td width=20%></td>
                                            <td width=20%><br><?php echo number_format($total_taxable,2); ?></td>
                                        </tr>
                                        <tr>
                                           <td width=20%></td><td style='text-align:center' width=60%>Vat (AED):</td><td width=20%></td><td width=20%><?php echo number_format($total_vat,2); ?></td>
                                        </tr>
                                        <tr>
                                           <td width=20% ></td><td style='text-align:center' class='top-line' width=60%>Total (AED):</td>
                                           <td width=20% class='top-line'></td>
                                           <td width=20% class='top-line'><?php echo number_format($grand_total,2); ?></td>
                                        </tr>
                                        <tr>
                                            <td width=20%></td><td colspan=2><br>Company's bank detail:<br>
                                                Bank Name:<br>
                                                Account Name:<br>
                                                Account No:<br>
                                                IBAN:<br>
                                                Branch&Swift Code:<br>
                                            </td><td width=20%></td><td width=20%></td>
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




