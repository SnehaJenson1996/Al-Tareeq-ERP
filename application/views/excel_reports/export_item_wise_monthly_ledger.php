<?php
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename=Stock Item Vouchers details.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $this->load->helper('stock_helper.php');?>
<?php
foreach($comapny_records as $row) {
	$company_name=$row->company_name;
	$company_address=$row->company_address;
	$company_city= $row->company_city;

	$company_pincode= $row->company_pincode;
	$company_country= $row->company_country;
	$company_email_id= $row->company_email_id;
	$company_telephone= $row->company_telephone;
	$company_website= $row->company_website;
	$company_TRN= $row->company_TRN;
}

$fromdate="01-".$month_no."-".$year;
?>

<html>
<body>
  <table width="100%" border=0 cellspacing="0" colspacing="0">
    <tr>
      <td align="center">
        <p style="font-size:16px; font-weight:bold;">Stock Item Vouchers details</p>
      </td>
      <td align="center"> <?php echo $company_name;?></td>
    </tr>
  </table>

  <table width="100%" border=1 cellspacing="0" colspacing="0">
    <tr>
      <td>Date : <?php echo date('d-M-Y');?></td>
    </tr>
  </table>
  <br>
  
 	<table width='100%' border=1 cellspacing="0" colspacing="0">
                <thead>
                            <tr>
				<th rowspan=2 width='40%'>Date</th>
				<th rowspan=2 width='40%'>Particulars</th>
				<th rowspan=2 width='40%'>Type</th>
				<th rowspan=2 width='40%'>Vch No</th>
				<th colspan=2 align='center'>Inward</th>
				<th colspan=2 align='center'>Outward</th>
				<th colspan=2 align='center'>Closing</th>
			   </tr>
                            <tr>
				<th>Quantity</th>
				<th>Value</th>
				<th>Quantity</th>
				<th>Value</th>
				<th>Quantity</th>
				<th>Value</th>
			   </tr>
		</thead>

		<tbody>
			<tr>
				<?php  $opening_rec= get_product_opening_stock($model_code,$fromdate,$warehouse_id);
				$opening_stock=0; $opening_price=0;
				foreach($opening_rec as $op)
				{
					$opening_stock= $op->stock;
					$opening_price= $op->total_price;
				}
				
				 ?>
				<td>Opening</td>
				<td></td>
				<td></td>
				<td></td>
				<td><?php echo $opening_stock;?></td>
				<td><?php echo sprintf("%0.2f",$opening_price);?></td>
				<td></td>
				<td></td>
				<td><?php echo $opening_stock;?></td>
				<td><?php echo sprintf("%0.2f",$opening_price);?></td>
			</tr>
			<?php $total_out=0; $total_outPrice=0; $total_closing_qty=0; $total_closing_price=0;
			$total_inward=0;  $total_inward_price=0; $inqty=0;$inprice=0;$outqty=0;$outprice=0;
			foreach($records as $r)
			{ ?>
			<tr>
				<td><?php echo date('d-M-Y',strtotime($r->stock_date));?></td>
				<td>
					<?php $invoice_rec='';$pname ='';$vch_no = '';
						if($r->remark=='Delivery Order')
						{
							$invoice_rec= get_sales_dc($r->trans_id);
						}
						elseif($r->remark=='GRN')
						{
							$invoice_rec = get_purchase_grn($r->trans_id);
						}
						foreach($invoice_rec as $k)
						{
							$pname = $k->perticular_name;
							$vch_no = $k->vch_no;
						}
						echo $pname;
					?>
				</td>
				<td><?php if($r->remark=='Delivery Order')echo 'Sales'; elseif($r->remark=='GRN')echo 'Purchase'; else echo $r->remark; ?></td>
				<td><?php echo $vch_no;?></td>
				<td><?php if($r->stock_type=='IN') { echo $inqty=$r->inward; $total_inward=$total_inward+$r->inward; } ?></td>
				<td><?php if($r->stock_type=='IN') { echo $inprice=$r->price; $total_inward_price=$total_inward_price+$r->price;}?></td>
				<td><?php if($r->stock_type=='OUT') { echo $outqty=$r->inward; $total_out=$total_out+$r->inward;}?></td>
				<td><?php if($r->stock_type=='OUT') { echo $outprice=$r->price; $total_outPrice=$total_outPrice+$r->price;}?></td>
				<td><?php echo $opening_stock= $opening_stock+$inqty-$outqty; $total_closing_qty=$total_closing_qty+ $opening_stock;?></td>
				<td><?php echo $opening_price= $opening_price+$inprice-$outprice; $total_closing_price=$total_closing_price+$opening_price; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<th>Grand Total</th>
				<td></td>
				<td></td>
				<td></td>
				<td><?php echo $total_inward;?></td>
				<td><?php echo $total_inward_price;?></td>
				<td><?php echo $total_out;?></td>
				<td><?php echo $total_outPrice;?></td>
				<td><?php echo $opening_stock;?></td>
				<td><?php echo $opening_price;?></td>
			</tr>
		
		</tbody>
            </table>
</body>
</html>
