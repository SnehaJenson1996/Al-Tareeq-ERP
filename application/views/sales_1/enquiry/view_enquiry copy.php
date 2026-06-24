<style>
  .nav-link.active {
    background-color: #007FFF !important; /* set background */
    color: white !important;           /* make text readable */
}
</style>
<div class="clearfix"></div>

    <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Tabs <small>Float left</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">New</a>
                            <a class="dropdown-item" href="#">Site Survey</a>
                            <a class="dropdown-item" href="#">Estimation</a>
                            
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link <?= isset($enquiry_data['enquiry_status'])&&$enquiry_data['enquiry_status']==1?"active":""?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" <?= isset($enquiry_data['enquiry_status'])&&$enquiry_data['enquiry_status']==1?"aria-selected='true'":"aria-selected='false'"?>><?= $enquiry_data['enquiry_code']?></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link <?= isset($enquiry_data['enquiry_status'])&&$enquiry_data['enquiry_status']==2?"active":""?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" <?= isset($enquiry_data['enquiry_status'])&&$enquiry_data['enquiry_status']==2?"aria-selected='true'":"aria-selected='false'"?>>Site Survey</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 3) ? 'active' : '' ?>" id="contact-tab" data-toggle="tab" href="#contact"  role="tab" aria-controls="contact" <?= isset($enquiry_data['enquiry_status'])&&$enquiry_data['enquiry_status']==3?"aria-selected='true'":"aria-selected='false'"?>>
                          Estimation
                        </a>
                      </li>       
                    </ul>
                    <div class="tab-content" id="myTabContent">
                                <!-- HOME TAB -->
                                <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 1 ? 'active show' : '' ?>"
                                    id="home"
                                    role="tabpanel"
                                    aria-labelledby="home-tab">

                                    <form action="<?= base_url('index.php/Sales/save_enquiry') ?>"
                                        method="post"
                                        enctype="multipart/form-data"
                                        autocomplete="off">

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Project Name <span class="required">*</span></label>
                                                <input type="text"
                                                    name="project_name"
                                                    class="form-control"
                                                    value="<?= isset($enquiry_data) ? $enquiry_data['project_name'] : "" ?>"
                                                    required>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Project Subject <span class="required">*</span></label>
                                                <input type="text"
                                                    name="project_subject"
                                                    value="<?= isset($enquiry_data) ? $enquiry_data['project_subject'] : "" ?>"
                                                    class="form-control"
                                                    required>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Project Location</label>
                                                <input type="text"
                                                    name="project_location"
                                                    value="<?= isset($enquiry_data) ? $enquiry_data['project_location'] : "" ?>"
                                                    class="form-control">
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Enquiry Category <span class="required">*</span></label>
                                                <select name="enquiry_category" class="form-control" required>
                                                    <option value="">-- Select --</option>
                                                    <option value="Project" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Project') ? 'selected' : '' ?>>Project</option>
                                                    <option value="Trading" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Trading') ? 'selected' : '' ?>>Trading</option>
                                                    <option value="Service" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Service') ? 'selected' : '' ?>>Service</option>
                                                    <option value="Others" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Others') ? 'selected' : '' ?>>Others</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label for="enquiry_code">Enquiry Code<span class="required">*</span></label>
                                                <input type="text"
                                                    name="enquiry_code"
                                                    value="<?= isset($enquiry_data) ? $enquiry_data['enquiry_code'] : $enquiry_code ?>"
                                                    class="form-control"
                                                    readonly>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label for="enquiry_date">Enquiry Date <span class="required">*</span></label>
                                                <input type="date"
                                                    name="enquiry_date"
                                                    id="enquiry_date"
                                                    value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d') ?>"
                                                    class="form-control"
                                                    required>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Enquiry Source <span class="required">*</span></label>
                                                <select name="enquiry_source" class="form-control" required>
                                                    <option value="">-- Select --</option>
                                                    <option value="Email" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source'] == 'Email') ? 'selected' : '' ?>>Email</option>
                                                    <option value="Phone" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source'] == 'Phone') ? 'selected' : '' ?>>Phone</option>
                                                    <option value="Meeting" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source'] == 'Meeting') ? 'selected' : '' ?>>Meeting</option>
                                                    <option value="Website" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source'] == 'Website') ? 'selected' : '' ?>>Website</option>
                                                    <option value="Referral" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source'] == 'Referral') ? 'selected' : '' ?>>Referral</option>
                                                </select>
                                            </div>
                                        </div> <!-- /.row -->

                                        <!-- Customer Section -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Customer Details</h4>
                                                <hr>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Select Customer <span class="required">*</span></label>
                                                <div id="customer_dropdown_wrapper">
                                                    <select name="customer_id" class="form-control select2" required>
                                                        <option value="">-- Select Customer --</option>
                                                        <?php foreach ($customer_list as $c): ?>
                                                            <option value="<?= $c->customer_id ?>" <?= (isset($enquiry_data['enquiry_customer']) && $enquiry_data['enquiry_customer'] == $c->customer_id) ? 'selected' : '' ?>>
                                                                <?= $c->customer_name ?> (<?= $c->customer_code ?>) => <?= $c->contact_number ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <small>
                                                    <a href="" target="_blank" class="view-employees" data-bs-toggle="modal" data-bs-target="#myModal" data-id="1">+ Add New Customer</a>
                                                </small>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Client Ref No</label>
                                                <input type="text"
                                                    name="client_ref_no"
                                                    value="<?= isset($enquiry_data['client_ref_no']) ? $enquiry_data['client_ref_no'] : "" ?>"
                                                    class="form-control">
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <label>Remarks / Comments</label>
                                                <textarea name="comments" class="form-control" rows="5"><?= isset($enquiry_data['comments']) ? $enquiry_data['comments'] : "" ?></textarea>
                                            </div>

                                            <div class="col-md-6 col-sm-6 form-group">
                                                <div class="site-survey-toggle">
                                                    <label for="siteSurveyToggle">Site Survey Required</label>
                                                    <label class="switch">
                                                        <input type="hidden" name="allow_site_survey" value="0">
                                                        <input type="checkbox"
                                                            id="siteSurveyToggle"
                                                            name="allow_site_survey"
                                                            value="1"
                                                            <?= !empty($enquiry_data['site_survey']) && $enquiry_data['site_survey'] == 1 ? 'checked' : '' ?>>
                                                        <span class="slider"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> <!-- /.row (Customer Section) -->

                                        <div class="ln_solid"></div>

                                    </form>
                                </div> <!-- /.tab-pane #home -->

                                <!-- PROFILE TAB (Site Survey) -->
                                <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 2 ? 'active show' : '' ?>"
                                    id="profile"
                                    role="tabpanel"
                                    aria-labelledby="profile-tab">
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
                                                <input type="date"
                                                    name="actual_date"
                                                    id="actual_date"
                                                    value="<?= !empty($survey_data['actual_date']) ? date('Y-m-d', strtotime($survey_data['actual_date'])) : '' ?>"
                                                    class="form-control">
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
                                </div> <!-- /.tab-pane #profile -->

                                <!------ ESTIMATION TAB ------>
                                <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 3 ? 'active show' : '' ?>"
                                    id="contact"
                                    role="tabpanel"
                                    aria-labelledby="contact-tab">

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

                                    <!-- Main Heading / Sub Heading / Product Section -->
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="addMainHeading">
                                            + Add Main Heading
                                        </button>
                                    </div>
                                <form action="<?= base_url()?>index.php/Sales/save_estimation" method="post">
                                     <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
                                    <div id="mainMenuContainer"></div>

                                    <div class="row mt-12">
                                        <div class="col-md-12">
                                            <div class="card p-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Sub Total</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="subtotal" name="sub_total" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Margin (%)</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" step="0.01" id="marginPercent" name="margin_percent" class="form-control">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="number" step="0.01" id="marginAmount" name="margin_amount" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Freight (%)</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" step="0.01" id="freightPercent" name="freight_percent" class="form-control">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="number" step="0.01" id="freightAmount" name="freight_amount" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Bank Charge</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="bankCharge" name="bank_charge" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Travel Expense</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="travelExpense" name="travel_expense" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Other Expense</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="otherExpense" name="other_expense" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Inspection Cost</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="inspectionCost" name="inspection_cost" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group row bg-light font-weight-bold p-2">
                                                    <label class="col-sm-4 col-form-label">Total Cost</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" step="0.01" id="totalCost" name="total_cost" class="form-control font-weight-bold" readonly>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save Survey</button>
                                        </form>

                                            </div> <!-- /.card -->
                                        </div> <!-- /.col-md-12 -->
                                    </div> <!-- /.row mt-12 -->

                                </div> <!-- /.tab-pane #contact -->

                            </div> <!-- /.tab-content -->
                                        
                      
                    </div>
                  </div>
                </div>
              </div>
    </div>
