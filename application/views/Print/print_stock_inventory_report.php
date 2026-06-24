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
    <tr>
      <td>Date : <?php echo date('d-M-Y');?></td>
	  
		<td>Warehouse: <?= isset($selected_store)&& !empty($selected_store)?$selected_store[0]->warehouse_name:""?></td>
	  ?>
    </tr>
  </table>
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

