<html>
	<head>
		<title>
			Delivery Note
		</title>
        <link rel="stylesheet" href="public/assests/quotation_print_styles.css" />
        <style>
             body {
                            font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
                            font-size:13px;
            }
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
                    <tr height=50px style='text-align:center;background-color:#dbdad5' >
                        <td> <b>DELIVERY NOTE</b> </td>
                    </tr>
				</table>
			    <table cellpadding=5 width=100% style='border-collapse: collapse;font-size: 12px;border-left:1px solid black;border-right:1px solid black;border-top:0;border-bottom:0'>
                    
                    <tr><td width=60%><b> To:<?php echo $dn['customer_name']; ?></b></td><td width=40%><b> Doc No:<?php echo $dn['dn_code']; ?></b></td></tr>
                    <tr><td> </td><td><b> Date:<?php echo $dn['dn_date']; ?></b></td></tr>
				</table>
				<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 13px;text-align:center'>
					<thead>
                         <tr style='text-align:center' class='gray-row'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:21%'>Model/Details</td>
                                    <td style='width:5.3%'>Brand</td>
                                    <td style='width:8.3%'>Qty</td>
                                    <!-- <td style='width:8.3%'>Serial No</td> -->
                                    <td style='width:5.3%'>Remarks</td>
                            </tr>
						
					</thead>
					<tbody>
                        <?php $sl_no=1;foreach($dn_details as $detail){ ?>
                         <tr valign='middle' <?php if($sl_no%2 ==0) echo 'class=gray-row';  ?> style='height:50px'>
                                    <td><?php echo $sl_no; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model; ?></td>
                                    <td><?php echo $detail->brand_name; ?></td>
                                     <td><?php echo $detail->delivery_quantity; ?></td>
                                      <!-- <td><?php echo $detail->stock_ids; ?></td> -->
                                    <td><?php echo $detail->delivery_remarks; ?></td>
                                    
                                </tr>
						<?php $sl_no++;} ?>
                    </tbody>
			
				</table>
                <br>
                <table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 13px;text-align:center'>
					<thead>
                         <tr style='text-align:center' class='gray-row'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:10%'>Model/Details</td>
                                    <td style='width:88%'>Serial No</td>
                            </tr>
						
					</thead>
					<tbody>
                        <?php $sl_no=1;foreach($dn_details as $detail){ ?>
                         <tr valign='middle' <?php if($sl_no%2 ==0) echo 'class=gray-row';  ?>>
                                    <td><?php echo $sl_no; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model; ?></td>
                                    <td><?php echo $detail->stock_ids; ?></td>
                                    
                                </tr>
						<?php $sl_no++;} ?>
                    </tbody>
			
				</table>
                <br>
                <table cellpadding=5 border=1 class='outer-border-only' width=100% style='border-collapse: collapse;font-size: 12px;'>
                    
                    <tr><td width=50%></td><td width=50%> Receiver Name:<?php //echo $dn['dn_code']; ?></td></tr>
                    <tr><td width=50%></td><td width=50%> Contact No:<?php //echo $dn['dn_code']; ?></td></tr>
                    <tr><td width=50%></td><td width=50%> Signature:<?php //echo $dn['dn_code']; ?></td></tr>
				</table>
                <p style='background-color:#dbdad5'>Thank you for your business</p>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    window.print();
});
</script>