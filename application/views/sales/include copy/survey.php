 <?php if(isset($Resurvey)&&!empty($Resurvey)){?>
                                        <form method="post"  action="<?= base_url() ?>index.php/Sales/save_survey/<?= $enquiry_data['enquiry_id'] ?>">
                                    <?php } else{?>
                                    <form method="post"  action="<?= base_url() ?>index.php/Sales/accept_site_survey/<?= $enquiry_data['enquiry_id'] ?>">
                                    <?php }?>    
                                    <input type="hidden" name="enquiry_id" value="<?= isset($enquiryid) ? $enquiryid : "" ?>">

                                        <div class="form-group">
                                            <label for="employee_survey">Surveyor</label>
                                            <select name="employee_survey" id="employee_survey" class="form-control">
                                                <?php foreach ($employee_list as $employee): ?>
                                                    <option value="<?= $employee->employee_id ?>" <?= (isset($survey_data) && $survey_data['assigned_person_id'] == $employee->employee_id) ? "selected" : "" ?>>
                                                        <?= $employee->employee_name ?> (<?= $employee->uid_number ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <!-- Survey Date -->
                                            <div class="col-md-2 form-group">
                                                <label for="survey_date">Schedule Date</label>
                                                <input type="date"
                                                    name="survey_date"
                                                    id="survey_date"
                                                    value="<?= isset($survey_data['scheduled_date']) ? date('Y-m-d', strtotime($survey_data['scheduled_date'])) : date('Y-m-d') ?>"
                                                    class="form-control">
                                            </div>

                                            <!-- Start Time -->
                                            <div class="col-md-4 form-group">
                                                <div class="col-md-6 col-sm-6 form-group">
                                                    <label for="survey_start_datetime">Start Date & Time <span class="required">*</span></label>
                                                    <input type="datetime-local"
                                                        name="survey_start_datetime"
                                                        id="survey_start_datetime"
                                                        class="form-control"
                                                        value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d\TH:i', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d\TH:i') ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <!-- End Time -->
                                            <div class="col-md-4 form-group">
                                                <div class="col-md-6 col-sm-6 form-group">
                                                    <label for="survey_end_datetime">End Date & Time <span class="required">*</span></label>
                                                    <input type="datetime-local"
                                                        name="survey_end_datetime"
                                                        id="survey_end_datetime"
                                                        class="form-control"
                                                        value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d\TH:i', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d\TH:i') ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label for="end_time">Total Hours</label>
                                                <input type="text"
                                                    name="total_hours"
                                                    id="total_hours"
                                                    value="<?= isset($survey_data['scheduled_hours']) ? $survey_data['scheduled_hours'] : ''; ?>"
                                                    class="form-control"
                                                    readonly>
                                            </div>
                                        </div> <!-- /.row -->

                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control" rows="3"><?= isset($survey_data['remarks']) ? $survey_data['remarks'] : ''; ?></textarea>
                                        </div>

                                        <?php  if(empty($Resurvey) && $enquiry_data['enquiry_status'] == 2 ){?>

                                        <hr>
                                        <h3>Survey data</h3>

                                        <div class="row">
                                            <!-- Actual Date -->
                                            <div class="col-md-2 form-group">
                                                <label for="actual_date">Actual Date</label>
                                                <input type="date"   name="actual_date"   id="actual_date"    value="<?= !empty($survey_data['actual_date']) ? date('Y-m-d', strtotime($survey_data['actual_date'])) : '' ?>" class="form-control">
                                            </div>

                                            <!-- Actual Start Time -->
                                            <div class="col-md-4 form-group">
                                                <div class="col-md-6 col-sm-6 form-group">
                                                    <label for="actual_start_time">Actual Start Date & Time</label>
                                                    <input type="datetime-local"
                                                        name="actual_start_time"
                                                        id="actual_start_time"
                                                        class="form-control"
                                                        value="<?= !empty($survey_data['actual_start_time']) ? date('Y-m-d\TH:i', strtotime($survey_data['actual_start_time'])) : '' ?>">
                                                </div>
                                            </div>

                                            <!-- Actual End Time -->
                                            <div class="col-md-4 form-group">
                                                <div class="col-md-6 col-sm-6 form-group">
                                                    <label for="actual_end_time">Actual End Date & Time</label>
                                                    <input type="datetime-local"
                                                        name="actual_end_time"
                                                        id="actual_end_time"
                                                        class="form-control"
                                                        value="<?= !empty($survey_data['actual_end_time']) ? date('Y-m-d\TH:i', strtotime($survey_data['actual_end_time'])) : '' ?>">
                                                </div>
                                            </div>

                                            <!-- Actual Hours -->
                                            <div class="col-md-2 form-group">
                                                <label for="actual_hours">Actual Hours</label>
                                                <input type="text"    name="actual_hours"  id="actual_hours" value="<?= !empty($survey_data['actual_hours']) ? $survey_data['actual_hours'] : '' ?>"   class="form-control" readonly>
                                            </div>
                                        </div> <!-- /.row -->

                                        <!-- Survey Comments -->
                                        <div class="form-group">
                                            <label for="survey_comments">Survey Comments</label>
                                            <textarea name="survey_comments" id="survey_comments" class="form-control" rows="3"><?= !empty($survey_data['survey_comments']) ? $survey_data['survey_comments'] : '' ?></textarea>
                                        </div>

                                        <!-- Material Details -->
                                        <div class="form-group">
                                            <label for="material_details">Material Details</label>
                                            <textarea name="material_details" id="material_details" class="form-control" rows="3"><?= !empty($survey_data['material_details']) ? $survey_data['material_details'] : '' ?></textarea>
                                        </div>

                                        <!-- Attachments -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Attachments</label>

                                                <!-- Existing survey files -->
                                                <?php if (!empty($survey_files) && count($survey_files) > 0): ?>
                                                    <?php foreach ($survey_files as $index => $file): ?>
                                                        <div class="mb-2">
                                                            <a href="<?= base_url('public/survey_files/' . $file->file_name) ?>"
                                                            target="_blank"
                                                            class="btn btn-sm btn-info">
                                                                View / Download (File <?= $index + 1 ?>)
                                                            </a>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <!-- Add new attachment button -->
                                                <button type="button" id="add-attachment" class="btn btn-sm btn-primary mt-2">
                                                    + Upload Attachment
                                                </button>

                                                <!-- Dynamic file inputs will appear here -->
                                                <div id="attachments-wrapper" class="mt-2"></div>
                                            </div>
                                        </div> <!-- /.row (Attachments) -->
                                                      
                                        <br><br>

                                        <!-- Action Buttons -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php if ($enquiry_data['enquiry_status'] == 2 || $enquiry_data['enquiry_status'] == 1): ?>
                                                    <!-- <button type="submit" class="btn btn-primary">Re Schedule Survey</button> -->
                                                    <a href="<?= base_url('index.php/Sales/re_schedule_survey/' . $enquiry_data['enquiry_id'])?>"
                                                    class="btn btn-primary">Re Schedule Survey</a>
                                                    <a href="<?= base_url('index.php/Sales/accept_site_survey/' . $enquiry_data['enquiry_id']) ?>"
                                                    class="btn btn-primary">Go To Estimation</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="survey_table_id" value="<?= !empty($survey_data['survey_id']) ? $survey_data['survey_id'] : '' ?>">
                                <?php } else{?>
                                        <input type="hidden" name="re_survey_id" value="<?= isset($Resurvey)?$Resurvey:"" ?>">
                                        <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">

                                        <button type="submit" class="btn btn-primary">Schedule Survey</button>
                                <?php } ?>
                                    </form>
                                    <h4>Survey History</h4>
                                    <?php if (!empty($old_survey_data)):$i=1; ?>
                                        <?php foreach ($old_survey_data as  $survey): ?>
                                             <button type="button" class="btn btn-outline-primary btn-sm m-1 viewSurveyBtn"     data-id="<?= $survey->survey_id ?>">
                                                    Survey <?= $i ?>
                                            </button>
                                        <?php $i++; endforeach; ?>
                                    <?php else: ?>
                                        <p>No old surveys found.</p>
                                    <?php endif; ?>