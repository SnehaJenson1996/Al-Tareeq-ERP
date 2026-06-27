<?php 
$page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$user = $this->session->userdata('user_id');
?>

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">

                
                 <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_customer') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Add Customer
                    </a>
                </div>
      
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
       
         <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                      <?php echo $this->session->flashdata('error'); ?>
                    </div>
          <?php endif; ?>
          <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success">
              <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php endif; ?> 
<table id="customerTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer Code</th>
              <th>Customer Name</th>
              
              <th>Email</th>
              <th>Contact Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_customers as $customer): ?>
              <tr>
                <th scope="row"><?= $i; ?></th>
                               <td><?= $customer->customer_code; ?></td>

                 <td><?= $customer->customer_name; ?></td>
                <td><?= $customer->customer_email; ?></td>
                <td><?= $customer->office_telephone; ?></td>
                <td>
                    <a href="<?= base_url('index.php/Setup/edit_customer/' . $customer->customer_id); ?>" title="Edit" class="btn btn-primary btn-sm">Edit
                      
                    </a>

                  &nbsp;&nbsp;&nbsp;

                              <a href="javascript:void(0);" onclick="confirmcancel(<?= $customer->customer_id ?>)" title="Delete"  class="btn btn-danger btn-sm">Delete
                                  
                              </a>
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