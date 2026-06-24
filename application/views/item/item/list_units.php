<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_content">
<table id="unitTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>unit </th>
              <th>unit details </th>
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
                      <?php if(has_access($user,$page_name,'E')){ ?>
                        <a href='<?php echo base_url().'index.php/Item/edit_unit/'.$unit->unit_id ; ?>' title='Edit'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                      <?php } ?>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <?php if (has_access($user, $page_name, 'D')): ?>
                      <a href="javascript:void(0);" onclick="confirmDeleteUnit(<?= $unit->unit_id ?>)" title="Delete">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                      </a>
                       <?php endif; ?>
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
  if (confirm("Are you sure you want to delete this supplier?")) {
    $.ajax({
      url: "<?= base_url('index.php/Item/delete_unit') ?>",
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
