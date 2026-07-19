<style>
th{
    background:#f5f5f5;
    font-weight:bold;
}
td,th{
    vertical-align:middle!important;
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

<div class="x_title">
    <h2>Resource Planning</h2>

    <div class="pull-right">
        <a href="<?=base_url('index.php/Project/add_resource_planning/'.$project_id)?>"
           class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Add Resource Planning
        </a>
    </div>

    <div class="clearfix"></div>
</div>

<div class="x_content">

<?php if($this->session->flashdata('success')){ ?>

<div class="alert alert-success">
    <?=$this->session->flashdata('success');?>
</div>

<?php } ?>

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap"
width="100%">

<thead>

<tr>
    <th>#</th>
    <th>Code</th>
    <th>Project</th>
    <th>Machine</th>
    <th>Operator</th>
    <th>Operation</th>
    <th>Hours</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Status</th>
    <th width="8%">Action</th>
</tr>

</thead>

<tbody>

    <?php

    $i=1;

    foreach($resources as $row){

    ?>

    <tr>

        <td><?=$i++;?></td>

        <td><?=$row->resource_code;?></td>

        <td><?=$row->project_name;?></td>

        <td><?=$row->machine_name;?></td>

        <td><?=$row->employee_name;?></td>

        <td><?=$row->operation_name;?></td>

        <td><?=$row->hours_needed;?></td>

        <td><?=date('d-m-Y',strtotime($row->start_date));?></td>

        <td><?=date('d-m-Y',strtotime($row->end_date));?></td>

        <td>

        <?php

        $class='warning';

        switch($row->status){

        case 'Completed':
        $class='success';
        break;

        case 'In Progress':
        $class='primary';
        break;

        case 'Cancelled':
        $class='danger';
        break;

        }

        ?>

        <span class="label label-<?=$class;?>">
        <?=$row->status;?>
        </span>

        </td>

        <td class="text-center">

        <div class="dropdown">

        <button class="btn btn-light btn-sm border"

        data-toggle="dropdown">

        <i class="fa fa-ellipsis-v"></i>

        </button>

        <div class="dropdown-menu dropdown-menu-right">

        <a class="dropdown-item"

        href="<?=base_url('index.php/Project/edit_resource_planning/'.$project_id.'/'.$row->resource_id)?>">

        <i class="fa fa-edit text-warning"></i>

        Edit

        </a>

        <a class="dropdown-item text-danger"

        onclick="return confirm('Delete this Resource Planning?')"

        href="<?=base_url('index.php/Project/delete_resource_planning/'.$project_id.'/'.$row->resource_id)?>">

        <i class="fa fa-trash"></i>

        Delete

        </a>

        </div>

        </div>

        </td>

    </tr>

    <?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

