<style>
	.action-icons i {
		font-size: 18px;
		margin: 0 5px;
		vertical-align: middle;
	}
</style>

<div class="card-body">
	<div class="dt-responsive table-responsive">
		<table id="datatable" class="table table-striped" data-toggle="data-table">
			<thead>
				<tr>
                <th>Sr No</th>
					<th>Employee Name</th>
					<th>passport release date</th>
					<th>Return date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1;
				foreach ($records as $row) { ?>
					<tr>
						<td><?php echo $i;
							$i++; ?></td>
						<td><?php echo $row->employee_name; ?></td>
						<td><?php echo date('d-M-Y', strtotime($row->outdate)); ?></td>
						<td><?php echo date('d-M-Y', strtotime($row->indate)); ?></td>
						

						<td class="action-icons">

							<a href="<?php echo base_url() . 'index.php/Hr/edit_passport_release/' . $row->emp_docId; ?>" title="Edit"><i class="fa fa-edit"></i><?php echo $this->session->userdata('edit_icon'); ?></a>

							<a href="<?php echo base_url() . 'index.php/Hr/print_passport_release/' . $row->emp_docId; ?>" title="Print" target="_blank"><i class="fa fa-print" style="font-size:18px"></i></i></a>

							<a href="<?php echo base_url() . 'index.php/Hr/delete_passport_release/' . $row->emp_docId; ?>" title="Delete" onclick="return confirmcancel(<?php echo $row->emp_docId; ?>);"><i class="fa fa-trash"></i><?php echo $this->session->userdata('delete_icon'); ?></a>

						</td>
					</tr>
				<?php  } ?>
			</tbody>
		</table>
	</div>
</div>
</div>
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
					table_name: 'employee_document_details',
					where_key: 'emp_docId',
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