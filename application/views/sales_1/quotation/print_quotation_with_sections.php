<html>
	<head>
		<title>
			Quotation
		</title>
         <link rel="stylesheet" href="public/assests/quotation_print_styles.css" />
        <style>
            body {
                    font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
                    font-size:13px;
                }
            .gray-row{
                background-color:#e8e8e8;
            }
        </style>
	</head>
	<body style="margin-left: 5px; margin-top:5px;text-align:center">
	    <table width=100% style='border: 0'>
			<thead>		
				<th>					
					<img src="<?php echo base_url() ?>public/header/header.jpg" alt="Header Image" width='100%' >										
				</th>
			</thead>
			<tbody id="table-body">
                <tr  class='calc'>
                    <td>
                        <table cellpadding=0 width=100% style='border:0;text-align:center;font-weight:bold'>
                            <tr height='22px'>
                                <td width=60% style="background-color: #525453;color:#e8b41a;font-size:12px"><b>Customer Contact Details</b></td><td width=40% style="color:#e8b41a;font-size:18px"><b>SALES QUOTATION</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
				<tr  class='calc'>
					<td>
                        <table cellpadding=5 border=0 width=100% >
                            <tr>
                                <td width='60%'>
                                    <table width=100% style='border-collapse: collapse;border:0;font-size:12px'>
                                        <tr>
                                            <td width=30%>Name</td><td width=70%><?php echo $quotation['contact_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td width=30%>Company</td><td width=70%><?php echo $quotation['customer_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td width=30%>Address</td><td width=70%><?php echo $quotation['customer_address']; ?></td>
                                        </tr>
                                        <tr>
                                            <td width=30%>Contact No</td><td width=70%><?php echo $quotation['contact_phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <td width=30%>Email</td><td width=70%><?php echo $quotation['contact_email']; ?></td>
                                        </tr>						
                                    </table>
                                </td>
                                
                                <td width='40%'>
                                    <table width=100% style='border-collapse: collapse;border:0;font-size:12px'>
                                            <tr>
                                                <td width=30%></td><td width=70%></td>
                                            </tr>
                                            <tr>
                                                <td width=30%>Date:</td><td width=70%><?php echo date('d-M-Y',strtotime($quotation['quotation_date'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td width=30%>Quote#:</td><td width=70%><?php echo $quotation['quotation_code']; ?></td>
                                            </tr>
                                            <tr>
                                                <td width=30%>Customer ID:</td><td width=70%><?php echo $quotation['customer_code']; ?></td>
                                            </tr>
                                            <tr>
                                                <td width=30%>Valid Untill:</td><td width=70%><?php echo date('d-M-Y',strtotime($quotation['validity']));?></td>
                                            </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>						
					</td>
				</tr>
               <tr class='calc' height=5px style="background-color: #525453;"><td></td></tr>
                <tr class='calc'>
                    <td>
                        <table cellpadding=0 border=0 width=100% style='border-collapse: collapse;border:0;font-size:10px'>
                            <tr>
                                <td>Prepared by:<?php echo $quotation['quotation_by'];?></td><td></td><td>Sales Person:<?php echo $quotation['enquiry_by'];?></td><td></td><td>Project Details:<?php echo $quotation['project_name']; ?></td><td></td>
                            </tr>
                        </table>
                    </td>
                    
                </tr>
				<tr>
					<td>
                        <table cellpadding=10 width=100% style='font-size: 10px; border-collapse:collapse;border:2px solid'>
                            <thead>
                                <tr class='calc' style="background-color: #525453;color:#e8b41a;font-style:bold">
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:10%'>Model</td>
                                    <td style='width:3%'>Brand</td>
                                    <td style='width:18%'>Description</td>
                                    <td style='width:5%'>Qty</td>
                                    <td style='width:5%'>Unit</td>
                                    <td style='width:5%'><b>Unit Price(AED)</b></td>
                                    <td style='width:5%'><b>Taxable Amount(AED)</b></td>
                                    <td style='width:5%'>VAT% </td>
                                    <td style='width:5%'>Total(AED)</td>
                                     <?php if($quotation['print_stock']){?>
                                    <td style='width:5%'><b>Stock</b></td>
                                    <?php } ?>
                                    <?php if($quotation['print_coo']){?>
                                    <td style='width:5%'>Origin</td>
                                    <?php } ?>
                                    <?php if($quotation['print_hsc']){?>
                                    <td style='width:5%'>HS Code</td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody style='font-size: 10px;'>
                                <?php $sl_no=1;$subtotal=0;$additional_discount=0;$total_vat=0;
                                foreach($quotation_details as $section => $details) :?>
                                <tr class='calc' style="font-weight:bold"><td colspan=12><?= $section ?></td></tr>
                                <?php foreach ($details as $detail):
                                $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                $vat_percent = $quotation['vat_percent']/100;
                                $actual_price = $detail->actual_price;
                                $discount1_percent = $detail->discount1_percent/100;  
                                $discount2_percent = $detail->discount2_percent/100; 
                                $quantity = $detail->quantity; 
                                if($discount1_percent > 0)
                                    $discount1_amount = ($actual_price * $discount1_percent) * $quantity;
                                if($discount2_percent > 0)
                                    $discount2_amount = (($actual_price * $quantity)-$discount1_amount) * $discount2_percent ;
                                $unit_price = $actual_price - ($actual_price*$discount1_percent);
                                $taxable_amount = $unit_price * $quantity;
                                $vat_amount = $taxable_amount * $vat_percent;
                                $total = $taxable_amount + $vat_amount;
                                $subtotal += $taxable_amount;
                                $additional_discount += $discount2_amount;
                                $total_vat+=$vat_amount;?>
                                <tr class='calc <?php if($sl_no % 2 ==0) echo 'gray-row'; ?>' valign='middle' style='height:50px'>
                                    <td><?php echo $sl_no; ?></td>
                                    <td><?php echo $detail->item_model; ?></td>
                                    <td><?php echo $detail->brand_name; ?></td>
                                    <td><?php echo $detail->item_description; ?></td>
                                    <td><?php echo $detail->quantity; ?></td>
                                    <td><?php echo $detail->unit_name; ?></td>
                                    <td><?php echo $unit_price; ?></td>
                                    <td><?php echo $taxable_amount; ?></td>
                                    <td><?php echo $quotation['vat_percent'].'%'; ?></td>
                                    <td><?php echo $total; ?></td>
                                     <?php if($quotation['print_stock']){?>
                                    <td>
                                        <?php if($detail->current_stock > 0){
                                            if($detail->current_stock >= $detail->quantity) echo 'Ex-Stock'; else echo 'Partial';
                                        } else{ echo 'No-Stock';} ?>
                                    </td>
                                    <?php } ?>
                                    <?php if($quotation['print_coo']){?>
                                        <td><?php echo $detail->c_o_o; ?></td>
                                    <?php } ?>
                                    <?php if($quotation['print_hsc']){?>
                                        <td><?php echo $detail->hs_code; ?></td>
                                    <?php } ?>
                                </tr>
                                <?php $sl_no++;endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
					</td>
				</tr>
                <tr class='calc'>
                <td>
                        <table cellpadding=5 border=0 width=100% style='border-collapse: collapse;border:0'>
                            <tr style='height:40px'>
                            <td rowspan=4 width=50% style='font-size:12px;font-style:bold' ><?php if(!empty($quotation['notes'])) echo 'Note: '.$quotation['notes']; ?></td><td width=20% style="text-align:center">Subtotal</td><td width=10% style=""><?php echo number_format($subtotal,2) ?></td>
                            </tr>
                            <?php if($additional_discount > 0){ ?>
                            <tr style='height:50px'>
                            <td width=30% style="background-color:#e8e8e8;text-align:center">Additional Discount</td><td width=20% style="background-color:#e8e8e8"><?php echo number_format($additional_discount,2) ?></td>
                            </tr>
                            <?php } ?>
                            <tr style='height:50px'>
                            <td width=30% style="background-color:#e8e8e8;text-align:center" >VAT(<?php echo $quotation['vat_percent']; ?>%)</td><td width=20% style="background-color:#e8e8e8"><?php echo number_format($total_vat,2) ?></td>
                            </tr>
                            <tr style='height:50px'>
                            <td width=30% style="background-color:#9c988e;text-align:center">TOTAL-<?php echo $quotation['currency_abbr']; ?></td><td width=20% style="background-color:#9c988e"><?php echo number_format($quotation['grand_total'],2) ?></td>
                            </tr>
                        </table>
                    </td>
                    
                </tr>
                <tr class='calc'>
                <td>
                        <table border=0 cellpadding=15 width=100% style='border-collapse: collapse;border:0'>
                            <tr>
                            <td width=50% style="background-color: #525453;color:e8b41a;">TERMS AND CONDITIONS</td><td width=50%></td>
                            </tr>
                        </table>
                </td>
                </tr>
                <tr class='calc'>
                <td>
                        <table cellpadding=15 width=100% style='border-collapse: collapse;border:0;font-weight:bold;font-size:12px;'>
                            
                            <tr>
                            <td width=20%>PAYMENT</td><td>:<?php echo $quotation['payment']; ?></td>
                            </tr>
                            <tr>
                            <td width=20%>DELIVERY</td><td>:<?php echo $quotation['delivery']; ?></td>
                            </tr>
                            <?php $quotation_date = new DateTime($quotation['quotation_date']); $quotation_validity = new DateTime($quotation['validity']);?>
                            <tr>
                            <td width=20%>VALIDITY</td><td>:<?php echo $quotation_date->diff($quotation_validity)->days; ?> DAYS</td>
                            </tr>
                            <tr>
                            <td width=20%>AVAILABILITY</td><td>:<?php echo $quotation['availability']; ?></td>
                            </tr>
                            <tr>
                            <td width=20%>WARRANTY</td><td>:<?php echo $quotation['warranty']; ?></td>
                            </tr>
                            <tr>
                            <td colspan=2 style='font-weight:normal'>
                                <?php echo nl2br($quotation['conditions']); ?>
                            </td>
                            </tr>
                        </table>
                    </td>
                    
                </tr>
                <tr class='calc' height=5px style="color: #e8b41a;text-align:center">
                    <td>Thank you for giving us the opportunity to quote you. We appreciate your business with us.</td>
                </tr>
                
                
				
			</tbody>
			<tfoot class='footer'>		
				
			</tfoot>
		</table>
	</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>






