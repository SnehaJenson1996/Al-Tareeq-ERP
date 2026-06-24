<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_title">
				<h2>Edit Designation</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form action="<?= base_url('index.php/Company/update_designation/'.$designation->id) ?>" method="post" autocomplete="off">
                    <input type="hidden" name="designation_id" value="<?=$designation_id?>">
					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Designation Code</label>
							<input type="text" name="designation_code" value="<?= $designation->designation_code ?>" class="form-control" readonly>
						</div>
						<div class="col-md-6 form-group">
							<label>Designation Name</label>
							<input type="text" name="designation_name" value="<?= $designation->designation_name ?>" class="form-control" required>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Department</label>
							<input type="text" name="department" value="<?= $designation->department ?>" class="form-control">
						</div>
						<div class="col-md-6 form-group">
							<label>Reporting To</label>
							<input type="text" name="reporting_to" value="<?= $designation->reporting_to ?>" class="form-control">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Level / Grade</label>
							<input type="text" name="level" value="<?= $designation->level ?>" class="form-control">
						</div>
						<div class="col-md-6 form-group">
							<label>Employment Type</label>
							<select name="employment_type" class="form-control">
								<option <?= ($designation->employment_type == "Permanent") ? "selected" : "" ?>>Permanent</option>
								<option <?= ($designation->employment_type == "Contract") ? "selected" : "" ?>>Contract</option>
								<option <?= ($designation->employment_type == "Intern") ? "selected" : "" ?>>Intern</option>
								<option <?= ($designation->employment_type == "Consultant") ? "selected" : "" ?>>Consultant</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Job Location</label>
							<input type="text" name="location" value="<?= $designation->location ?>" class="form-control">
						</div>
						<div class="col-md-6 form-group">
							<label>Required Skills</label>
							<input type="text" name="skills" value="<?= $designation->skills ?>" class="form-control">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Qualification</label>
							<input type="text" name="qualification" value="<?= $designation->qualification ?>" class="form-control">
						</div>
						<div class="col-md-6 form-group">
							<label>Experience</label>
							<input type="text" name="experience" value="<?= $designation->experience ?>" class="form-control">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Job Description</label>
							<textarea name="job_description" class="form-control"><?= $designation->job_description ?></textarea>
						</div>
						<div class="col-md-6 form-group">
							<label>Responsibilities</label>
							<textarea name="responsibilities" class="form-control"><?= $designation->responsibilities ?></textarea>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 form-group">
							<label>Status</label>
							<select name="status" class="form-control">
								<option value="Active" <?= ($designation->status == "Active") ? "selected" : "" ?>>Active</option>
								<option value="Inactive" <?= ($designation->status == "Inactive") ? "selected" : "" ?>>Inactive</option>
							</select>
						</div>
					</div>

					<div class="form-group mt-3">
						<div class="col-md-6 offset-md-3">
							<button type="submit" class="btn btn-success">Update</button>
							<a href="<?= base_url('index.php/Company/list_designation') ?>" class="btn btn-secondary">Cancel</a>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
