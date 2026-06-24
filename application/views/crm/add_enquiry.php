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
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                     <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?= isset($menu_status)&&$menu_status==1?"active":""?>" id="home-tab" data-toggle="tab" href="#home" role="tab">New</a>
    </li>

    <?php if(isset($enquiry_data['site_survey_required']) && $enquiry_data['site_survey_required']==1): ?>
    <li class="nav-item">
        <a class="nav-link <?= isset($menu_status) && $menu_status == 2 ? "active" : "" ?>" 
           id="profile-tab" data-toggle="tab" href="#profile" role="tab">Site Survey</a>
    </li>
    <?php endif; ?>
</ul>
                    <div class="tab-content" id="myTabContent">

                <!-- Enquiry Tab -->
                <div class="tab-pane fade <?= isset($menu_status) && $menu_status == 1 ? "show active" : "" ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="<?= base_url('index.php/CRM/save_enquiry') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                
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
                                        <label>Project Name <span class="text-danger">*</span></label>
                                        <?= form_error('project_name', '<small class="text-danger">', '</small>') ?>
                                        <input type="text" name="project_name" class="form-control" 
       value="<?= set_value('project_name', $enquiry_data['project_name'] ?? '') ?>" required>

                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Project Subject <span class="text-danger">*</span></label>
                                        <?= form_error('project_location', '<small class="text-danger">', '</small>') ?>
                                        <input type="text" name="project_subject" class="form-control" 
       value="<?= set_value('project_subject', $enquiry_data['project_subject'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Project Location<span class="text-danger">*</span></label>
                                        <?= form_error('project_location', '<small class="text-danger">', '</small>') ?>
                                        <input type="text" name="project_location" class="form-control" 
       value="<?= set_value('project_location', $enquiry_data['project_location'] ?? '') ?>" required>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Enquiry Category <span class="text-danger">*</span></label>
                                        <?= form_error('enquiry_category', '<small class="text-danger">', '</small>') ?>
                                        <select name="enquiry_category" class="form-control" >
                                            <option value="">-- Select --</option>
                                            <option value="Project" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Project') ? 'selected' : '' ?>>Project</option>
                                            <option value="Trading" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Trading') ? 'selected' : '' ?>>Trading</option>
                                            <option value="Service" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Service') ? 'selected' : '' ?>>Service</option>
                                            <option value="Others" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category'] == 'Others') ? 'selected' : '' ?>>Others</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Enquiry Code <span class="text-danger">*</span></label>
                                        <?= form_error('enquiry_code', '<small class="text-danger">', '</small>') ?>
                                        <input type="text" name="enquiry_code" class="form-control" value="<?= isset($enquiry_data) ? $enquiry_data['enquiry_code'] : $enquiry_code ?>" readonly>
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Enquiry Date <span class="text-danger">*</span></label>
                                        <?= form_error('enquiry_date', '<small class="text-danger">', '</small>') ?>
                                        <input type="date" name="enquiry_date" id="enquiry_date" class="form-control" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d') ?>" required >
                                    </div>

                                    <div class="col-md-6 col-sm-6 form-group">
                                        <label>Enquiry Source <span class="text-danger">*</span></label>
                                        <?= form_error('enquiry_source', '<small class="text-danger">', '</small>') ?>
                                        <select name="enquiry_source" class="form-control" >
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
                                        <label>Select Customer <span class="text-danger">*</span></label>
                                        <div id="customer_dropdown_wrapper">
                                            <?= form_error('customer_id', '<small class="text-danger">', '</small>') ?>
                                            <select name="customer_id" id="customer_id" class="form-control select2" >
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
                                    
                                </div>
<div class="form-check mt-3">
    <input type="checkbox" class="form-check-input" id="allow_site_survey" name="allow_site_survey" value="1"
<?= isset($site_survey_required) && $site_survey_required == 1 ? 'checked' : '' ?>>
<label class="form-check-label" for="allow_site_survey">Site Survey Required</label>

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

     <?php