<div class="modal fade" id="surveyModal" tabindex="-1" role="dialog" aria-labelledby="surveyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="surveyModalLabel">Survey Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="surveyModalBody">
        <!-- Content will load here -->
        <div class="text-center p-3">
          <span class="spinner-border"></span> Loading...
        </div>
      </div>
    </div>
  </div>
</div> 
<!----------------------------Modal for New product-------------------->
<div class="modal fade" id="addProductModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Add New Product</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="newProductForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label>Product Name</label>
            <input type="text" class="form-control" name="item_name" required>
          </div>
          <div class="mb-3">
            <label >Item Code<span class="required">*</span></label>
                <input type="text" id="item_code" name="item_code" required class="form-control"  value="">
          </div>

          <div class="mb-3">
            <label>Unit</label>
            <select class="form-control select2" name="item_unit" id="item_unit" required>
              <option value="">-- Select Unit --</option>
              <!-- <option value="__new_unit__">+ Add New Unit</option> -->
            </select>
          </div>
          <div  id="newUnitDiv" style="display:none;">
             <div class="mb-3">
                <label>New Unit Name</label>
                <input type="text" class="form-control" name="new_unit_name" id="new_unit_name" placeholder="Enter new unit">
            <!-- <button type="button" class="btn btn-sm btn-success mt-2" onclick="saveNewUnit()">Save Unit</button> -->
            </div>
            <div class="mb-3">
                <label>New Unit Code</label>
                <input type="text" class="form-control" name="new_unit_code" id="new_unit_code" placeholder="Enter new unit">
            </div>
        </div>

          <div class="mb-3">
            <label>Unit Price</label>
            <input type="number" step="0.01" class="form-control" name="unit_price" required>
          </div>
          <div class="mb-3">
                <label >Item Description</label>
                <textarea id="item_description" name="item_description" class="form-control"></textarea>
			</div>

          <div class="mb-3">
            <label>Product Image</label>
            <input type="file" class="form-control" name="item_image" accept="image/*">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveNewProduct()">Save</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ------------------ 1. Survey Total Hours Calculation ------------------
