<?php
$page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$user = $this->session->userdata('user_id');
?>
<div class="x_panel">
  <div class="x_content">
    <div class="row">
      <div class="col-sm-12">
        <!-- ✅ table-responsive wrapper -->
        <div class="table-responsive">

          <table id="datatable-responsive"
            class="table table-striped table-bordered dt-responsive"
            cellspacing="0" width="100%">
            <thead>
              <tr style="background-color:#2A3F54; color:#ffffff;">
                <th>#</th>
                <th>Enq Code</th>
                <th>Project</th>
                <th>Project Subject</th>
                <th>Customer</th>
                <th>Enquiry Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($all_enquiries)) {
                $i = 1;
                foreach ($all_enquiries as $enquiry) { ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td>
                      <a href="<?= base_url('index.php/CRM/view_enquiry/' . $enquiry->enquiry_id . '/view'); ?>">
                        <?= $enquiry->enquiry_code; ?><br><?= $enquiry->enquiry_date; ?>
                      </a>
                      
                    </td>
                    <td><?= $enquiry->project_name; ?></td>
                    <td><?= $enquiry->project_subject; ?></td>
                    <td><?= $enquiry->customer_name; ?></td>
                    <!-- <td> -->
                      <!-- <?php
                      echo $enquiry->enquiry_status == 1 ? "Under Site Survey" : ($enquiry->enquiry_status == 2 ? "Completed Site Survey" : (($enquiry->enquiry_status == 3 || $enquiry->enquiry_status == 4)  ? "Estimation" : (($enquiry->enquiry_status == 5 || $enquiry->enquiry_status == 6) ? "Quotation" : (($enquiry->enquiry_status == 7 || $enquiry->enquiry_status == 8) ? "Sales Order" : ($enquiry->enquiry_status == 9  ? "Invoice" :
                                  "Unknown Status")))));
                      ?> -->

                      <td>
<?php
if ($enquiry->enquiry_status == 2) {

    if ($enquiry->survey_status == 2) {

        echo "Site Survey Completed";

    } elseif ($enquiry->survey_status == 1) {

        if ($enquiry->re_survey_status == 1) {
            echo "Site Survey Rescheduled";
        } else {
            echo "Site Survey Scheduled";
        }

    } else {

        echo "Under Site Survey";
    }
}elseif ($enquiry->enquiry_status == 3 || $enquiry->enquiry_status == 4) {
    echo "Estimation";

} elseif ($enquiry->enquiry_status == 5 || $enquiry->enquiry_status == 6) {
    echo "Quotation";

} elseif ($enquiry->enquiry_status == 7 || $enquiry->enquiry_status == 8) {
    echo "Sales Order";

} elseif ($enquiry->enquiry_status == 9) {
    echo "Invoice";

} else {
    echo "Under Site Survey";
}
?>
</td>
                    <!-- </td> -->
                    <td>
                      <!-- <?php if (has_access($user, $page_name, 'E')) { ?> -->
                      <a href="<?= base_url('index.php/CRM/view_enquiry/' . $enquiry->enquiry_id. '/view'); ?>"
                        title="view" class="btn btn-primary btn-xs">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="<?= base_url('index.php/CRM/view_enquiry/' . $enquiry->enquiry_id . '/edit'); ?>" title="edit" class="btn btn-primary btn-xs">
                        
                         <i class="fa fa-edit"></i>
                      </a>
                      <!-- <?php } ?> -->
                      <?php if (has_access($user, $page_name, 'D')) { ?>
                        <!-- <a href="<?= base_url('index.php/Sales/delete_enquiry/' . $enquiry->enquiry_id); ?>" 
                           title="Delete" class="btn btn-danger btn-xs" 
                           onclick="return confirm('Are you sure?');">
                          <i class="fa fa-trash"></i>
                        </a> -->
                      <?php } ?>
                    </td>
                  </tr>
                <?php }
              } else { ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">No enquiries found</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

        </div><!-- /.table-responsive -->
      </div>
    </div>
  </div>
</div>