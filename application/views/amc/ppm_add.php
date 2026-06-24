<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>AMC/add_ppm_data" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
	  		<label class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-form-label">Select Project<span style="color: red;"> * </span></label>
	  		<div class="col-xs-12 col-sm-9 col-md-4 col-lg-4" role='group'>
				<select tabindex="1" class="form-select form-control-sm select2" id="project_name" name="project_name" required >
   <option value="">Select Project</option>
    <?php foreach($records as $s) { ?>
        <option value="<?php echo $s->quote_id; ?>">
            <?php echo $s->project_name . ' | ' . $s->customer_name . ' | ' . $s->quotation_code; ?>
        </option>
    <?php } ?>
</select>
    	    </div>

            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Form ID</label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
				    <input type="text" class="form-control form-control-sm " id="ppm_code" name="ppm_code" value="<?php echo $code;?>" tabindex='3' readonly>
			</div>
            <label class="col-xs-12 col-sm-3 col-md-3 col-lg-1 col-form-label"> Date <span style="color: red;"> * </span></label>
		    <div class="col-xs-12 col-sm-9 col-md-3 col-lg-2">
						                  
		    			<input type="text" class="form-control form-control-sm " id="ppm_date" name="ppm_date" value="<?php echo date('d-m-Y')?>" required tabindex='3' readonly>
				
    	    </div>
		</div>

		
		
		<div class="form-group row">
		
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Number of PPM</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-3">
<input type="text" name="no_of_sch" id="no_of_sch" 
       class="form-control" onchange="createPPMTable()" />		     </div>	
			
		
			<label class="col-xs-12 col-sm-1 col-md-2 col-lg-2 col-form-label">Remarks</label>
	    	    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
			      <textarea name="remarks" id="remarks" class="form-control"></textarea>
		     </div>	
			 
        </div>
		<div class="form-group row">	
		<h6>PPM Schedule Summary</h6>	
		<div style="overflow:auto; width:100%;">	          	
        <table class="table table-striped" id="dataTable1" width="150%">
			<thead>
				<tr>
					
					<th width="20%">PPM </th>
					<th width="10%">Schedule Date</th>
					<th width="10%">Finished Date</th>
					<th width="10%">Total Amount</th>
					<th width="20%">Remarks</th>
					<th width="20%">Status</th>
					
				</tr>
			</thead>
			<tbody id="ppmTableBody">
			<!-- <tr id="row0">
				<td><input type="text" class="form-control" id="srno0" name="srno[0]"></td>
				<td><input type="text" class="form-control" id="ppm_num0" name="ppm_num[0]"></td>
				<td><input type="date" class="form-control" id="ppm_sch_date0" name="ppm_sch_date[0]"></td>
				<td><input type="date" class="form-control" id="ppm_finish_date0" name="ppm_finish_date[0]"></td>
				<td><input type="text" class="form-control" id="ppm_amt0" name="ppm_amt[0]"></td>
				<td><input type="text" class="form-control" id="ppm_remarks0" name="ppm_remarks[0]"></td>
				<td>
					<select class="form-control" id="ppm_status0" name="ppm_status[0]">
						<option value="Scheduled">Scheduled</option>
						<option value="Finished">Finished</option>

					</select>
				</td>
				<td><a href="" onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr> -->
			</tbody>
    	</table>
		</div>
    	
		     	
		</div>
		
		<div class="form-group row">	
		<h6>PPM Details</h6>	    
		<div style="overflow:auto;">	     	
        <table id="dataTable2"  style="margin-top:3%">
			<thead>
    <tr style="text-align:center; vertical-align:middle;">
        <th style="width:5%;">Sr.No</th>
        <th style="width:8%;">PPM</th>
        <th style="width:10%;">Building</th>
        <th style="width:8%;">Flat</th>
        <th style="width:12%;">Visit Date</th>
        <th style="width:10%;">No. of Equipments</th>
        <th style="width:8%;">Rate</th>
        <th style="width:8%;">Total</th>
        <th style="width:10%;">Technician</th>
        <th style="width:15%;">Remarks</th>
        <th style="width:8%;">Status</th>
        <th style="width:4%;">
            <a onclick="addRow2()" style="cursor:pointer;">
                <span class="fa fa-plus"></span>
            </a>
        </th>
    </tr>
