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
        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                <th>#</th>
                <th>Delivery Code</th>
                <th>Invoice Code</th>
                <th>Sales Order Code</th>
                <th>Customer</th>
                <th>Note</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
              <?php $i=1;foreach($sales_return as $ret){ ?>
                  <tr>
                      <th scope="row"><?php echo $i; ?></th>
                      <td><?php echo $ret['dn_code']; ?><br><?php echo date('d-M-Y',strtotime($ret['dn_date'])); ?></td>
                      <td><?php echo $ret['invoice_code']; ?><br><?php echo date('d-M-Y',strtotime($ret['invoice_date'])); ?></td>
                      <td><?php echo $ret['pi_code']; ?></td>
                      <td><?php echo $ret['customer_name']; ?></td>
                      <td><?php echo $ret['notes']; ?></td>
                      <td>
                        <?php  if(has_view_access($user,$page_name)){ ?>
                          <a target='_blank' href='<?php echo base_url().'index.php/Sales/print_sales_return/'.$ret['return_id']; ?>' title='Print'>View</span></a>
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