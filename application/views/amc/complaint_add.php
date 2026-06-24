<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_complaint_data" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Select AMC Project<span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-4 col-lg-4" role='group'>
				<select tabindex="1" class="form-select form-control-sm select2" id="project_name" name="project_name" required>
				<option value="">Select Project</option>
				<!-- <option value="new">New Project</option> -->
				<?php foreach($records as $s) { ?>
    <option value="<?php echo $s->quote_id; ?>">
        <?php echo $s->project_name . ' | ' . $s->customer_name; ?>
    </option>
<?php } ?>
			      </select>

    	    </div>

            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Form ID</label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
				    <input type="text" class="form-control form-control-sm " id="cmp_code" name="cmp_code" value="<?php echo $code;?>" tabindex='3' readonly>
			</div>
            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Date <span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
						                  
		    			<input type="text" class="form-control form-control-sm " id="cmp_date" name="cmp_date" value="<?php echo date('d-m-Y')?>" required tabindex='3' readonly>
				
    	    </div>
		</div>

		<div id='project_div' style="display:none;">
		<div class="form-group row" >	
		    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-3 col-form-label">Enter Project Name<span style="color: red;"> * </span>:</label>
		    <div class="col-xs-12 col-sm-10 col-md-3 col-lg-3">
			     <input type='text' tabindex="5" class="form-control form-control-sm" id="newproject_name" name="newproject_name" placeholder="" />
		    </div> 	
		   
		</div>	
		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Flat No</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
			      <input type="text" name="flat_no" class="form-control"/>
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-1 col-form-label">Location</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-2">
			      <input type="text" name="location" class="form-control"/>
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-1 col-form-label">Type</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			      <select class="form-control form-control-sm" name="cmp_type">
                    <option value="">--Select--</option>
                    <option value="AMC">AMC</option>
                    <option value="Non-AMC">Non-AMC</option>
                    
                  </select>
				</div>
				 
		</div>
		<div class="form-group row">	
		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Details</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-4 col-lg-4">
			      <textarea name="description" id="description" class="form-control"></textarea>
		     </div>	 
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Complaint Status<span style="color: red;"> * </span></label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
			      <select class="form-control form-control-sm" name="cmp_status" reuired>
                    <option value="">--Select--</option>
                    <option value="Open">Open</option>
					<option value="Waiting for LPO">Waiting for LPO</option>
                    <option value="Visited">Visited</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Closed">Closed</option>
                  </select>
		     </div>	
			
		</div>
		<div class="form-group row">
		<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Receive Date</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-3">
				<input type="datetime-local" name="receive_date" id="receive_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
		     </div>	
			
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Closing Date</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-3">
			      <input type="datetime-local" name="close_date" class="form-control"/>
		     </div>	
		</div>
		
		<div class="form-group row">
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Upload Picture</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="file" name="cmp_file" id="cmp_file" class="form-control form-control-sm">
		     </div>	
			 <label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Upload Document(doc,pdf)</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
			      <input type="file" name="doc_file" id="doc_file" class="form-control form-control-sm" accept=".pdf,.doc,.docx">
		     </div>	
			 
            		
		</div>
		
        <div class="form-group row">
		
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Remarks</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-8">
			      <textarea name="cmp_remarks" id="cmp_remarks" class="form-control"></textarea>
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
				<th></th>
				</tr>
			</thead>
			<tbody>
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
				<td><input type="text" class="form-control" id="hours0" name="hours[]"></td>
				<td><input type="text" class="form-control expenseInput" id="expense0" name="expense[]" onkeyup="calculate_lab_total(0);"></td>
				<td><input type="text" class="form-control" id="remarks0" name="remarks[]"></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
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
				<th><a onclick="addRow2()"><span class="fa fa-plus"></span></a></th>
				</tr>
			</thead>
			<tbody>
			<tr id="row0">	
				<td><input type="text" class="form-control" id="material0" name="material[0]"  onkeyup="calculate_total(0);"></td>	
				<!-- <td>
					<select class="form-select form-control-sm select2 select2Width" id="material0" name="material[0]" onchange="get_product_info(0);" >
						<option value="">Select Code</option>
						<?php foreach($amc_products as $s) {?>
						  <option value="<?php echo $s->product_id;?>"><?php echo $s->product_name;?></option>
						<?php } ?>
					</select>
				</td> -->
				<td><input type="text" class="form-control" id="qty0" name="qty[0]"  onkeyup="calculate_total(0);"></td>
				<td><input type="text" class="form-control" id="cost0" name="cost[0]"  onkeyup="calculate_total(0);"></td>
				<td><input type="text" class="form-control costInput" id="total0" name="total[0]" readonly></td>
				<td><a  onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
			</tbody>
			</table>
			<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Material Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='mat_cost' name='mat_cost' class="form-control form-control-sm"   >
		      </div>	
			  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Labour Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='labor_cost' name='labor_cost'  class="form-control form-control-sm"   >
		      </div>
			  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Total Cost</label>
		    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		      <input type="text" id='grand_total' name='grand_total'  class="form-control form-control-sm"  >
		      </div>
			</div>    	
		</div>	  
		<div class="form-group row">
			<label class="col-sm-2"></label>
			<div class="col-sm-10">
			<button type="submit"  tabindex="6"  id="add" class="btn btn-primary m-b-0">Save</button>
			</div>
		</div>
	    </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script>
function remove_row(append_id)
   {    	 
        $('#addr'+append_id).attr("id","addr"+append_id+"x");
        $('#addr'+append_id+"x").remove();
        calculate_grand_total();
   }
   function get_product_info(append_id)
	{
	
	var product_id= document.getElementById("material"+append_id).value;
	//alert(product_id);
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  var rowNum = 1; // Initialize row number counter

  $(document).keypress(function(event) {
		if (event.which == 13) {
		    if ($(event.target).is('input, select')) {
		        event.preventDefault();
		    }
		}
	    });

	$(document).on('keyup', 'input[name^="remarks"]', function(event) {
    if (event.which === 13) { // Enter key
        event.preventDefault(); // Prevent form submission

        var currentRow = $(this).closest('tr'); // Get the closest table row
        addRow1(); // Add new row

        // Optionally, you can focus the first input in the new row
        $('#addr' + i + ' input:first').focus();
    }
});

$(document).on('keyup', 'input[name^="total"]', function(event) {
    if (event.which === 13) { // Enter key
        event.preventDefault(); // Prevent form submission

        var currentRow = $(this).closest('tr'); // Get the closest table row
        addRow2(); // Add new row

        // Optionally, you can focus the first input in the new row
        $('#addr' + i + ' input:first').focus();
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

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Project",
        allowClear: true,
        width: '100%'
    });
});
document.getElementById("doc_file").addEventListener("change", function () {

    var file = this.value.toLowerCase();

    var allowedExtensions = /(\.pdf|\.doc|\.docx)$/i;

    if (!allowedExtensions.exec(file)) {

        alert("Only PDF, DOC and DOCX files are allowed.");

        this.value = '';

        return false;
    }
});
  </script>