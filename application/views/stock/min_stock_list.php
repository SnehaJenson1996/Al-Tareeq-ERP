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
                  <th title="Item">Sr </th> 
                  <th title="Item">Product</th>
                  <th title="Item">Description</th>
                  <th title="Item">Quantity</th>      
                  
                  <th title="Item">Action</th>   
                  <th ></th>   
            </tr>
              </thead>		
                
              <tbody id="mytbbody">
              <?php $i=1; foreach($records as $row) :?>
              <tr>
                      <td><?php echo $i;$i++;?></td>
                      <td><?php echo $row->item_name;?></td>
                      <td><?php echo $row->item_description;?></td>
                      <td><?php echo $row->min_stock_qty;?></td>
                      <td>
                      <!--  <a href="<?php // echo base_url().'index.php/Stock/edit_min_stock/'.$row->item_id;?>" title="Edit" style="margin-right:10px;">
                          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      </a> -->

                    

                      <a href='<?php echo base_url().'index.php/Stock/delete_min_stock/'.$row->item_id; ?>' title="Delete" class="delete" id="delete">
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
