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
                <!-- Flash message section -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
                  <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">New</a>
                            <a class="dropdown-item" href="#">Site Survey</a>
                            <a class="dropdown-item" href="#">Estimation</a>
                            <a class="dropdown-item" href="#">Quotation</a>
                            <a class="dropdown-item" href="#">Sales Order</a>
                            <a class="dropdown-item" href="#">Delivery Notes</a>                            
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
                                <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [3,4])) ? 'active' : '' ?>" 
                                    id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                                    aria-selected="<?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [3,4])) ? 'true' : 'false' ?>">
                                    Estimation
                                </a>
                        </li> 
                        <li class="nav-item">
                        <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [5,6])) ? 'active' : '' ?>" 
                            id="quotation-tab" 
                            data-toggle="tab" 
                            href="#quotation"  
                            role="tab" 
                            aria-controls="quotation"   
                            aria-selected="<?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [5,6])) ? 'true' : 'false' ?>">
                            Quotation
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [7,8])) ? 'active' : '' ?>" 
                            id="sales_order-tab" 
                            data-toggle="tab" 
                            href="#sales_order"  
                            role="tab" 
                            aria-controls="sales_order"   
                            aria-selected="<?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [7,8])) ? 'true' : 'false' ?>">
                            Sales Order
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [9])) ? 'active' : '' ?>" 
                            id="delivery_notes-tab" 
                            data-toggle="tab" 
                            href="#delivery_notes"  
                            role="tab" 
                            aria-controls="delivery_notes"   
                            aria-selected="<?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [9])) ? 'true' : 'false' ?>">
                            Delivery Challan
                        </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link <?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [9])) ? 'active' : '' ?>" 
                            id="invoice-tab" 
                            data-toggle="tab" 
                            href="#invoice"  
                            role="tab" 
                            aria-controls="invoice"   
                            aria-selected="<?= (isset($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [10])) ? 'true' : 'false' ?>">
                            Invoice
                        </a>
                        </li>
   
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- HOME TAB -->
                        <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 1 ? 'active show' : '' ?>"
                            id="home"
                            role="tabpanel"
                            aria-labelledby="home-tab">

                            <?php $this->load->view('sales/include/enquiry.php') ?>
                        </div> <!-- /.tab-pane #home -->

                        <!-- PROFILE TAB (Site Survey) -->
                        <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 2 ? 'active show' : '' ?>"
                            id="profile"
                            role="tabpanel"
                            aria-labelledby="profile-tab">
                            <?php $this->load->view('sales/include/survey.php') ?>
                        </div> <!-- /.tab-pane #profile -->

                        <!------ ESTIMATION TAB ------>
                        <div class="tab-pane fade <?= isset($enquiry_data['enquiry_status']) &&($enquiry_data['enquiry_status'] == 3 || $enquiry_data['enquiry_status'] == 4 )? ' active show' : '' ?>"
                            id="contact"
                            role="tabpanel"
                            aria-labelledby="contact-tab">
                                <?php $this->load->view('sales/include/estimation.php') ?>
                        

                        </div> 

                         <!------ Quotation TAB ------>
                        <div class="tab-pane fade <?= (!empty($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [5,6])) ? ' active show' : '' ?>"   id="quotation" role="tabpanel" aria-labelledby="quotation-tab">
                                <?php $this->load->view('sales/include/quotation.php') ?>                      

                        </div> 

                         <!------ Sales order TAB ------>
                        <div class="tab-pane fade <?= (!empty($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [7,8])) ? ' active show' : '' ?>"   id="sales_order" role="tabpanel" aria-labelledby="sales_order-tab">
                            <?php $this->load->view('sales/include/sales_order.php') ?>                      
                        </div> 

                        <!------ Delivery notes TAB ------>
                        <div class="tab-pane fade <?= (!empty($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [9])) ? ' active show' : '' ?>"   id="delivery_notes" role="tabpanel" aria-labelledby="delivery_notes-tab">
                            <?php $this->load->view('sales/include/delivery_notes.php') ?>                      
                        </div> 
                        <!------ invoice  TAB ------>
                        <div class="tab-pane fade <?= (!empty($enquiry_data['enquiry_status']) && in_array($enquiry_data['enquiry_status'], [9])) ? ' active show' : '' ?>"   id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                            <?php $this->load->view('sales/include/invoice.php') ?>                      
                        </div> 
                      
                    </div><!-- /.tab-content -->

                </div> <!-- /.x_content -->

            </div> <!-- /.x_panel -->

        </div> <!-- /.col -->

    </div> <!-- /.row -->
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
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('delivery_term');
    CKEDITOR.replace('terms_condition');
    CKEDITOR.replace('so_delivery_term');
    CKEDITOR.replace('so_terms_condition');
    CKEDITOR.replace('so_edit_delivery_term');
    CKEDITOR.replace('so_edit_terms_condition');
