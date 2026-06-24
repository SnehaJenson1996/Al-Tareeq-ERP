
<div class="card-body">
		<div class="dt-responsive table-responsive">
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
							<th>Sr.no</th>
                            <th>PPM Code</th>
							<th>Project</th>
							<th>Date</th>
							<th>Remarks</th>
							<th></th>
							
							
						</tr>
					</thead>

					<tbody>
					<?php $i=1; foreach($records as $row) :?>
						<tr>
							<td><?php echo $i;$i++;?></td>
							<td>
    <a href="<?php echo base_url().'index.php/AMC/edit_ppm/'.$row->ppm_id.'/ppm_list'; ?>">
        <?php echo $row->ppm_code; ?>
    </a>
</td>
							
							<?php echo $row->project_name?></td>
							<td>
							<?php echo $row->ppm_date;?></td>
							<td>
							<?php echo $row->remarks;?>	
							</td>
							<td>
    <!-- Edit Icon -->
    <a href="<?php echo base_url().'index.php/AMC/edit_ppm/'.$row->ppm_id.'/ppm_list'; ?>" title="Edit">
    <i class="fa fa-edit" style="color: #007bff;"></i>
</a>

    <!-- Delete Icon -->
    <a href="javascript:confirmcancel(<?php echo $row->ppm_id;?>)" title="Delete" style="margin-left: 10px;">
        <i class="fa fa-trash" style="color: red;"></i>
    </a>
</td>

							
							
						</tr>
					<?php endforeach; ?>
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

function confirmcancel(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/AMC/delete_ppm",
     		type: "POST",
     		data: {id:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href=window.location.pathname; 	                    		  
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
</script>
        