$(document).ready(function() {
    const startField = document.getElementById("survey_start_datetime");
    const endField = document.getElementById("survey_end_datetime");
    const totalHoursField = document.getElementById("total_hours");

    function calculateTotalHours() {
        const startValue = startField.value;
        const endValue = endField.value;

        if (startValue && endValue) {
            const startDate = new Date(startValue);
            const endDate = new Date(endValue);

            if (endDate > startDate) {
                const diffMs = endDate - startDate; // ms difference
                const diffHrs = diffMs / (1000 * 60 * 60); // hours
                totalHoursField.value = diffHrs.toFixed(2);
            } else {
                totalHoursField.value = "";
            }
        }
    }

    startField.addEventListener("change", calculateTotalHours);
    endField.addEventListener("change", calculateTotalHours);
});


// ------------------ 2. Dynamic Main Headings & Subheadings ------------------
let mainIndex = 0;

// Add first main heading on page load
$(document).ready(function () {
    $("#mainMenuContainer").append(generateMainHeadingHTML(mainIndex));
    mainIndex++;
});

// Button click → Add main heading
$("#addMainHeading").on("click", function () {
    $("#mainMenuContainer").append(generateMainHeadingHTML(mainIndex));
    mainIndex++;
});

// Generate HTML for main heading block
function generateMainHeadingHTML(i) {
    return `
    <div id="main_heading_block_${i}" class="border p-2 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div style="width: 40%;">
                <label>Main Heading</label>
                <input type="text" name="main_heading[${i}]" class="form-control form-control-sm" placeholder="Enter Main Heading" required>
            </div>
            <div style="width: 45%;">
                <label>Details</label>
                <textarea name="main_details[${i}]" class="form-control form-control-sm" placeholder="Enter Details"></textarea>
            </div>
            <div class="mt-4">
                <button type="button" class="btn btn-success btn-sm addSubHeading" data-main="${i}">+ Sub Heading</button>
                <button type="button" class="btn btn-danger btn-sm removeMainHeading" data-main="${i}">🗑</button>
            </div>
        </div>
        <div class="subHeadingContainer" id="subHeadingContainer_${i}">
            ${generateSubHeadingHTML(i)}
        </div>
    </div>`;
}

