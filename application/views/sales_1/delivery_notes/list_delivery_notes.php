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
        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
          <li class="nav-item">
                        <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Active</a>
          </li>
          <li class="nav-item">
                        <a class="nav-link" id="cancel-tab" data-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">Cancel</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>DN Code</th>
                    <th>Invoice Code</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;foreach($active_delivery_notes as $dn){ ?>
                    <tr>
                        <th scope="row"><?php echo $i; ?></th>
                        <td>
                          <?php echo $dn['dn_code']; ?><br><?php echo date('d-M-Y',strtotime($dn['dn_date'])); ?>
                        </td>
                        <td><?php echo $dn['invoice_code']; ?></td>
                        
                        <td><?php echo $dn['customer_name']; ?></td>
                        <td><?php echo $dn['dn_status']>=0?'Active':'Cancelled'; ?></td>
                      <td>
                          <?php  if(has_view_access($user,$page_name)){ ?>
                            <a target='_blank' href='<?php echo base_url().'index.php/Sales/print_delivery_note/'.$dn['dn_id']; ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                          <?php } ?>
                        
                        </td>
                    </tr>
                  <?php $i++;} ?>
                </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>DN Code</th>
                    <th>Invoice Code</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;foreach($cancelled_delivery_notes as $dn){ ?>
                    <tr>
                        <th scope="row"><?php echo $i; ?></th>
                        <td>
                          <?php echo $dn['dn_code']; ?><br><?php echo date('d-M-Y',strtotime($dn['dn_date'])); ?>
                        </td>
                        <td><?php echo $dn['invoice_code']; ?></td>
                        
                        <td><?php echo $dn['customer_name']; ?></td>
                        <td><?php echo $dn['dn_status']>=0?'Active':'Cancelled'; ?></td>
                      <td>
                          <?php  if(has_view_access($user,$page_name)){ ?>
                            <a target='_blank' href='<?php echo base_url().'index.php/Sales/print_delivery_note/'.$dn['dn_id']; ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                          <?php } ?>
                        
                        </td>
                    </tr>
                  <?php $i++;} ?>
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