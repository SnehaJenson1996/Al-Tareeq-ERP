<style>
  .nav-link.active {
    background-color: #007FFF !important; /* set background */
    color: white !important;              /* make text readable */
  }
  .form-control-sm {
    height: 30px;
    padding: 3px 6px;
    font-size: 13px;
}
</style>

<div class="clearfix"></div>

<div class="row">
  <div class="col-md-12 col-sm-12">
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

      <!-- Panel header -->
      <div class="x_title">
        <ul class="nav navbar-right panel_toolbox">
          <li>
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              <i class="fa fa-wrench"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="#">Quotation</a>
              <a class="dropdown-item" href="#">Sales Order</a>
              <a class="dropdown-item" href="#">Delivery Notes</a>
              <a class="dropdown-item" href="#">Invoice</a>
            </div>
          </li>
          <li>
            <a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">

        <!-- Tabs -->
        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">

          <!-- Quotation -->
          <li class="nav-item">
            <a class="nav-link <?= in_array($enquiry_data['enquiry_status'], [5,6]) ? 'active' : '' ?>"
               id="quotation-tab"
               data-toggle="tab"
               href="#quotation"
               role="tab"
               aria-controls="quotation"
               aria-selected="<?= in_array($enquiry_data['enquiry_status'], [5,6]) ? 'true' : 'false' ?>">
               Quotation
            </a>
          </li>

          <!-- Sales Order -->
          <li class="nav-item">
            <a class="nav-link <?= in_array($enquiry_data['enquiry_status'], [7,8]) ? 'active' : '' ?>"
               id="sales_order-tab"
               data-toggle="tab"
               href="#sales_order"
               role="tab"
               aria-controls="sales_order"
               aria-selected="<?= in_array($enquiry_data['enquiry_status'], [7,8]) ? 'true' : 'false' ?>">
               Sales Order
            </a>
          </li>

          <!-- Delivery Notes -->
          <li class="nav-item">
            <a class="nav-link <?= ($enquiry_data['enquiry_status'] == 9) ? 'active' : '' ?>"
               id="delivery_notes-tab"
               data-toggle="tab"
               href="#delivery_notes"
               role="tab"
               aria-controls="delivery_notes"
               aria-selected="<?= ($enquiry_data['enquiry_status'] == 9) ? 'true' : 'false' ?>">
               Delivery Challan
            </a>
          </li>

          <!-- Invoice -->
          <li class="nav-item">
            <a class="nav-link <?= ($enquiry_data['enquiry_status'] >= 10) ? 'active' : '' ?>"
               id="invoice-tab"
               data-toggle="tab"
               href="#invoice"
               role="tab"
               aria-controls="invoice"
               aria-selected="<?= ($enquiry_data['enquiry_status'] >= 10) ? 'true' : 'false' ?>">
               Invoice
            </a>
          </li>

        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">

          <!-- Quotation -->
          <div class="tab-pane fade <?= in_array($enquiry_data['enquiry_status'], [5,6]) ? 'active show' : '' ?>"
               id="quotation"
               role="tabpanel"
               aria-labelledby="quotation-tab">
            <?php $this->load->view('sales/include/quotation.php') ?>
          </div>

          <!-- Sales Order -->
          <div class="tab-pane fade <?= in_array($enquiry_data['enquiry_status'], [7,8]) ? 'active show' : '' ?>"
               id="sales_order"
               role="tabpanel"
               aria-labelledby="sales_order-tab">
            <?php $this->load->view('sales/include/sales_order.php') ?>
          </div>

          <!-- Delivery Notes -->
          <div class="tab-pane fade <?= ($enquiry_data['enquiry_status'] == 9) ? 'active show' : '' ?>"
               id="delivery_notes"
               role="tabpanel"
               aria-labelledby="delivery_notes-tab">
            <?php $this->load->view('sales/include/delivery_notes.php') ?>
          </div>

          <!-- Invoice -->
          <div class="tab-pane fade <?= ($enquiry_data['enquiry_status'] >= 10) ? 'active show' : '' ?>"
               id="invoice"
               role="tabpanel"
               aria-labelledby="invoice-tab">
            <?php $this->load->view('sales/include/invoice.php') ?>
          </div>

        </div><!-- /.tab-content -->

      </div><!-- /.x_content -->
    </div><!-- /.x_panel -->
  </div><!-- /.col -->
</div><!-- /.row -->





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
  ['delivery_term', 'terms_condition', 'so_delivery_term', 
   'so_terms_condition', 'so_edit_delivery_term', 'so_edit_terms_condition']
   .forEach(function(id) {
      var el = document.getElementById(id);
      if (el && el.tagName.toLowerCase() === 'textarea') {
          CKEDITOR.replace(id);
      }
   });
</script>
<script>


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


