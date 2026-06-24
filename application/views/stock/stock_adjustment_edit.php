<style type="text/css">

.select2Width {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 220px !important;
  min-width: 220px !important;
}

</style>

<div class="card-body">
<?php foreach($records1 as $row) { ?>	
<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Purchase/update_stock_adjustment" id="addform" autocomplete="off" enctype="multipart/form-data" onSubmit="formsubmit();">
	<div class="form-group row">	
              <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Warehouse <span style="color: red;"> * </span></label>
              <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3">	     
	         <select name="warehouse_id" id="warehouse_id" class="form-control select2" required >
		      <option value="">Select warehouse</option>
		      <?php foreach($store_records as $g) { ?>
			   <option <?php if($g->warehouse_id==$row->warehouse_id) echo 'selected'; ?> value="<?php echo $g->warehouse_id;?>"><?php echo $g->warehouse_name; ?></option>
		      <?php } ?>
	      	  </select>
	      </div>  	
	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Stock Date<span style="color: red;"> * </span></label>
	    <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
	      <input type="text" name="stock_date" id="stock_date" class="form-control bg-soft-gray" value="<?php echo date('d-m-Y', strtotime($row->stock_date)); ?>" required readonly="TRUE">
	      </div>	
	</div>
	<div class="form-group row">
	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Remark</label>
	    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
	    	<textarea name="remark" id="remark" class="form-control" rows="1" cols="2" placeholder="enter remark"><?php echo $row->remark;?></textarea>
	    </div>   	     	    
	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Stock Type<span style="color: red;"> * </span></label>
	    <div class="col-xs-12 col-sm-10 col-md-4 col-lg-2">
	    	<select tabindex="11" class="form-select form-control-sm bg-soft-gray" id="inward_type" name="inward_type" required tabindex='4' readonly>
			<option value="">Select</option>
			<option <?php if($row->stock_type=='Opening') echo 'selected';?> value="Opening">Opening Stock</option>
			<option <?php if($row->stock_type=='IN') echo 'selected';?> value="IN">Stock Inward</option>
			<option <?php if($row->stock_type=='OUT') echo 'selected';?> value="OUT">Stock Outward</option>
	        </select>
	    </div> 
		<!-- <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Stock Code</label>
	    <div class="col-xs-12 col-sm-10 col-md-2 col-lg-1">
	    	<input type="text" name="stock_code" readonly id="stock_code" tabindex='9' class="form-control form-control-sm bg-soft-gray" value="<?php echo $row->model_code;?>" ><br>
	    	
	    </div>    -->
	</div>
	<!-- <div class="form-group row">
	    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Order code</label>
	    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
		      <input type="text" name="order_code" id="order_code0" tabindex='6' class="form-control form-control-sm bg-soft-gray" value="<?php echo $row->order_code;?>" readonly onkeyup="get_product_description(0);" readonly>
			
		       <input type="hidden" name="pcode" id="pcode0" class="form-control form-control-sm" placeholder="" >
			<textarea rows='7' readonly cols='26' name="desc" id="desc0" style="font-size:11px; font-weight:bold; bg-soft-gray" class="form-control form-control-sm" tabindex='7' placeholder="Description"><?php echo $row->item_desc;?></textarea>
	    </div>   	    
	    <label class="col-xs-12 col-sm-2 col-md-1 col-lg-2 col-form-label">Size</label>
	    <div class="col-xs-12 col-sm-10 col-md-2 col-lg-2">
	    	<input type="text" name="size" id="size" tabindex='8' class="form-control form-control-sm bg-soft-gray" value="<?php echo $row->size;?>" onkeyup="get_stock_code()" readonly>
	    </div>   		     -->
	    <!-- <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 col-form-label">Min Stock</label>
	    <div class="col-xs-12 col-sm-10 col-md-2 col-lg-1">
	    	<input type="text" name="min_stock_qty" readonly id="min_stock_qty" tabindex='10' class="form-control form-control-sm bg-soft-gray"  value="<?php echo $row->min_qty;?>">
	    </div>  	     -->
	<!-- </div> -->
	
	<div class="form-group row">
	    <div class="col-md-12">
		<div class="dt-responsive table-responsive">
		<table class="table table-bordered table-hover" id="tab_logic">
			   <thead>
				<tr>
				    	<th title="Item">Sr </th> 
					<th title="Item">Bill of entry</th>      
					<th title="Item">Order ref no</th>      
					<!-- <th title="Item">Box no</th>   -->
					<th title="Item">Quantity/ Price</th>   
					<th title="Item">Remark /Location </th>   
				</tr>
			    </thead>		
			   		
			    <tbody id="mytbbody">
			    <?php  $i=1; foreach($records2 as $r) {?>
				<tr id='addr0'>
	                                <td>
	                                	<?php echo $i;?>
	                                	<br>
						<!-- <a id='delete_row' title="Delete" onclick='confirmcancel("<?php echo $r->stock_id;?>")' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a> -->
					</td>
	                                <td>
					      <input type="text" name="bill_entry" id="bill_entry0" tabindex='12' class="form-control form-control-sm select2Width" value="<?php echo $r->bill_no;?>"><br>
					      Year<br>
					      <input type="text" name="year" id="year0" tabindex='13' class="form-control form-control-sm select2Width" placeholder="Enter year" value="<?php echo $r->year;?>">
					</td>
					<td>
						<input type="text" name="ref_no" id="ref_no0" tabindex='15' class="form-control form-control-sm" value="<?php echo $r->order_ref_no;?>">
						
					</td>
					<!-- <td>
						<input type="text" name="box_no" id="box_no0" tabindex='15' class="form-control form-control-sm" value="<?php echo $r->box_no;?>">
						
					</td> -->
					<td>Quantity<br><input type="number" name="qty" id="qty0" tabindex='14' class="form-control form-control-sm" value="<?php echo $r->total_qty;?>"  required><br>
					Price <input type="number" step='0.01' name="price" id="price0" tabindex='14' class="form-control form-control-sm" value="<?php echo $r->price;?>"></td>
					
					<td>
						<textarea name="item_remark" id="item_remark0" tabindex='16' class="form-control form-control-sm" value="<?php echo $r->item_remark;?>"></textarea>
						<br>
						Storage Location
						<textarea rows='5' cols='30'  name="storage_location" id="valve_serial_no" style="font-size:11px; font-weight:bold;" class="form-control form-control-sm" placeholder="Enter Rack shell bin details"><?php echo $r->storage_location;?> </textarea></td>
						
					<input type="hidden" name="stock_id"  value='<?php echo $r->stock_id;?>'>
		                 </tr>
				<?php $i++; } ?>	
			    </tbody>
		</table>	
	    </div>    	
	    </div>    
	 </div>
 
	 <div class="form-group row">
		<label class="col-sm-2"></label>
		<div class="col-sm-10">
			<input type="hidden" name="product_id"  value='<?php echo $row->product_id;?>'>
			<input type="hidden" name="sno"  value='<?php echo $row->sno;?>'>
			<button type="submit" id='add' class="btn btn-primary m-b-0">Update</button>
			<button type="reset"  id='reset' class="btn btn-primary m-b-0">Reset</button>
		</div>
	 </div>
	</form>
<?php } ?>	
        </div>
    </div>
</div>
</div>
</div>
</div>

<script>

function confirmcancel(id)
{   var r= confirm("Are you sure you want to Delete this row?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",
     		type: "POST",
     		data: {table_name:'stock_details', where_key:'stock_id', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
			}
		        else {
			      	alert("Can't Delete record. Data already exist!!!");
		       }
		    },
		});
      		return true;
      	}
        else
        	return false;
	    	
}
</script>