</thead>
			<tbody>
			<tr id="row0">
				<td><input type="text" class="form-control" id="sr_no0" name="sr_no[0]" value="1" readonly></td>
				<td><input type="text" class="form-control" id="ppmnum0" name="ppmnum[0]"></td>
				<td><input type="text" class="form-control" id="ppm_building0" name="ppm_building[0]"></td>
				<td><input type="text" class="form-control" id="ppm_flat0" name="ppm_flat[0]"></td>
				<td><input type="date" class="form-control" id="ppm_visit_date0" name="ppm_visit_date[0]"></td>
				<td><input type="text" class="form-control" id="ppm_acnum0" name="ppm_acnum[0]" onkeyup="calculate_cost(0);"></td>
				<td><input type="text" class="form-control" id="ppm_rate0" name="ppm_rate[0]" onkeyup="calculate_cost(0);" value="50"></td>
				<td><input type="text" class="form-control" id="ppm_cost0" name="ppm_cost[0]"></td>
				<td><input type="text" class="form-control" id="ppm_tech0" name="ppm_tech[0]"></td>
				<td><input type="text" class="form-control" id="ppm_notes0" name="ppm_notes[0]"></td>
				
				<td>
					<select class="form-control" id="ppmstatus0" name="ppmstatus[0]">
						<option value="Scheduled">Scheduled</option>
						<option value="Scheduled">Pending</option>
						<option value="Finished">Finished</option>
					</select>
				</td>
				<td><a href="" onclick="deleteRow(this)"><span class="fa fa-trash"></span></a></td>
				</tr>
			</tbody>
    	</table>
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
function get_quotation_info()
{
	var qid = document.getElementById("qid").value;	
   	if(qid!='')
   	{
	   	$.ajax({
	   	async:"false",
		type: "POST",
		url:"<?php echo base_url()?>index.php/Ajax/ajax_get_amc_quotation_info",
		data: {qid:qid} ,
		success: function(msg){
			
			document.getElementById('item_list_id').innerHTML=msg;
			get_inv_code();
			
				$.ajax({
			   	async:"false",
				type: "POST",
				url:"<?php echo base_url()?>index.php/Ajax/ajax_get_cust_accountId_from_quote",
				data: {qid:qid} ,
				success: function(accid){
					// document.getElementById('inv_debtor0').value=accid;
					
					var grand_total= document.getElementById("grand_total").value;
					var sub_total= document.getElementById("sub_total").value;
					var discount_amt= document.getElementById("discount_amt").value;
					var vat_amt= document.getElementById("vat_amt").value;
					var crate = document.getElementById('crate').value;
					var x= (grand_total*crate).toFixed(2);	
					
				     }
				});
		     }
		});
	}
}





</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  var rowNum = 1; // Initialize row number counter

