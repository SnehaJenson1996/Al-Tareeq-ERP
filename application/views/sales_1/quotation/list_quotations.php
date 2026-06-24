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
        <table class="table table-hover">
            <thead>
                <tr>
                <th>#</th>
                <th>Quotation Code</th>
                <th>Estimation Code</th>
                <th>Revisions</th>
                <th>Customer</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1;foreach($quotations as $quotation){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td>
                      <?php echo $quotation['quotation_code'].'-Rev'.$quotation['quotation_revision']; ?><br><?php echo date('d-M-Y',strtotime($quotation['quotation_date'])); ?>
                    </td>
                    <td><?php echo $quotation['estimation_code']; ?></td>
                    <td>
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Revisions<span class="caret"></span></a>
                        <div class="dropdown-menu" id='revision_dropdown' aria-labelledby="dropdownMenuButton">
                          <?php foreach ($quotation_revisions[$quotation['estimation_code']] as $rev): ?>
                          <a class="dropdown-item"  href="<?= base_url('index.php/Sales/edit_quotation/' . $rev->quotation_id.'/1') ?>" target="_blank">Rev <?= $rev->quotation_revision ?> - <?= date('d-m-Y', strtotime($rev->quotation_date)) ?></a>
                          <?php endforeach; ?>
                        </div> 
                      
                    </td>
                    <td><?php echo $quotation['customer_name']; ?></td>
                    <td><?php echo $quotation['grand_total']; ?></td>
                    <td><?php echo $quotation['quotation_status']==0?'Active':'Cancelled'; ?></td>
                    <td>
                    <?php 
                    if($quotation['approval'] == 1 ){
                      if($quotation['quotation_status'] >= 0 ){?>
                      <a href='<?php echo base_url().'index.php/Sales/edit_quotation/'.$quotation['quotation_id'].'/0'; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                      <?php } ?>
                      <?php  if(has_view_access($user,$page_name)){ ?>
                        <a target='_blank' href='<?php echo base_url().'index.php/Sales/print_quotation/'.$quotation['quotation_id']; ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <?php } ?>
                     
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
  
</script>