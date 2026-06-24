<?php 
	$user = $this->session->userdata('user_id');
?>
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Sales/sales_report" autocomplete="off" enctype="multipart/form-data">
	
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
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">VAT:</label>
                  <div class="col-md-2">
                    <select name="vat_option" id="vat_option" class="form-control" tabindex="2">
                      <option value='0'>VAT Exclusive</option>
                      <option value='1' <?php if($vat_option) echo 'selected'; ?>>VAT Inclusive</option>
                    </select>
                  </div>
                </div>
             
              <div class="item form-group">
                  <label class="control-label col-md-1 col-sm-3 col-xs-3">Filter by:</label>
                  <div class="col-md-2">
                  <select name="item_model" id="item_model" class="form-control item-select2" tabindex="2">
                   <?php if(isset($item_details)) {?>
                    <option value='<?= $item_details['item_id'] ?>'><?= $item_details['item_model'] ?></option>
                    <?php } ?>
                  </select>
                  </div>
                  <div class="col-md-2">
                  <select name="item_brand" id="item_brand" class="form-control select2" tabindex="2">
                    <option value="">Select brand</option>
                    <?php foreach($all_brands as $brand){?>
                      <option value='<?= $brand->brand_id ?>' <?php if($brand->brand_id==$brand_id) echo 'selected'; ?>><?= $brand->brand_name ?></option>
                    <?php } ?>
                   </select>
                  </div>
              
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-success">Go</button>
                    <a href="" class="btn btn-success" onclick="printRFQReport()">Print</a>
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
                    <!-- <th>Invoice Code</th> -->
										<th>Item model</th>
										<th>Brand</th>
										<th>Quantity</th>
                    <th>Amount</th>
                    <?php if($vat_option){?>
                      <th>VAT</th>
                      <th>Total</th>
                    <?php }?>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1;$total_quantity=0;$total_amount=0;$total_vat=0;$net_total=0; foreach($records as $row) :?>
                    <tr>
											<td><?php echo  $i; $i++;?></td>
											<!-- <td><a target='blank' href="<?php echo base_url().'index.php/Sales/print_invoice/'.$row->invoice_id;?>"><?php echo $row->invoice_code; ?></a></td> -->
                      <td><?php echo $row->item_model; ?></td>
											<td><?php echo $row->brand_name; ?></td>
											<td><?php echo $row->sales_quantity; ?></td>
                      <td><?php echo number_format($row->sales_amount,2); ?></td>
                      <?php if($vat_option){?>
                        <?php $vat_amount = $row->sales_amount*$row->vat_percent; ?>
                      <td><?php echo number_format($vat_amount,2); ?></th>
                         <?php $total = $row->sales_amount+$vat_amount; ?>
                      <td><?php echo number_format($total,2); ?></th>
                    <?php }?>
                    </tr>
                    <?php $total_quantity += $row->sales_quantity;$total_amount += $row->sales_amount;if($vat_option){$total_vat+=$vat_amount;$net_total+=$total;} ?>
                  <?php endforeach; ?>
                  <tr>
                    <td colspan=3 style='text-align:right'>Total:</td><td><?php echo $total_quantity; ?></td><td><?php echo number_format($total_amount,2); ?></td><?php if($vat_option){?><td><?php echo number_format($total_vat,2); ?></td><td><?php echo number_format($net_total,2); ?></td> <?php }?>
                  </tr>
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