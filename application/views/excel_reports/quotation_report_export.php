<?php
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename=quotation_report.xls");
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
                <img src="<?= base_url() ?>public/header/2.png" width="69%" height="20%" />
            </td>
        </tr>
    </table>
	<br><br><br><br><br><br><br>
	
    <div id="printable-content">
        <table width="100%" cellpadding="5" border="1">
            <tr>
                <td colspan="7" align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title ?></b>
                </td>
            </tr>
        </table>
       

    <div style="border: 1px solid #000;" >  
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th align="center" >Today's Date: <?= date('d-M-Y'); ?></th>
        <th colspan="3">From Date: <?= $from; ?></th>
        <th colspan="3">To Date: <?= $to; ?></th>
    </tr>
    <tr>			
			<th> Supplier : <?= isset($customer_details)?$customer_details[0]->user_name:"" ?> </th>
			<th> Quotation : <?= $quotation_id ==0?"Cancle Quotation":(($quotation_id ==1)?"Approval Quotation":"All Quotation") ?> </th>
			<th></th>
		</tr>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Quotation Code</th>
            <th colspan="2">Date</th>
            <th>Customer & Customer Ref</th>
            <th colspan="2">Grand Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($records)) : $i = 1; ?>
            <?php foreach ($records as $row) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td>
                        <?= $row->quotation_code; ?><br>
                        <?php if ($row->revision > 0): ?>
                            <?php for ($k = 1; $k <= $row->revision; $k++): ?>
                                <u>
                                    <a target="_blank" href="<?= base_url("index.php/Sales/print_quotation/{$row->quote_id}/$k/0"); ?>" title="View Revision">
                                        Revision <?= $k; ?>
                                    </a>
                                </u><br>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </td>
                    <td colspan="2"><?= date('d-M-Y', strtotime($row->quotation_date)); ?></td>
                    <td>
                        <?= $row->cust_name; ?><br>
                        <?= $row->client_ref; ?>
                    </td>
                    <td colspan="2"><?= $row->grand_total; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center">No records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>

</html>
