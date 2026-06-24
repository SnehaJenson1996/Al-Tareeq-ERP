<style>
label, h4 {
  color: black;
  font-weight: bold;
}

.arrow-container {
  display: flex;
  justify-content: flex-end; /* Aligns buttons to right */
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.arrow-button {
  position: relative;
  display: inline-block;
  background-color: #3498db;
  color: white;
  padding: 12px 30px 12px 20px;
  font-size: 16px;
  border: none;
  cursor: pointer;
  margin-right: 0;
  outline: none;
  transition: background-color 0.3s ease;
  border-radius: 0;
}

.arrow-button:not(:last-child) {
  margin-right: 1px;
}

.arrow-button::after {
  content: "";
  position: absolute;
  top: 0;
  right: -20px;
  width: 0;
  height: 0;
  border-top: 25px solid transparent;
  border-bottom: 25px solid transparent;
  border-left: 20px solid #3498db;
  z-index: 1;
}

.arrow-button:hover {
  background-color: #2980b9;
}

.arrow-button:hover::after {
  border-left-color: #2980b9;
}

.arrow-button:last-child::after {
  content: none;
}

.arrow-button.active {
  background-color: #2c3e50;
}
.arrow-button.active::after {
  border-left-color: #2c3e50;
}

.bottom-right {
  text-align: right;
  margin-top: 20px;
}
</style>
<style>
.site-survey-toggle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 9999;
}

.site-survey-toggle label {
  font-weight: bold;
  font-size: 14px;
}

/* Switch Styling */
.switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  background-color: #ccc;
  border-radius: 24px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  transition: 0.4s;
  font-size: 16px;
  text-align: center;
  line-height: 24px;
  color: white;
}

.switch input:checked + .slider {
  background-color: #4caf50;
}

.switch input:checked + .slider::before {
  content: "✓";
}

.slider::before {
  content: "×";
  position: absolute;
  left: 0;
  right: 0;
  text-align: center;
}
</style>


<!-- Arrow Buttons -->
<div class="row">
  <div class="col-md-12">
    <div class="arrow-container">
      <button type="button" class="arrow-button <?=$status==1?"active":""?>">New</button>
      <button type="button" class="arrow-button <?=$status==2?"active":""?>">Site Survey</button>
      <button type="button" class="arrow-button">Estimation</button>
      <button type="button" class="arrow-button">Under Approval</button>
      <button type="button" class="arrow-button">Quotation</button>
      <button type="button" class="arrow-button">Send To Client</button>
    </div>
  </div>
</div>

