<style>
  body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }
</style>
<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Estimation Code</th>
              <th>Enquiry</th>
              <th>View Revisions</th>
              <th>Customer</th>
              <th>Grand Total</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1;foreach($estimations as $estimation){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $estimation['estimation_code'].'-Rev'.$estimation['estimation_revision']; ?><br><?php echo date('d-M-Y',strtotime($estimation['estimation_date'])); ?></td>
                    <td><?php echo $estimation['enquiry_code']; ?></td>
                    <td>
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Revisions<span class="caret"></span></a>
                        <div class="dropdown-menu" id='revision_dropdown' aria-labelledby="dropdownMenuButton">
                          <?php foreach ($estimation_revisions[$estimation['estimation_code']] as $rev): ?>
                          <a class="dropdown-item"  href="<?= base_url('index.php/Sales/edit_estimation/' . $rev->estimation_id.'/1') ?>" target="_blank">Rev <?= $rev->estimation_revision ?> - <?= date('d-m-Y', strtotime($rev->estimation_date)) ?></a>
                          <?php endforeach; ?>
                        </div> 
                      
                    </td>
                    <td><?php echo $estimation['customer_name']; ?></td>
                    <td><?php echo $estimation['grand_total']; ?></td>
                    <td>
                      
                    <?php if(has_access($user,$page_name,'E')){ ?>
                      <a href='<?php echo base_url().'index.php/Sales/edit_estimation/'.$estimation['estimation_id'].'/0'; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                    <?php } ?> 
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <?php if(has_access($user,$page_name,'D')){ ?>
                        <a href="javascript:delete_estimation(<?php echo $estimation['estimation_id']; ?>,<?php echo $estimation['enquiry_id']; ?>);" title="Edit"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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