if (isset($enquiry_data['site_survey']) && $enquiry_data['site_survey'] != 1) {
?>
<div class="mt-3">
   <a href="<?= base_url('index.php/CRM/view_enquiry/' . $enquiry_data['enquiry_id'] . '/edit/3') ?>"
   class="btn btn-primary">
    Go To Estimation
</a>
</div>
<?php } ?>

                        </div>

                        <!-- Survey Tab -->
                        <div class="tab-pane fade <?= isset($menu_status) && $menu_status == 2 ? 'active show' : "" ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="post" action="<?= base_url()?>index.php/CRM/save_survey">
                                <input type="hidden" name="enquiry_id" value="<?= isset($enquiryid) ? $enquiryid : "" ?>">

                                 <div class="form-group">
    <label for="employee_survey">Surveyor</label>
    <select name="employee_survey" id="employee_survey" class="form-control">
        <option value="">-- Select --</option>
        <?php foreach($employee_list as $employee): ?>
            <option value="<?= $employee->employee_id ?>" 
                <?= isset($survey_data['employee_survey']) && $survey_data['employee_survey'] == $employee->employee_id ? 'selected' : '' ?>>
                <?= $employee->employee_name ?> (<?= $employee->uid_number ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label for="survey_date">Schedule Date</label>
                                        <input type="date" name="survey_date" id="survey_date" class="form-control" value="<?= isset($survey_data['scheduled_date']) ? date('Y-m-d', strtotime($survey_data['scheduled_date'])) : date('Y-m-d') ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="survey_start_datetime">Schedule Start Date & Time <span class="required">*</span></label>
                                        <input type="datetime-local" name="survey_start_datetime" id="survey_start_datetime" class="form-control" value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d\TH:i', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d\TH:i') ?>" required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="survey_end_datetime">Schedule End Date & Time <span class="required">*</span></label>
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
    const subtotalField         = document.getElementById('subtotal');
    const marginPercentField    = document.getElementById('marginPercent');
    const marginAmountField     = document.getElementById('marginAmount');
    const freightPercentField   = document.getElementById('freightPercent');
    const freightAmountField    = document.getElementById('freightAmount');
    const bankChargeField       = document.getElementById('bankCharge');
    const travelExpenseField    = document.getElementById('travelExpense');
    const otherExpenseField     = document.getElementById('otherExpense');
    const inspectionCostField   = document.getElementById('inspectionCost');
    const totalCostField        = document.getElementById('totalCost');

    function calculateTotalCost() {
        const subtotal              = parseFloat(subtotalField.value) || 0;

        // Margin
        const marginPercent         = parseFloat(marginPercentField.value) || 0;
        const marginAmount          = subtotal * marginPercent / 100;
        marginAmountField.value     = marginAmount.toFixed(2);

        // Freight
        const freightPercent        = parseFloat(freightPercentField.value) || 0;
        const freightAmount         = subtotal * freightPercent / 100;
        freightAmountField.value    = freightAmount.toFixed(2);

        // Other Expenses
        const bankCharge            = parseFloat(bankChargeField.value) || 0;
        const travelExpense         = parseFloat(travelExpenseField.value) || 0;
        const otherExpense          = parseFloat(otherExpenseField.value) || 0;
        const inspectionCost        = parseFloat(inspectionCostField.value) || 0;

        // Total
        const total                 = subtotal + marginAmount + freightAmount + bankCharge + travelExpense + otherExpense + inspectionCost;
        totalCostField.value        = total.toFixed(2);
    }

        // Add event listeners to recalculate whenever any field changes
        document.addEventListener('DOMContentLoaded', function() {
            [
                subtotalField,
                marginPercentField,
                freightPercentField,
                bankChargeField,
                travelExpenseField,
                otherExpenseField,
                inspectionCostField
            ].forEach(field => {
                if (field) { // ✅ only attach if element exists
                field.addEventListener('input', calculateTotalCost);
                }
            });
            });


$('#allow_site_survey').change(function() {
    if ($(this).is(':checked')) {
        // Add Site Survey tab if not already present
        if ($('#profile-tab').length == 0) {
            let tab = `<li class="nav-item">
                           <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                               Site Survey
                           </a>
                       </li>`;
            $('.nav-tabs').append(tab);

            // Add empty tab content if missing
            if ($('#profile').length == 0) {
                let content = `<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                   <p>Fill site survey details here after saving enquiry.</p>
                               </div>`;
                $('.tab-content').append(content);
            }
        }
    } else {
        // Remove Site Survey tab & content if unchecked
        $('#profile-tab').closest('li').remove();
        $('#profile').remove();
    }
});

$(document).ready(function() {
    if ($('#allow_site_survey').is(':checked')) {
        // Make tab active if needed
        if ($('#profile-tab').length == 0) {
            let tab = `<li class="nav-item">
                           <a class="nav-link <?= isset($menu_status) && $menu_status==2 ? "active" : "" ?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab">
                               Site Survey
                           </a>
                       </li>`;
            $('.nav-tabs').append(tab);
        }
        if ($('#profile').length == 0) {
            $('.tab-content').append(`<div class="tab-pane fade <?= isset($menu_status) && $menu_status==2 ? "show active" : "" ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                           <p>Fill site survey details here after saving enquiry.</p>
                                       </div>`);
        }
    }
});

$(document).ready(function() {
    let branchId = $('#branch').val();
    let selectedCustomer = '<?= isset($enquiry_data['enquiry_customer']) ? $enquiry_data['enquiry_customer'] : "" ?>';

    if(branchId && selectedCustomer) {
        $.ajax({
            url: '<?= base_url("index.php/Company/get_customers_by_branch") ?>',
            type: 'POST',
            data: {branch_id: branchId},
            dataType: 'json',
            success: function(data) {
                $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
                $.each(data, function(index, customer) {
                    let selected = customer.customer_id == selectedCustomer ? 'selected' : '';
                    $('#customer_id').append(
                        '<option value="' + customer.customer_id + '" '+ selected +'>' + 
                        customer.customer_name + ' (' + customer.customer_code + ') => ' + customer.contact_number +
                        '</option>'
                    );
                });
                $('#customer_id').trigger('change'); // Refresh select2
            }
        });
    }
});






</script>

