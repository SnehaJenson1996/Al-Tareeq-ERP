<html>
	<head>
		<title>
			Sales Return
		</title>
        <style>
            .gray-row{
                background-color:#dbdad5;
            }
            .outer-border-only {
                border-collapse: collapse;
                border: 1px solid black; /* Outer border */
            }

            .outer-border-only td {
                border: none; /* Hide all inner borders */
                padding: 8px;
            }
        </style>
	</head>
	<body>
		<center>
			<main>	
				<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 15px;'>
                    <tr>
                        <td><img src="<?php echo base_url().'public/header/dn_header.png'?>" alt='logo.png' width=100%></td>
                    </tr>
                    <tr height=30px style='text-align:center;background-color:#dbdad5' >
                        <td> <b>SALES RETURN DOCUMENT</b> </td>
                    </tr>
				</table>
			    <table cellpadding=5 width=100% style='border-collapse: collapse;font-size: 12px;border-left:1px solid black;border-right:1px solid black;border-top:0;border-bottom:0'>
                    
                    <tr><td width=60%> To:<?php echo $sales_return['customer_name']; ?></td><td width=40%> Delivery Note Ref:<?php echo $sales_return['dn_code']; ?></td></tr>
                    <tr><td> </td><td> Date:<?php echo $sales_return['return_date']; ?></td></tr>
				</table>
				<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 13px;text-align:center'>
					<thead>
                         <tr style='text-align:center' class='gray-row'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:21%'>Model/Details</td>
                                    <td style='width:8.3%'>Qty</td>
                                    <td style='width:8.3%'>Rate</td>
                                    <td style='width:5.3%'>per</td>
                                    <td style='width:8.3%'>Amount</td>
                                    <td style='width:8.3%'>VAT%</td>
                                    <td style='width:8.3%'>VAT (AED)</td>
                                    <td style='width:8.3%'>Total Incl VAT (AED)</td>
                            </tr>
						
					</thead>
					<tbody>
                        <?php $sl_no=1;$subtotal=0;$additional_discount=0;$total_vat=0;$total_qty=0;$total_taxable=0;$grand_total=0;?>
                        <?php foreach($return_details as $detail){ 

                            $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                $vat_percent = $sales_return['vat_percent']/100;
                                $actual_price = $detail->actual_price;
                                $discount1_percent = $detail->discount1_percent/100;  
                                $discount2_percent = $detail->discount2_percent/100; 
                                $quantity = $detail->return_quantity; $total_qty += $quantity;
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
                            <tr valign='top' <?php if($sl_no%2 ==0) echo 'class=gray-row';  ?>>
                                    <td><?php echo $sl_no; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model.'-'.$detail->item_description; ?></td>
                                    <td><?php echo $quantity; ?></td>
                                     <td><?php echo $unit_price; ?></td>
                                     <td><?php echo $detail->unit_name; ?></td>
                                     <td><?php echo number_format($taxable_amount,2); ?></td>
                                    <td><?php echo $sales_return['vat_percent']; ?></td>
                                    <td><?php echo number_format($vat_amount,2); ?></td>
                                    <td><?php echo number_format($total,2); ?></td>
                                    
                                </tr>
						<?php $sl_no++;} ?>
                    </tbody>
			
				</table>
                
