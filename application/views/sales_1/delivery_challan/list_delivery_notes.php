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
                    <th>Dchallan Code</th>
                    <th>Delivery  Code</th>
                    <th>Delivery Mode</th>
                    <th>Deliverd By</th>
                    <th>Sales Order Code</th>
                    <th>Quotation Code</th>
                    <th>Enquiry Code</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;foreach($active_delivery_challan as $dn){ ?>
                    <tr>
                        <th scope="row"><?php echo $i; ?></th>
                        <td>
                          <?php echo $dn['delivery_code']; ?>
                        </td>
                        <td><?php echo date('d-M-Y',strtotime($dn['delivery_date'])); ?></td>
                        
                        <td><?php echo $dn['delivery_mode']; ?></td>
                        <td><?php echo $dn['deliverd_by']; ?></td>
                        <td><?php echo $dn['so_code']; ?></td>
                        <td><?php echo $dn['enquiry_code']; ?></td>
                        <td><?php echo $dn['quotation_code']; ?></td>
                      <td>
                          <?php  if(has_view_access($user,$page_name)){ ?>
<a target="_blank" 
   href="<?= base_url('index.php/Document_controller/print_delivery_challan/'.$dn['del_id'].'/'.$dn['enquiry_id']); ?>" 
   title="Print">
   <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
</a>                          <?php } ?>
                        
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