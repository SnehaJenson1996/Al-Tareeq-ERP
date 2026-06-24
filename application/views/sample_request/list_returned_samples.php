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
                <th>Request Code</th>
                <th>Enquiry Code</th>
                <th>Customer</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
              <?php //$i=1;foreach($sample_requests as $req){ ?>
                  <!-- <tr>
                      <th scope="row"><?php echo $i; ?></th>
                      <td><?php echo $req['request_code']; ?><br><?php echo date('d-M-Y',strtotime($req['request_date'])); ?></td>
                      <td><?php echo $req['enquiry_code']; ?><br><?php echo date('d-M-Y',strtotime($req['enquiry_date'])); ?></td>
                      <td><?php echo $req['customer_name']; ?></td>
                      <td>
                        <?php  //if(has_access($user,$page_name,'E')){ ?>
                          <a target='_blank' href='<?php echo base_url().'index.php/SampleRequest/edit_sample_request/'.$req['request_id']; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></span></a>
                        <?php // } ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php  //if(has_view_access($user,$page_name)){ ?>
                           <a target='_blank' href='<?php echo base_url().'index.php/SampleRequest/print_sample_request/'.$req['request_id']; ?>' title='Edit'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></span></a>
                        <?php // } ?>
                      </td>
                  </tr> -->
              <?php //$i++;} ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
 
</script>