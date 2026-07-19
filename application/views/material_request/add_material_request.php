
<link href="<?php echo base_url()."public/build/css/popup.css"; ?>" rel="stylesheet">
<form id="mr_form" action="<?= base_url('index.php/Project/save_material_request') ?>" method="post">

<!-- Select Approved Project -->
<div class="col-md-6">
    <label for="project_id" class="form-label">Select Approved Project</label>
    <select name="project_id" id="project_id" class="form-control" required>
    <option value="">-- Select Project --</option>
    <?php foreach ($approved_projects as $proj): ?>
        <option value="<?= $proj['project_id'] ?>"
            <?= (isset($selected_project_id) && $selected_project_id == $proj['project_id']) ? 'selected' : '' ?>>
            <?= $proj['project_name'] ?> (<?= $proj['project_code'] ?>)
        </option>
    <?php endforeach; ?>
</select>

</div>

<!-- Initiated By -->
<div class="col-md-6">
    <label for="initiated_by" class="form-label">Initiated By</label>
    <select name="initiated_by" id="initiated_by" class="form-control" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['user_id'] ?>"
                <?= ($user['user_id'] == $this->session->userdata('user_id')) ? 'selected' : '' ?>>
                <?= $user['user_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-12 clear topp">
    <!-- Auto-filled Project Info -->
    <table class="table table-bordered">
    <tr>
        <th>Project</th>
        <td id="project_name">-</td>
    </tr>
    <tr>
        <th>Customer</th>
        <td id="customer_name">-</td>
    </tr>
    <tr>
        <th>Branch</th>
        <td id="branch_name">-</td>
    </tr>
    <tr>
        <th>Requested Date</th>
        <td>
            <input type="date" name="requested_date" class="form-control" 
                value="<?= date('Y-m-d') ?>" required>
        </td>
    </tr>
    <tr>
        <th>Required Date</th>
        <td>
            <input type="date" name="required_date" class="form-control" required>
        </td>
    </tr>
    </table>
</div>
<div class="col-md-12 mt-2">
        <label>Items</label>
     </div>
     <div class="col-md-12 mt-6">
       
		  	<table class="table table-bordered table-hover" id="tab_logic">
				   <thead>
				    <tr>
				    	    <th title="Item">Item Name</th>
                            <th title="Item">Quantity</th>
                            <th title="Item">Description</th>
                            <th title="Item">Remarks</th>    
				    	    <th width='30px'><a id="add_row" title="Add" class="btn btn-xs bg-orange" ><span class="fa fa-plus"></span></a></th>
					</tr>
				    </thead>		 
				    <tbody id="mytbbody">
	     				<?php foreach($mitems as $r){?>
				    	<tr id='addr0' style='font-size: 13px;'>
						<td> <select name="product[]"  tabindex='2' class="form-control">
                                <option value="">-- Select product --</option>
                                <?php foreach ($pitems as $itm): ?>
                                    <option value="<?= $itm['product_id'] ?>"
                                        >
                                        <?= htmlspecialchars($itm['product_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        
						<td><input type="number" name="pdt_qty[]" tabindex="14" class="form-control form-control-sm" placeholder=""></td>
						<td><textarea rows="3" cols="20" name="desc[]" id="desc0" style="font-size:11px; font-weight:bold;" class="form-control form-control-sm" tabindex="13" placeholder="Description"></textarea>
                        </td>
                        <td><textarea name="item_remark[]" rows="3" id="item_remark0" tabindex="16" class="form-control form-control-sm" placeholder="remark"></textarea></td>
						<td width='30px'><a id='delete_row' title="Delete" onclick='remove_row(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>
					    </tr>
						<td width='30px'>
						<input type="hidden"  name="m_id[]" value="<?php echo $r->pjt_material_id;?>" >
						<a  href="javascript:confirmcancel(<?php echo $r->pjt_material_id;?>)" title="Delete" class="btn btn-xs bg-orange"><span class="fa fa-trash"></span></a></td>
					</tr>
	     				<?php } ?>
                        <?php if(empty($mitems)) { ?>
					<tr id='addr0' style='font-size: 13px;'>
						<td> <select name="product[]"  tabindex='2' class="form-control">
                                <option value="">-- Select product --</option>
                                <?php foreach ($pitems as $itm): ?>
                                    <option value="<?= $itm['product_id'] ?>"
                                        >
                                        <?= htmlspecialchars($itm['product_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                            <td><input type="number" name="pdt_qty[]" tabindex="14" class="form-control form-control-sm" placeholder=""></td>
						<td><textarea rows="4" cols="20" name="desc[]" id="desc0" style="font-size:11px; font-weight:bold;" class="form-control form-control-sm" tabindex="13" placeholder="Description"></textarea>
                            </td>
                        <td><textarea name="item_remark[]" rows="4" id="item_remark0" tabindex="16" class="form-control form-control-sm" placeholder="remark"></textarea></td>
						<td width='30px'><a id='delete_row' title="Delete" onclick='remove_row(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>
					</tr>
                    <?php } ?>
					<tr id='addr1'></tr>
					</tbody>
				</table>
		</div>

<input type="hidden" name="project_code" id="project_code">
<input type="hidden" name="customer_name" id="hidden_customer_name">
<input type="hidden" name="branch_name" id="hidden_branch_name">
<div class="col-md-12">
<div class="text-end mt-3">
    <button type="submit"  id="saveBtn" class="btn btn-success">Create MR</button>
    <a href="<?= base_url('index.php/Project/list_material_request') ?>" class="btn btn-secondary">Cancel</a>
</div></div>

</form>
<?php
$productOptions="";
$productOptions .= '<option value="">-- Select Product --</option>';

foreach ($pitems as $itm) {
    $id = $itm['product_id'];
    $name = $itm['product_name'];
    $productOptions .= '<option value="' . $id . '">' .
                        htmlspecialchars($name) .
                       '</option>';
}
?>
<script>
var productOptions = `<?= $productOptions ?>`;
</script>
<script>
$(document).ready(function(){
    $('#project_id').change(function(){
        var project_id = $(this).val();
        var units = <?php echo json_encode($units); ?>;
        if(!project_id){
            // Clear fields
            $('#project_name').text('-');
            $('#customer_name').text('-');
            $('#branch_name').text('-');
            $('#items_table tbody').html('<tr><td colspan="3">Select a project to load items</td></tr>');
            return;
        }

        $.ajax({
            url: '<?= base_url("index.php/Project/get_project_details_ajax") ?>',
            type: 'POST',
            data: { project_id: project_id },
            dataType: 'json',
            success: function(res){
                // Fill project info
                $('#project_name').text(res.project.project_name);
                $('#customer_name').text(res.project.customer_name);
                $('#branch_name').text(res.project.branch_name);

                  // Fill hidden inputs for form submission
    $('#project_code').val(res.project.project_code);
    $('#hidden_customer_name').val(res.project.customer_name);
    $('#hidden_branch_name').val(res.project.branch_name);

                // Fill items table
                var rows = '';
                res.items.forEach(function(item, i){
                    rows += `<tr>
                        <td>${i+1}</td>
                        <td>${item.product_name}</td>
                        <td>
                <select name="unit_id[]" class="form-control" required>
                    <option value="">-- Select Unit --</option>
                    ${units.map(u => `<option value="${u.unit_id}" ${u.unit_id == item.item_unit ? 'selected' : ''}>${u.unit_name}</option>`).join('')}
                </select>
            </td>
                        <td>
                            <input type="hidden" name="product_id[]" value="${item.product_id}">
                            <input type="number" name="quantity[]" value="${item.quantity}" class="form-control" min="1">
                        </td>
                    </tr>`;
                });

                $('#items_table tbody').html(rows);
            }
        });
    });
});

$(document).ready(function(){
    var selectedProject = $('#project_id').val();
    if(selectedProject){
        $('#project_id').trigger('change'); // Auto-fill details
    }
});

document.getElementById("mr_form").addEventListener("submit", function (e) {
    var requestedDate = document.querySelector('input[name="requested_date"]').value;
    var requiredDate  = document.querySelector('input[name="required_date"]').value;
    var btn = document.getElementById("saveBtn");

    // Convert to Date objects
    if(requestedDate && requiredDate){
        var reqDate = new Date(requestedDate);
        var matDate = new Date(requiredDate);

        if(matDate < reqDate){
            alert("Material Required Date cannot be earlier than Requested Date.");
            document.querySelector('input[name="required_date"]').focus();
            e.preventDefault(); // Stop form submission
            btn.disabled = false;
            btn.innerHTML = "Create MR";
            return false;
        }
    }

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    btn.disabled = true;
    btn.innerHTML = "Processing...";
});


$(document).ready(function(){
	var i=1;
	var i = 1;

    $("#add_row").click(function () {

        var html = "";

        html += "<td>";
        html += "<select name='product[]' class='form-control' tabindex='2'>";
        html += productOptions;
        html += "</select>";
        html += "</td>";

        html += "<td>";
        html += "<input type='number' name='pdt_qty[]' class='form-control form-control-sm' tabindex='14'>";
        html += "</td>";
        html += "<td>";
        html += "<textarea rows='4' cols='20' name='desc[]' id='desc" + i + "' ";
        html += "class='form-control form-control-sm' ";
        html += "style='font-size:11px;font-weight:bold;' ";
        html += "placeholder='Description'></textarea>";
        html += "</td>";
        html += "<td>";
        html += "<textarea name='item_remark[]' rows='4' id='item_remark" + i + "' ";
        html += "class='form-control form-control-sm' ";
        html += "placeholder='Remark'></textarea>";
        html += "</td>";

        html += "<td width='30px'>";
        html += "<a onclick='remove_row(" + i + ");' class='btn btn-xs bg-orange remove1'>";
        html += "<span class='fa fa-trash'></span>";
        html += "</a>";
        html += "</td>";

        $("#addr" + i).html(html);

        $("#mytbbody tr:last").after('<tr id="addr' + (i + 1) + '"></tr>');

        i++;
    });
	   
});
var j=1;
function remove_row(append_id){    	 
    $('#addr'+append_id).attr("id","addr"+append_id+"x");
    $('#addr'+append_id+"x").remove();
    calculateTotal();
}  


</script>
