<html>
	<head>
		<title>
			Enquiry Report
		</title>
	</head>
	<body style="margin-left: 5px; margin-top:5px; font-family:Arial;font-size: 12px;text-align:center">
	    <table width=100% style='border: 0'>
			
			<tbody id="table-body">
                <tr  class='calc'>
                    <td>
                        <table cellpadding=5 width=95% style='font-size: 20px;border:0;text-align:center'>
                            <tr height='22px'>
                                <td width=100% style="color:e8b41a">Enquiry Report</td>
                            </tr>
                        </table>
                    </td>
                </tr>
				<tr  class='calc'>
					<td>
                        <table cellpadding=5 border=0 width=95% style=' border-collapse: collapse;border:0'>
                            <tr>
                                <td width='60%'>
                                    <table width=100% style='font-size: 12px;'>
                                        <tr>
                                            <td align="center">Enquiry Report from <?php echo $_GET["from_date"]; ?> to <?php echo $_GET["to_date"]; ?></td>
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
                        <table cellpadding=5 border=0 width=95% style='font-size: 12px; border-collapse: collapse;border:0'>
                            <tr>
                                <td>Prepared by:<?php ?></td>
                            </tr>
                        </table>
                    </td>   
                </tr>
				<tr>
					<td>
                        <table cellpadding=10 width=100% style='font-size: 12px; border-collapse:collapse;border:1px solid'>
                            <thead>
                    <tr>
                      <th>Sl.no</th>
                      <th>Enquiry Code</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Project</th>
                      <th>Sales person</th>
                      <th>Created by</th>
                      <th>Last updated by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;
                    foreach($records as $row) :?>
                    <tr>
						            <td><?php echo  $i; $i++;?></td>
                        <td><?php echo $row->enquiry_code; ?></td>
                        <td><?php echo $row->enquiry_date; ?></td>
                        <td><?php echo $row->customer_name; ?></td>
                        <td><?php echo $row->project_name; ?></td>
                        <td><?php echo $row->sales_person; ?></td>
                        <td><?php echo $row->created; ?></td>
                        <td><?php echo $row->last_updated; ?></td>
                    </tr>
                    
                  <?php endforeach; ?>
                  
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






