<?php
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=stock_inventory.xls");
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
                <img src="<?= base_url() ?>public/header/<?= $branch ?>.png" width="35%" height="20%" />
            </td>
        </tr>
    </table>
 <br><br><br><br><br><br><br>
 <div id="printable-content">
	<table width="100%" cellpadding="13" border="1">
            <tr>
                <td colspan="3" align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title?></b>
                </td>
            </tr>
        </table>
  <table width="100%" border=1 cellspacing="0" colspacing="0">
    <tr>
      <td colspan="3">Date : <?php echo date('d-M-Y');?></td>
    </tr>
  </table>
  <br>
  
 	<table width='100%' border=1 cellspacing="0" colspacing="0">
        <thead>
			<tr>
				<th>Srn</th>
				<th>Name</th>
				<th>Stock Qty</th>
			</tr>
		</thead>

		<tbody>
		<?php $i=1; $tot1=0; $st=0;
		foreach($records as $row) :?>
			<tr>
			<td><?php echo $i;$i++;?></td>		
				<td><?php echo $row->product_name;?></td>
				<td style="text-align: right;"><?php echo $row->current_quantity; $st=$st+$row->current_quantity;?></td>
				
			</tr>
		<?php endforeach; ?>
		<tr class="bg-soft-primary">
			<th></th>
			<th>Total</th>			
			<th style="text-align: right;"><?php echo $st;?></th>
		</tr>
		</tbody>
    </table>
</body>
</html>
