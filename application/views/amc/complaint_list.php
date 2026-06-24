
<div class="card-body">
		<div class="dt-responsive table-responsive">
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
							<th>Sr.no</th>
                            <th>Code/Project</th>
							
							<th>Receive Date</th>
							<th>Close Date</th>
							<th>Type</th>
							<th>Picture</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					<?php $i=1; foreach($records as $row) :?>
						<tr>
							<td><?php echo $i;$i++;?></td>
							<td>
								<a href="<?php echo base_url().'index.php/AMC/edit_complaint/'.$row->cmp_id.'';?>"><?php echo $row->cmp_code;?></a>
							<br/>
							<?php echo $row->project_name;?>
							<br/>
							<?php echo $row->location;?>	
							</td>
							
                            
                            <td><?php echo date('d-M-Y',strtotime($row->receive_date));?></td>
							<td><?php echo date('d-M-Y',strtotime($row->close_date));?></td>
							<td><?php echo $row->cmp_type;?></td>
							<td><img src="<?php echo base_url('public/uploaded_documents/'.$row->cmp_file); ?>" width="50px" height="50px"/></td>
							<td><?php echo $row->cmp_status;?></td>
							<td>
    <a href="javascript:confirmcancel(<?php echo $row->cmp_id;?>)"
       title="Delete"
       class="text-danger">
        <i class="fa fa-trash"></i>
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
     		url: "<?php echo base_url()?>index.php/AMC/delete_cmp",
     		type: "POST",
     		data: {cmp_id:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
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
        
