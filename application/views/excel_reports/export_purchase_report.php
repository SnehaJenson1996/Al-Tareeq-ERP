<?php
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=Supplier_Payment.xls");
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
                <img src="<?= base_url() ?>public/header/<?= $branch ?>.png" width="100%" height="20%" />
            </td>
        </tr>
    </table>

    <br><br><br><br><br><br><br>
    <div id="printable-content">
        <table width="100%" cellpadding="13" border="1">
            <tr>
                <td colspan="10" align="center" bgcolor="#94C973">
                    <b style="font-size: 30px; color: #ffffff;"><?= $title?></b>
                </td>
            </tr>
        </table>

    <!-- Filter Info -->
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <tr align="center">
            <th colspan="4">Supplier: <?= isset($supplier_data[0]->supplier_name) ? $supplier_data[0]->supplier_name : "" ?></th>
            <th colspan="3">From Date: <?= $from; ?></th>
            <th colspan="3">To Date: <?= $to; ?></th>
        </tr>
    </table>
    <!-- Main Data Table -->
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr style="background-color:#f0f0f0;">
                <th>Sr.no</th>
                <th>PO Code</th>
                <th>PO Date</th>
                <th>Supplier & Ref No</th>
                <th>Project Name</th>
                <th>Items</th>
                <th>Qnty</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)) : ?>
                <?php 
                $i = 1;
                $last = [
                    'po_date'       => null,
                    'po_code'       => null,
                    'supplier_name' => null,
                    'supplier_ref'  => null,
                    'project_name'  => null
                ];
                $total_qty = 0;
                $total_price = 0;
                $total_amount = 0;

                foreach ($records as $row) :
                    $is_new_date     = $last['po_date'] !== $row->po_date;
                    $is_new_po_code  = $last['po_code'] !== $row->po_code;
                    $is_new_supplier = $last['supplier_name'] !== $row->supplier_name || $last['supplier_ref'] !== $row->supplier_ref;
                    $is_new_project  = $last['project_name'] !== $row->project_name;

                    $show_date     = $is_new_date;
                    $show_po_code  = $is_new_po_code;
                    $show_supplier = $is_new_supplier || $is_new_po_code || $is_new_date;
                    $show_project  = $is_new_project || $show_supplier;

                    // Update last values
                    $last['po_date']       = $row->po_date;
                    $last['po_code']       = $row->po_code;
                    $last['supplier_name'] = $row->supplier_name;
                    $last['supplier_ref']  = $row->supplier_ref;
                    $last['project_name']  = $row->project_name;

                    $total_qty    += (float)$row->quantity;
                    $total_price  += (float)$row->price;
                    $total_amount += (float)$row->total;
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td>
                        <?php if ($show_po_code): ?>
                            <?= $row->po_code ?><br>
                            <?php for ($k = 1; $k <= $row->revision; $k++): ?>
                                <u><a target="_blank" href="<?= base_url() ?>index.php/Purchase/PO_print/<?= $row->po_id ?>/<?= $k ?>/0">Revision <?= $k ?></a></u><br>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $show_date ? date('d-M-Y', strtotime($row->po_date)) : '' ?></td>
                    <td>
                        <?php if ($show_supplier): ?>
                            <?= $row->supplier_name ?><br><?= $row->supplier_ref ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $show_project ? $row->project_name : '' ?></td>
                    <td><?= $row->product_name ?></td>
                    <td><?= $row->quantity ?></td>
                    <td><?= $row->unit_name ?></td>
                    <td><?= $row->price ?></td>
                    <td><?= $row->total ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

        <!-- Footer Totals -->
        <tfoot>
            <tr style="background-color: #dcdcdc; font-weight: bold;">
                <td colspan="6" align="right">Total</td>
                <td><?= isset($total_qty)?$total_qty:"" ?></td>
                <td></td>
                <td><?=  isset($total_price)?$total_price:""  ?></td>
                <td><?=  isset($total_amount)? $total_amount:""  ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
