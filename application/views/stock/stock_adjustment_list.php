   <!-- page content -->
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Stock/add_stock_adjustment_records" autocomplete="off" enctype="multipart/form-data">
	
          <!-- page content -->
          <div class="form-group" role="main">
          <div class="">
            <div class="page-title"></div>
            <div class="clearfix"></div>
            


            
            <div class="x_content">
                  
           
          <div class="col-md-12">
        <div class="dt-responsive table-responsive">
        <table class="table table-bordered table-hover" id="tab_logic">
            <thead>
            <tr>
                  <th title="Item">Sl. No </th> 
                  <th title="Item">Stock Code</th>
                  <th title="Item">Item Details</th>      
                  <th title="Item">Stock Date</th>      
                  <th title="Item">Stock Type</th>   
                  <th title="Item">Remark</th>   
                  <th ></th>   
            </tr>
              </thead>		
                
              <tbody id="mytbbody">
              <?php $i=1; foreach($records as $row) :?>
              <tr>
                      <td><?php echo $i;$i++;?></td>
                      <td><?php echo $row->stock_code;?></td>
                      <td><?php echo $row->product_code;?><br/><?php echo $row->product_name;?></td>
                      <td><?php echo $row->stock_date;?></td>
                      <td><?php echo $row->stock_type;?></td>
                      <td><?php echo $row->remark;?></td>
                      <td> <a href="<?php echo base_url().'index.php/Stock/edit_stock_adjustment/'.$row->sno;?>" title="Edit" style="margin-right:10px;">
                          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      </a>

                    

                      <a href='<?php echo base_url().'index.php/Stock/delete_stock_adjustment/'.$row->sno; ?>' title="Delete" class="delete" id="delete">
                          <i class="glyphicon glyphicon-trash"></i>
                      </a></td>
              </tr>
              <?php endforeach; ?>
              </tbody>
        </table>	
	    </div>    	
	    
	 </div>
                </div>
               
                  
           
           

           
            <!--  -->
              </div>
            </div>
            
          </div>
        </div>
       
      

        <!-- /page content -->
</form>
