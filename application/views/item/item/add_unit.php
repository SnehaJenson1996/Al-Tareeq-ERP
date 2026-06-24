<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />

				<form action="<?php echo base_url('index.php/Item/add_unit'); ?>" method="post" autocomplete='off' enctype="multipart/form-data">
					
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="unit_name">Unit Name<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="text" id="unit_name" name="unit_name" required="required" class="form-control" 
								value="<?= isset($unit[0]->unit_abbr) ? htmlspecialchars($unit[0]->unit_abbr) : '' ?>">
						</div>
					</div>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="unit_code">Unit Code<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="text" id="unit_code" name="unit_code" required="required" class="form-control" 
								value="<?= isset($unit[0]->unit_name) ? htmlspecialchars($unit[0]->unit_name) : '' ?>">
						</div>
					</div>

					<div class="item form-group">
						<div class="col-md-6 col-sm-6 offset-md-3">
							<?php if (isset($unit)): ?>
								<!-- Edit mode -->
								<button type="submit" name="action" class="btn btn-success" value="update">Update</button>
								<!-- hidden field to identify which unit to update -->
								<input type="hidden" name="unit_id" value="<?= $unit[0]->unit_id ?>">
							<?php else: ?>
								<!-- Add mode -->
								<button type="submit" name="action" class="btn btn-success" value="save">Save</button>
							<?php endif; ?>
						</div>
					</div>  
				</form>


			</div>
		</div>
	</div>
</div>