</script>
<script>
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
<?php if($enquiry_data['enquiry_status'] == 4){?>
    let mainIndex=<?= $mainIndex ?>
<?php } else {?>
    let mainIndex = 0;
<?php } ?>


<?php if($enquiry_data['enquiry_status'] != 4) {?>
// Add first main heading on page load
$(document).ready(function () {
    $("#mainMenuContainer").append(generateMainHeadingHTML(mainIndex));
    mainIndex++;
});
<?php } ?>
// Button click → Add main heading
$("#addMainHeading").on("click", function () {
    $("#mainMenuContainer").append(generateMainHeadingHTML(mainIndex));
    mainIndex++;
});
let subHeadingIndex = {}; 
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
    if (!subHeadingIndex[mainId]) subHeadingIndex[mainId] = 0;
    const subId = subHeadingIndex[mainId]++;
    return `
    <div class="border p-2 mb-2 subHeadingContainer" data-main="${mainId}" data-sub="${subId}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sub_heading[${mainId}][${subId}]" class="form-control form-control-sm w-75" placeholder="Enter Sub Heading">
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
                ${generateProductRow(mainId, subId)}
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
let productRowIndex = {}; 
function generateProductRow(mainId, subId) {
    const key = mainId + '_' + subId;
    if (!productRowIndex[key]) productRowIndex[key] = 0;
    const rowId = productRowIndex[key]++;
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
            <select name="products[${mainId}][${subId}][${rowId}][product_id]" class="form-control form-control-sm product-select" onchange="onProductChange(this)">
                ${productOptions}
            </select><br/><br/>
            <textarea name="products[${mainId}][${subId}][${rowId}][product_description]"  id="product_description" class="form-control form-control-sm"></textarea>
        </td>
        <td>
            <select name="products[${mainId}][${subId}][${rowId}][unit_id]" class="form-control form-control-sm unit-select">
                ${unitOptions}
            </select>
        </td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][quantity]" class="form-control form-control-sm qty"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][unit_price]" class="form-control form-control-sm unitPrice"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][amount]" class="form-control form-control-sm amount" readonly></td>
        <td>
            <button type="button" class="btn btn-success btn-sm addProductRow">+</button>
        </td>
    </tr>`;
}


// ------------------ 5. Add / Remove Product Rows ------------------

