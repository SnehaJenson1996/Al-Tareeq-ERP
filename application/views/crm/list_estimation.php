<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-list"></i> Estimation List</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="fa fa-wrench"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Settings 1</a>
                    <a class="dropdown-item" href="#">Settings 2</a>
                </div>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Enquiry Code</th>
                    <th>Enquiry Date</th>
                    <th>Estimation Date</th>
                    <th>Grand Total</th>
                    <th>Prepared By</th>
                    <th>Approval</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($estimations)): $i=1; ?>
                    <?php foreach($estimations as $est): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $est['customer_name'] ?? '' ?></td>
                            <td><?= $est['branch_name'] ?? '' ?></td>
                            <td><?= $est['enquiry_code'] ?? '' ?></td>
                            <td><?= date('d-m-Y', strtotime($est['enquiry_date'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($est['estimation_date'])) ?></td>
                            <td><?= number_format($est['grand_total'], 2) ?></td>
                            <td><?= $est['preparedby'] ?? '' ?></td>
                            <td>
                                <?php if($est['approval']==1): ?>
                                    <span class="badge badge-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                               <a href="<?= base_url('index.php/CRM/view_enquiry/' . $est['enquiry_id'] . '/edit/3') ?>" class="btn btn-sm btn-primary">
    Edit
</a>

                                <a href="<?= base_url('index.php/sales/view_estimation/'.$est['estimation_id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <a href="<?= base_url('index.php/sales/print_estimation/'.$est['estimation_id'].'/'.$est['enquiry_id']) ?>" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No estimations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Initialize DataTables (if not already initialized globally) -->
<script>
$('.datatable').DataTable({ responsive: true });

$(document).ready(function(){
    // menu_status = 1: Site Survey, 2: Quotation, 3: Estimation, etc.
    var activeTab = <?= $menu_status ?>;
    $('.nav-tabs li:nth-child(' + activeTab + ') a').tab('show');
});
</script>

