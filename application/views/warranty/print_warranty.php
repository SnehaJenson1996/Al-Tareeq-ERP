<html>
	<head>
		<title>
			    Warranty Claim
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
                        <td> <b>WARRANTY CLAIM</b> </td>
                    </tr>
				</table>
			    <table cellpadding=5 width=100% style='border-collapse: collapse;font-size: 12px;border-left:1px solid black;border-right:1px solid black;border-top:0;border-bottom:0'>
                    
                    <tr><td >Doc No:<?php echo $warranty['warranty_code']; ?></td></tr>
                    <tr><td> Date:<?php echo $warranty['warranty_date']; ?></td></tr>
				</table>
				<table cellpadding=5 border=1 width=100% style='border-collapse: collapse;font-size: 13px;text-align:center'>
					<thead>
                         <tr style='text-align:center' class='gray-row'>
                                    <td style='width:2%'>Sl No</td>
                                    <td style='width:21%'>Model/Details</td>
                                    <td style='width:5.3%'>Description</td>
                                    <td style='width:8.3%'>Qty</td>
                                    <td style='width:8.3%'>Serial No</td>
                                    <td style='width:5.3%'>Remarks</td>
                            </tr>
						
					</thead>
					<tbody>
                        <?php $sl_no=1;foreach($warranty_details as $detail){ ?>
                         <tr valign='top' <?php if($sl_no%2 ==0) echo 'class=gray-row';  ?>>
                                    <td><?php echo $sl_no; ?></td>
                                    <td style="font-weight:bold;font-style:normal"><?php echo $detail->item_model; ?></td>
                                    <td><?php echo $detail->item_description; ?></td>
                                     <td>1</td>
                                      <td><?php echo $detail->issued_serial; ?></td>
                                    <td><?php echo $detail->remarks; ?></td>
                                    
                                </tr>
						<?php $sl_no++;} ?>
                    </tbody>
			
				</table>
                <table cellpadding=5 border=1 class='outer-border-only' width=100% style='border-collapse: collapse;font-size: 12px;'>
                    
                    <tr><td width=50%></td><td width=50%> Receiver Name:<?php //echo $dn['dn_code']; ?></td></tr>
                    <tr><td width=50%></td><td width=50%> Contact No:<?php //echo $dn['dn_code']; ?></td></tr>
                    <tr><td width=50%></td><td width=50%> Signature:<?php //echo $dn['dn_code']; ?></td></tr>
				</table>
                <p style='background-color:#dbdad5'>Thank you for your business</p>
