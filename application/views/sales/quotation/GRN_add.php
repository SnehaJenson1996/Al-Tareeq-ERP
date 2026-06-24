<div class="card-body">

	<form onsubmit="return check_total();" id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/add_grn_records" id="addform" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Select PO<span style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-2 col-md-6 col-lg-6">
				<select name="pid" id="pid" class="form-control select2" required onchange="get_po_detail()">
					<option value="">Please select</option>
					<?php foreach ($po_records as $g) { ?>
						<option value="<?php echo $g->po_id; ?>"><?php echo $g->po_code . ' ' . $g->supplier_name; ?> </option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div id="item_list_id">
		</div>

		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">GRN By Person:</label>
			<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
				<select tabindex="1" class="form-select form-control-sm select2" id="user_id" name="user_id" required style='width:170px'>
					<option value="">Select</option>
					<?php foreach ($user_records as $s) { ?>
						<option <?php if ($this->session->userdata('user_id') == $s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name; ?></option>
					<?php } ?>
				</select>
			</div>
			<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-form-label">Select Stamp</label>
			<div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
				<select tabindex="2" class="form-select form-control-sm" id="stamp" name="stamp">
					<option value="">Select</option>
					<?php foreach ($stamp_details as $r) { ?>
						<option <?php if ($r->img_id == 4) echo 'selected'; ?> value="<?php echo $r->img_id; ?>"><?php echo $r->stamp_name; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
<!-- ====================manju===================== -->
		Purchase Invoice Account Entry :
		<div class="form-group row">
			<div class="col-md-6">
				<table class="table table-bordered table-hover" id="inv_dr_table">
					<thead>
						<tr>
							<th title="Item">Debit Purchase (Dr)</th>
							<th title="Item">Debit Amount (AED)</th>
							<!--<th width='10%'><a id="inv_dr_add_row" title="Add" class="btn btn-sm bg-orange" ><span class="fa fa-plus"></span></a></th>-->

						</tr>
					</thead>
					<tbody id="inv_dr_body">
						<tr id='inv_dr_addr0'>
							<td>
								<select class="form-select form-control-sm select2 select2Width" id="inv_debtor0"
									name="inv_debtor[]">
									<option value="">Select</option>
									<?php foreach ($sundry_accounts1 as $row) { ?>
										<option <?php if ($row->account_id == 1120)
													echo 'selected'; ?>
											value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
										</option>
									<?php } ?>
								</select>
							</td>
							<td><input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount0"
									class="form-control form-control-sm debit_sum" min=0>
							</td>
							<!--<td><a id='delete_row1' title="Delete" onclick='remove_row_inv_dr(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>-->
						</tr>
						<tr id='inv_dr_addr1'>
							<td>
								<select class="form-select form-control-sm select2 select2Width" id="inv_debtor1"
									name="inv_debtor[]">
									<option value="">Select</option>
									<?php foreach ($sundry_accounts3 as $row) { ?>
										<option <?php if ($row->account_id == 1122)
													echo 'selected'; ?>
											value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
										</option>
									<?php } ?>
								</select>
							</td>

							<td><input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount1"
									class="form-control form-control-sm debit_sum" onkeyup="calculate_grand_total()">
							</td>
							<!--<td><a id='delete_row1' title="Delete" onclick='remove_row_inv_dr(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>-->
						</tr>
						<tr id='inv_dr_addr2'>
							<td>
								<select class="form-select form-control-sm select2 select2Width" id="inv_debtor2"
									name="inv_debtor[]">
									<option value="">Select</option>
									<?php foreach ($sundry_accounts3 as $row) { ?>
										<option <?php if ($row->account_id == 226)
													echo 'selected'; ?>
											value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
										</option>
									<?php } ?>
								</select>
							</td>
							<td><input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount2"
									class="form-control form-control-sm debit_sum" min=0
									onkeyup="calculate_grand_total()">
							</td>
							<!--<td><a id='delete_row1' title="Delete" onclick='remove_row_inv_dr(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>-->
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered table-hover" id="inv_cr_table">
					<thead>
						<tr>
							<th title="Item">Credit Supplier (Cr)</th>
							<th title="Item">Credit Amount (AED)</th>


						</tr>
					</thead>
					<tbody id="inv_cr_body">
						<tr id='inv_cr_addr0'>
							<td>
								<select class="form-select form-control-sm select2Width" id="inv_creditor0"
									name="inv_creditor[]">
									<option value="">Select</option>
									<?php foreach ($sundry_accounts2 as $row) { ?>
										<option <?php if ($row->account_id == 228)
													echo 'selected'; ?>
											value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
										</option>
									<?php } ?>
								</select>
								<!--<label id='set_balanceinv_cr0'>Balance</label>-->
							</td>
							<td><input type="number" step='0.001' name="inv_cr_amount[]" id="inv_cr_amount0"
									class="form-control form-control-sm credit_sum" required min=0>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

<!-- ====================================================== -->


		<div class="form-group row">
			<label class="col-sm-2"></label>
			<div class="col-sm-10">
				<button type="submit" id='add' class="btn btn-primary m-b-0">Add GRN Entry</button>
				<button type="reset" id='reset' class="btn btn-primary m-b-0">Reset</button>
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
	function get_po_detail() {
		var qid = document.getElementById("pid").value;
		if (qid != '') {
			$.ajax({
				async: "false",
				type: "POST",
				url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_po_for_grn",
				data: {
					qid: qid
				},
				success: function(msg) {
					document.getElementById('item_list_id').innerHTML = msg;
					// ==============Accounts===========================
					$('.datepicker1').datepicker({
						format: "dd-mm-yyyy",
						todayHighlight: "true",
						//endDate:"today",
						toggleActive: "true",
						autoclose: true,
					});
					//alert(111);
					$.ajax({
						type: "POST",
						url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_supplier_accountId_from_po",
						data: { po_id: qid },
						success: function (accid) {

							document.getElementById('inv_creditor0').value = accid;
							var grand_total = document.getElementById("grand_total").value;
							var sub_total = document.getElementById("sub_total").value;
							var discount_amt = document.getElementById("discount_amt").value;
							var vat_amt = document.getElementById("vat_amt").value;
							var crate = document.getElementById('crate').value;
							var x = (grand_total * crate).toFixed(2);
							
							document.getElementById("inv_cr_amount0").value = x;
							document.getElementById("inv_dr_amount0").value = (sub_total * crate).toFixed(2);
							document.getElementById("inv_dr_amount1").value = (discount_amt * crate).toFixed(2);
							document.getElementById("inv_dr_amount2").value = (vat_amt * crate).toFixed(2);
						}
					});

					// =========================================================
				}
			});

		}
	}

	function remove_row(append_id) {
		$('#addr' + append_id).attr("id", "addr" + append_id + "x");
		$('#addr' + append_id + "x").remove();
		calculate_grand_total();
	}

	function check_vat_option() {
		var checkBox = document.getElementById("vatbox");
		var vat_percent = document.getElementById("vat_percent1").value;

		// If the checkbox is checked, display the output text
		if (checkBox.checked == true) {
			$("#vat_percent").val(vat_percent);
			var total_before_vat = document.getElementById("total_before_vat").value;
			var subtot = parseFloat(total_before_vat * (vat_percent / 100)).toFixed(2);
			var x = parseFloat(total_before_vat) + parseFloat(subtot);
			document.getElementById("vat_amt").value = subtot;
			document.getElementById("grand_total").value = parseFloat(x).toFixed(2);

		} else {

			$("#vat_percent").val(0);
			document.getElementById("vat_amt").value = 0.00;
			var total_before_vat = document.getElementById("total_before_vat").value;
			document.getElementById("grand_total").value = total_before_vat;
		}
	}

	function get_currency_conversion() {
		var str = $('#currency_id').val();
		var myarray = str.split("@");
		var cid = myarray[0];
		var crate = myarray[1];
		var currabrev = myarray[2];
		document.getElementById('cid').value = cid;
		document.getElementById('crate').value = crate;
		document.getElementById('currabrev').innerHTML = currabrev;

		var grand_total = document.getElementById("grand_total").value;
		var crate = document.getElementById('crate').value;
		var x = (grand_total * crate).toFixed(2);
		document.getElementById("inv_cr_amount0").value = x;

		calculate_grand_total();
	}

	function calculate_total(append_id) {
		var price = parseFloat(document.getElementById("price" + append_id).value);
		var quantity = parseFloat(document.getElementById("qty" + append_id).value);
		var total = price * quantity;
		document.getElementById("total" + append_id).value = parseFloat(total).toFixed(2);

		calculate_grand_total();
	}

	function calculate_grand_total() {
		var i_value = 0;
		i_total = 0;
		$('.subItemAmt').each(function() {
			i_value = $(this).val();
			if (i_value == '')
				i_value = 0;
			else
				i_total += parseFloat(i_value);
		});
		if (isNaN(i_total)) var s_total = 0;

		document.getElementById("sub_total").value = parseFloat(i_total).toFixed(2);

		if (document.getElementById("discount").value == 0)
			var discount = 0;
		else {
			var discount_per = parseFloat(document.getElementById("discount").value / 100);
			var discount = i_total * discount_per
		}
		var total_before_vat = i_total - discount;

		document.getElementById("discount_amt").value = parseFloat(discount).toFixed(2);
		document.getElementById("total_before_vat").value = parseFloat(total_before_vat).toFixed(2);


		var vat_percent = document.getElementById("vat_percent").value;
		var vat_per = parseFloat(vat_percent / 100);
		var calVatAmt = parseFloat(total_before_vat * vat_per);
		document.getElementById("vat_amt").value = parseFloat(calVatAmt).toFixed(2);
		var grand_total = parseFloat(calVatAmt + total_before_vat);

		//var crate= document.getElementById('crate').value;
		var crate = 1;
		var grand_total = parseFloat(grand_total * crate);
		document.getElementById("grand_total").value = parseFloat(grand_total).toFixed(2);


		document.getElementById("inv_cr_amount0").value = parseFloat(grand_total * document.getElementById('crate').value).toFixed(2);
	}

	function add_new_row(rowId) {
		var rowcount = document.getElementById("rowcount" + rowId).value;
		var newcnt = parseInt(rowcount) + 1;
		document.getElementById("rowcount" + rowId).value = newcnt;
		var model_code = document.getElementById("product_id" + rowId).value;

		$.ajax({
			async: "false",
			type: "POST",
			url: "<?php echo base_url() ?>index.php/Ajax/ajax_add_bill_row_grn",
			data: {
				rowId: rowId,
				newcnt: newcnt,
				model_code: model_code
			},
			success: function(msg) {
				//document.getElementById('stock_addr'+rowId+rowcount).innerHTML=msg;
				$('#stock_addr' + rowId + rowcount).replaceWith(msg);
			}
		});
	}

	function remove_bill_row(append_id) {
		$('#tr_row' + append_id).attr("id", "tr_row" + append_id + "x");
		$('#tr_row' + append_id + "x").remove();
	}

	function check_total() {
		/*var k_value=0;k_total=0;
		$('.credit_sum').each(function()
		{
			k_value=$(this).val();
			if(k_value=='')
				 k_value = 0;
			else
				k_total+=parseFloat(k_value);
		});
		if(isNaN(k_total)) var cr_total = 0;
		*/

		var a1 = parseFloat(document.getElementById("inv_dr_amount0").value);
		var a2 = parseFloat(document.getElementById("inv_dr_amount1").value);
		var a3 = parseFloat(document.getElementById("inv_dr_amount2").value);
		var k_total = parseFloat(a1 + a2 - a3).toFixed(2);

		var dr_total = $('#inv_cr_amount0').val();

		if (parseFloat(k_total) != parseFloat(dr_total)) {
			alert("Both debit total and credit total must match");
			// return false;
		}
	}
	function calculate_stock_qty(row){

	var grn_qty = $('#qty'+row).val()||0;
	var conv = $('#conv_factor'+row).val()||0;

	var stock_in_qty = parseFloat(grn_qty) * parseFloat(conv);
	$('#stk_in_qty'+row).val(stock_in_qty.toFixed(2));

}
</script>