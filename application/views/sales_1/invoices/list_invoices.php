<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<style>
  body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }
</style>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
        
        <!-- <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
          <li class="nav-item">
                        <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Active</a>
          </li>
          <li class="nav-item">
                        <a class="nav-link" id="cancel-tab" data-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">Cancel</a>
          </li>
        </ul> -->
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Invoice Code</th>
                                <th>Delivery Challan Code</th>
                                <th>Sales Order Code</th>
                                <th>Enquiry code</th>
                                <th>Grand Total</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;foreach($active_invoice_list as $invoice){ ?>
                              <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td>
                                      <?php echo $invoice['invoice_code']; ?><br><?php echo date('d-M-Y',strtotime($invoice['invoice_date'])); ?>
                                    </td>
                                    <td><?php echo $invoice['delivery_code']; ?></td>                                    
                                    <td><?php echo $invoice['so_code']; ?></td>
                                    <td><?php echo $invoice['enquiry_code']; ?></td>
                                    <td><?php echo number_format($invoice['grand_total'],2,'.',''); ?></td>
                                  <td>
                                      <?php  if(has_view_access($user,$page_name)){ ?>
                                        <a target='_blank' href='<?php echo base_url().'index.php/Document_controller/print_invoice/'.$invoice['invoice_id'].'/'.$invoice['enquiry_id'] ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <!-- <a href='<?php echo base_url().'index.php/Document_controller/edit_invoice/'.$invoice['invoice_id']; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> -->
                                        <?php } ?>
                                    
                                    </td>
                              </tr>
                              <?php $i++;} ?>
                            </tbody>
                        </table>
                      </div>
                      <!-- <div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
                       <table id="datatable-responsive" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Invoice Code</th>
                                <th>Invoice Status</th>
                                <th>Sales Order Code</th>
                                <th>Customer</th>
                                <th>Grand Total</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;foreach($cancelled_invoices as $invoice){ ?>
                              <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td>
                                      <?php echo $invoice['invoice_code']; ?><br><?php echo date('d-M-Y',strtotime($invoice['invoice_date'])); ?>
                                    </td>
                                    <td><?php echo $invoice['invoice_status']>=0?'Active':'Cancelled'; ?></td>
                                    <td><?php echo $invoice['pi_code']; ?></td>
                                    
                                    <td><?php echo $invoice['customer_name']; ?></td>
                                    <?php $grand_total = $invoice['total_before_vat']+($invoice['total_before_vat']*($invoice['vat_percent']/100)); ?>
                                    <td><?php echo number_format($grand_total,2,'.',''); ?></td>
                                  <td>
                                      <?php  if(has_view_access($user,$page_name)){ ?>
                                        <a target='_blank' href='<?php echo base_url().'index.php/Sales/print_invoice/'.$invoice['invoice_id']; ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <a href='<?php echo base_url().'index.php/Sales/view_invoice/'.$invoice['invoice_id']; ?>' title='View'>View</a>
                                        <?php } ?>
                                    
                                    </td>
                              </tr>
                              <?php $i++;} ?>
                            </tbody>
                        </table>
                      </div> -->
                      
          </div>
        
      </div>
    </div>
  </div>
</div>

<script>

</script>