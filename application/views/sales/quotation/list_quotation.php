<!-- <div class="col-md-12 col-sm-12"> -->
<div class="x_panel">
  <div class="x_title">

    <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
          <i class="fa fa-wrench"></i>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Export Excel</a></li>
          <li><a href="#">Export PDF</a></li>
        </ul>
      </li>
      <li><a class="close-link"><i class="fa fa-close"></i></a></li>
    </ul>
    <div class="clearfix"></div>
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

  </div>

  <div class="x_content">
    <div class="row">
      <div class="col-sm-12">
        <div class="card-box table-responsive">

          <table id="datatable-responsive"
            class="table table-striped table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
              <tr style="background-color:#2A3F54; color:#ffffff;">
                <th>#</th>
                <th>Quotation Code</th>
                <th>Type</th>
                <th>Revision</th>
                <th>Date</th>
                <th>Enquiry</th>
                <th>Branch</th>
                <th>Customer</th>
                <th style="text-align:right;">Grand Total</th>
                <th>Status</th>
                <th>Created On</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($quotations)) {
                $i = 1;
                foreach ($quotations as $qtn) { ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><a href="<?= base_url('index.php/Sales/view_quotation/' . $qtn['qtn_id']); ?>"><strong><?= $qtn['quotation_code']; ?></strong></a></td>
                    <td><?= strtoupper($qtn['quotation_type']); ?></td>
                    <td><?= $qtn['quotation_revision']; ?></td>
                    <td><?= date("d-m-Y", strtotime($qtn['quotation_date'])); ?></td>
                    <td><a href="<?= base_url() ?>index.php/CRM/view_enquiry/<?= $qtn['enquiry_id'] ?>"><?= $qtn['enquiry_code']; ?></a></td>
                    <td><?= $qtn['branch_name']; ?></td>
                    <td><?= $qtn['customer_name']; ?></td>
                    <td style="text-align:right;"><?= number_format($qtn['grand_total'], 2); ?></td>
                    <td>
                      <?php
                      if ($qtn['aproval'] == 1) {
                        echo '<span class="badge bg-success">Approved</span>'; // green
                      } else {
                        echo '<span class="badge bg-warning">Not approved</span>'; // grey
                      }
                      ?>


                    </td>
                    <td><?= date("d-m-Y H:i:s", strtotime($qtn['created_on'])); ?></td>
                   <td>
    <a href="<?= base_url('index.php/Sales/delete_quotation/' . $qtn['qtn_id']); ?>" 
       class="btn btn-danger btn-xs"
       onclick="return confirm('Are you sure?');">
       <i class="fa fa-trash"></i>
    </a>
</td>
                  </tr>
                <?php }
              } else { ?>
                <tr>
                  <td colspan="10" class="text-center text-muted">No quotations found</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- </div> -->
 <script>
$(document).ready(function() {
    $('.approve-btn').on('click', function() {
        var quotationId = $(this).data('id');
        var approvedBy = $(this).data('user'); // current logged-in user
        var $btn = $(this);
alert(quotationId);
        if(confirm('Are you sure you want to approve this quotation?')) {
            $.ajax({
                url: '<?= base_url("index.php/Sales/approve_quotation") ?>',
                type: 'POST',
                data: { 
                    quotation_id: quotationId,
                    approved_by: approvedBy
                },

                dataType: 'json',
                success: function(response) {
                    if(response.status == 'success') {
                        $btn.removeClass('bg-warning text-dark').addClass('bg-success').text('Approved');
                    } else {
                        alert('Failed to approve. Try again.');
                    }
                },
                error: function() {
                    alert('Error processing request.');
                }
            });
        }
    });
});
</script>
