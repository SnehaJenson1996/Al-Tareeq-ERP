<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$logged_user = $this->session->userdata('user_id');
?>

<style>
	.remarks-wrap {
		white-space: normal !important;
		word-wrap: break-word;
		overflow-wrap: break-word;
		max-width: 400px;
	}
	.action-icons i {
		font-size: 18px;
		margin: 0 5px;
		vertical-align: middle;
	}
</style>

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_content">
					
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

					<table id="departmentTable" class="table table-hover">
						<thead>
							<tr>
								<th>Sr.No.</th>
								<th>Department Name</th>
								<th>Status</th>
								<th>Remark</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php if(!empty($department_list)){ ?>
							<?php $i=1; foreach($department_list as $row){ ?>

							<tr>
								<td><?= $i++; ?></td>
								<td><?= $row->dept_name; ?></td>

								<td>
									<?= ($row->status==0) ? 'Active' : 'Inactive'; ?>
								</td>

								<td class="remarks-wrap">
									<?= $row->remark; ?>
								</td>

								<td class="action-icons">
									<?php if(has_access($logged_user,$page_name,'E')){ ?>
										<a href="<?= base_url('index.php/Company/edit_department/'.$row->dept_id); ?>"title='Edit'>
											<i class="fa fa-edit"></i>
										</a>
									<?php } ?>

										<a href="javascript:void(0)" onclick="confirmDeleteDepartment(<?= $row->dept_id ?>)"title="Delete">
											<i class="fa fa-trash"></i>
										</a>
								</td>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>		
    		</div>
    	</div>
	</div>


<script>

function confirmDeleteDepartment(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",
     		type: "POST",
     		data: {table_name:'department_master', where_key:'dept_id', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href=window.location.pathname;;   		                    		  
			}
		        else {
			      	alert("Can't Delete record. Data already exist!!!");
		       }
		    },
		});
      		return true;
      	}
        else
        	return false;
	    	
}
$(document).ready(function () {
    $('#departmentTable').DataTable({
        pageLength: 10
    });
});
</script>
