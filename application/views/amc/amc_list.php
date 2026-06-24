
<div class="card-body">
		<div class="dt-responsive table-responsive">
			<table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
							<th>Sr.no</th>
                            <th>AMC Code</th>
							<th>Customer</th>
							<th>AMC Start Date</th>
							<th>AMC End Date</th>
							<th>Amount</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					<?php $i=1; foreach($records as $row) :?>
						<tr>
							<td><?php echo $i;$i++;?></td>
							<td><?php echo $row->invoice_code ?? ''; ?></td>

<td>
    <?php echo ($row->customer_code ?? '') . "-" . ($row->customer_name ?? ''); ?>
</td>

<td>
    <?php echo !empty($row->amc_start_date) ? date('d-M-Y', strtotime($row->amc_start_date)) : ''; ?>
</td>

<td>
    <?php echo !empty($row->amc_end_date) ? date('d-M-Y', strtotime($row->amc_end_date)) : ''; ?>
</td>
							<td><?php echo $row->grand_total;?></td>
							
							<td class="text-nowrap">

    <!-- EDIT -->
    <a href="<?php echo base_url().'index.php/AMC/edit_amc/'.$row->invoice_id;?>"
       title="Edit Quotation"
       class="btn btn-sm btn-primary">
        <i class="fa fa-edit"></i>
    </a>
							<!-- <td>
							<a href="<?php echo base_url().'index.php/AMC/edit_amc/'.$row->invoice_id;?>" title="Edit"><?php echo $this->session->userdata('edit_icon');?></a> -->
						
							<a target='_blank' href="<?php echo base_url().'index.php/AMC/print_amc/'.$row->invoice_id;?>" title="print AMC">Print</a>
							<br/>
							<a href="javascript:confirmcancel(<?php echo $row->quote_id;?>,<?php echo $row->invoice_id;?>)" title="Delete" class='delete' id='delete'><?php echo $this->session->userdata('delete_icon');?></a>
							
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

function confirmcancel(quote_id,invoice_id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/AMC/delete_amc",
     		type: "POST",
     		data: {quote_id:quote_id,invoice_id:invoice_id} ,
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
        