function addRow1() {
  var table = document.getElementById("dataTable1").getElementsByTagName('tbody')[0];
  var newRow = table.insertRow(table.rows.length);
  newRow.id = 'row' + rowNum; // Assign unique ID to the new row
  var srnum = rowNum+1;
  var cell1 = newRow.insertCell(0);
  var cell2 = newRow.insertCell(1);
  var cell3 = newRow.insertCell(2);
  var cell4 = newRow.insertCell(3);
  var cell5 = newRow.insertCell(4);
  var cell6 = newRow.insertCell(5);
  var cell7 = newRow.insertCell(6);
  var cell8 = newRow.insertCell(7);


  cell1.innerHTML = '<input type="text" class="form-control" id="srno' + rowNum + '" name="srno[]">';
  cell2.innerHTML = '<input type="text" class="form-control" id="ppm_num' + rowNum + '" name="ppm_num[]">';
  cell3.innerHTML = '<input type="date" class="form-control" id="ppm_sch_date' + rowNum + '" name="ppm_sch_date[]">';
  cell4.innerHTML = '<input type="date" class="form-control" id="ppm_finish_date' + rowNum + '" name="ppm_finish_date[]">';
  cell5.innerHTML = '<input type="text" class="form-control" id="ppm_amt' + rowNum + '" name="ppm_amt[]">';
  cell6.innerHTML = '<input type="text" class="form-control" id="ppm_remarks' + rowNum + '" name="ppm_remarks[]">';
  

  var optionsHTML = '<select class="form-control" id="ppm_status' + rowNum + '" name="ppm_status[]">' +
                  '<option value="Scheduled">Scheduled</option>' +
                  '<option value="Finished">Finished</option>' +
                  '</select>';

	// Set the optionsHTML as innerHTML of cell5
	cell7.innerHTML = optionsHTML;				
	cell8.innerHTML = '<a href="" onclick="deleteRow(this)"><span class="fa fa-trash"></span></a>';
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
  var cell6 = newRow.insertCell(5);
  var cell7 = newRow.insertCell(6);
  var cell8 = newRow.insertCell(7);
  var cell9 = newRow.insertCell(8);
  var cell10 = newRow.insertCell(9);
  var cell11 = newRow.insertCell(10);	
  var cell12 = newRow.insertCell(11);		

  cell1.innerHTML = '<input type="text" class="form-control" id="sr_no' + rowNum + '" name="sr_no[]">';
  cell2.innerHTML = '<input type="text" class="form-control" id="ppmnum' + rowNum + '" name="ppmnum[]">';
  cell3.innerHTML = '<input type="text" class="form-control" id="ppm_building' + rowNum + '" name="ppm_building[]">';
  cell4.innerHTML = '<input type="text" class="form-control" id="ppm_flat' + rowNum + '" name="ppm_flat[]">';
  cell5.innerHTML = '<input type="date" class="form-control" id="ppm_visit_date' + rowNum + '" name="ppm_visit_datet[]">';
  cell6.innerHTML = '<input type="text" class="form-control" id="ppm_acnum' + rowNum + '" name="ppm_acnum[]" onkeyup="calculate_cost(rowNum);">';
  cell7.innerHTML = '<input type="text" class="form-control" id="ppm_rate' + rowNum + '" name="ppm_rate[]" onkeyup="calculate_cost(0);" value="50">';
  cell8.innerHTML = '<input type="text" class="form-control" id="ppm_cost' + rowNum + '" name="ppm_cost[]">';
  cell9.innerHTML = '<input type="text" class="form-control" id="ppm_tech' + rowNum + '" name="ppm_tech[]">';
  cell10.innerHTML = '<input type="text" class="form-control" id="ppm_notes' + rowNum + '" name="ppm_notes[]">';
  var optionsHTML = '<select class="form-control" id="ppmstatus' + rowNum + '" name="ppmstatus[]">' +
                  '<option value="Scheduled">Scheduled</option>' +
				  '<option value="Pending">Pending</option>' +
                  '<option value="Finished">Finished</option>' +
                  '</select>';

	cell11.innerHTML = optionsHTML;				
	cell12.innerHTML = '<a href="" onclick="deleteRow(this)"><span class="fa fa-trash"></span></a>';
  	rowNum++; 
}
function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}
function get_project_div() 
 {
   	var p_id = document.getElementById("project_name").value;
	if(p_id=='new')
	{		
		document.getElementById("project_div").style.display = "block";
		document.getElementById("newproject_name").required = true;
	}
	else
	{
		document.getElementById("newproject_name").required = false;
		document.getElementById("project_div").style.display = "none";
        }
	
 }   
 function createPPMTable() {
    var ppmCount = parseInt(document.getElementById('no_of_sch').value);

    var tableBody = document.getElementById('ppmTableBody');
    tableBody.innerHTML = ''; // Clear existing table rows

    for (var i = 0; i < ppmCount; i++) {
        var ppmNumber = "PPM" + (i + 1).toString().padStart(2, '0');

        var row = document.createElement('tr');
        row.id = 'row' + i;

        row.innerHTML = `
           
            <td><input type="text" class="form-control" id="ppm_num${i}" name="ppm_num[${i}]" value="${ppmNumber}" readonly></td>
            <td><input type="date" class="form-control" id="ppm_sch_date${i}" name="ppm_sch_date[${i}]"></td>
            <td><input type="date" class="form-control" id="ppm_finish_date${i}" name="ppm_finish_date[${i}]"></td>
            <td><input type="text" class="form-control" id="ppm_amt${i}" name="ppm_amt[${i}]"></td>
            <td><input type="text" class="form-control" id="ppm_remarks${i}" name="ppm_remarks[${i}]"></td>
            <td>
                <select class="form-control" id="ppm_status${i}" name="ppm_status[${i}]">
                    <option value="Scheduled">Scheduled</option>
                    <option value="Finished">Finished</option>
                </select>
          </td>
        `;

        tableBody.appendChild(row);
    }
}

function deleteRow(link) {
    var row = link.closest('tr');
    row.remove();
}

function calculate_cost(rowNum) {
    
    var acnumInput = document.getElementById('ppm_acnum' + rowNum);
	var rateValue = parseFloat(document.getElementById('ppm_rate' + rowNum).value);
    var acnumValue = parseFloat(acnumInput.value); 
    var costValue = acnumValue * rateValue;
    var costInput = document.getElementById('ppm_cost' + rowNum);
    costInput.value = costValue.toFixed(2); 
}

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Project",
        allowClear: true,
        width: '100%'
    });
});

document.getElementById("main").addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        return false;
    }
});
  </script>