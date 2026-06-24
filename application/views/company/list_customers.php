<?php 
$page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$user = $this->session->userdata('user_id');
?>

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

      <div class="x_content">
         <!-- <div class="x_title">
                <form action="<?= base_url()?>index.php/Company/list_customers" method="post">
            <div class="col-md-4"  >
                <input type="text" id="smart_filter" name="filter" class="form-control" placeholder="Type filter..." style="width: 400px;">
                <ul id="filter_list" style="border: 1px solid #ccc; list-style: none; margin: 0; padding: 5px; position: absolute; display: none; background: #fff; width: 400px; z-index: 10;">
                        <li data-key="designation_code ">Designation Code</li>
                        <li data-key="designation_name">Designation Name</li>
                        <li data-key="department">Department</li>
                        <li data-key="reporting_to">Reporting To</li>
                        <li data-key="level">Level</li>
                        <li data-key="employment_type">Type</li>
                        <li data-key="location">Location</li>
                        <li data-key="status">Status</li>
                </ul>
            </div>
            <div class="col-md-3" style="float: right;" >
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </form>
                <div class="clearfix"></div> -->
            <!-- </div>
         <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                      <?php echo $this->session->flashdata('error'); ?>
                    </div>
          <?php endif; ?>
          <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success">
              <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php endif; ?> --> 
<table id="customerTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer Name</th>
              <th>Branch</th>
              <th>Customer Code</th>
              <th>Email</th>
              <th>Contact Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_customers as $customer): ?>
              <tr>
                <th scope="row"><?= $i; ?></th>
                <td><?= $customer->customer_name; ?></td>
                <td><?= $customer->branch_name; ?></td>
                <td><?= $customer->customer_code; ?></td>
                <td><?= $customer->customer_email; ?></td>
                <td><?= $customer->contact_number; ?></td>
                <td>
                  <?php if (has_access($user, $page_name, 'E')): ?>
                    <a href="<?= base_url('index.php/Company/edit_customer/' . $customer->customer_id); ?>" title="Edit">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>
                  <?php endif; ?>

                  &nbsp;&nbsp;&nbsp;

                  <?php if (has_access($user, $page_name, 'D')): ?>
                              <a href="javascript:void(0);" onclick="confirmcancel(<?= $customer->customer_id ?>)" title="Delete">
                                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                              </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php $i++; endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>

function confirmcancel(tid) {
     if (event) event.preventDefault();
    if (confirm("Are you sure you want to delete this Customer?")) {
        $.ajax({
            url: "<?= base_url('index.php/Company/delete_customer') ?>",
            type: "POST",
            data: { id: tid },
            dataType: "json",
            success: function(response) {
             console.log(response); 
                if (response.status == 1) {
                    alert(response.message);
                    location.reload(); // Reload only on successful deletion
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
                alert("An unexpected error occurred.");
            }
        });
    }
}
$(document).ready(function() {
    $('#customerTable').DataTable({
        "pageLength": 10
    });
});
</script>