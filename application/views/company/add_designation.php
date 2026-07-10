<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_title">
				
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form action="<?= base_url('index.php/Company/save_designation') ?>" method="post" autocomplete="off">

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Designation Code <span class="required">*</span></label>
								<?= form_error('designation_code', '<small class="text-danger">', '</small>') ?>
								<input type="text" name="designation_code" class="form-control" value="<?=$designation_code?>" readonly>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Designation Name <span class="required">*</span></label>
								<?= form_error('designation_name', '<small class="text-danger">', '</small>') ?>
								<input type="text" name="designation_name" class="form-control" required>
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Department</label>
								<?= form_error('department', '<small class="text-danger">', '</small>') ?>
								<select name="department" class="form-control select2" required>
									<option value="">-- Select Department --</option>
									<?php foreach($departments as $dept){ ?>
										<option value="<?= $dept->dept_name; ?>">
											<?= $dept->dept_name; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Reporting To</label>
								<input type="text" name="reporting_to" class="form-control">
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Level / Grade</label>
								<input type="text" name="level" class="form-control" placeholder="e.g., Junior, L3, Senior">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Employment Type</label>
								<select name="employment_type" class="form-control">
									<option value="">-- Select Type --</option>
									<option value="Permanent">Permanent</option>
									<option value="Contract">Contract</option>
									<option value="Intern">Intern</option>
									<option value="Consultant">Consultant</option>
								</select>
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Job Location</label>
								<input type="text" name="location" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Required Skills</label>
								<input type="text" name="skills" class="form-control" placeholder="e.g.,courses,Communication">
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Qualification</label>
								<input type="text" name="qualification" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Experience</label>
								<input type="text" name="experience" class="form-control" placeholder="e.g., 2-4 Years">
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Job Description</label>
								<textarea name="job_description" class="form-control" rows="2"></textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Responsibilities</label>
								<textarea name="responsibilities" class="form-control" rows="2"></textarea>
							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-6 offset-md-3">
							<button type="submit" class="btn btn-success">Submit</button>
							<button type="reset" class="btn btn-secondary">Reset</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
