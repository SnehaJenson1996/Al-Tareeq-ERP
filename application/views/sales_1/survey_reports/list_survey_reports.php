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
</style>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h4>Survey Reports</h4>
            </div>
            <div class="x_content">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
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
                                        <a href="<?= base_url('index.php/Sales/print_survey/'.$survey['survey_id'].'/'.$survey['enquiry_id']) ?>" 
                                           class="btn btn-sm btn-primary">print</a>
                                        
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
