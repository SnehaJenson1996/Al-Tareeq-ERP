<div class="page-body">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header table-card-header">
          
        </div>
        <div class="card-block">
          <form class="form-horizontal" action="<?php echo base_url().'index.php/'; ?>AMC/get_quotation_report" id="question" method="post" name="question" >
            <div class="form-group row">
              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">From <span style="color: red;"> * </span></label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
                
                  <input tabindex="1" type="date" class="form-control" id="from" name="from" value="<?php echo $from; ?>" required autocomplete="off">
                  
                </div>
             

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">To <span style="color: red;"> * </span></label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
               
                  <input tabindex="2" type="date" class="form-control" id="to" name="to" value="<?php echo $to; ?>" required autocomplete="off">
                  
                </div>
             

              <label class="col-xs-1 col-sm-1 col-md-1 col-lg-2 col-form-label">Customer</label>
              <div class="col-sm-3">
                <select tabindex="3" name="customer_id" id="customer_id" class="form-control select2" tabindex="3">
                  <option value="">Please select</option>
                  <?php foreach($customer_list as $g) {?>
                    <option <?php if($customer_id==$g->customer_id) echo 'selected'; ?> value="<?php echo $g->customer_id ?>"><?php echo $g->cust_name;?> </option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-xs-1 col-sm-1 col-md-1 col-lg-2 col-form-label">Quotations</label>
              <div class="col-sm-3">
                <select tabindex="4" name="quotation_id" id="quotation_id" class="form-control select2">
                  <option value="">Please Select</option>
                  <?php foreach($sale_quotation_records as $row) { ?>
                    <option <?php if($quotation_id==$row->quote_id) echo 'selected'; ?> value="<?php echo $row->quote_id;?>"><?php echo $row->quotation_code.' / '.date('d-m-Y', strtotime($row->quotation_date));?></option>
                  <?php } ?>
                </select>
              </div>

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">Status</label>
              <div class="col-sm-2">
                <select tabindex="5" name="status" id="status" class="form-control" tabindex="4">
                  <option value="" >Please Select</option>
                  <option <?php if($status == '0') echo 'selected'; ?> value="0">With AMC Discount</option>
                  <option <?php if($status == '1') echo 'selected'; ?> value="1">Without AMC Discount</option>
                  <option <?php if($status == '2') echo 'selected'; ?> value="2">All</option>

                </select>
              </div>

              <div class="col-sm-2">
                <table>
                  <tr>
                    <td>
                      <input tabindex="6" type="submit" id="view" name="go" value="Go" class="btn btn-sm btn-primary m-b-0" />
                    </form>
                  </td>
                  <td>&nbsp;</td>
                  <td>
                    <form target="_blank" action="<?php echo base_url().'index.php/'; ?>AMC/print_quotation_report" id="ques1" method="post" name="ques1" >
                      <input type="hidden" name="from" value="<?php echo $from; ?>" />
                      <input type="hidden" name="to" value="<?php echo $to; ?>" />
                      <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                      <input type="hidden" name="status" value="<?php echo $status; ?>" />
                      
                      <input type="submit" id="print" value="Print" class="btn btn-warning btn-sm" tabindex="7"/>
                    </form></td>
                    <td>&nbsp;</td>
                    <td>
                      <form action="<?php echo base_url().'index.php/'; ?>AMC/export_quotation_report" id="ques1" method="post" name="ques1" >
                        <input type="hidden" name="from" value="<?php echo $from; ?>" />
                        <input type="hidden" name="to" value="<?php echo $to; ?>" />
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                        <input type="hidden" name="status" value="<?php echo $status; ?>" />
                        
                        <input type="submit" id="export" value="Export to excel" class="btn btn-warning btn-sm" tabindex="8" />
                      </form>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Quotation Code</th>
                    <th>Quotation Date</th>
                    <th>Customer Name</th>
                   
                    <th>Grand Total</th>
                    
                  </tr>
                </thead>

               <tbody>
  <?php $i = 1; ?>
  <?php if (!empty($amc_quotation_list) && is_array($amc_quotation_list)) : ?>
    <?php foreach ($amc_quotation_list as $row) : ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td>
            <?php echo $row->quotation_code; ?>
          
        </td>
        <td><?php echo date('d-M-Y', strtotime($row->quotation_date)); ?></td>
        <td>
          <a target='_blank' title="Customer Details" href="<?php echo base_url().'index.php/Setup/edit_customer/'.$row->customer_id; ?>">
            <b><?php echo $row->cust_name; ?></b>
          </a>
        </td>
        <td><?php echo number_format($row->grand_total, 2); ?></td>
       
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="6" class="text-center">No quotations found for the selected criteria.</td>
    </tr>
  <?php endif; ?>
</tbody>

                <tfoot>
                  <tr>
                    <th>Sr. No</th>
                    <th>Quotation Code</th>
                    <th>Quotation Date</th>
                    <th>Customer Name</th>
                   
                    <th>Grand Total</th>
                    
                    
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- End Page Body -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
