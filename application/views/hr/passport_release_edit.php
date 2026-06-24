<div class="x_panel">
    <div class="x_title">
        <h2>Edit Passport Release</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" class="form-horizontal form-label-left" method="post"
              action="<?php echo base_url('index.php/Hr/update_passport_release'); ?>" autocomplete="off">

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6 col-sm-6">

                    <!-- Employee -->
                    <div class="form-group">
                        <label>Select Employee *</label>
                        <select class="form-control" name="employee_id" disabled>
                            <option value="">Select</option>
                            <?php foreach($records as $emp) { ?>
                                <option value="<?= $emp->employee_id ?>"
                                    <?= ($emp->employee_id == $record1->employee_id) ? 'selected' : ''; ?>>
                                    <?= $emp->user_code . ' - ' . $emp->employee_name ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="employee_id_hidden" value="<?= $record1->employee_id ?>">
                    </div>

                    <!-- Passport Issue Date -->
                    <div class="form-group">
                        <label>Passport Issue Date</label>
                        <input type="text" class="form-control" readonly
                               value="<?= isset($record->passport_issue_date) ? date('d-m-Y', strtotime($record->passport_issue_date)) : ''; ?>">
                    </div>

                    <!-- Employee Number -->
                    <div class="form-group">
                        <label>Employee Number</label>
                        <input type="text" class="form-control" readonly
                               value="<?= $record->user_code ?? ''; ?>">
                    </div>

                    <!-- Passport Keeping Location -->
                    <div class="form-group">
                        <label>Passport Keeping Location *</label>
                        <select class="form-control" name="location" required>
                            <option value="">Select</option>
                            <option value="Company" <?= ($record1->posession == 'Company') ? 'selected' : ''; ?>>Company</option>
                            <option value="Their Own" <?= ($record1->posession == 'Their Own') ? 'selected' : ''; ?>>Their Own</option>
                        </select>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-md-6 col-sm-6">

                    <!-- Passport Expiry Date -->
                    <div class="form-group">
                        <label>Passport Expiry Date</label>
                        <input type="text" class="form-control" readonly
                               value="<?= isset($record->passport_expiry_date) ? date('d-m-Y', strtotime($record->passport_expiry_date)) : ''; ?>">
                    </div>

                    <!-- Passport Number -->
                    <div class="form-group">
                        <label>Passport No</label>
                        <input type="text" class="form-control" readonly
                               value="<?= $record->passport_number ?? ''; ?>">
                    </div>

                    <!-- Passport Release Date -->
                    <div class="form-group">
                        <label>Passport Release Date *</label>
                            <input type="date" class="form-control" name="outdate" required
                                   value="<?= $record1->outdate ?? ''; ?>">
                        </div>

                    <!-- Return Date -->
                    <div class="form-group">
                        <label>Return Date *</label>
                            <input type="date" class="form-control" name="indate" required
                                   value="<?= $record1->indate ?? ''; ?>">
                    </div>

                </div>
            </div>

            <!-- Purpose & Remark -->
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Passport Release Purpose *</label>
                        <textarea name="reason" class="form-control" rows="2" required><?= $record1->reason ?? ''; ?></textarea>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea name="remark" class="form-control" rows="2"><?= $record1->remark ?? ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-group text-center">
                <input type="hidden" name="id" value="<?= $record1->emp_docId ?? ''; ?>">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                <a href="<?= base_url('index.php/Hr/view_passport_release_list'); ?>" class="btn btn-dark">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

        </form>
    </div>
</div>

<!-- Styles -->
<style>
    .form-group label { font-weight: 600; }
    .form-control { font-size: 13px !important; }
</style>

<!-- JS: only for add mode, disabled in edit -->
<script>
    var is_edit = true; // disable get_user_info in edit mode
</script>
