<?php
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename=Notificatio_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php
foreach ($comapny_records as $row) {
	$company_name = $row->company_name;
	$company_address = $row->company_address;
	$company_city = $row->company_city;

	$company_pincode = $row->company_pincode;
	$company_country = $row->company_country;
	$company_email_id = $row->company_email_id;
	$company_telephone = $row->company_telephone;
	$company_website = $row->company_website;
	$company_TRN = $row->company_TRN;
}
?>
<html>
<body>
	<table width="100%" border=0 cellspacing="0" colspacing="0">
		<tr align="center">
			<td>
				<p style="font-size:16px; font-weight:bold;">All Notification Report</p>
			</td>
			
		</tr>
	</table>
       <table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr align="center">
			<th>Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th> From Date : <?php echo $from; ?> </th>
			<th> To Date : <?php echo $to; ?> </th>
		</tr>
	</table>
	<br>
       <table width='100%' border=1 cellspacing="0" colspacing="0">
       <thead>
            <tr>
                <th>Sr.no</th>
                <th>Msg_id</th>
                <th>Ref_id</th>
                <th>Message</th>
                <th>Msg_date</th>
                <th>Read_date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)) : ?>
                <?php $i = 1;
                foreach ($records as $row) : ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td><?php echo $row->msg_id; ?></td>
                        <td><?php echo $row->ref_id; ?></td>
                        <td><?php echo $row->message; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->msg_date)); ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row->read_date)); ?></td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

        </tbody>
	</table>
</body>

</html>
