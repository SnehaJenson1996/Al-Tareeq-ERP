<div class="page-body">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header table-card-header">
          <!-- <h5>AMC Report</h5> -->
        </div>
        <div class="card-block" style="font-size:12px;">
          <form class="form-horizontal" action="<?php echo base_url().'index.php/'; ?>AMC/get_reports" method="post" >
            <div class="form-group row">

            <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">Date type </label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
               
                 <select name ="date_type" class="form-control select2" tabindex="1">
                    <option <?php if($date_type == 'invoice_date') echo 'selected';?> value="invoice_date">Agreement Date</option>
                    <option <?php if($date_type == 'amc_start_date') echo 'selected';?> value="amc_start_date">AMC start Date</option>
                    <option <?php if($date_type == 'amc_end_date') echo 'selected';?> value="amc_end_date">AMC End Date</option>
                    <option <?php if($date_type == 'pay_date') echo 'selected';?> value="pay_date">Payment Date</option>
                 </select>
                 
                
              </div>
              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">From </label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
               
                  <input tabindex="2" type="date" class="form-control date_today" id="from" name="from" value="<?php echo $from;?>"  autocomplete="off">
                 
                
              </div>

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">To</label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
                
                  <input tabindex="3" type="date" class="form-control date_today" id="to" name="to" value="<?php echo $to;?>" autocomplete="off">
                  
                </div>

                <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
                
                <select name ="rpt_type" class="form-control select2" tabindex="4">
                  <option <?php if($rpt_type == 'Detailed') echo 'selected';?> value="Detailed">Detailed</option>
                  <option <?php if($rpt_type == 'Summary') echo 'selected';?> value="Summary">Summary</option>
                  <!-- <option <?php //if($rpt_type == 'Yearly Summary') echo 'selected';?> value="Yearly Summary">Yearly Summary</option> -->
                </select>
              </div>
              </div>
              <div class="form-group row">
              <label class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-form-label">Customer</label>
              <div class="col-sm-2">
                <select tabindex="3" name="customer_id" id="customer_id" class="form-control select2" tabindex="3">
                  <option value="">Please select</option>
                  <?php foreach($amc_customer_list as $g) {?>
                    <option <?php if($cust_id == $g->customer_id) echo 'selected';?> value="<?php echo $g->customer_id ?>"><?php echo $g->customer_name;?> </option>
                  <?php } ?>
                </select>
              </div>
           
             
              <label class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-form-label">Project</label>
              <div class="col-sm-2">
                <select name="project_name" id="project_name" class="form-control select2">
    <option value="">Please Select</option>

    <?php foreach($amc_project_list as $row) { ?>
        <option value="<?php echo $row->quote_id; ?>"
            <?php if($project_name == $row->quote_id) echo 'selected'; ?>>
            <?php echo $row->project_name; ?>
        </option>
    <?php } ?>

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
                   </td>
                    <td>&nbsp;</td>
                    <td>
                    <form target="_blank" action="<?php echo base_url().'index.php/'; ?>AMC/print_amc_report" id="ques1" method="post" name="ques1" >
                      <input type="hidden" name="from" value="<?php echo $from; ?>" />
                      <input type="hidden" name="to" value="<?php echo $to; ?>" />
                     
                      <input type="hidden" name="date_type" value="<?php echo $date_type; ?>" />
                      <input type="hidden" name="project_name" value="<?php echo $project_name; ?>" />
                      <input type="hidden" name="customer_id" value="<?php echo $cust_id; ?>" />
                      
                     
                      <input type="submit" id="print" value="Print" class="btn btn-warning btn-sm" tabindex="7" />
                    </form>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="col-md-12" style="width:100%;overflow:scroll">
              <?php if ($rpt_type == 'Summary' ){ ?>
                <table id="basic-btn" class="table  table-bordered nowrap">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Month</th>
                    <th>Project Name</th>
                   
                    <th>AMC Start Date</th>
                    <th>AMC End Date</th>
                   
                    <th>Amount</th>
                  </tr>
                </thead>

                <tbody>
               <?php 
$i = 1;
$total_grand_total = 0;

if (!empty($amc_list)) {
    foreach ($amc_list as $row) {

        $amount = $row->total_grand_total ?? $row->grand_total ?? 0;
        $total_grand_total += $amount;
?>
<tr>
    <td><?php echo $i++; ?></td>
<td><?php echo $row->month ?? '-'; ?></td>
    <td><?php echo $row->project_name; ?></td>

    <td><?php echo date('d-M-Y', strtotime($row->amc_start_date)); ?></td>
    <td><?php echo date('d-M-Y', strtotime($row->amc_end_date)); ?></td>

    <td><?php echo number_format($amount, 2); ?></td>
</tr>
<?php 
    }
}
?>
                    <!-- Total Row -->
   <tr>
    <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
    <td><strong><?php echo number_format($total_grand_total ?? 0, 2); ?></strong></td>
</tr>
                  </tbody>

                  <tfoot>
                  
                  </tfoot>
                </table>
            <?php  }
              else {?>
            <table id="basic-btn" class="table  table-bordered nowrap">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>AMC Code</th>
                    <th>Customer Name</th>
                    <th>Project Name</th>
                    <th>AMC Start Date</th>
                    <th>AMC End Date</th>
                    <!-- <th>Instalment</th>
                    <th>Payment Date</th> -->
                    <th>Amount</th>
                  </tr>
                </thead>

                <tbody>
                  <?php $i=1; $total_grand_total = 0; if(!empty($amc_list)){foreach($amc_list as $row) : $total_grand_total += $row->grand_total;?>
                    <tr>
                    <td><?php echo  $i; $i++;?></td>
                    <td><?php echo $row->invoice_code; ?></td>
                    <td><b><?php echo $row->customer_name; ?></b> </td>
                    <td><b><?php echo $row->project_name; ?></b> </td>                 
                    <td><?php echo date('d-M-Y',strtotime($row->amc_start_date)); ?></td>
                    <td><?php echo date('d-M-Y',strtotime($row->amc_end_date)); ?></td>
                    <td><?php echo number_format($row->grand_total ?? 0, 2); ?></td>

                    
			
		            </tr>
                    <?php endforeach; }?>
                    <tr>
                      <td colspan="8" style="text-align: right;"><strong>Total:</strong></td>
                      <td><?php echo number_format($total_grand_total, 2); ?></td>
                  </tr>
                  </tbody>

                  <tfoot>
                  
                  </tfoot>
                </table>
              <?php } ?>
             
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
