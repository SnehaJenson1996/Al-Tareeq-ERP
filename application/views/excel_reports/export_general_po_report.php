<?php
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=po_report.xls");
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
                <img src="<?= base_url() ?>public/header/<?= $branch ?>.png" width="62%" height="20%" />
            </td>
        </tr>
    </table>

    <br><br><br><br><br><br><br>
    <div id="printable-content">
        <table width="100%" cellpadding="13" border="1">
            <tr>
                <td colspan=<?= !isset($customer_details)?"5":"4"?> align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title?></b>
                </td>
            </tr>
        </table>
    <table width="100%" border=1 cellspacing="0" colspacing="0">
        <tr align="center">
            <th colspan="2">Todays Date : <?php echo date('d-M-Y'); ?></th>
            <th colspan="2"> From Date : <?php echo $from; ?> </th>
            <th> To Date : <?php echo $to; ?> </th>
            
        </tr>
    </table>
    <br>
    <table width='100%' border=1 cellspacing="0" colspacing="0">
        <thead>
            <tr>
                <th>Sr.no</th>
                <th>PO Code</th>
                <th>PO Date</th>
                <th>Supplier & Ref No</th>
                <th>Grand total</th>

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
                        <?php echo $row->po_code; ?><br>
                        <?php $ev = $row->revision;
                        if ($row->revision > 0) {
                            for ($k = 1; $k <= $ev; $k++) { ?>
                                <u><a target='_blank' href="<?php echo base_url() . 'index.php/Purchase/PO_print/' . $row->po_id . '/' . $k . '/0'; ?>" title="View Revision">Revision <?php echo $k; ?></a></u><br>
                        <?php }
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo date('d-M-Y', strtotime($row->po_date)); ?>
                    </td>
                    </td>
                    <td>

                        <?php echo $row->supplier_name; ?>

                        <br>
                        <?php echo $row->supplier_ref; ?>
                    </td>
                    <td><?php echo $row->grand_total; ?>

                    </td>

                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</body>

</html>