// Add product row
$(document).on("click", ".addProductRow", function () {
    let $table = $(this).closest("table");
    let $subHeadingContainer = $(this).closest(".subHeadingContainer");

    let mainId = $subHeadingContainer.data("main");
    let subId = $subHeadingContainer.data("sub");

    // Append new product row
    $table.find("tbody").append(generateProductRow(mainId, subId));

    // Change "+" button to delete
    $(this).removeClass("btn-success addProductRow")
           .addClass("btn-danger removeProductRow")
           .text("🗑");

    // Re-initialize select2 for the new row
    $table.find(".product-select").select2({
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

    // If "Add New Product" is selected
    if (productId === "__new__") {
        $select.val(""); // reset dropdown
        $("#productMessage").hide().text("");
        $("#addProductModal").modal("show");
        $("#addProductModal").data("targetSelect", selectElement);
        return;
    }

    // Normal product selected
    if (productId) {
        let product = productsList.find(p => p.item_id == productId);
        if (product) {
            let unitSelect = row.find("select.unit-select");

            // Add unit option if not already there
            if (!unitSelect.find(`option[value='${product.item_unit}']`).length) {
                let unitName = unitsList.find(u => u.unit_id == product.item_unit)?.unit_name || "Unknown Unit";
                unitSelect.append(new Option(unitName, product.item_unit, true, true));
            }

            // Set unit
            unitSelect.val(product.item_unit).trigger('change'); // trigger if using select2

            // Set unit price
            row.find("input.unitPrice").val(product.unit_price);
        } else {
            row.find("select.unit-select").val("").trigger('change');
            row.find("input.unitPrice").val("");
        }
    } else {
        row.find("select.unit-select").val("").trigger('change');
        row.find("input.unitPrice").val("");
    }
}


$(document).on("input", "input[name^='qty']", function () {
    let row = $(this).closest("tr");
    let qty = parseFloat($(this).val()) || 0;
    let unitPrice = parseFloat(row.find("input[name^='unit_price']").val()) || 0;

     row.find("input[name^='amount']").val((qty * unitPrice).toFixed(2));
   //calculateSubtotal();
});

// $(document).on("input", "input[name^='qty']", function () {
//     let row = $(this).closest("tr");
//     let qty = parseFloat($(this).val()) || 0;
//     let unitPrice = parseFloat(row.find("input[name^='unit_price']").val()) || 0;

//      row.find("input[name^='amount']").val((qty * unitPrice).toFixed(2));
//    //calculateSubtotal();
// });

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
   // Collect all fields safely
const subtotalField       = document.getElementById('subtotal');
const marginPercentField  = document.getElementById('marginPercent');
const marginAmountField   = document.getElementById('marginAmount');
const freightPercentField = document.getElementById('freightPercent');
const freightAmountField  = document.getElementById('freightAmount');
const bankChargeField     = document.getElementById('bankCharge');
const travelExpenseField  = document.getElementById('travelExpense');
const otherExpenseField   = document.getElementById('otherExpense');
const inspectionCostField = document.getElementById('inspectionCost');
const totalCostField      = document.getElementById('totalCost');

// Calculation function (only runs if required fields exist)
function calculateTotalCost() {
    if (!subtotalField || !marginPercentField || !marginAmountField ||
        !freightPercentField || !freightAmountField || !bankChargeField ||
        !travelExpenseField || !otherExpenseField || !inspectionCostField ||
        !totalCostField) {
        return; // exit safely if estimation fields not loaded
    }

    const subtotal = parseFloat(subtotalField.value) || 0;

    // Margin
    const marginPercent = parseFloat(marginPercentField.value) || 0;
    const marginAmount  = subtotal * marginPercent / 100;
    marginAmountField.value = marginAmount.toFixed(2);

    // Freight
    const freightPercent = parseFloat(freightPercentField.value) || 0;
    const freightAmount  = subtotal * freightPercent / 100;
    freightAmountField.value = freightAmount.toFixed(2);

    // Other Expenses
    const bankCharge     = parseFloat(bankChargeField.value) || 0;
    const travelExpense  = parseFloat(travelExpenseField.value) || 0;
    const otherExpense   = parseFloat(otherExpenseField.value) || 0;
    const inspectionCost = parseFloat(inspectionCostField.value) || 0;

    // Total
    const total = subtotal + marginAmount + freightAmount +
                  bankCharge + travelExpense + otherExpense + inspectionCost;
    totalCostField.value = total.toFixed(2);
        }

        // Safely add event listeners only if field exists
        [
            subtotalField,
            marginPercentField,
            freightPercentField,
            bankChargeField,
            travelExpenseField,
            otherExpenseField,
            inspectionCostField
        ].forEach(field => {
            if (field) {
                field.addEventListener('input', calculateTotalCost);
            }
        });

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

//Autosave
setInterval(function() {
        autosaveFormData();
    }, 50000); 

 // Auto-save form data every 30 seconds
function autosaveFormData() {	
    var formData = $('#estimationForm').serialize(); 		
    
    $.ajax({
        url: '<?php echo base_url("index.php/Sales/save_estimation_dummy"); ?>',
        type: 'POST',
        data: formData,
        dataType: 'json', // 👈 this makes jQuery expect JSON
        success: function(response) {
            if (response.status === 'success') {
                console.log('Form data autosaved to database!');
            } else {
                console.warn('Autosave failed:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving form data:', error);
        }
    });
}
function restoreProducts(){
	
	document.getElementById('mainMenuContainer').innerHTML='';
	$.ajax({
        type: "POST",
        url:"<?php echo base_url()?>index.php/Ajax/restore_estimation_items",
        success: function(msg){	  
		document.getElementById('mainMenuContainer').innerHTML=msg;
		$('.select2').select2({ });
        calculateSubtotal()
	     }
	});
}

$(document).ready(function() {
    $('#Action_estimation').on('change', function () {
    var action = $(this).val();
    var estimation_id = $('#estimation_id').val(); // hidden input with current estimation_id

    if (action === 'edit') {
        // Make fields editable
        $('.estimation_edit').prop('readonly', false);

        // Add update button if not already present
        if ($('#updateBtn').length === 0) {
            $('#actionButtons').html(
                '<button type="submit" id="updateBtn" class="btn btn-success">Update Estimation</button>'
            );
        }
    } else if (action === 'approve') {
        // Confirm before approve
        if (!confirm("Are you sure you want to approve this estimation?")) {
            return;
        }

        $.ajax({
            url: "<?= base_url('index.php/Sales/approve_estimation') ?>",
            type: "POST",
            data: { estimation_id: <?= isset($master['estimation_id'])?$master['estimation_id']:0 ?> },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    alert("Estimation approved successfully!");
                    location.reload();
                } else {
                    alert("Failed: " + res.message);
                }
            },
            error: function () {
                alert("Error while approving estimation.");
            }
        });
    } else {
        // Reset to readonly
        $('.estimation_edit').prop('readonly', true);

        // Remove update button
        $('#actionButtons').empty();
    }
});

});
$(document).ready(function () {
    function calculateDiscount() {
        let estimationCost = parseFloat($("#qtn_estimation_cost").val()) || 0;
        let discountPercentage = parseFloat($("#qtn_discount_percentage").val()) || 0;
        let discountAmount = parseFloat($("#qtn_discount_amount").val()) || 0;

        // If % is entered → calculate amount
        if ($("#qtn_discount_percentage").is(":focus")) {
            discountAmount = (estimationCost * discountPercentage / 100).toFixed(2);
            $("#qtn_discount_amount").val(discountAmount);
        }

        // If amount is entered → calculate %
        if ($("#qtn_discount_amount").is(":focus")) {
            if (estimationCost > 0) {
                discountPercentage = ((discountAmount / estimationCost) * 100).toFixed(2);
                $("#qtn_discount_percentage").val(discountPercentage);
            }
        }

        // Update total before VAT
        let totalBeforeVat = estimationCost - discountAmount;
        $("#total_before_vat").val(totalBeforeVat.toFixed(2));
        updateTotals()
    }

    // Trigger calculation when typing in either field
    $("#qtn_discount_percentage, #qtn_discount_amount").on("input", function () {
        calculateDiscount();
    });

     function updateTotals() {
        let totalBeforeVat = parseFloat($("#total_before_vat").val()) || 0;
        let vatPercentage = parseFloat($("#vat_percentage").val()) || 0;
        let vatAmount = 0;
        let grandTotal = totalBeforeVat;

        if ($("#apply_vat").is(":checked")) {
            vatAmount = (totalBeforeVat * vatPercentage / 100).toFixed(2);
            grandTotal = (totalBeforeVat + parseFloat(vatAmount)).toFixed(2);
            $("#vat_percentage").show();  // show field if checked
        } else {
            $("#vat_percentage").hide();  // hide field if unchecked
        }

        $("#vat").val(vatAmount);
        $("#qtn_grand_total").val(grandTotal);
    }

    // Trigger calculation on checkbox change
    $("#apply_vat").on("change", function () {
        updateTotals();
    });

    // Also recalc whenever total_before_vat changes
    $("#total_before_vat").on("input", function () {
        updateTotals();
    });
});
$(document).ready(function () {
    $('#Action_quotation').on('change', function () {
        var action = $(this).val();

        if (action === 'edit') {
            // ✅ Enable normal input, select, textarea fields
            $('.quotation_edit, .quotation_input').prop('readonly', false).prop('disabled', false);

            // ✅ Enable CKEditor fields
            $('.quotation_edit').each(function () {
                var editorId = $(this).attr('id');
                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].setReadOnly(false);
                }
            });

            // ✅ Add checkbox first, then update button if not already there
            if ($('#qtn_new_revision').length === 0) {
                $('#action_button_quotation').html(
                '<div class="form-check d-inline-block me-3">' +
                    '<input class="form-check-input" type="checkbox" id="qtn_new_revision" name="qtn_new_revision" value="1">' +
                    '<label class="form-check-label" for="qtn_new_revision">Create New Revision</label>' +
                '</div>' +
                '<button type="submit" id="updateQtnBtn" class="btn btn-success">Update Quotation</button>'
            );
            }
        } else if (action === 'approve') {
                $.ajax({
                    url: "<?= base_url('index.php/Sales/approve_quotation') ?>",
                    type: "POST",
                    data: {
                        quotation_id: "<?= isset($qtn_master['quotation_id'])?$qtn_master['quotation_id']:"" ?>",
                        approved_by: "<?= $this->session->userdata('user_id') ?>"
                    },
                    success: function (res) {
                        alert("Quotation approved successfully!");
                        location.reload();
                    },
                    error: function () {
                        alert("Error while approving quotation.");
                    }
                });
            
        }else if (action === 'resurvey') {
                $.ajax({
                    url: "<?= base_url('index.php/Sales/resurvey_from_qtn') ?>",
                    type: "POST",
                    dataType: "json", // expect JSON response
                    data: {
                        enquiry_id:     "<?= isset($enquiry_data['enquiry_id'])?$enquiry_data['enquiry_id']:"" ?>",
                        estimation_id:  "<?= isset($qtn_master['estimation_id'])?$qtn_master['estimation_id']:""?>",
                        quotation_id:   "<?= isset($qtn_master['quotation_id'])?$qtn_master['quotation_id']:"" ?>",
                        created_by:     "<?= $this->session->userdata('user_id') ?>"
                    },
                    success: function (res) {
                        if (res.status === "success") {
                            alert("Rescheduled Survey successfully!");
                            location.reload(true);
                        } else {
                            alert("Something went wrong while rescheduling survey.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Error while rescheduling survey. Please try again.");
                    }
                });

            
        } else {
            // ❌ Lock normal fields again
            $('.quotation_edit, .quotation_input').prop('readonly', true).prop('disabled', true);

            // ❌ Lock CKEditor fields again
            $('.quotation_edit').each(function () {
                var editorId = $(this).attr('id');
                if (CKEDITOR.instances[editorId]) {
                    CKEDITOR.instances[editorId].setReadOnly(true);
                }
            });

            // ❌ Remove checkbox + button
            $('#action_button_quotation').empty();
        }
    });
});



    // Initial calculation on page load
   // Qtn_calculateTotals();
//});



</script>

