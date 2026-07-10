<style>
    .action-icons i {
        font-size: 18px;
        margin: 0 5px;
        vertical-align: middle;
    }
</style>

<div class="card-body">
	<div class="dt-responsive table-responsive">

		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger">
				<?php echo $this->session->flashdata('error'); ?>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success">
				<?php echo $this->session->flashdata('success'); ?>
			</div>
		<?php endif; ?>

		<table id="datatable" class="table table-striped" data-toggle="data-table">
			<thead>
				<tr>
					<th>Sr No</th>
					<th>Leave Code</th>
					<th>Employee Name</th>
					<th>Leave Type</th>
					<th>Leave From / To</th>
					<th>Applied On</th>
					<th>Leave Status</th>
					<!-- <th>Approved Admin/MD</th>
					<th>Approved Hr</th> -->
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				<?php foreach ($records as $row) { ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $row->leave_code; ?></td>
						<td><?php echo $row->employee_name; ?></td>
						<td><?php echo $row->leave_type; ?></td>
						<td><?php
							// Check if start_date and end_date are not null before applying strtotime()
							$start_date = isset($row->start_date) ? strtotime($row->start_date) : false;
							$end_date = isset($row->end_date) ? strtotime($row->end_date) : false;

							// If the dates are valid, format them; otherwise, display empty or a default message
							echo ($start_date !== false ? date('d-M-Y', $start_date) : '') . ' <br> ' . ($end_date !== false ? date('d-M-Y', $end_date) : '');
							?>
						</td>
						<td><?php echo date('d-M-Y', strtotime($row->application_date)); ?></td>

						<td>
							<?php
							$badge = '<span class="badge badge-warning">
										<i class="fa fa-clock-o"></i> Pending
									</span>';

							foreach ($record1 as $app) {
								if ($row->leave_id == $app->approval_leave_id) {

									if ($app->leave_status == 1) {
										$badge = '<span class="badge badge-success">
													<i class="fa fa-check"></i> Approved
												</span>';
									}
									elseif ($app->leave_status == 2) {
										$badge = '<span class="badge badge-danger">
													<i class="fa fa-times"></i> Rejected
												</span>';
									}
									else {
										$badge = '<span class="badge badge-warning">
													<i class="fa fa-clock-o"></i> Pending
												</span>';
									}

									break;
								}
							}

							echo $badge;
							?>
						</td>

						<!-- <?php
								$admin_md_name = '';
								$hr_name = '';

								foreach ($record2 as $a) {
									if ($row->admin_md == $a->user_id) {
										$admin_md_name = $a->user_name;
									}
									if ($row->hr == $a->user_id) {
										$hr_name = $a->user_name;
									}
								}
								?>
						<td><?php echo $admin_md_name; ?></td>
						<td><?php echo $hr_name; ?></td> -->

						<td class="action-icons">
							<a href="<?php echo base_url() . 'index.php/Hr/edit_leave_application/' . $row->leave_id; ?>" title="Edit"><i class="fa fa-edit"></i>
							</a> 

							<a href="<?php echo base_url() . 'index.php/Hr/delete_leave_application/' . $row->leave_id; ?>"
								title="Delete" onclick="return confirmcancel(<?php echo $row->leave_id; ?>);">
								<i class="fa fa-trash"></i>
							</a>

							<a href="<?php echo base_url() . 'index.php/Hr/print_leave_application/' . $row->leave_id; ?>" title="Print" target="_blank"><i class="fa fa-print" style="font-size:18px"></i>
							</a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
</div>
</div>

<!-- Static Table End -->

<script>
	function confirmcancel(tid) {
		var r = confirm("Are you sure you want to Delete Record?");
		if (r == true) {
			$.ajax({
				url: "<?php echo base_url() ?>index.php/Ajax/delete_record",
				type: "POST",
				data: {
					table_name: 'employee_leave',
					where_key: 'leave_id',
					where_val: tid
				},
				success: function(msg) {
					if (msg == 1) {
						window.location.href = "<?php echo $_SERVER['PHP_SELF'] ?>";
					} else {
						alert("Can't Delete record. Data already exist!!!");
					}
				},
			});
			return true;
		} else
			return false;
	}
</script>