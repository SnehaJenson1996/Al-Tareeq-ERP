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
        
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Order Code</th>
                    <th>Quotation Code</th>
                    <th>Enquiry Code</th>
                    <th>Status</th>
                    <th>Sub total</th>
                    <th>Discount Amount</th>
                    <th>Vat Amount</th>
                    <th>Grand Total</th>
                    <th>Action</th>
                    </tr>
                </thead>
               <tbody>
                <?php $i = 1; foreach ($active_sales_orders as $sales_order) { ?>
                  <tr>
                    <th scope="row"><?= $i; ?></th>
                    <td>
                      <?= $sales_order['so_code'] ?><br>
                      <?= date('d-M-Y', strtotime($sales_order['so_date'])) ?>
                    </td>
                    <td>
                      <?= $sales_order['quotation_code'] ?>
                      
                    </td>
                    <td>
                      <?= $sales_order['enquiry_code'] ?>
                      
                    </td>
                    <td>
                      <?= $sales_order['active'] >= 0 ? 'Active' : 'Cancelled'; ?>
                    </td>

                    <!-- Sub Total -->
                    <td>
                      <?= number_format((float)$sales_order['sub_total'], 2) ?>
                    </td>

                    <!-- Discount -->
                    <td>
                      (<?= $sales_order['discount_percentage'] ?>)%<br>
                      <?= number_format((float)$sales_order['discount_amount'], 2) ?>
                    </td>

                    <!-- VAT -->
                    <td>
                      (<?= $sales_order['vat_percentage'] ?>)%<br>
                      <?= number_format((float)$sales_order['vat_amount'], 2) ?>
                    </td>

                    <!-- Grand Total -->
                    <td>
                      <?= number_format((float)$sales_order['grand_total'], 2) ?>
                    </td>
                     <td>
                        <?php  if(has_access($user,$page_name,'E')){ ?>
                          <!-- <a target='_blank' href='<?php echo base_url().'index.php/SampleRequest/edit_sample_request/'.$req['request_id']; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></span></a> -->
                        <?php } ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php  if(has_view_access($user,$page_name)){ ?>
                           <a target='_blank' href='<?php echo base_url().'index.php/Document_controller/print_sales_order/'.$sales_order['so_id'].'/'.$sales_order['enquiry_id']; ?>' title='print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></span></a>
                        <?php } ?>
                      </td>
                  </tr>

                <?php $i++; } ?>
              </tbody>

            </table>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function delete_estimation(estimation_id,enquiry_id){
    $.ajax({
                url: '<?= base_url("index.php/Sales/delete_estimation_by_id") ?>', 
                type: 'POST',
                data: { estimation_id: estimation_id,enquiry_id:enquiry_id },
                success: function(response) {
                  console.log(response);
                  
                    if(response){
                      alert("Record deleted!");
                      window.location.href=window.location.pathname;  	
                    }
                    else
                      alert("Something went wrong!");
                    
                }
          });
  }
</script>