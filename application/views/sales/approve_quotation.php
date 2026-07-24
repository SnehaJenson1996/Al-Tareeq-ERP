<div class="card-body">
	<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Sales/accept_quotation_approval"
		autocomplete="off" enctype="multipart/form-data">
		<div class="form-group row">
			<label class="col-xs-12 col-sm-2 col-md-3 col-lg-3 col-form-label">Select Quotation <span
					style="color: red;"> * </span></label>
			<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6" role='group'>
				<select tabindex="1" class="form-select form-control-sm select2" id="qid" name="qid" required>
					<option value="">Select</option>
					<?php foreach ($records as $s) { ?>
						<option value="<?php echo $s->qtn_id ?>"><?php echo $s->quotation_code . ' ' . $s->customer_name; ?>
						</option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Upload Client PO / Supporting Document
(PDF, JPG, JPEG, PNG)</label>
			<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<input type='file' name="po_file" id="po_file" tabindex='2' class="form-control form-control-sm" />
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Client PO Number</label>
			<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<input type='text' name="po_no" id="po_no" tabindex='3' class="form-control form-control-sm" />
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Remark</label>
			<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<textarea cols='50' rows='4' name="approval_remark" id="approval_remark" tabindex='4'
					class="form-control form-control-sm"></textarea>
			</div>
		</div>


		<div class="form-group row">
			<label class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-form-label">Status</label> 
			<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<select class="form-control" id="status" name="status" required>
    <option value="">Select Status</option>
    <option value="1">Approve & Lock Quotation</option>
    <option value="2">Reject Quotation</option>
</select>
			</div>
		</div>
				<div class="form-group row">
			<label class="col-sm-2"></label>
			<div class="col-sm-10">
				<button type="submit" tabindex="9" id="add" class="btn btn-primary m-b-0">Submit Status</button>
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

</script>