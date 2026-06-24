<div class="card-body">
	<div class="dt-responsive table-responsive">
		<table id="datatable" class="table table-striped" data-toggle="data-table">
			<thead>
				<tr class="headings">
				   	<th>Account Code</th>
				  	<th>Account Name</th>
				  	<th>Account Group</th>
				  <!--	<th>Opening Balance</th>-->                 	
				  	<th>Action</th>
			    	</tr>
                	</thead>
                	<tbody>
				<?php foreach ($ledger_records as $row) : ?> 
				    <tr>
				    	<td><?php echo $row->account_id;?></td>
				    	<td><?php echo $row->account_name;?></td>
				    	<td><?php echo $row->group_name;?></td>
				 		<!--<td><?php echo $row->opening_balance .' '. $row->opening_balance_type ;?></td> -->               	
				    	<td>
					  	<a href="<?php echo base_url() . 'index.php/Accounts/edit_general_ledger_account_form/'.$row->account_id;?>" title="Edit" ><span class="btn btn-xs btn-success fa fa-edit"></span></a>
						<?php if($row->group_name=='Farmer' || $row->group_name=='Vendors/Suppliers') { ?>
						<a title="Delete" onclick="delete_area(<?php echo $row->account_id;?>)" id='delete' ><span class="btn btn-xs btn-danger fa fa-trash" ></span></a>
						<?php } ?>
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
        
<script>
    function delete_area(id) {
   	
	 var x;
	var r=bootbox.confirm("Are you sure you want to delete record?!", function (res) {
	if(res == true) 
        {
		  	$.ajax({
             		url: "<?php echo base_url()?>index.php/accounts/delete_ledger_record",
             		type: "POST",
             		dataType: "json",
             		data: {account_id:id} ,
             		success: function(msg) {
            	 	if(msg==1)
                           {
                                  	alert("The record is deleted!");
                                    window.location.href="<?php echo $_SERVER['PHP_SELF']?>";
                                    }
                                    else
                                    {
                                    bootbox.alert("The record is not deleted!");
                                    }

                             },
                             error: function(xhr, textStatus, errorThrown)
                             {
                        alert('Cannot deleted mater records');
                            }
                        });
         }
	else
        	return false;

}
)};
</script>