// Remove a main heading
$(document).on("click", ".removeMainHeading", function () {
    let i = $(this).data("main");
    $("#main_heading_block_" + i).remove();
});


// ------------------ 3. Subheadings & Product Table ------------------

// Add subheading inside a main heading
$(document).on("click", ".addSubHeading", function () {
    let mainId = $(this).data("main");
    $("#subHeadingContainer_" + mainId).append(generateSubHeadingHTML(mainId));
});

// Generate HTML for subheading block
function generateSubHeadingHTML(mainId) {
    return `
    <div class="border p-2 mb-2">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sub_heading[${mainId}][]" class="form-control form-control-sm w-75" placeholder="Enter Sub Heading">
            <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
        </div>
        <table class="table table-bordered productTable mb-0">
            <thead>
                <tr style="background-color:#f8f9fa;">
                    <th>Product</th>
                    <th style="width:100px;">Unit</th>
                    <th style="width:100px;">Qty</th>
                    <th style="width:120px;">Unit Price</th>
                    <th style="width:120px;">Amount</th>
                    <th style="width:60px;">Action</th>
                </tr>
            </thead>
            <tbody>
                ${generateProductRow(mainId)}
            </tbody>
        </table>
    </div>`;
}

// Remove subheading
$(document).on("click", ".removeSubHeading", function () {
    $(this).closest(".border").remove();
});


// ------------------ 4. Product Row & Dropdown Data ------------------
let productsList = <?php echo json_encode($all_products); ?>; 
let unitsList = <?php echo json_encode($active_units); ?>; 

// Generate product row HTML
function generateProductRow(mainId, subId) {
    let productOptions = '<option value="">-- Select Product --</option>';
    productOptions += '<option value="__new__">+ Add New Product</option>';
    productsList.forEach(p => {
        productOptions += `<option value="${p.item_id}">${p.item_name}</option>`;
    });

    let unitOptions = '<option value="">-- Select Unit --</option>';
    unitsList.forEach(u => {
        unitOptions += `<option value="${u.unit_id}">${u.unit_name}</option>`;
    });

    return `
    <tr>
        <td>
            <select name="products[${mainId}][${subId}][][product_id]" class="form-control form-control-sm product-select" onchange="onProductChange(this)">
                ${productOptions}
            </select>
        </td>
        <td>
            <select name="products[${mainId}][${subId}][][unit_id]" class="form-control form-control-sm">
                ${unitOptions}
            </select>
        </td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][][quantity]" class="form-control form-control-sm qty"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][][unit_price]" class="form-control form-control-sm unitPrice"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][][amount]" class="form-control form-control-sm amount" readonly></td>
        <td><button type="button" class="btn btn-success btn-sm addProductRow">+</button></td>
    </tr>`;
}



// ------------------ 5. Add / Remove Product Rows ------------------

// Add product row
$(document).on("click", ".addProductRow", function () {
    let mainId = $(this).closest("table").closest(".border").find(".addSubHeading").data("main");
    $(this).closest("tbody").append(generateProductRow(mainId));
    // Change old "+" to delete button
    $(this).removeClass("btn-success addProductRow").addClass("btn-danger removeProductRow").text("🗑");
     $(".product-select").select2({
        placeholder: "Select Product",
        allowClear: true
    });
    
});

// Remove product row
$(document).on("click", ".removeProductRow", function () {
    $(this).closest("tr").remove();
    calculateSubtotal()
});




