<style>
    table th, table td {
        color: black;
        font-size: 13px;
        vertical-align: middle !important;
    }
    h4 {
        color: black;
        font-weight: bold;
    }
    table#datatable-responsive thead th {
    position: sticky;
    top: 0;
    background-color: #343a40; /* Match .thead-dark */
    color: white;
    z-index: 10;
    box-shadow: 0 2px 2px -1px rgba(0,0,0,0.2);
}
</style>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
               
            </div>
<div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
    <table id="datatable-responsive"
            class="table table-striped table-bordered dt-responsive"
            cellspacing="0" width="100%">                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Branch</th>
                            <th>Enquiry Code</th>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Surveyor</th>
                            <th>Scheduled Date</th>
                            <th>Scheduled Hours</th>
                            <th>Actual Date</th>
                            <th>Actual Hours</th>
                            <th>Survey Comments</th>
                            <th>Files</th>
                            <th>Created By</th>
                            <th>Updated On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($survey_list)): ?>
                            <?php $i = 1; foreach ($survey_list as $survey): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $survey['branch_name'] ?></td>
                                    <td><?= $survey['enquiry_code'] ?></td>
                                    <td><?= $survey['project_name'] ?></td>
                                    <td><?= $survey['enquiry_category'] ?></td>
                                    <td><?= $survey['surveyor'] ?></td>
                                    <td><?= $survey['scheduled_date'] ?></td>
                                    <td><?= $survey['scheduled_hours'] ?></td>
                                    <td><?= $survey['actual_date'] ?></td>
                                    <td><?= $survey['actual_hours'] ?></td>
                                    <td><?= $survey['survey_comments'] ?></td>
                                    <td>
                                        <?php if (!empty($survey['file_names'])): ?>
                                            <?php foreach (explode(',', $survey['file_names']) as $file): ?>
                                                <a href="<?= base_url('public/survey_files/' . trim($file)) ?>" target="_blank" class="badge badge-info">
                                                    <?= $file ?>
                                                </a><br>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No Files</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $survey['preparedby'] ?></td>
                                    <td><?= $survey['updated_on'] ?></td>
                                    <td>
                                        <a href="<?= base_url('index.php/Document_controller/print_survey/'.$survey['survey_id'].'/'.$survey['enquiry_id']) ?>" 
                                           class="btn btn-sm btn-primary">Print</a>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="14" class="text-center text-muted">No surveys found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#datatable-responsive')) {
        $('#datatable-responsive').DataTable().destroy();
    }

    $('#datatable-responsive').DataTable({
        responsive: {
            details: {
                type: 'column'
            }
        },
        columnDefs: [
            { responsivePriority: 1, targets: 0 },   // SL No
            { responsivePriority: 2, targets: 1 },   // Branch
            { responsivePriority: 3, targets: 2 },   // Enquiry Code
            { responsivePriority: 4, targets: 3 },   // Project
            { responsivePriority: 5, targets: -1 }   // Action column (print)
        ]
    });
});
</script>