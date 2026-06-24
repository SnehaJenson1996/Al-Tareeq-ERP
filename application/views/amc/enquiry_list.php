<div class="card-body">
		<div class="dt-responsive table-responsive">
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                                        <thead>
                                            <tr>
							<th>Sr.no</th>
							<th>Enq Code</th>
							<th>Date</th>
							<th>Project</th>
							<th>Customer</th>
							
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					<?php $i=1; foreach($records as $row) :?>
						<tr>
							<td><?php echo $i;$i++;?></td>
							<td>
								<?php echo $row->amc_enq_code; ?>
								
								<?php //if ($row->enq_type=='1') echo 'Company Products'; else if($row->enq_type=='2') echo 'New Products'; else if ($row->enq_type=='3') echo 'Partial';?>
								
							</td>
							<td>
								<?php echo date('d-M-Y',strtotime($row->enq_date));?><br>
								
							</td>
							<td><?php echo $row->project_name; ?></td>
							<td>
								<a title="View customer details" target='blank' href="<?php echo base_url().'index.php/Users/edit_customer/'.$row->cust_id;?>" >
								<?php echo $row->customer_name;?>
								</a>
							</td>
							
							<td>
							<a href="<?php echo base_url().'index.php/AMC/edit_enquiry/'.$row->amc_enq_id.'/1'.'/'.$row->revision;?>" title="Edit"><?php echo $this->session->userdata('edit_icon');?></a>
							<a href="javascript:confirmcancel(<?php echo $row->amc_enq_id;?>)" title="Delete" class='delete' id='delete'><?php echo $this->session->userdata('delete_icon');?></a>
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

function confirmcancel(enquiry_id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/AMC/delete_enquiry",
     		type: "POST",
     		data: {enquiry_id:enquiry_id} ,
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
