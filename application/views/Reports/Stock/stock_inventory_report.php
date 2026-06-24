<div class="card-body">

	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Reports/get_stock_inventory_report" id="addform" autocomplete="off" enctype="multipart/form-data" >
            <div class="form-group row">
              <label class="col-xs-6 col-sm-2 col-md-2 col-lg-2 col-form-label">Warehouse</label>
              <div class="col-xs-12 col-sm-10 col-md-2 col-lg-2">
                <select name="warehouse_id" id="warehouse_id" class="form-control select2" required >
		      <option value="">Select warehouse</option>
		      <?php foreach($store_records as $g) { ?>
			   <option  value="<?php echo $g->warehouse_id;?>"><?php echo $g->warehouse_name; ?></option>
		      <?php } ?>
	      	  </select>
              </div>
              
              	<label class="col-xs-6 col-sm-2 col-md-2 col-lg-2 col-form-label">Select Product</label>
              	<div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
		        <select tabindex="11" class="form-select form-control-sm select2 select2Width" id="product_id" name="product_id" >
			<option value="">Select Code</option>
			<?php foreach($products as $s) {
			// $size= str_replace('"', ' Inch' ,$s->size);?>
			  <option  value="<?php echo $s->item_id; ?>"><?php echo $s->item_name;?></option>
			<?php } ?>
		      </select>
              	</div>
            	<div class="col-sm-3">
                <table width='100%'>
                  <tr>
                    <td>
                      <input type="submit" id="view" name="go" value="Go" class="btn btn-sm btn-primary m-b-0" />
                    </form>
                  </td>
                  <td>&nbsp;</td>
                  <td>
                    <form target="_blank" action="<?php echo base_url().'index.php/'; ?>Reports/print_stock_inventory_report" id="ques1" method="post" name="ques1" >
                      <input type="hidden" name="warehouse_id" value="<?php echo $warehouse_id; ?>" />
                      <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
                      <input tabindex="6" type="submit" id="print" value="Print" class="btn btn-warning btn-sm" />
                    </form></td>
                    <td>&nbsp;</td>
                    <td>
                      <form action="<?php echo base_url().'index.php/'; ?>Reports/export_stock_inventory_report" id="ques1" method="post" name="ques1" >
                        <input type="hidden" name="warehouse_id" value="<?php echo $warehouse_id; ?>" />
                        <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
                        <input tabindex="7" type="submit" id="export" value="Export to excel" class="btn btn-warning btn-sm" />
                      </form>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                 <thead>
                            <tr>
				<th>Srn</th>
				<th>Stock Code</th>
				
				<th>Stock Qty</th>
				<th>Unit Price</th>
				<th>Total</th>
				<th>Allocated Qty</th>
			   </tr>
		</thead>

		<tbody>
		<?php $i=1; $tot1=0; $st=0;
		foreach($records as $row) :?>
			<tr>
				<td><?php echo $i;$i++;?></td>
				<td>
					<a target='_blank' href="<?php echo base_url().'index.php/'; ?>Reports/item_wise_ledger/<?php echo $row->item_code.'/'.$warehouse_id;?>"><?php echo $row->item_name;?></a>
				</td>
				
				<td><?php echo $row->stock; $st=$st+$row->stock;?></td>
				<td><?php echo $row->price;?></td>
				<td align='right'><?php echo $tot= sprintf("%0.2f",$row->stock*$row->price); $tot1=$tot1+$tot;?></td>
				<td><?php echo $row->allocation;?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="bg-soft-primary">
			<th>Total</th>
			<th></th>
			<th><?php echo $st;?></th>
			<th></th>
			<th align='right'><?php echo sprintf("%0.2f",$tot1);?></th>
			<th></th>
		</tr>
		</tbody>
            </table>
           </div>


        
        </div>
    </div>
</div>
</div>
</div>
</div>
<script>
document.getElementById("export").addEventListener("click", function(e) {

    // count table rows (excluding header)
    var rowCount = document.querySelectorAll("#basic-btn tbody tr").length;

    // check if only total row OR no data
    if(rowCount <= 1) {
        e.preventDefault();
        alert("No data available to export. Please check your filter criteria.");
        return false;
    }

});
</script>