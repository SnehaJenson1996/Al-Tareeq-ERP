<?php 
	$user = $this->session->userdata('user_id');
?>
   <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Reports/custom_quotation_report" autocomplete="off" enctype="multipart/form-data">
	
          <!-- page content -->
          <div class="form-group" role="main">
          <div class="">
            <div class="page-title"></div>
            <div class="clearfix"></div>

            <div class="x_content">
              <div class="well" style="overflow: auto">
                
             
              <div class="item form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-3">Select Quotation:</label>
                  
                  
                  <div class="col-md-2">
                  <select name="quotation_id" id="quotation_id" class="form-control select2" tabindex="2">
                    <option value="">Select Quotation</option>
                    <?php foreach($approved_quotations as $quotation){?>
                      <option value='<?= $quotation->quotation_id ?>' <?php if($quotation->quotation_id==$quotation_id) echo 'selected'; ?>><?= $quotation->quotation_code.'/Rev-'.$quotation->quotation_revision ?></option>
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
                      <th>PI Code</th>
                      <th>Invoice Code</th>
                      <th>Delivery Code</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;$current_pi = null;$current_invoice = null;
                foreach ($records as $row) {
                    $pi_col = "";
                    $invoice_col = "";
                    $dn_col = "";

                    // Show PI code once per PI group
                    if ($current_pi !== $row['pi_id']) {
                        $current_pi = $row['pi_id'];
                        $pi_col = $row['pi_code'] ?: "—";  // show dash if no PI
                        $current_invoice = null; 
                    }

                    // Show Invoice code once per Invoice group
                    if ($current_invoice !== $row['invoice_id']) {
                        $current_invoice = $row['invoice_id'];
                        $invoice_col = $row['invoice_code'] ?: "—"; // dash if no invoice
                    }

                    // Show DN if exists
                    $dn_col = $row['dn_code'] ?: "—";

                    echo "<tr>";
                    echo "<td>$pi_col</td>";
                    echo "<td>$invoice_col</td>";
                    echo "<td>$dn_col</td>";
                    echo "</tr>";
                } ?>
                  
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
