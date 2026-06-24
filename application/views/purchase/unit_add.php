<div class="card-body">
					<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Setup/add_unit_records" autocomplete="off">
						<div class="form-group row">
						<label class="col-sm-2 col-form-label">Unit Name <span style="color: red;"> * </span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="uname" id="uname" placeholder="Enter unit name" required>
							</div>
						</div>

						<div class="form-group row">
						<label class="col-sm-2 col-form-label">Unit Abbreviation <span style="color: red;"> * </span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="uabbr" name="uabbr"  placeholder="Enter your unit abbreviation" required>
							</div>
						</div>

						

						<div class="form-group row">
						<label class="col-sm-2"></label>
							<div class="col-sm-4">
							<button type="submit" id="add" class="btn btn-primary m-b-0">Save</button>
							<button type="reset" class="btn btn-primary m-l-50">Reset</button>
							</div>
						</div>
					</form>

					

        </div>
    </div>
</div>
</div>
</div>
</div>
         
