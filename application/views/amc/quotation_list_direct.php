<div class="card-body">
		<div class="dt-responsive table-responsive">
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                                        <thead>
                                            <tr>
							<th>Sr.no</th>
							<th>Quotation Code</th>
							
							<th>Customer & Customer Ref</th>
							
							<th>Grand total</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					<?php $i=1; foreach($records as $row) :?>
						<tr>
							<td><?php echo $i;$i++;?></td>
							<td><?php echo $row->quotation_code;?>
							<br>
							<?php echo date('d-M-Y',strtotime($row->quotation_date));?>
							</td>
							
							<td>
								<a title="View customer details" target='blank' href="<?php echo base_url().'index.php/Users/edit_customer/'.$row->customer_id;?>" >
								<?php echo $row->customer_name;?>
								</a>
								<br>
								<?php echo $row->client_ref;?>
							</td>
							
							<td><?php echo $row->grand_total;?>
							
							</td>
							<td class="text-nowrap">

    <!-- EDIT -->
    <a href="<?php echo base_url().'index.php/AMC/edit_direct_quotation/'.$row->quote_id.'/'.$row->revision.'/1';?>"
       title="Edit Quotation"
       class="btn btn-sm btn-primary">
        <i class="fa fa-edit"></i>
    </a>

    <!-- DELETE -->
  <a href="javascript:void(0);"
   onclick="confirmcancel(<?= $row->quote_id ?>, <?= $row->enq_master_id ?>)"
   title="Delete Quotation"
   class="btn btn-sm btn-danger">
    <i class="fa fa-trash"></i>
</a>


    <!-- PRINT -->
    <a target="_blank"
       href="<?php echo base_url().'index.php/AMC/print_quotation/'.$row->quote_id.'/'.$row->revision.'/'.$row->enq_type.'/1/1';?>"
       title="Print Quotation"
       class="btn btn-sm btn-info">
        <i class="fa fa-print"></i>
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

function confirmcancel(id,enq_master_id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_amc_quotation",
     		type: "POST",
     		data: { quoteID:id, enq_master_id:enq_master_id} ,
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
