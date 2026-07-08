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
					<th>Resign Code</th>
					<th>Employee Name</th>
					<th>Resignation Date:</th>
					<th>Last Working Date</th>
					<th>Notice Period</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				<?php $i = 1;
				foreach ($records as $row) { ?>
					<tr>
						<td><?php echo $i;
							$i++; ?></td>
						<td><?php echo $row->resign_code; ?></td>
						<td><?php echo $row->name; ?></td>
						<td><?php echo date('d-M-Y', strtotime($row->resignation_date)); ?></td>
						<td><?php echo date('d-M-Y', strtotime($row->last_working_date)); ?></td>
						<td><?php echo $row->notice_days; ?></td>

						<td class="action-icons">
							<a href="<?php echo base_url('index.php/Hr/edit_emp_resignation/' . $row->resig_id); ?>" title="Edit">
								<i class="fa fa-edit"></i>
							</a>

							<a href="<?php echo base_url('index.php/Hr/delete_resignation_application/' . $row->resig_id); ?>" 
							title="Delete">
								<i class="fa fa-trash"></i>
							</a>

							<a href="<?php echo base_url() . 'index.php/Hr/print_resignation_application/' . $row->resig_id; ?>" 
							title="Print" target="_blank">
								<i class="fa fa-print"></i>
							</a>
						</td>
					</tr>
				<?php  } ?>
			</tbody>
		</table>
	</div>
</div>

