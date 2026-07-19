<style>
    th {
        background: #f5f5f5;
        font-weight: bold;
    }
    td, th {
        vertical-align: middle !important;
    }
    .dropdown-menu{
    min-width:220px;
    border-radius:8px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.dropdown-item{
    padding:8px 15px;
}

.dropdown-item i{
    width:20px;
}
</style>

<div class="row">
<div class="col-md-12">
<div class="x_panel">
<!-- <div class="x_title">
    <h4>Project List</h4>
    <div class="text-end">
        <a href="<?= base_url('index.php/Project/add_project/'.$project_id) ?>" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Add Project
        </a>
    </div>
    <div class="clearfix"></div>
</div> -->

<div class="x_content">

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

<!-- <table class="table table-bordered table-striped" id="projectTable"> -->
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Project Code</th>
    <th>Project Name</th>
    <th>Customer</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Duration</th>
    <th>Grand Total</th>
    <th>Status</th>
    <th width="15%">Action</th>
</tr>
</thead>

<tbody>
<?php if (!empty($projects)): ?>
<?php foreach ($projects as $i => $row): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><a class="view-item" data-id="<?php echo $row['project_id']; ?>" href="" title="View Items"><?= $row['project_code'] ?></a></td>
    <td><?= $row['project_name'] ?></td>
    <td><?= $row['customer_name'] ?></td>
    <td><?= date('d-m-Y', strtotime($row['start_date'])) ?></td>
    <td><?= date('d-m-Y', strtotime($row['end_date'])) ?></td>
    <td><?= $row['duration'] ?> Days</td>
    <td class="text-end"><?= number_format($row['grand_total'], 2) ?></td>
    <td>
    <?php
        $status = $row['status'] ?? 'Open';
        $badgeClass = 'bg-secondary'; // Default

        switch(strtolower($status)){
            case 'pending':
                $badgeClass = 'bg-warning';
                break;
            case 'approved':
                $badgeClass = 'bg-success';
                break;
            case 'rejected':
                $badgeClass = 'bg-danger';
                break;
            case 'open':
                $badgeClass = 'bg-info';
                break;
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
</td>

    <td class="text-center">

    <div class="dropdown">
        <button class="btn btn-light btn-sm border"
                type="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item"
            href="<?= base_url('index.php/Project/edit_project/'. $row['project_id']) ?>">
                <i class="fa fa-edit text-warning"></i> Edit Project
            </a>

            <a class="dropdown-item view-item"
            href="#"
            data-id="<?= $row['project_id'] ?>">
                <i class="fa fa-eye text-info"></i> View Materials
            </a>
             <a class="dropdown-item" href="<?= base_url('index.php/Project/list_resource_planning/'. $row['project_id']) ?>">
                <i class="fa fa-calendar"></i> Resource Planning
            </a>


            <a class="dropdown-item view-team" data-id="<?= $row['project_id'] ?>" href="<?= base_url('index.php/Project/list_task/'.$row['project_id']) ?>">
                <i class="fa fa-users text-primary"></i>View Assigned Team
            </a>

            <a class="dropdown-item"  href="<?= base_url('index.php/Project/list_task/'. $row['project_id']) ?>">
                <i class="fa fa-tasks text-success"></i> Project Tasks
            </a>

            <a class="dropdown-item" href="#">
                <i class="fa fa-calendar"></i> Timeline
            </a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-danger"
            href="<?= base_url('index.php/Project/delete/'.$row['project_id']) ?>"
            onclick="return confirm('Delete this project?')">
                <i class="fa fa-trash"></i> Delete
            </a>

        </div>
    </div>

</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="10" class="text-center">No projects found</td>
</tr>
<?php endif; ?>
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
               <button type="button" class="close custom-close" data-dismiss="modal">
    &times;
</button>
            </div>

            <div class="modal-body">
                <p class="text-center">Loading content...</p>
            </div>

        </div>
    </div>
</div>
<div class="modal-backdrop-custom" style="display:none;"></div>
<!--modal-->

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#datatable-responsive')) {
        $('#datatable-responsive').DataTable({
            responsive: true
        });
    }
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
        url: "<?php echo base_url()?>index.php/Project/project_popup_materials",
        type: "POST",
        data: { id: id },
        success: function (response) {
            var data = JSON.parse(response);
            $("#ajaxModal .modal-title").text(data.title);
            $("#ajaxModal .modal-body").html(data.html);
        }
    });
});
$(document).on("click", ".close, .btn-close, .modal-backdrop-custom", function () {
    $("#ajaxModal").hide();
    $(".modal-backdrop-custom").hide();
});

//view assined task

$(document).on("click", ".view-team", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let id = $(this).data("id");
    $(".modal-backdrop-custom").show();
    $("#ajaxModal").addClass("modal-show").show();
    $(".modal-title").text("Loading...");
    $(".modal-custom-body").html("Loading...");
     $.ajax({
        url: "<?php echo base_url()?>index.php/Project/project_task_popup",
        type: "POST",
        data: { id: id },
        success: function (response) {
            var data = JSON.parse(response);
            $("#ajaxModal .modal-title").text(data.title);
            $("#ajaxModal .modal-body").html(data.html);
        }
    });
});
$(document).on("click", ".close, .btn-close, .modal-backdrop-custom", function () {
    $("#ajaxModal").hide();
    $(".modal-backdrop-custom").hide();
});
//popover
$(document).on("click",".action-btn",function(e){

    e.stopPropagation();

    $(".action-dropdown").not($(this).parent()).removeClass("active");

    $(this).parent().toggleClass("active");

});

$(document).click(function(){

    $(".action-dropdown").removeClass("active");

});
</script>