<!-- Enquiry Form -->
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_content">
        <form action="<?= base_url('index.php/Sales/save_enquiry') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="row">
			<div class="col-md-6 col-sm-6 form-group">
              <label>Project Name <span class="required">*</span></label>
              <input type="text" name="project_name" class="form-control" value="<?= isset($enquiry_data)?$enquiry_data['project_name']:""?>" required>
            </div>
			<div class="col-md-6 col-sm-6 form-group">
              <label>Project Subject <span class="required">*</span></label>
              <input type="text" name="project_subject" value="<?= isset($enquiry_data)?$enquiry_data['project_subject']:""?>" class="form-control" required>
            </div>
            <div class="col-md-6 col-sm-6 form-group">
              <label>Project Location</label>
              <input type="text" name="project_location" value="<?= isset($enquiry_data)?$enquiry_data['project_location']:""?>" class="form-control">
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
               <input type="text" name="enquiry_code" value="<?= isset($enquiry_data)?$enquiry_data['enquiry_code']:$enquiry_code?>" class="form-control" readonly>
            </div>
            <div class="col-md-6 col-sm-6 form-group">
              <label for="enquiry_date">Enquiry Date <span class="required">*</span></label>
              <input type="date" name="enquiry_date" id="enquiry_date" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d', strtotime($enquiry_data['enquiry_date']))  : date('Y-m-d') ?>"class="form-control" required>
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
           
            
          </div>

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
                <?php foreach($customer_list as $c): ?>
                  <option value="<?= $c->customer_id  ?>"  <?= (isset($enquiry_data['enquiry_customer']) && $enquiry_data['enquiry_customer'] == $c->customer_id ) ? 'selected' : '' ?> ><?= $c->customer_name ?> (<?= $c->customer_code ?>)=><?= $c->contact_number ?></option>
                <?php endforeach; ?>
              </select>
                </div>
              <small><a href="" target="_blank"  class='view-employees' data-bs-toggle='modal' data-bs-target='#myModal' data-id="1">+ Add New Customer</a></small>
            </div>
            <div class="col-md-6 col-sm-6 form-group">
                    <label>Client Ref No</label>
                    <input type="text" name="client_ref_no" value="<?= isset($enquiry_data['client_ref_no']) ?$enquiry_data['client_ref_no']: ""?>" class="form-control">
                  </div>
                  <div class="col-md-6 col-sm-6 form-group">
            <label>Remarks / Comments</label>
            <textarea name="comments" class="form-control" rows="5"><?= isset($enquiry_data['comments']) ?$enquiry_data['comments']: ""?></textarea>
            </div>
            
            
          </div>

          <div class="ln_solid"></div>
          <?php if(!isset($enquiry_data)){?>
            <!-- Bottom Buttons -->
                    <div class="form-group">
                      <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>

                    <!-- Bottom Right Button -->
          <?php }?>      
        </form>
		<!-- Site Survey as radio -->
     
    		<!-- Toggle Button -->
	<form method="post" action="<?= base_url()?>index.php/Sales/save_survey">
    <!-- Toggle -->
    <div class="site-survey-toggle">
        <label for="siteSurveyToggle">Site Survey Required</label>
        <label class="switch">
            <input type="checkbox" id="siteSurveyToggle" name="allow_site_survey"
    <?= isset($survey_data) ? 'checked' : ''; ?>>
            <span class="slider"></span>
        </label>
    </div>

    <!-- Hidden Employee Survey Div -->
    <div id="employeeSurveyDiv" style="<?= isset($survey_data)?"":"display: none;"?> margin-top: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <input type="hidden" name="enquiry_id" value="<?= isset($enquiryid)?$enquiryid:""?>">
    <div class="form-group">
            <label for="employee_survey">Surveyor</label>
            <select name="employee_survey" id="employee_survey" class="form-control">
                <?php foreach($employee_list as $employee): ?>
                  <option value="<?= $employee->employee_id   ?>" ><?= $employee->employee_name ?> (<?= $employee->uid_number ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <!-- Survey Date -->
            <div class="col-md-4 form-group">
                <label for="survey_date">Schedule Date</label>
                <input type="date" name="survey_date" id="survey_date" value="<?= isset($survey_data['scheduled_date']) ? date('Y-m-d', strtotime($survey_data['scheduled_date']))  : date('Y-m-d') ?>"  class="form-control">
            </div>

            <!-- Start Time -->
            <div class="col-md-3 form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time"value="<?= isset($survey_data['start_time']) ? $survey_data['start_time'] : ''; ?>" class="form-control">
            </div>

            <!-- End Time -->
            <div class="col-md-3 form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time" value="<?php echo isset($survey_data['end_time']) ? $survey_data['end_time'] : ''; ?>" class="form-control">
            </div>
             <!-- End Time -->
            <div class="col-md-2 form-group">
                <label for="end_time">Total Hours</label>
                <input type="text" name="total_hours" id="total_hours"  value="<?php echo isset($survey_data['scheduled_hours']	) ? $survey_data['scheduled_hours']	 : ''; ?>" class="form-control" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3"><?php echo isset($survey_data['remarks']) ? $survey_data['remarks'] : ''; ?></textarea>
        </div>
                  <?php if(!isset($survey_data)){?>
                     <button type="submit" class="btn btn-primary">Schedule Survey</button>
                  <?php } ?>
       
    </div>
</form>

      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal"  class="modal fade mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- modal-xl for extra width -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
	  <!-- Success message placeholder -->
  <div id="customer-success-alert" class="alert alert-success m-3" style="display: none;">
    Customer saved successfully!
  </div>

      <div class="modal-body" id="modal-body-content">
        <!-- Dynamic content will be injected here -->
        Loading...
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
  $('.view-employees').on('click', function (e) {
    e.preventDefault();

    $('#modal-body-content').html("Loading...");
    $('#myModal').modal('show');

    $.ajax({
        url: "<?= base_url('index.php/Ajax/add_new_customer') ?>",
        type: "POST",
        success: function(response) {
            console.log("AJAX Success");
            $('#modal-body-content').html(response);
        },
        error: function(xhr, status, error) {
            console.log("AJAX Error:");
            console.log(xhr.responseText);
            alert("AJAX error: " + error);
        }
    });
});
     
});
 

function openModal() {
  document.getElementById('myModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('myModal').style.display = 'none';
}
document.getElementById('siteSurveyToggle').addEventListener('change', function () {
    // Show or hide the employee survey div
    document.getElementById('employeeSurveyDiv').style.display = this.checked ? 'block' : 'none';

    // Get all arrow buttons
    const arrowButtons = document.querySelectorAll('.arrow-container .arrow-button');

    // Remove active from all
    arrowButtons.forEach(btn => btn.classList.remove('active'));

    // If checked, set Site Survey button active
    if (this.checked) {
        arrowButtons[1].classList.add('active'); // Index 1 = "Site Survey"
    } else {
        arrowButtons[0].classList.add('active'); // Back to "New"
    }
});

function calculateHours() {
        let start = document.getElementById("start_time").value;
        let end = document.getElementById("end_time").value;

        if (start && end) {
            let startTime = new Date(`1970-01-01T${start}:00`);
            let endTime = new Date(`1970-01-01T${end}:00`);

            // If end time is past midnight (next day)
            if (endTime < startTime) {
                endTime.setDate(endTime.getDate() + 1);
            }

            let diffMs = endTime - startTime;
            let diffHrs = diffMs / (1000 * 60 * 60);

            document.getElementById("total_hours").value = diffHrs.toFixed(2);
        }
    }

    document.getElementById("start_time").addEventListener("change", calculateHours);
    document.getElementById("end_time").addEventListener("change", calculateHours);
</script>
