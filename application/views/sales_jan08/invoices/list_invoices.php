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

        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Active</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="direct-tab" data-toggle="tab" href="#direct" role="tab" aria-controls="direct" aria-selected="false">Direct Invoices</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="cancel-tab" data-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">Cancel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-return-tab" data-toggle="tab" href="#sales-return" role="tab" aria-controls="sales-return" aria-selected="false">Sales Return</a>
          </li>

        </ul>
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
                <?php $i = 1;
                foreach ($active_invoice_list as $invoice) { ?>
                  <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td>
                      <?php echo $invoice['invoice_code']; ?><br><?php echo date('d-M-Y', strtotime($invoice['invoice_date'])); ?>
                    </td>
                    <td><?php echo $invoice['delivery_code']; ?></td>
                    <td><?php echo $invoice['so_code']; ?></td>
                    <td><?php echo $invoice['enquiry_code']; ?></td>
                    <td><?php echo number_format($invoice['grand_total'], 2, '.', ''); ?></td>
                    <td>
                      <?php if (has_view_access($user, $page_name)) { ?>
                        <a target='_blank' href='<?php echo base_url() . 'index.php/Document_controller/print_invoice/' . $invoice['invoice_id'] . '/' . $invoice['enquiry_id'] ?>' title='Print'><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                      <?php } ?>
                      <div class="btn-group">
                        <button type="button" class="btn btn-danger">Action</button>
                        <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="#" data-action="sales_return" data-invoice-id="<?= $invoice['invoice_id'] ?>">Sales Return</a>
                          <a class="dropdown-item" href="#" data-action="cancel" data-invoice-id="<?= $invoice['invoice_id'] ?>">Cancel invoice</a>
                        </div>
                      </div>

                    </td>
                  </tr>
                <?php $i++;
                } ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="direct" role="tabpanel" aria-labelledby="direct-tab">
            <table id="datatable-direct" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Invoice Code</th>
                  <th>Customer</th>
                  <th>Grand Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
                foreach ($direct_invoices as $invoice): ?>
                  <tr>
                    <th scope="row"><?= $i++; ?></th>
                    <td>
                      <?= $invoice['invoice_code']; ?><br>
                      <?= date('d-M-Y', strtotime($invoice['invoice_date'])); ?>
                    </td>
                    <td><?= $invoice['customer_name']; ?> </td>
                    <td><?= number_format($invoice['grand_total'], 2, '.', ''); ?></td>
                    <td>
                      <?php if (has_view_access($user, $page_name)) { ?>
                        <a target='_blank' href='<?= base_url("index.php/Document_controller/print_direct_invoice/" . $invoice['invoice_id']) ?>' title='Print'>
                          <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                        </a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
            <table id="datatable-responsive" class="table table-striped table-bordered" style="width:100%">
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
                <?php $i = 1;
                foreach ($cancelled_invoices as $invoice): ?>
                  <tr>
                    <th scope="row"><?= $i++; ?></th>
                    <td><?= $invoice['invoice_code']; ?><br><?= date('d-M-Y', strtotime($invoice['invoice_date'])); ?></td>
                    <td><?= $invoice['delivery_code']; ?></td>
                    <td><?= $invoice['so_code']; ?></td>
                    <td><?= $invoice['enquiry_code']; ?></td>
                    <td><?= number_format($invoice['grand_total'], 2, '.', ''); ?></td>
                    <td><span class="badge badge-danger">Cancelled</span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="sales-return" role="tabpanel" aria-labelledby="sales-return-tab">
            <table id="datatable-responsive" class="table table-striped table-bordered" style="width:100%">
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
                <?php $i = 1;
                foreach ($sales_return_invoices as $invoice): ?>
                  <tr>
                    <th scope="row"><?= $i++; ?></th>
                    <td><?= $invoice['invoice_code']; ?><br><?= date('d-M-Y', strtotime($invoice['invoice_date'])); ?></td>
                    <td><?= $invoice['delivery_code']; ?></td>
                    <td><?= $invoice['so_code']; ?></td>
                    <td><?= $invoice['enquiry_code']; ?></td>
                    <td><?= number_format($invoice['grand_total'], 2, '.', ''); ?></td>
                    <td><span class="badge badge-danger">Sales return</span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>

<script>
  $('.dropdown-menu .dropdown-item').on('click', function(e) {
    e.preventDefault();

    let selected = $(this).data('action');
    let invoice_id = $(this).data('invoice-id');

    if (selected === "sales_return") {
      window.location.href = "<?php echo base_url('index.php/Sales/sales_return/'); ?>" + invoice_id;
    } else if (selected === "cancel") {
      if (confirm("Are you sure you want to cancel this invoice?")) {
        $.ajax({
          url: "<?php echo base_url('index.php/Sales/cancel_invoice'); ?>",
          type: "POST",
          data: {
            invoice_id: invoice_id
          }, // pass invoice id dynamically
          success: function(response) {
            alert("Invoice cancelled successfully!");
            // You can refresh table or redirect if needed
            location.reload();
          },
          error: function() {
            alert("Something went wrong while cancelling.");
          }
        });
      }
    }
  });
</script>