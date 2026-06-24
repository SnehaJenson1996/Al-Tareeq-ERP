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
                <?php if(!empty($quotations)) { $i=1; foreach($quotations as $qtn) { ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><a href="<?= base_url('index.php/Sales/view_enquiry/'.$qtn['qtn_id']); ?>" ><strong><?= $qtn['quotation_code']; ?></strong></a></td>
                    <td><?= date("d-m-Y", strtotime($qtn['quotation_date'])); ?></td>
                    <td><a href="<?= base_url()?>index.php/CRM/view_enquiry/<?= $qtn['enquiry_id']?>"><?= $qtn['enquiry_code']; ?></a></td>
                    <td><?= $qtn['branch_name']; ?></td>
                    <td><?= $qtn['customer_name']; ?></td>
                    <td style="text-align:right;"><?= number_format($qtn['grand_total'], 2); ?></td>
                    <td>
                      <?php
                        if($qtn['quotation_status'] == 1 || $qtn['quotation_status'] == 'Approved') {
                          echo '<span class="label label-success">Approved</span>';
                        } elseif($qtn['quotation_status'] == 0 || $qtn['quotation_status'] == 'Pending') {
                          echo '<span class="label label-warning">Pending</span>';
                        } else {
                          echo '<span class="label label-default">'.$qtn['quotation_status'].'</span>';
                        }
                      ?>
                    </td>
                    <td><?= date("d-m-Y H:i:s", strtotime($qtn['created_on'])); ?></td>
                    <td>
                      <a href="<?= base_url('index.php/Sales/view_quotation/'.$qtn['qtn_id']); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                      <a href="<?= base_url('index.php/Sales/delete/'.$qtn['qtn_id']); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php } } else { ?>
                  <tr><td colspan="10" class="text-center text-muted">No quotations found</td></tr>
                <?php } ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
<!-- </div> -->