// ------------------ 7. Auto-calculate Amount ------------------
// Qty or Unit Price change → Update Amount
$(document).on("input", ".qty, .unitPrice", function () {
    let row = $(this).closest("tr");
    let qty = parseFloat(row.find(".qty").val()) || 0;
    let price = parseFloat(row.find(".unitPrice").val()) || 0;
    row.find(".amount").val((qty * price).toFixed(2));
});
$(document).ready(function() {
    $(".product-select").select2({
        placeholder: "Select Product",
        allowClear: true
    });
});
// ------------------ 6. Auto-fill Unit & Price on Product Change ------------------
function onProductChange(selectElement) {
    let $select = $(selectElement);
    let productId = $select.val();
    let row = $select.closest("tr");

    // ✅ If "Add New Product" is selected
    if (productId === "__new__") {
        $select.val(""); // reset dropdown so it doesn’t stay on "__new__"
        $("#productMessage").hide().text("");
        $("#addProductModal").modal("show"); // show modal
        $("#addProductModal").data("targetSelect", selectElement);
         return; // stop here
    }

    // ✅ If normal product selected
    if (productId) {
        let product = productsList.find(function (prod) {
            return prod.item_id == productId;
        });

        if (product) {
            row.find("select[name^='unit']").val(product.item_unit);
            row.find("input[name^='unit_price']").val(product.unit_price);
        } else {
            row.find("select[name^='unit']").val("");
            row.find("input[name^='unit_price']").val("");
        }
    } else {
        row.find("select[name^='unit']").val("");
        row.find("input[name^='unit_price']").val("");
    }
}

$(document).on("input", "input[name^='qty']", function () {
    let row = $(this).closest("tr");
    let qty = parseFloat($(this).val()) || 0;
    let unitPrice = parseFloat(row.find("input[name^='unit_price']").val()) || 0;

     row.find("input[name^='amount']").val((qty * unitPrice).toFixed(2));
   //calculateSubtotal();
});

