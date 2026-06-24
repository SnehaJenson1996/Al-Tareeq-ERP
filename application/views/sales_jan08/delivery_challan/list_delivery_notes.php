<?php
$page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$user = $this->session->userdata('user_id');
?>
<style>
  body {
    font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
    font-size: 13px;
  }
</style>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fa fa-check-circle"></i></strong>
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fa fa-exclamation-circle"></i></strong>
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Dchallan Code</th>
                  <th>Delivery Date</th>
                  <th>Delivery Mode</th>
                  <th>Deliverd By</th>
                  <th>Sales Order Code</th>
                  <th>Quotation Code</th>
                  <th>Enquiry Code</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                foreach ($active_delivery_challan as $dn) { ?>
                  <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td>
                      <?php echo $dn['delivery_code']; ?>
                    </td>
                    <td><?php echo date('d-M-Y', strtotime($dn['delivery_date'])); ?>/<?=$dn['enquiry_id']?></td>

                    <td><?php echo $dn['delivery_mode']; ?></td>
                    <td><?php echo $dn['deliverd_by']; ?></td>
                    <td><?php echo $dn['so_code']; ?></td>
                    <td><?php echo $dn['enquiry_code']; ?></td>
                    <td><?php echo $dn['quotation_code']; ?></td>
                    <td>
                      <?php if (has_view_access($user, $page_name)) { ?>
                        <!-- <a target="_blank" 
                                href="<?= base_url('index.php/Document_controller/print_delivery_challan/' . $dn['del_id'] . '/' . $dn['enquiry_id']); ?>" 
                                title="Print">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                              </a>                           -->
                      <?php } ?>
                      <div class="btn-group">
                        <button type="button" class="btn btn-success">Print</button>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split"
                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="<?php echo base_url() . 'index.php/Document_controller/print_delivery_challan/' . $dn['del_id'] . '/' . $dn['enquiry_id']; ?>" data-action="sales_return" data-invoice-id="#">Delivery Challan</a>
                          <a class="dropdown-item" href="<?php echo base_url() . 'index.php/Document_controller/print_commercial_invoice/' . $dn['del_id'] . '/' . $dn['enquiry_id']; ?>" data-action="cancel" data-invoice-id="#">Commercial invoice</a>
                        </div>
                    </td>
                  </tr>
                <?php $i++;
                } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function delete_estimation(estimation_id, enquiry_id) {
    $.ajax({
      url: '<?= base_url("index.php/Sales/delete_estimation_by_id") ?>',
      type: 'POST',
      data: {
        estimation_id: estimation_id,
        enquiry_id: enquiry_id
      },
      success: function(response) {
        console.log(response);

        if (response) {
          alert("Record deleted!");
          window.location.href = window.location.pathname;
        } else
          alert("Something went wrong!");

      }
    });
  }
</script>