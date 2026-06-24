<?php
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename=enquiry_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
$this->load->helper('menu_helper.php');

$logo_path = FCPATH . 'public/logo/logo.jpeg';
$logo1 = '';
if (file_exists($logo_path)) {
    $type = pathinfo($logo_path, PATHINFO_EXTENSION);
    $data = file_get_contents($logo_path);
    $logo1 = 'data:image/' . $type . ';base64,' . base64_encode($data);
}

$branch = '2';
// foreach ($records as $row) {
//     $branch = $row->branch_id;
// }
?>
<style>
    table {
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 14px;
        width: 100%;
    }
    th, td {
        border: 1px solid #000;
        padding: 10px;
        vertical-align: top;
    }
    thead th {
        background-color: #E8E8E8;
    }
    .section-header {
        background-color: #94C973;
        font-weight: bold;
    }
    .text-center {
        text-align: center;
    }
</style>
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
	<body style="font-family: Arial; font-size: 12px;">
    <table cellpadding="8" border="0">
        <tr>
            <td>
                <img src="<?= base_url() ?>public/header/2.png" width="65%" height="20%" />
            </td>
        </tr>
    </table>
	<br><br><br><br><br><br><br>
	
    <div id="printable-content">
        <table width="100%" cellpadding="6" border="1">
            <tr>
                <td colspan="7" align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title ?></b>
                </td>
            </tr>
        </table>
       

    <div style="border: 1px solid #000;" >   
       <table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr align="center">
			<th colspan="3">Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th colspan="2"> From Date : <?php echo $from; ?> </th>
			<th colspan="2"> To Date : <?php echo $to; ?> </th>
		</tr>
	</table>
       <table width='100%' border=1 cellspacing="0" colspacing="0">
		<thead>
			<tr>
				<th>Sr.no</th>
				<th>Enq Code</th>
				<th>Date</th>
				<th>Customer</th>
				<th>Client Ref</th>
				<th>Enquiry Source</th>
				<th>Enquiry Type</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($records)) : ?>
				<?php $i = 1;
				foreach ($records as $row) : ?>
					<tr>
						<td><?php echo $i;$i++; ?></td>
						<td>
							<?php echo $row->enquiry_id . '/1'; ?>
							<?php echo $row->enquiry_code; ?><br>
						</td>
						<td>
							<?php echo date('d-M-Y', strtotime($row->enq_date)); ?><br>
						</td>
						<td>
							<?php echo $row->cust_name; ?>
						</td>
						<td><?php echo $row->client_ref; ?></td>
						<td><?php echo $row->enq_source; ?></td>
						<td><?php echo $row->enq_type; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</body>

</html>
