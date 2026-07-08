<div class="clearfix"></div>
<div class="row">
<div class="col-md-12 col-sm-12">
<div class="x_panel">
<div class="x_content">
<br />

<form action="<?php echo base_url().'index.php/Setup/' . (isset($product) ? 'update_item/'.$product['product_id'] : 'add_item_data'); ?>" 
      method="post" autocomplete="off" id="product" enctype="multipart/form-data">

<div class="row">

    <!-- Product Code -->
    <div class="col-md-6">
        <label>Product Code <span class="required">*</span></label>
        <input type="text" id="product_code" name="product_code" required class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['product_code']) : '' ?>">
    </div>

    <!-- Product Name -->
    <div class="col-md-6">
        <label>Product Name <span class="required">*</span></label>
        <input type="text" id="product_name" name="product_name" required class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['product_name']) : '' ?>">
    </div>

    <!-- Description -->
    <div class="col-md-6 mt-2">
        <label>Description</label>
        <textarea name="description" class="form-control"><?= isset($product) ? htmlspecialchars($product['description']) : '' ?></textarea>
    </div>

    <!-- Unit -->
    <div class="col-md-6 mt-2">
        <label>Unit <span class="required">*</span></label>
        <select name="unit_id" class="form-control" required>
            <option value="">-- Select Unit --</option>
            <?php foreach ($active_units as $unit): ?>
                <option value="<?= $unit->unit_id ?>"
                    <?= isset($product) && $product['unit_id'] == $unit->unit_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($unit->unit_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Retail Price -->
    <div class="col-md-6 mt-2">
        <label>Retail Price</label>
        <input type="number" step="any" name="retail_price" id="retail_price" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['retail_price']) : '' ?>">
    </div>
    <!-- raw materials -->
     <div class="col-md-12 mt-2">
        <label>Raw Materials</label>
     </div>
     <div class="col-md-12 mt-6">
       
		  	<table class="table table-bordered table-hover" id="tab_logic">
				   <thead>
				    <tr>
				    	    <th title="Item">Material Name</th>
                            <th title="Item">Quantity</th>    
				    	    <th title="Item">Unit Price</th>    
				    	    <th title="Item">Unit</th>    
				    	    <th width='30px'><a id="add_row" title="Add" class="btn btn-xs bg-orange" ><span class="fa fa-plus"></span></a></th>
					</tr>
				    </thead>		 
				    <tbody id="mytbbody">
	     				<?php foreach($rawmat as $r){?>
				    	<tr style='font-size: 13px;'>
						<td><input type="text" tabindex="11" name="mname_old[]" tabindex='2' class="form-control" placeholder="" value="<?php echo $r->material_name;?>" required></td>
						<td><input type="text" tabindex="11" name="qty_old[]"  tabindex='3' class="form-control qty " placeholder="" value="<?php echo $r->quantity_required;?>" ></td>
						<td><input type="text" tabindex="11" name="uprice_old[]"  tabindex='3' class="form-control uprice" placeholder="" value="<?php echo $r->cost;?>" ></td>
						<td>
                            <select name="unit_old[]"  tabindex='2' class="form-control">
                                <option value="Kg"<?php if($r->unit=='Kg'):?> selected="selected"<?php endif;?>>Kg</option>
                                <option value="Gram"<?php if($r->unit=='Gram'):?> selected="selected"<?php endif;?>>Gram</option>
                                <option value="Ltr"<?php if($r->unit=='Ltr'):?> selected="selected"<?php endif;?>>Ltr</option>
                                <option value="Piece"<?php if($r->unit=='Piece'):?> selected="selected"<?php endif;?>>Piece</option>
                                <option value="Meter"<?php if($r->unit=='Meter'):?> selected="selected"<?php endif;?>>Meter</option>
                            </select>
                            
						<td width='30px'>
						<input type="hidden"  name="m_id[]" value="<?php echo $r->id;?>" >
						<a  href="javascript:confirmcancel(<?php echo $r->id;?>)" title="Delete" class="btn btn-xs bg-orange"><span class="fa fa-trash"></span></a></td>
					</tr>
	     				<?php } ?>
                        <?php if(empty($rawmat)) { ?>
					<tr id='addr0' style='font-size: 13px;'>
						<td><input type="text" tabindex="11" name="mname[]" tabindex='2' class="form-control" placeholder=""  ></td>
						<td><input type='number' step='1' tabindex="11" name="qty[]"  tabindex='3' class="form-control qty" placeholder=""  ></td>
						<td><input type='number' step='any' tabindex="11" name="uprice[]"  tabindex='3' class="form-control uprice" placeholder=""  ></td>
						<td><select name="unit[]" tabindex='2' class="form-control">
                                <option value="Kg">Kg</option>
                                <option value="Gram">Gram</option>
                                <option value="Ltr">Ltr</option>
                                <option value="Piece">Piece</option>
                                <option value="Meter">Meter</option>
                            </select>
                        </td>
						<td width='30px'><a id='delete_row' title="Delete" onclick='remove_row(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>
					</tr>
                    <?php } ?>
					<tr id='addr1'></tr>
					</tbody>
				</table>
		</div>
	<!-- Total -->
    <div class="col-md-6 mt-2">
        <label>Total Price </label>
        <input type="text" name="total_amount"  id="total_amount" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['total_price']) : '' ?>"  readonly>
    </div>
	
    

    <!-- Group Code -->
    <div class="col-md-6 mt-2">
        <label>Group Code</label>
        <input type="text" name="group_code" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['group_code']) : '' ?>">
    </div>

    <!-- Category Code -->
  <div class="col-md-6 mt-2">
    <label>Category </label>
    <select name="category_id" class="form-control" >
        <option value="">-- Select Category --</option>

        <?php foreach($categories as $cat){ ?>
            <option value="<?= $cat->category_id; ?>"
                <?= (isset($product) && $product['category_id'] == $cat->category_id) ? 'selected' : ''; ?>>
                <?= $cat->category_code; ?> - <?= htmlspecialchars($cat->category_name); ?>
            </option>
        <?php } ?>

    </select>