$(document).on("input", ".qty, .unitPrice", function () {
    let row = $(this).closest("tr");
    let qty = parseFloat(row.find(".qty").val()) || 0;
    let price = parseFloat(row.find(".unitPrice").val()) || 0;
    row.find(".amount").val((qty * price).toFixed(2));

    calculateSubtotal();
});
function calculateSubtotal() {
    let subtotal = 0;
    $(".amount").each(function() {
        subtotal += parseFloat($(this).val()) || 0;
    });
      
    console.log("Subtotal:", subtotal); // debug
    $("#subtotal").val(subtotal.toFixed(2));
    calculateTotalCost();
}
const subtotalField = document.getElementById('subtotal');
    const marginPercentField = document.getElementById('marginPercent');
    const marginAmountField = document.getElementById('marginAmount');
    const freightPercentField = document.getElementById('freightPercent');
    const freightAmountField = document.getElementById('freightAmount');
    const bankChargeField = document.getElementById('bankCharge');
    const travelExpenseField = document.getElementById('travelExpense');
    const otherExpenseField = document.getElementById('otherExpense');
    const inspectionCostField = document.getElementById('inspectionCost');
    const totalCostField = document.getElementById('totalCost');

    function calculateTotalCost() {
        const subtotal = parseFloat(subtotalField.value) || 0;

        // Margin
        const marginPercent = parseFloat(marginPercentField.value) || 0;
        const marginAmount = subtotal * marginPercent / 100;
        marginAmountField.value = marginAmount.toFixed(2);

        // Freight
        const freightPercent = parseFloat(freightPercentField.value) || 0;
        const freightAmount = subtotal * freightPercent / 100;
        freightAmountField.value = freightAmount.toFixed(2);

        // Other Expenses
        const bankCharge = parseFloat(bankChargeField.value) || 0;
        const travelExpense = parseFloat(travelExpenseField.value) || 0;
        const otherExpense = parseFloat(otherExpenseField.value) || 0;
        const inspectionCost = parseFloat(inspectionCostField.value) || 0;

        // Total
        const total = subtotal + marginAmount + freightAmount + bankCharge + travelExpense + otherExpense + inspectionCost;
        totalCostField.value = total.toFixed(2);
    }

    // Add event listeners to recalculate whenever any field changes
    [
        subtotalField,
        marginPercentField,
        freightPercentField,
        bankChargeField,
        travelExpenseField,
        otherExpenseField,
        inspectionCostField
    ].forEach(field => field.addEventListener('input', calculateTotalCost));

    $(document).ready(function() {
    let attachmentCount = 1; // tracks number of attachments

    $('#add-attachment').click(function() {
        attachmentCount++;

        let attachmentHtml = `
            <div class="col-md-4 mb-2">
                <label>Attachment ${attachmentCount}</label>
                <input type="file" name="attachment[]" class="form-control">
            </div>
        `;

        $('#attachments-wrapper').append(attachmentHtml);
    });
});
$(document).on("click", ".viewSurveyBtn", function() {
    let surveyId = $(this).data("id");
    alert(surveyId);
    $("#surveyModalBody").html('<div class="text-center p-3"><span class="spinner-border"></span> Loading...</div>');
    $("#surveyModal").modal("show");
    $.ajax({
        url: "<?= base_url('index.php/Ajax/get_survey_details/') ?>" + surveyId,
        type: "GET",
        success: function(response) {
            $("#surveyModalBody").html(response);
        },
        error: function() {
            $("#surveyModalBody").html("<p class='text-danger'>Failed to load survey details.</p>");
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    var addProductModal = document.getElementById('addProductModal');
     $('#item_unit').select2({
    placeholder: "-- Select Unit --",
    allowClear: true,
    width: '100%',
    dropdownParent: $('#addProductModal') // 👈 important for modals
});

    if (addProductModal) {
        addProductModal.addEventListener('show.bs.modal', function () {
             $.ajax({
        url: '<?= base_url("index.php/Item/list_unit_ajax") ?>', // controller endpoint
        type: 'GET',
        success: function(data) {
            let units = JSON.parse(data);
            let unitSelect = $("#item_unit");
            unitSelect.empty().append('<option value="">-- Select Unit --</option>');
            
            units.forEach(function(u) {
                unitSelect.append('<option value="'+u.unit_id+'">'+u.unit_name+'</option>');
            });

            unitSelect.append('<option value="__new_unit__">+ Add New Unit</option>');
        }
    });
        });
    }
});
$(document).ready(function () {
$('#item_unit').on('change', function () {
        if ($(this).val() === "__new_unit__") {
            // Show the div
            $('#newUnitDiv').slideDown();
            // reset selection so dropdown doesn’t stay on "__new_unit__"
           // $(this).val('').trigger('change');
        } else {
            // Hide if user selects a normal unit
            $('#newUnitDiv').slideUp();
        }
    });
        });
function saveNewProduct() {
    let form = document.getElementById("newProductForm");
    let formData = new FormData(form);

    $.ajax({
        url: "<?= base_url('index.php/Ajax/save_product_ajax') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            let response = JSON.parse(res);

            if (response.status === "success") {
                // 1. Show message in modal
                if (!$('#productMessage').length) {
                    $('#newProductForm').prepend(
                        '<div id="productMessage" class="alert alert-success py-2"></div>'
                    );
                }
                $('#productMessage').text("✅ Product saved successfully!");

                // 2. Add new product to productsList
                if (typeof productsList !== "undefined") {
                    productsList.push({
                        item_id: response.item_id,
                        item_name: response.item_name
                    });
                }

                // 3. Wait 1.5s then close modal
               setTimeout(function () {
                    // 1. Hide modal
                    $("#addProductModal").modal("hide");

                    // 2. Get the select that triggered "Add New Product"
                    let targetSelect = $("#addProductModal").data("targetSelect");

                    if (targetSelect) {
                        let $targetSelect = $(targetSelect);

                        // 3. Add new product option if not already in list
                        if (!$targetSelect.find("option[value='" + response.item_id + "']").length) {
                            let newOption = new Option(response.item_name, response.item_id, true, true);
                            $targetSelect.append(newOption);
                        }

                        // 4. Select the new product
                        $targetSelect.val(response.item_id).trigger("change");

                        // 5. Get the row containing this select
                        let row = $targetSelect.closest("tr");

                        // 6. Update unit dropdown
                        let unitSelect = row.find("select[name^='unit']");
                        if (unitSelect.length && response.unit_id) {
                            if (!unitSelect.find("option[value='" + response.unit_id + "']").length) {
                                let newUnitOption = new Option(response.unit_name, response.unit_id, true, true);
                                unitSelect.append(newUnitOption);
                            }
                            unitSelect.val(response.unit_id).trigger("change");
                        }

                        // 7. Update unit price
                        let priceInput = row.find("input[name^='unit_price']");
                        if (priceInput.length && response.unit_price) {
                            priceInput.val(response.unit_price);
                        }
                    }

                    // 8. Show success message
                    alert("Product added successfully!");
                }, 500); // <-- delay allows modal to close smoothly

            } else {
                alert("❌ " + response.message);
            }
        },
        error: function () {
            alert("Something went wrong while saving product.");
        }
    });
}

</script>
