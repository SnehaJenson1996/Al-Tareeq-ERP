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
                            <a class="dropdown-item" href="#">Under Approval</a>
                            <a class="dropdown-item" href="#">Quotation</a>
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
                        <a class="nav-link <?= isset($menu_status)&&$menu_status==1?"active":""?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" <?= isset($menu_status)&&$menu_status==1?"aria-selected='true'":"aria-selected='false'"?>>New</a>
                      </li>
                     <?php if (!isset($enquiry_data) || !empty( $enquiry_data['site_survey'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($menu_status) && $menu_status == 2 ? "active" : "" ?>" 
                              id="profile-tab" 
                              data-toggle="tab" 
                              href="#profile" 
                              role="tab" 
                              aria-controls="profile" 
                              <?= isset($menu_status) && $menu_status == 2 ? "aria-selected='true'" : "aria-selected='false'" ?>>
                              Site Survey
                            </a>
                        </li>
                    <?php endif; ?>
                      <li class="nav-item">
                        <a class="nav-link <?= isset($menu_status)&&$menu_status==3?"active":""?>" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" <?= isset($menu_status)&&$menu_status==3?"aria-selected='true'":"aria-selected='false'"?>>Estimation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" <?= isset($menu_status)&&$menu_status==4?"aria-selected='true'":"aria-selected='false'"?>>Under Approval</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" <?= isset($menu_status)&&$menu_status==5?"aria-selected='true'":"aria-selected='false'"?>>Quotation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" <?= isset($menu_status)&&$menu_status==6?"aria-selected='true'":"aria-selected='false'"?>>Send To Client</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                <!-- Enquiry Tab -->
                <div class="tab-pane fade <?= isset($menu_status) && $menu_status == 1 ? "show active" : "" ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="<?= base_url('index.php/Sales/save_enquiry') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Branch <span class="text-danger">*</span></label>
                                        <?= form_error('branch', '<small class="text-danger">', '</small>') ?>
                                        <select name="branch" id="branch" class="form-control">
                                            <option value=''>Select</option>
                                            <?php foreach($branch_list as $branch): ?>
                                                <option value="<?= $branch->branch_id ?>" <?= isset($enquiry_data) && $enquiry_data['branch_id'] == $branch->branch_id ? "selected" : "" ?>>
                                                    <?= $branch->branch_name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Project Name <span class="required">*</span></label>
                                        <input type="text" name="project_name" class="form-control" value="<?= isset($enquiry_data) ? $enquiry_data['project_name'] : "" ?>" required>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Project Subject <span class="required">*</span></label>
                                        <input type="text" name="project_subject" class="form-control" value="<?= isset($enquiry_data) ? $enquiry_data['project_subject'] : "" ?>" required>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Project Location</label>
                                        <input type="text" name="project_location" class="form-control" value="<?= isset($enquiry_data) ? $enquiry_data['project_location'] : "" ?>">
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
                                        <label>Enquiry Code <span class="required">*</span></label>
                                        <input type="text" name="enquiry_code" class="form-control" value="<?= isset($enquiry_data) ? $enquiry_data['enquiry_code'] : $enquiry_code ?>" readonly>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Enquiry Date <span class="required">*</span></label>
                                        <input type="date" name="enquiry_date" id="enquiry_date" class="form-control" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d') ?>" required>
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
                                            <select name="customer_id" id="customer_id" class="form-control select2" required>
                                            </select>
                                        </div>
                                        <small><a href="" target="_blank" class='view-employees' data-bs-toggle='modal' data-bs-target='#myModal' data-id="1">+ Add New Customer</a></small>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Client Ref No</label>
                                        <input type="text" name="client_ref_no" class="form-control" value="<?= isset($enquiry_data['client_ref_no']) ? $enquiry_data['client_ref_no'] : "" ?>">
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Remarks / Comments</label>
                                        <textarea name="comments" class="form-control" rows="5"><?= isset($enquiry_data['comments']) ? $enquiry_data['comments'] : "" ?></textarea>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <div class="site-survey-toggle">
                                            <label for="siteSurveyToggle">Site Survey Required</label>
                                            <label class="switch">
                                                <input type="checkbox" id="siteSurveyToggle" name="allow_site_survey">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>

                                <?php if(!isset($enquiry_data)){ ?>
                                    <div class="form-group">
                                        <div class="col-md-6 offset-md-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                <?php } ?>

                            </form>
                        </div>

                        <!-- Survey Tab -->
                        <div class="tab-pane fade <?= isset($menu_status) && $menu_status == 2 ? 'active show' : "" ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="post" action="<?= base_url()?>index.php/Sales/save_survey">
                                <input type="hidden" name="enquiry_id" value="<?= isset($enquiryid) ? $enquiryid : "" ?>">

                                <div class="form-group">
                                    <label for="employee_survey">Surveyor</label>
                                    <select name="employee_survey" id="employee_survey" class="form-control">
                                        <?php foreach($employee_list as $employee): ?>
                                            <option value="<?= $employee->employee_id ?>"><?= $employee->employee_name ?> (<?= $employee->uid_number ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="survey_date">Schedule Date</label>
                                        <input type="date" name="survey_date" id="survey_date" class="form-control" value="<?= isset($survey_data['scheduled_date']) ? date('Y-m-d', strtotime($survey_data['scheduled_date'])) : date('Y-m-d') ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="survey_start_datetime">Start Date & Time <span class="required">*</span></label>
                                        <input type="datetime-local" name="survey_start_datetime" id="survey_start_datetime" class="form-control" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d\TH:i', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d\TH:i') ?>" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="survey_end_datetime">End Date & Time <span class="required">*</span></label>
                                        <input type="datetime-local" name="survey_end_datetime" id="survey_end_datetime" class="form-control" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d\TH:i', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d\TH:i') ?>" required>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="total_hours">Total Hours</label>
                                        <input type="text" name="total_hours" id="total_hours" class="form-control" value="<?= isset($survey_data['scheduled_hours']) ? $survey_data['scheduled_hours'] : '' ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="3"><?= isset($survey_data['remarks']) ? $survey_data['remarks'] : '' ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Schedule Survey</button>
                            </form>
                        </div>

                        <!-- Contact Tab -->
                        <div class="tab-pane fade <?= isset($menu_status) && $menu_status == 3 ? 'active show' : "" ?>" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                           <div class="mt-2">
                                  <button type="button" class="btn btn-primary btn-sm" id="addMainHeading">
                                      + Add Main Heading
                                  </button>
                              </div>
                              <div id="mainMenuContainer"></div>
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
                        
                                    </div>
                            </div>
                    </div> <!-- End tab-content -->
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
                const diffMs = endDate - startDate; // difference in milliseconds
                const diffHrs = diffMs / (1000 * 60 * 60); // convert to hours
                totalHoursField.value = diffHrs.toFixed(2); // 2 decimal places
            } else {
                totalHoursField.value = "";
            }
        }
    }

    startField.addEventListener("change", calculateTotalHours);
    endField.addEventListener("change", calculateTotalHours);
});
$(document).ready(function() {
  $('#customer_id').select2({
        placeholder: "-- Select Customer --",
        allowClear: true,
        width: '100%' // ensures it fits the container
    });
    $('#branch').change(function() {
        var branch_id = $(this).val();

        if(branch_id) {
            $.ajax({
                url: '<?= base_url("index.php/Company/get_customers_by_branch") ?>',
                type: 'POST',
                data: {branch_id: branch_id},
                dataType: 'json',
                success: function(data) {
                    $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
                    $.each(data, function(index, customer) {
                        $('#customer_id').append(
                            '<option value="' + customer.customer_id + '">' + 
                            customer.customer_name + ' (' + customer.customer_code + ') => ' + customer.contact_number +
                            '</option>'
                        );
                    });
                    $('#customer_id').trigger('change'); // Refresh select2
                }
            });
        } else {
            $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
        }
    });
});

