<html>
	<head>
		<title>
			Purchase Order Report
		</title>
        <style>
        @page {
            margin: 10mm 10mm 25mm 10mm;

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }

            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Al Adel Automatic Doors TR. LLC, Designed and developed by Concepts 360 Plus";
            }
        }
    </style>
	</head>
	<body style="margin-left: 5px; margin-top:5px; font-family:Arial;font-size: 12px;text-align:center">
	    <table width=100% style='border: 0'>
            <thead>		
				<th>					
                                   <img src="<?= $headerPath ?>" alt="Company Logo" class="header-img">
				</th>
			</thead>
			<tbody id="table-body">
                <tr  class='calc'>
                    <td style="background-color:#BFDBF2;">
                        <table cellpadding=5 width=95% style='font-size: 15px;border:0;text-align:center'>
                            <tr height='25px' >
                                <td width=100% style="color:black">GRN Report (<?php echo $_GET["from_date"]; ?> to <?php echo $_GET["to_date"]; ?>)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
				
                <tr class='calc' height=5px style="background-color: #525453;"><td></td></tr>
                <tr class='calc'>
                <td>
                        <table cellpadding=5 border=0 width=95% style='font-size: 12px; border-collapse: collapse;border:0'>
                            <tr>
                                <td>Prepared by:<?php ?></td>
                            </tr>
                        </table>
                    </td>   
                </tr>
				<tr>
					<td>
                       <table cellpadding="8" width="100%"
       style="font-size:12px; border-collapse:collapse; border:1px solid #000;">
    
    <thead>
        <tr style="font-weight:bold; background:#f2f2f2;">
            <th style="width:5%; border:1px solid #000; text-align:center;">Sl No</th>
            <th style="width:15%; border:1px solid #000;">GRN Code</th>
            <th style="width:15%; border:1px solid #000;">GRN Date</th>
            <th style="width:35%; border:1px solid #000;">Supplier</th>
            <th style="width:20%; border:1px solid #000; text-align:right;">Grand Total</th>
        </tr>
    </thead>

    <tbody>
        <?php 
        $sl_no = 1;
        $total_grand = 0;

        foreach ($records as $detail): 
            $total_grand += $detail->grand_total;
        ?>
            <tr>
                <td style="border:1px solid #000; text-align:center;">
                    <?php echo $sl_no++; ?>
                </td>
                <td style="border:1px solid #000;">
                    <?php echo $detail->grn_code; ?>
                </td>
                <td style="border:1px solid #000;">
                    <?php echo $detail->grn_date; ?>
                </td>
                <td style="border:1px solid #000;">
                    <?php echo $detail->supplier_name; ?>
                </td>
                <td style="border:1px solid #000; text-align:right;">
                    <?php echo number_format($detail->grand_total, 2); ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- Total row -->
        <tr style="font-weight:bold;">
            <td colspan="4"
                style="border:1px solid #000; text-align:right;">
                Total
            </td>
            <td style="border:1px solid #000; text-align:right;">
                <?php echo number_format($total_grand, 2); ?>
            </td>
        </tr>
    </tbody>
</table>
	</td>
	</tr>
               
               
            	
			</tbody>
			<tfoot class='footer'>		
				
			</tfoot>
		</table>
	</body>
</html>






