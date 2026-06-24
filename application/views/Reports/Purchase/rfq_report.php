<?php 
	$user = $this->session->userdata('user_id');
?>
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Reports/get_rfq_report" autocomplete="off" enctype="multipart/form-data">
	
          <!-- page content -->
          <div class="form-group" role="main">
          <div class="">
            <div class="page-title"></div>
            <div class="clearfix"></div>

            <div class="x_content">



              <div class="well" style="overflow: auto">
                <div class="col-md-12">
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Date From:</label>
                  <div class="col-md-2">
                    <input type="date" name ="from_date" class="form-control" value="<?php echo $from; ?>"/>
                  </div>
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Date To:</label>
                  <div class="col-md-2">
                    <input type="date" name ="to_date" class="form-control" value="<?php echo $to; ?>"/>
                  </div>
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Supplier:</label>
                  <div class="col-md-2">
                  <select name="supplier_id" id="supplier_id" class="form-control select2" tabindex="2">
                  <option value="">-select-</option>
                 
                    <?php foreach($supplier_records as $g) { ?>
                        <option <?php if($supplier_id==$g->supplier_id) echo 'selected'; ?> value="<?php echo $g->supplier_id;?>" ><?php echo $g->supplier_code.' '.$g->supplier_name; ?> </option>
                        <?php } ?>
                    </select>  
                  </div>
                  
               
              
                <div class="col-md-2">
                  
                  <button type="submit" class="btn btn-success">Go</button>
<a href="javascript:void(0);" class="btn btn-success" onclick="printRFQReport(event)">Print</a>                 
                </div>
                </div>
              </div>
            </div>


            <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
										<th>Sr. No</th>
										<th>RFQ Code</th>
										<th>RFQ Date</th>
										<th>Supplier</th>
										<th>Created By</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach($records as $row) :?>
                    <tr>
											<td><?php echo  $i; $i++;?></td>
											<td><a target='blank' title="RFQ Details" href="<?php echo base_url().'index.php/Purchase/edit_rfq/'.$row->rfq_id.'/'.$row->rev_version;?>"><?php echo $row->rfq_code; ?></a></td>
                      <td><?php echo date('d-M-Y',strtotime($row->rfq_date)); ?></td>
											<td><?php echo $row->supplier_name; ?></td>
											<td><?php echo $row->rfq_created_by; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
									<th>Sr. No</th>
									<th>RFQ Code</th>
									<th>RFQ Date</th>
									
									<th>Supplier</th>
									
									<th>Created By</th>
                </tfoot>
              </table>
            </div>
           
            <!--  -->
              </div>
            </div>
            
          </div>
        </div>
       
      

        <!-- /page content -->
</form>
<script>
 function printRFQReport(event) {

    if (event) event.preventDefault(); // 🔥 stop any default action

    const fromDate = document.querySelector('input[name="from_date"]').value;
    const toDate = document.querySelector('input[name="to_date"]').value;
    const supplierId = document.querySelector('select[name="supplier_id"]').value;

    const baseUrl = "<?= base_url('index.php/Reports/print_rfq_report'); ?>";

    const params = new URLSearchParams({
        from_date: fromDate,
        to_date: toDate,
        supplier_id: supplierId
    });

    window.open(baseUrl + "?" + params.toString(), '_blank');

    return false;
}
</script>