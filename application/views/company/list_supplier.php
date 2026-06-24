<?php 
$page_name = $this->uri->segment(1) . '/' . $this->uri->segment(2);
$user = $this->session->userdata('user_id');
?>

<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
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

<table id="supplierTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Supplier Name</th>
              <th>Email</th>
              <th>Contact Number</th>
              <th>TRN No.</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_suppliers as $supplier): ?>
              <tr>
                <th scope="row"><?= $i; ?></th>
                <td><?= $supplier->supplier_name; ?></td>
                <td><?= $supplier->supplier_email; ?></td>
                <td><?= $supplier->contact_number; ?></td>
                <td><?= $supplier->trn_no; ?></td>
                <td>
                  <?php if (has_access($user, $page_name, 'E')): ?>
                    <a href="<?= base_url('index.php/Company/edit_supplier/' . $supplier->supplier_id); ?>" title="Edit">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>
                  <?php endif; ?>

                  &nbsp;&nbsp;&nbsp;

                  <?php if (has_access($user, $page_name, 'D')): ?>
                    <a href="javascript:void(0);" onclick="confirmDeleteSupplier(<?= $supplier->supplier_id ?>)" title="Delete">
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
function confirmDeleteSupplier(id) {
  if (event) event.preventDefault();
  if (confirm("Are you sure you want to delete this supplier?")) {
    $.ajax({
      url: "<?= base_url('index.php/Company/delete_supplier') ?>",
      type: "POST",
      data: { id: id },
      dataType: "json",
      success: function(response) {
        if (response.status == 1) {
          alert(response.message);
          location.reload();
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
    $('#supplierTable').DataTable({
        "pageLength": 10
    });
});
</script>
