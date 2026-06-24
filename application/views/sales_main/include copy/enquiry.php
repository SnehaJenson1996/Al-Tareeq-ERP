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