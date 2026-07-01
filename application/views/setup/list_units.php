<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      
                <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_unit') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>  Add Unit
                    </a>
                </div>
      <div class="x_content">
<table id="unitTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Unit </th>
              <th>Unit Abbreviation</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1;foreach($units as $unit){ ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $unit->unit_name; ?></td>
                    <td><?php echo $unit->unit_abbr; ?></td>
                    <td>
                        <a href='<?php echo base_url().'index.php/Setup/edit_unit/'.$unit->unit_id ; ?>' title='Edit'  class="btn btn-primary btn-sm">Edit</a>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="javascript:void(0);" onclick="confirmDeleteUnit(<?= $unit->unit_id ?>)" title="Delete"  class="btn btn-danger btn-sm">
                       Delete
                      </a>
                       </td>
                  </tr>
              <?php $i++;} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
function confirmDeleteUnit(id) {
  if (event) event.preventDefault();
  if (confirm("Are you sure you want to delete this unit?")) {
    $.ajax({
      url: "<?= base_url('index.php/Setup/delete_unit') ?>",
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
    $('#unitTable').DataTable({
        "pageLength": 10
    });
});
</script>
