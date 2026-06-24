<?php
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=rfq_report.xls");
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
//foreach ($records as $row) {
    //$branch = $row->branch_id;
//}
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
// Extract company details
foreach ($comapny_records as $row) {
    $company_name    = $row->company_name;
    $company_address = $row->company_address;
    $company_city    = $row->company_city;
    $company_pincode = $row->company_pincode;
    $company_country = $row->company_country;
    $company_email   = $row->company_email_id;
    $company_phone   = $row->company_telephone;
    $company_website = $row->company_website;
    $company_TRN     = $row->company_TRN;
}
?>

<html>
<body>
    <table  width="100%" cellpadding="13" border="0">
        <tr>
            <td colspan="10">
                <img src="<?= base_url() ?>public/header/<?= $branch ?>.png" width=<?=isset($customer_details)?"68%":"70%"?> height="20%" />
            </td>
        </tr>
    </table>

    <br><br><br><br><br><br><br>
    <div id="printable-content">
        <table width="100%" cellpadding="13" border="1">
            <tr>
                <td colspan="4" align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title?></b>
                </td>
            </tr>
        </table>
       <table width="100%" border=1 cellspacing="0" colspacing="0">
		<tr align="center">
			<th>Todays Date : <?php echo date('d-M-Y'); ?></th>
			<th> From Date : <?php echo $from; ?> </th>
			<th> To Date : <?php echo $to; ?> </th>
            <th></th>
		</tr>
	</table>
	<br>
       <table width='100%' border=1 cellspacing="0" colspacing="0">
       <thead>
            <tr>
                <th>Sr.no</th>
                <th>RFQ Code</th>
                <th>Date</th>
                <th>Supplier</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($records)) : ?>

            <?php $i = 1;
            foreach ($records as $row) : ?>
                <tr>
                    <td><?php echo $i;
                        $i++; ?></td>
                    <td>
                        <?php echo $row->rfq_code; ?>
                    </td>
                    <td><?php echo date('d-M-Y', strtotime($row->rfq_date)); ?></td>
                    <td>
                        <?php echo $row->supplier_name; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>

        </tbody>

	</table>
</body>

</html>
