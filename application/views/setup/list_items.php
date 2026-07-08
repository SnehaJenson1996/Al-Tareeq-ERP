<?php 
	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<!-- Bootstrap CSS -->
<style>
/* BACKDROP */
.modal-backdrop-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* MODAL WRAPPER */
.modal-custom {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 800px;
    max-width: 95%;
    background: #fff;
    border-radius: 8px;
    z-index: 1000;
    display: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* HEADER */
.modal-custom-header {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BODY */
.modal-custom-body {
    padding: 15px;
    max-height: 70vh;
    overflow-y: auto;
}

/* CLOSE BUTTON */
.modal-close {
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
}

/* SHOW */
.modal-show {
    display: block;
}

/* ANIMATION */
.modal-custom {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from { transform: translate(-50%, -55%); opacity: 0; }
    to { transform: translate(-50%, -50%); opacity: 1; }
}
</style>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="pull-right">
                    <a href="<?= base_url('index.php/Setup/add_item') ?>"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>Add Item
                    </a>
                </div>
      <div class="x_content">
<table id="itemTable" class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Item Code</th>
              <th>Item name</th>
               <th>Item Unit</th>
              <th>Unit Price</th>
              <th>Total Price</th>
              <th>Item Description</th>
              <th>Image</th>  <!-- New column -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; foreach($all_items as $item){ ?>
              <tr>
                <th scope="row"><?php echo $i; ?></th>
                <td><a class="view-item" data-id="<?php echo $item->product_id; ?>" href="" title="Raw Materials"><?php echo $item->product_code; ?></a></td>
                <td><?php echo $item->product_name; ?></td>
                <td><?php echo $item->unit_name; ?></td>
                <td><?php echo $item->retail_price; ?></td>
                <td><?php echo $item->total_price; ?></td>
                <td><?php echo $item->description; ?></td>

                <td>
                  <?php if (!empty($item->product_image) && file_exists('./public/items/' . $item->product_image)) { ?>
                    <img src="<?php echo base_url('public/items/' . $item->product_image); ?>" alt="Item Image" style="width: 60px; height: auto;"/>
                  <?php } else { ?>
                    <span>No Image</span>
                  <?php } ?>
                </td>
                <td>
                    <a href='<?php echo base_url().'index.php/Setup/edit_item/'.$item->product_id; ?>' title='Edit' class="btn btn-primary btn-sm">Edit</a>
                    
                    
                  &nbsp;&nbsp;&nbsp;&nbsp;
                   

                    <a href='<?php echo base_url().'index.php/Setup/delete_item/'.$item->product_id; ?>' 
   title='Delete' 
   onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-sm">
   Delete
</a>
                </td>
              </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="ajaxModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Loading...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
            </div>

            <div class="modal-body">
                <p class="text-center">Loading content...</p>
            </div>

        </div>
    </div>
</div>
<div class="modal-backdrop-custom" style="display:none;"></div>

<div class="modal-custom" id="ajaxModal">
    <div class="modal-custom-header">
        <h4 class="modal-title">Loading...</h4>
        <span class="modal-close">&times;</span>
    </div>

    <div class="modal-custom-body">
        Loading...
    </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#itemTable').DataTable({
        "pageLength": 10
    });
});

$(document).on("click", ".view-item", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let id = $(this).data("id");

    $(".modal-backdrop-custom").show();
    $("#ajaxModal").addClass("modal-show").show();

    $(".modal-title").text("Loading...");
    $(".modal-custom-body").html("Loading...");
     $.ajax({
        url: "<?php echo base_url()?>index.php/Ajax/popup_materials",
        type: "POST",
        data: { id: id },
        success: function (response) {

            var data = JSON.parse(response);

            $("#ajaxModal .modal-title").text(data.title);
            $("#ajaxModal .modal-body").html(data.html);
        }
    });
});


$(document).on("click", ".btn-close, .modal-backdrop-custom", function () {
    $("#ajaxModal").hide();
    $(".modal-backdrop-custom").hide();
});
</script>