///Estimation
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
function generateProductRow(mainId) {
    let productOptions = '<option value="">-- Select Product --</option>';
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
            <select name="product[${mainId}][]" class="form-control form-control-sm product-select"  onchange="onProductChange(this)">
                ${productOptions}
            </select>
        </td>
        <td>
            <select name="unit[${mainId}][]" class="form-control form-control-sm">
                ${unitOptions}
            </select>
        </td>
        <td><input type="number" step="0.01" name="quantity[${mainId}][]" class="form-control form-control-sm qty"></td>
        <td><input type="number" step="0.01" name="unit_price[${mainId}][]" class="form-control form-control-sm unitPrice"></td>
        <td><input type="number" step="0.01" name="amount[${mainId}][]" class="form-control form-control-sm amount"  readonly></td>
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
    let productId = $(selectElement).val();
    let row = $(selectElement).closest("tr");

    if (productId) {
        // Find the product in productsList
        let product = productsList.find(function (prod) {
            return prod.item_id == productId;
        });

        if (product) {
            // Set the unit dropdown and unit price
            row.find("select[name^='unit']").val(product.item_unit);
            row.find("input[name^='unit_price']").val(product.unit_price);
        } else {
            // Reset fields if no match found
            row.find("select[name^='unit']").val("");
            row.find("input[name^='unit_price']").val("");
        }
    } else {
        // Reset unit if no product selected
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
</script>