</div>

    <!-- Min Level -->
    <div class="col-md-6 mt-2">
        <label>Min Level</label>
        <input type="number" name="min_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['min_level']) : '' ?>">
    </div>

    <!-- Max Level -->
    <div class="col-md-6 mt-2">
        <label>Max Level</label>
        <input type="number" name="max_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['max_level']) : '' ?>">
    </div>

    <!-- Re-order Level -->
    <div class="col-md-6 mt-2">
        <label>Re-order Level</label>
        <input type="number" name="reorder_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['reorder_level']) : '' ?>">
    </div>

    <!-- HS Code -->
    <div class="col-md-6 mt-2">
        <label>HS Code</label>
        <input type="text" name="hs_code" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['hs_code']) : '' ?>">
    </div>

    <!-- Tax Applicable -->
    <div class="col-md-6 mt-2">
        <label>Tax Applicable</label><br>
        <input type="checkbox" name="tax_applicable" value="1"
            <?= isset($product) && $product['tax_applicable'] == 1 ? 'checked' : '' ?>>

			
    </div>

    <div class="col-md-6 mt-2">

        <label>Product Type</label>

        <div>
            <label>
                <input type="checkbox" name="is_finished_product" value="1"
                    <?= isset($product) && $product['is_finished_product'] == 1 ? 'checked' : '' ?>>
                Finished
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_custom_made" value="1"
                    <?= isset($product) && $product['is_custom_made'] == 1 ? 'checked' : '' ?>>
                Custom Made
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_non_standard" value="1"
                    <?= isset($product) && $product['is_non_standard'] == 1 ? 'checked' : '' ?>>
                Non Standard
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_inactive" value="1"
                    <?= isset($product) && $product['is_inactive'] == 1 ? 'checked' : '' ?>>
                Inactive
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_marked_delete" value="1"
                    <?= isset($product) && $product['is_marked_delete'] == 1 ? 'checked' : '' ?>>
                Marked Delete
            </label>
        </div>        </div>


    <!-- Image -->
    <div class="col-md-6 mt-2">
        <label>Product Image</label>
        <input type="file" name="product_image" class="form-control" accept="image/*">

        <?php if (isset($product) && !empty($product['product_image'])) { ?>
            <img src="<?= base_url('public/items/'.$product['product_image']) ?>" style="width:80px;margin-top:5px;">
        <?php } ?>
    </div>
	

</div>

<!-- Submit -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <button type="submit" id="saveBtn" class="btn btn-success">
            <?= isset($product) ? 'Update' : 'Submit' ?>
        </button>
    </div>
</div>

</form>

</div>
</div>
</div>
</div>

<script>
document.getElementById("product").addEventListener("submit", function (e) {
    var btn = document.getElementById("saveBtn");

    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    btn.disabled = true;
    btn.innerHTML = "Processing...";
});
$(document).on("keyup change", "#retail_price, .qty, .uprice", function () {
    calculateTotal();
});


$(document).ready(function(){
	var i=1;
	$("#add_row").click(function()
	{
	     $('#addr'+i).html("<td><input type='text' tabindex='11' name='mname[]'  tabindex='2' class='form-control' placeholder='' required ></td><td><input tabindex='11' name='qty[]' tabindex='3' class='form-control qty' placeholder='' type='number' step='1' required></td><td><input tabindex='11' name='uprice[]' tabindex='3' class='form-control uprice' placeholder='' type='number' step='any'></td><td><select name='unit[]' tabindex='2' class='form-control'><option value=''>Select</option><option value='Kg'>Kg</option><option value='Gram'>Gram</option><option value='Ltr'>Ltr</option><option value='Piece'>Piece</option><option value='Meter'>Meter</option></select></td><td><a onclick='remove_row("+i+");calculateTotal();' id='delete_row' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	    $('#mytbbody tr:last').after('<tr id="addr'+(i+1)+'"></tr>');
	      i++; 	 
          calculateTotal();    	
	});
    $("#delete_row").click(function(){
    		 if(i>1){
			 $("#addr"+(i-1)).html('');
			 i--;
		 }
	 });
	   
});
var j=1;
function remove_row(append_id){    	 
    $('#addr'+append_id).attr("id","addr"+append_id+"x");
    $('#addr'+append_id+"x").remove();
    calculateTotal();
}  

function calculateTotal() {

    var retail = parseFloat($("#retail_price").val()) || 0;
    var materialsTotal = 0;

    $("#mytbbody tr").each(function () {

        var qty = parseFloat($(this).find(".qty").val()) || 0;
        var price = parseFloat($(this).find(".uprice").val()) || 0;

        materialsTotal += qty * price;
    });
    $("#total_amount").val((retail + materialsTotal).toFixed(2));
}

function confirmcancel(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",
     		type: "POST",
     		data: {table_name:'amc_product_materials', where_key:'id', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
			}
		        else {
			      	alert("Can't Delete record. Data already used!!!");
		       }
		    },
		});
      		return true;
      	}
        else
        	return false;
	    	
}


</script>