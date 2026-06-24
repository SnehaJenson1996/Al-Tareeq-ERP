<?php  $this->load->helper('menu_helper.php');

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
<style>		
    html {
    height: 100%;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100%;
    }

	table.content-table {
      width: 100%;
      font-size: 12px;
      border-collapse: collapse;
      border: 1px solid black;
      table-layout: fixed;
    }
    table.content-table td,
    table.content-table th {
      border: 1px solid black;
      padding: 10px;
    }

    #printable-content {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    img {
    max-width: 100%;
    height: auto;
}

</style>

<html>
	<head>
		<title>
			Supplier Payment
		</title>
		<style>
			@page{
				margin-top:140px;
			}
			body {
				font-family: Arial, sans-serif;
				padding: 0;
			}
			table {
				width: 100%;
				border:0;
				border-collapse: collapse;
				page-break-inside: auto;
				font-size: 12px;
				padding : 5;
			}
			thead {
				display: table-header-group;
			}
			tfoot {
				display: table-footer-group;
			}
			tr {
				page-break-inside: avoid;
				page-break-after: auto;
			}
			td.content, th.content {
				padding: 5px;
				border: 1px solid #000;
				word-wrap: break-word;
				white-space: normal;
			}
			</style>
	</head>
	<body style="font-family:Arial;font-size: 12px;">
		
	<div id='printable-content'>
	<div style='width:100%;height:35px;text-align:center;background-color:#94C973;font-size:30px;color:#ffffff'><?= $title?></div>
		
    <table width="100%" border=1 cellspacing="0" colspacing="0">
        <tr align="center">
            <th>Supplier : <?= isset($supplier_data[0]->supplier_name)?$supplier_data[0]->supplier_name:"" ?></th>
            <th> From Date : <?= $from; ?> </th>
            <th> To Date : <?= $to; ?> </th>
        </tr>
    </table>
    <table width='100%' border=1 cellspacing="0" colspacing="0">
        <thead>
            <tr>
                 <tr>
                <th>Sr.no</th>
                <th>PO Date</th>
                <th>PO Code</th>                
                <th>Supplier & Ref No</th>
                <th>Project Name</th>
                <th>Items</th>
                <th>Qnty</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>

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
        // Detect changes
        $is_new_date       = $last['po_date'] !== $row->po_date;
        $is_new_po_code    = $last['po_code'] !== $row->po_code;
        $is_new_supplier   = $last['supplier_name'] !== $row->supplier_name || $last['supplier_ref'] !== $row->supplier_ref;
        $is_new_project    = $last['project_name'] !== $row->project_name;

        $show_date         = $is_new_date;
        $show_po_code      = $is_new_po_code;
        $show_supplier     = $is_new_supplier || $is_new_po_code || $is_new_date;
        $show_project      = $is_new_project || $show_supplier;

        // Update last values
        $last['po_date']       = $row->po_date;
        $last['po_code']       = $row->po_code;
        $last['supplier_name'] = $row->supplier_name;
        $last['supplier_ref']  = $row->supplier_ref;
        $last['project_name']  = $row->project_name;
    ?>
        <tr>
            <td><?= $i++; ?></td>

            <!-- PO Code -->
            <td>
                <?php if ($show_po_code): ?>
                    <?= $row->po_code ?><br>
                    <?php for ($k = 1; $k <= $row->revision; $k++): ?>
                        <u><a target="_blank" href="<?= base_url() ?>index.php/Purchase/PO_print/<?= $row->po_id ?>/<?= $k ?>/0">Revision <?= $k ?></a></u><br>
                    <?php endfor; ?>
                <?php endif; ?>
            </td>

            <!-- PO Date -->
            <td><?= $show_date ? date('d-M-Y', strtotime($row->po_date)) : '' ?></td>

            <!-- Supplier Name + Ref -->
            <td>
                <?php if ($show_supplier): ?>
                    <?= $row->supplier_name ?><br><?= $row->supplier_ref ?>
                <?php endif; ?>
            </td>

            <!-- Project Name -->
            <td>
                <?= $show_project ? $row->project_name : '' ?>
            </td>

            <!-- Always-shown item fields -->
            <td><?= $row->product_name ?></td>
            <td><?= $row->quantity ?></td>
            <td><?=$row->unit_name?></td>
            <td><?= $row->price ?></td>
            <td><?= $row->total ?></td>
           
        </tr>
    <?php 
$total_qty += (float)$row->quantity;
$total_price += (float)$row->price;
$total_amount += (float)$row->total;
endforeach; ?>


</tbody>
<tfoot>
  <tr style="background-color: #dcdcdc;">
    <td colspan="8" class="text-end" style="font-weight: bold;">Total</td>
    <!-- <td><?= $total_qty?></td> -->
    <td><?= $total_price?></td>
    <td><?= $total_amount?></td>
  </tr>
</tfoot>

<?php endif; ?>

        </tbody>
    </table>
</body>

</html>