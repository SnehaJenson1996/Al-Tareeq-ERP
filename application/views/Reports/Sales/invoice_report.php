<?php 
	$user = $this->session->userdata('user_id');
?>
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Reports/invoice_report" autocomplete="off" enctype="multipart/form-data">
	
          <!-- page content -->
          <div class="form-group" role="main">
          <div class="">
            <div class="page-title"></div>
            <div class="clearfix"></div>

            <div class="x_content">
              <div class="well" style="overflow: auto">
                <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Date From:</label>
                  <div class="col-md-2">
                    <input type="date" name ="from_date" class="form-control" value="<?php echo $from_date; ?>"/>
                  </div>
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Date To:</label>
                  <div class="col-md-2">
                    <input type="date" name ="to_date" class="form-control" value="<?php echo $to_date; ?>"/>
                  </div>
                  
                </div>
             
              <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Filter by:</label>
                  
                  <div class="col-md-2">
                  <select name="status" id="status" class="form-control select2" tabindex="2">
                    <option value='123'>All</option>
                    <option value=0 <?php if($status==0) echo 'selected'; ?>>Active</option>
                    <option value=-1 <?php if($status==-1) echo 'selected'; ?>>Cancelled</option>
                    
                   </select>
                  </div>
                  <div class="col-md-2">
                  <select name="customer" id="customer" class="form-control select2" tabindex="2">
                    <option value="">Select Customer</option>
                    <?php foreach($all_customers as $customer){?>
                      <option value='<?= $customer->customer_id ?>' <?php if($customer->customer_id==$customer_id) echo 'selected'; ?>><?= $customer->customer_name ?></option>
                    <?php } ?>
                   </select>
                  </div>
              
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-success">Go</button>
                    <!-- <a href="" class="btn btn-success" onclick="printRFQReport()">Print</a> -->
                  </div>
                </div>
              </div>
            </div>

            <?php if(isset($records)){ ?>            
            <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                      <th>Sl.no</th>
                      <th>Invoice Code</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Status</th>
                      <th>Created by</th>
                      <th>Last updated by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;
                    foreach($records as $row) :?>
                    <tr>
						            <td><?php echo  $i; $i++;?></td>
                        <td><?php echo $row->invoice_code; ?></td>
                        <td><?php echo $row->invoice_date; ?></td>
                        <td><?php echo $row->customer_name; ?></td>
                        <td><?php echo $row->invoice_status==0?'Active':'Cancelled'; ?></td>
                        <td><?php echo $row->created; ?></td>
                        <td><?php echo $row->last_updated; ?></td>
                    </tr>
                    
                  <?php endforeach; ?>
                  
                </tbody>
                
              </table>
            </div>
           <?php } ?>
            <!--  -->
              </div>
            </div>
            
          </div>
        </div>
       
      

        <!-- /page content -->
</form>
<script>
  function printRFQReport() {
    const fromDate = document.querySelector('input[name="from_date"]').value;
    const toDate = document.querySelector('input[name="to_date"]').value;
    const supplierId = document.querySelector('select[name="supplier_id"]').value;

    const baseUrl = "<?php echo base_url().'index.php/Reports/print_rfq_report'; ?>";
    const params = new URLSearchParams({
      from_date: fromDate,
      to_date: toDate,
      supplier_id: supplierId
    });

    const printUrl = `${baseUrl}?${params.toString()}`;
    window.open(printUrl, '_blank');
  }
</script>
