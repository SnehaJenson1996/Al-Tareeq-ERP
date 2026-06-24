<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/update_complaint_data" autocomplete="off" enctype="multipart/form-data">
	<?php  foreach($records as $row) :?>
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Project name<span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-4 col-lg-4" role='group'>
				<input type="text" id="project_name" class="form-control form-control-sm " name="project_name" value="<?php echo $row->project_name; ?>"/>
				<input type="hidden" id="cmp_id" name="cmp_id" value="<?php echo $row->cmp_id; ?>"/>

    	    </div>

            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Form ID</label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
				    <input type="text" class="form-control form-control-sm " id="cmp_code" name="cmp_code" value="<?php echo $row->cmp_code;?>" tabindex='3' readonly>
			</div>
            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Date <span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
						                  
		    			<input type="text" class="form-control form-control-sm " id="cmp_date" name="cmp_date" value="<?php echo $row->cmp_date?>" required tabindex='3' readonly>
				
    	    </div>
		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Flat No</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
			      <input type="text" name="flat_no" class="form-control" value="<?php echo $row->flat_no;  ?>"/>
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-1 col-form-label">Location</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-2">
			      <input type="text" name="location" class="form-control" value="<?php echo $row->location;  ?>"/>
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-1 col-form-label">Type</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			      <select class="form-control form-control-sm" name="cmp_type">
                    <option value="<?php echo $row->cmp_type;  ?>"><?php echo $row->cmp_type;  ?></option>
                    <option value="AMC">AMC</option>
                    <option value="Non-AMC">Non-AMC</option>
                    
                  </select>
				</div>
				 
		</div>
		<div class="form-group row">	
		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Details</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
			      <textarea name="description" id="description" class="form-control"><?php echo $row->description;  ?></textarea>
		     </div>	 
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Complaint Status<span style="color: red;"> * </span></label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			      <select class="form-control form-control-sm" name="cmp_status" reuired>
                    <option value="<?php echo $row->cmp_status;  ?>"><?php echo $row->cmp_status;  ?></option>
                    <option value="Open">Open</option>
                    <option value="Visited">Visited</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Closed">Closed</option>
                  </select>
		     </div>	
			
		</div>
		<div class="form-group row">
		<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Receive Date</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-3">
			      <input type="datetime-local" name="receive_date" class="form-control" value="<?php echo $row->receive_date;  ?>"/>
		     </div>	
			 
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Closing Date</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-3">
			      <input type="datetime-local" name="close_date" class="form-control" value="<?php echo $row->close_date;  ?>"/>
		     </div>	
		</div>
		
		<div class="form-group row">
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Upload Picture</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			      <input type="file" name="cmp_file" id="cmp_file" class="form-control form-control-sm">
				  <?php if(!empty( $row->cmp_file)){?>
					<img src="<?php echo base_url('public/uploaded_documents/'.$row->cmp_file); ?>"  height=50px width=50px/>
				  <?php } ?>
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Upload Document(doc,pdf)</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="file" name="doc_file" id="doc_file" class="form-control form-control-sm">
				  <?php if(!empty( $row->doc_file)){?>
<a target="_blank"
   href="<?php echo base_url('public/uploaded_documents/'.rawurlencode($row->doc_file)); ?>">
   Doc File
</a>				  <?php } ?>
		     </div>	
			 
            		
		</div>
		
        <div class="form-group row">
		
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Remarks</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-8">
			      <textarea name="cmp_remarks" id="cmp_remarks" class="form-control"><?php echo $row->remarks;?></textarea>
		     </div>	
			
        </div>

		<div class="form-group row">
		<h6>Visit Details</h6>  	
		<table class="table table-striped" id="dataTable1">
			<thead>
				<tr>
				<th>Visit date</th>
				<th>Technician</th>
				<th>Hours spent</th>
				<th>Cost</th>
				<th>Remarks</th>
				<th><a class="add-row"><span class="fa fa-plus"></span></a></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($tr_records1)){
			$i=5001;
			foreach ($tr_records1 as $r){?>
			<tr id="row0">
				<td><input type="datetime-local" class="form-control" id="visit_date<?php echo $i;?>" name="visit_date[]" value="<?php echo $r->visit_date; ?>"></td>
				<td>

				  <select class="form-select form-control-sm select2" id="technician<?php echo $i;?>" name="technician[]"  >
					<option value="">Select</option>
					<?php foreach($user_records as $s) {?>
					<option <?php if($r->technician ==$s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name;?></option>
					<?php } ?>
			      </select>
				</td>
				<td><input type="text" class="form-control" id="hours0" name="hours[]" value="<?php echo $r->hours; ?>"></td>
				<td><input type="text" class="form-control  expenseInput" id="expense<?php echo $i;?>" name="expense[]" value="<?php echo $r->expense; ?>" onkeyup="calculate_lab_total(<?php echo $i;?>);"></td>
				<td><input type="text" class="form-control" id="remarks<?php echo $i;?>" name="remarks[]" value="<?php echo $r->remarks; ?>"></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
				<?php $i++;} }else{?>
					<tr id="row0">
				<td><input type="datetime-local" class="form-control" id="visit_date0" name="visit_date[]"></td>
				<td>
					<select class="form-select form-control-sm select2" id="technician0" name="technician[]"  >
					<option value="">Select</option>
					<?php foreach($user_records as $s) {?>
					<option <?php if($this->session->userdata('user_id')==$s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name;?></option>
					<?php } ?>
			      </select>
				</td>
				<td><input type="text" class="form-control expenseInput" id="expense0" name="expense[]" onkeyup="calculate_lab_total(0);"></td>
				<td><input type="text" class="form-control" id="remarks0" name="remarks[]"></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
			<h6>Material Details</h6>  	
        <table class="table table-striped" id="dataTable2">
			<thead>
				<tr>
				<th>Materials</th>
				<th>Qty</th>
				<th>Rate</th>
				<th>Total</th>
				<th><a class="add-row2"><span class="fa fa-plus"></span></a></th>
				
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($tr_records2)){
			$i=0;
			foreach ($tr_records2 as $p){ ?>
			<tr id="row0">
				
				<!-- <td>
					<select class="form-select form-control-sm select2 select2Width" id="material<?php echo $i;?>" name="material[]" onchange="get_product_info(0);" >
						<option value="">Select Code</option>
						<?php foreach($amc_products as $s) { ?>
						  <option  <?php if($p->material ==$s->product_id) echo 'selected'; ?> value="<?php echo $s->product_id;?>"><?php echo $s->product_code;?></option>
						<?php } ?>
					</select>
				</td> -->
				<td><input type="text" class="form-control" id="material<?php echo $i;?>" onkeyup="calculate_total(<?php echo $i;?>);" name="material[]"  value="<?php echo $p->material; ?>"></td>
				<td><input type="text" class="form-control" id="qty<?php echo $i;?>" onkeyup="calculate_total(<?php echo $i;?>);" name="qty[]"  value="<?php echo $p->qty; ?>"></td>
				<td><input type="text" class="form-control" id="cost<?php echo $i;?>" onkeyup="calculate_total(<?php echo $i;?>);" name="cost[]"  value="<?php echo $p->cost; ?>"></td>
				<td><input type="text" class="form-control costInput" id="total<?php echo $i;?>" name="total[]"  value="<?php echo $p->total; ?>"></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
				<?php } $i++;} else { ?>
					<tr id="row0">		
				<td>
					<select class="form-select form-control-sm select2 select2Width" id="material0" name="material[0]" onchange="get_product_info(0);" >
						<option value="">Select Code</option>
						<?php foreach($amc_products as $s) {?>
						  <option value="<?php echo $s->product_id;?>"><?php echo $s->product_code;?></option>
						<?php } ?>
					</select>
				</td>
				<td><input type="text" class="form-control" id="qty0" name="qty[0]"  onkeyup="calculate_total(0);"></td>
				<td><input type="text" class="form-control" id="cost0" name="cost[0]"  onkeyup="calculate_total(0);"></td>
				<td><input type="text" class="form-control costInput" id="total0" name="total[0]" readonly></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
			<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Material Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='mat_cost' name='mat_cost' class="form-control form-control-sm" value="<?php echo $row->mat_cost;?>"  >
		      </div>	
			  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Labour Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='labor_cost' name='labor_cost'  class="form-control form-control-sm"  value="<?php echo $row->lab_cost;?>"  >
		      </div>
			  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Total Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total'  class="form-control form-control-sm"  value="<?php echo ($row->mat_cost+$row->lab_cost);?>" >
		      </div>
			</div>
    	
		     	
		</div>
    	
		     	
		
		  
		<div class="form-group row">
			<label class="col-sm-2"></label>
			<div class="col-sm-10">
			<button type="submit"  tabindex="6"  id="add" class="btn btn-primary m-b-0">Save</button>
			</div>
		</div>
		<?php endforeach; ?>
	    </form>

        </div>
    </div>
</div>
</div>
</div>
</div>



   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
var rowNum = 0; // Initialize row number counter
$('.select2').select2();
// Function to update rowNum based on existing rows
function updateRowNum() {
    var maxRowNum = 0;
    $('#dataTable1 tbody tr').each(function() {
        var rowId = $(this).attr('id');
        if (rowId && rowId.startsWith('row')) {
            var currentRowNum = parseInt(rowId.replace('row', ''), 10);
            if (currentRowNum > maxRowNum) {
                maxRowNum = currentRowNum;
            }
        }
    });
    rowNum = maxRowNum + 1; // Set rowNum to one more than the highest existing row number
}
function updateRowNum2() {
    var maxRowNum = 0;
    $('#dataTable2 tbody tr').each(function() {
        var rowId = $(this).attr('id');
        if (rowId && rowId.startsWith('row')) {
            var currentRowNum = parseInt(rowId.replace('row', ''), 10);
            if (currentRowNum > maxRowNum) {
                maxRowNum = currentRowNum;
            }
        }
    });
    rowNum = maxRowNum + 1; // Set rowNum to one more than the highest existing row number
}
	$(document).keypress(function(event) {
		if (event.which == 13) {
		    if ($(event.target).is('input, select')) {
		        event.preventDefault();
		    }
		}
	    });

	// Event handler to add a new row when Enter key is pressed in a remarks input
$(document).on('keyup', 'input[name^="remarks"]', function(event) {
    if (event.which === 13) { // Enter key
        event.preventDefault(); // Prevent form submission
        updateRowNum(); 
        addRow1();
        $('#row' + (rowNum - 1) + ' input:first').focus();
    }
});
	$(document).on('click', 'a.add-row', function(event) {
			updateRowNum(); 
			addRow1(); // Add new row
		});
	$(document).on('click', 'a.add-row2', function(event) {
		updateRowNum(); 
		addRow2(); // Add new row
	});

	$(document).on('keyup', 'input[name^="total"]', function(event) {
		if (event.which === 13) { 
			event.preventDefault(); 
			updateRowNum2(); 
			addRow2(); 
			$('#row' + (rowNum - 1) + ' select:first').focus();
		}
	});
	function addRow1() {
    var table = document.getElementById("dataTable1").getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(table.rows.length);
    newRow.id = 'row' + rowNum; // Assign unique ID to the new row

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
	var cell6 = newRow.insertCell(5);
	cell1.innerHTML = '<input type="datetime-local" class="form-control" id="visit_date' + rowNum + '" name="visit_date[]">';
	cell2.innerHTML = '<select tabindex="1" class="form-select form-control-sm select2" id="technician' + rowNum + '" name="technician[]" required>	<option value="">Select</option><?php foreach($user_records as $s) {?><option <?php if($this->session->userdata('user_id')==$s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name;?></option><?php } ?></select>';
	cell3.innerHTML = '<input type="text" class="form-control" id="hours' + rowNum + '" name="hours[]">'
	cell4.innerHTML = '<input type="text" class="form-control expenseInput"  id="expense' + rowNum + '" name="expense[]" onkeyup="calculate_lab_total('+ rowNum + ');">';
	cell5.innerHTML = '<input type="text" class="form-control" id="remarks' + rowNum + '" name="remarks[]">';
	cell6.innerHTML = '<a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a>';
	$('.select2').select2();
    rowNum++; // Increment row number counter for the next row
}

function addRow2() {
var table = document.getElementById("dataTable2").getElementsByTagName('tbody')[0];
var newRow = table.insertRow(table.rows.length);
newRow.id = 'row' + rowNum; // Assign unique ID to the new row
var cell1 = newRow.insertCell(0);
var cell2 = newRow.insertCell(1);
var cell3 = newRow.insertCell(2);
var cell4 = newRow.insertCell(3);
var cell5 = newRow.insertCell(4);

//cell1.innerHTML = '<select class="form-select form-control-sm select2 select2Width" id="material' + rowNum + '" name="material[' + rowNum + ']" onchange="get_product_info(' + rowNum + ');"><option value="">Select Code</option><?php foreach($amc_products as $s) {?><option value="<?php echo $s->product_id;?>"><?php echo $s->product_code;?></option><?php } ?></select>';
cell1.innerHTML = '<input type="text" class="form-control" id="material' + rowNum + '" name="material[]"  onkeyup="calculate_total(' + rowNum + ');">';
cell2.innerHTML = '<input type="text" class="form-control" id="qty' + rowNum + '" name="qty[]" onkeyup="calculate_total(' + rowNum + ');">';
cell3.innerHTML = '<input type="text" class="form-control" id="cost' + rowNum + '" name="cost[]" onkeyup="calculate_total(' + rowNum + ');">';
cell4.innerHTML = '<input type="text" class="form-control costInput" id="total' + rowNum + '" name="total[]"  onkeyup="calculate_mat_total(' + rowNum + ');">';
cell5.innerHTML = '<a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a>';

rowNum++; // Increment row number counter for the next row
}

function deleteRow(btn) {
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);
}
function calculate_lab_total(append_id) {
    var total = 0;
    var expenseInputs = document.getElementsByClassName("expenseInput");

    for (var i = 0; i < expenseInputs.length; i++) {
        var expense = parseFloat(expenseInputs[i].value);
        if (!isNaN(expense)) {
            total += expense;
        }
    }
    document.getElementById("labor_cost").value = total.toFixed(2); 
	calculate_grand_total();
}

function calculate_mat_total(append_id) {
    var total = 0;
    var expenseInputs = document.getElementsByClassName("costInput");

    for (var i = 0; i < expenseInputs.length; i++) {
        var expense = parseFloat(expenseInputs[i].value);
        if (!isNaN(expense)) {
            total += expense;
        }
    }
    document.getElementById("mat_cost").value = total.toFixed(2); 
	calculate_grand_total();
	
}
function calculate_total(append_id)
{
	var price = parseFloat(document.getElementById("cost"+append_id).value);
	var quantity = parseFloat(document.getElementById("qty"+append_id).value);
	var total = price*quantity;
	document.getElementById("total"+append_id).value=parseFloat(total).toFixed(2);
	calculate_mat_total();
	calculate_grand_total();
}
function calculate_grand_total()
{

	var mat_cost = parseFloat(document.getElementById('mat_cost').value) || 0;
    var lab_cost = parseFloat(document.getElementById('labor_cost').value) || 0;
	var total = mat_cost+lab_cost;
	document.getElementById("grand_total").value= parseFloat(total).toFixed(2);
}
function get_product_info(append_id)
	{

	var product_id= document.getElementById("material"+append_id).value;
	$.ajax
	({
		url: "<?php echo site_url('Product/ajax_get_product_details'); ?>",
		type: 'POST',
		data: {product_id: product_id },
		dataType: "json",
		success: function(msg) {
			
				document.getElementById("cost"+append_id).value=msg.price;
		}
	});
	
	}
	
</script>