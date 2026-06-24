<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }

    .ck-editor__editable_inline {
        min-height: 250px;
        /* approximately 10 rows */
    }
    .ck-editor__editable {
    min-height: 80px !important; 
  }  /* Reduce height */
/* Reduce CKEditor Width */
.product_editor + .ck-editor {
    max-width: 300px !important;   /* 👈 Change width here */
}

.product_editor + .ck-editor .ck-editor__editable {
    width: 100% !important;
}
</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle"></i></strong>
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle"></i></strong>
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?= base_url() ?>index.php/Quotation/save_direct_quotation" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Select Branch:</label>
                                <div class="col-sm-8"> <select name="branch" id="branch" class="form-control select2">
                                        <option value=''>Select</option> <?php foreach ($branch_list as $branch): ?> <option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option> <?php endforeach; ?>
                                    </select> </div>
                            </div>
                        </div>

                       <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Project Name:</label>
                                <div class="col-sm-8"> <input type="text" id="project_name" name="project_name" value="<?= isset($enquiry_data['project_name']) ? $enquiry_data['project_name'] : "" ?>" class="form-control"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Select Customer:</label>
                                <div class="col-sm-8"> <select name="customer_id" id="customer_id" class="form-control select2" required> </select> </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                         <div class="form-group row align-items-center">
                           <label class="col-sm-4 col-form-label">Project Location:</label>
                            <div class="col-sm-8">
                                 <input type="text" name="project_location" id="project_location"
                                  value="<?= isset($enquiry_data['project_location']) ? $enquiry_data['project_location'] : '' ?>"
                                  class="form-control" placeholder="Enter Project Location">
                            </div>
                          </div>

</div>
                    </div>
                    <div class="row"> <!-- Quotation Code -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Quotation Code:</label>
                                <div class="col-sm-8"> <input type="text" id="quotation_code" name="quotation_code" value="<?= isset($quotation_code) ? $quotation_code : "" ?>" class="form-control" readonly> </div>
                            </div>
                        </div> <!-- Quotation Date -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Quotation Date:</label>
                                <div class="col-sm-8"> <input type="date" id="quotation_date" name="quotation_date" value="" class="form-control" required> </div>
                            </div>
                        </div>
                    </div> 
                    
                   
<!-- Main Heading Button -->
                    <div class="mt-2"> <button type="button" class="btn btn-primary btn-sm" id="addMainHeading"> + Add Main Heading </button> </div>
                    <div id="mainMenuContainer"></div> <!-- Summary -->
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="row"> <!-- Sub Total & Margin -->
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Sub Total</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="subtotal" name="sub_total" class="form-control" readonly> </div>
                                    </div>
                                </div>
                                <!-- Margin -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Margin (%)</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="marginPercent" name="margin_percent" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" id="marginAmount" name="margin_amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!-- Freight & Bank Charge -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Freight (%)</label>
                                        <div class="col-sm-4">
                                            <input type="text" step="0.01" id="freightPercent" name="freight_percent" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" id="freightAmount" name="freight_amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Bank Charge</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="bankCharge" name="bank_charge" class="form-control"> </div>
                                    </div>
                                </div> <!-- Travel & Other -->
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Travel Expense</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="travelExpense" name="travel_expense" class="form-control"> </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Other Expense</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="otherExpense" name="other_expense" class="form-control"> </div>
                                    </div>
                                </div> <!-- Inspection & Total -->
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Inspection Cost</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="inspectionCost" name="inspection_cost" class="form-control"> </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Total Cost</label>
                                        <div class="col-sm-8"> <input type="number" step="0.01" id="totalCost" name="total_cost" class="form-control font-weight-bold" readonly> </div>
                                    </div>
                                </div>
                                <!-- Additional Discount -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="qtn_add_discount_percentage" name="qtn_add_discount_percentage" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" id="qtn_add_discount_amount" name="qtn_add_discount_amount" class="form-control " placeholder="Amt">
                                            <!-- <input type="text"  id="qtn_add_discount_amount" name="qtn_add_discount_amount" class="form-control"> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Total Before VAT</label>
                                        <div class="col-sm-8"> <input type="text" name="total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="" readonly> </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Apply VAT</label>
                                        <div class="col-sm-4 d-flex align-items-center">
                                            <div class="form-check me-3 mb-0"> <input type="checkbox" id="qtn_apply_vat" name="qtn_apply_vat" class="form-check-input"> </div> <input type="number" name="qtn_vat_percentage" id="qtn_vat_percentage" value="5" class="form-control form-control-sm" style="width:80px;" readonly>
                                        </div>
                                        <div class="col-sm-4"> <input type="text" name="qtn_vat_amount" id="qtn_vat_amount" class="form-control form-control-sm estimation_edit" placeholder="VAT Amount" readonly> </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row"> <label class="col-sm-4 col-form-label">Grand Total</label>
                                        <div class="col-sm-8"> <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="" readonly> </div>
                                    </div>
                                </div>
                            </div> <!-- /.row --> <!-- Payment and Terms -->
                            <div class="form-group row">
                                <div class="col-sm-6"> <label class="col-form-label">Payment Term</label> 
<textarea name="payment_term" id="payment_term"
    class="form-control estimation_edit"><?= isset($master['payment_term']) ? $master['payment_term'] : "" ?></textarea>
                            </div>
                                <div class="col-sm-6"> <label class="col-form-label">Validity</label> <input type="text" name="validity" id="validity" class="form-control estimation_edit" value="<?= isset($master['validity']) ? $master['validity'] : "" ?>"> </div>
                            </div>

                            <div class="form-group row">
    <div class="col-sm-6">
        <label class="col-form-label">Warranty</label>
        <input type="text" name="warranty" id="warranty"
            class="form-control estimation_edit"
            value="<?= isset($master['warranty']) ? $master['warranty'] : "" ?>">
    </div>

    <div class="col-sm-6">
        <label class="col-form-label">Warranty Description</label>
        <textarea name="warranty_description" id="warranty_description"
            class="form-control estimation_edit"><?= isset($master['warranty_description']) ? $master['warranty_description'] : "" ?></textarea>
    </div>
</div>
                            <div class="form-group row">
                                <div class="col-sm-6"> <label class="col-form-label">Delivery Term</label> <textarea name="delivery_term" id="delivery_term" rows="10" class="form-control estimation_edit"></textarea> </div>
                                <div class="col-sm-6"> <label class="col-form-label">Terms & Conditions</label> <textarea name="terms_condition" id="terms_condition" rows="10" class="form-control estimation_edit">
We trust that our proposal meets your requirements and look forward to the opportunity to execute your order with the highest priority and
commitment.<br><br>
Should you require any further clarification or additional information, please do not hesitate to contact our team at any time. We remain at
your disposal.<br><br>
We assure you of our best services and continuous support at all times.
</textarea> </div>
                            </div>

 <div class="row mt-3">
    <div class="col-md-12">
        <label>Notes</label>
        <textarea class="form-control"
                  name="notes"
                  id="notes"
                  rows="4">Electrical cable, fire alarm cable, fire exit door light shifting, side gypsum cutting for button cable, and painting will be under customer scope.</textarea>
    </div>
</div>

                            <div class="row mt-3">
      <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Prepared By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_prepared" name="employee_prepared" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>

       <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Approved By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_approved" name="employee_approved" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>      
    </div> 

                            <div class="row justify-content-center mt-3"> <button type="submit" class="btn btn-success" name="action" value="quotation">Create Quotation</button> 
                            <!-- <button type="submit" class="btn btn-primary ml-2" name="action" value="sales_order">Save & Create Sales Order</button> </div> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <!-- Include CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
    $(document).ready(function() {

        /* ==========================================================
           1. INITIALIZATION
        ========================================================== */

        // CKEditor Initialization
        ClassicEditor.create(document.querySelector('#delivery_term')).then(e => window.deliveryEditor = e).catch(console.error);
        ClassicEditor.create(document.querySelector('#terms_condition')).then(e => window.termsEditor = e).catch(console.error);
        ClassicEditor.create(document.querySelector('#payment_term')).then(e => window.termsEditor = e).catch(console.error);
        ClassicEditor.create(document.querySelector('#notes')).then(e => window.termsEditor = e).catch(console.error);


        // Select2 for Customer Dropdown
        $('#customer_id').select2({
            placeholder: "-- Select Customer --",
            allowClear: true,
            width: '100%'
        });

        /* ==========================================================
           2. AJAX: LOAD NEW CUSTOMER MODAL
        ========================================================== */
        $('.view-employees').on('click', function(e) {
            e.preventDefault();
            $('#modal-body-content').html("Loading...");
            $('#myModal').modal('show');
            $.ajax({
                url: "<?= base_url('index.php/Ajax/add_new_customer') ?>",
                type: "POST",
                success: function(response) {
                    $('#modal-body-content').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("AJAX error: " + error);
                }
            });
        });

        /* ==========================================================
           3. BRANCH → CUSTOMER FILTER
        ========================================================== */
        $('#branch').on('change', function() {
            const branch_id = $(this).val();
            const $customer = $('#customer_id');
            $customer.empty().append('<option value="">-- Select Customer --</option>');
            if (branch_id) {
                $.ajax({
                    url: '<?= base_url("index.php/Company/get_customers_by_branch") ?>',
                    type: 'POST',
                    data: {
                        branch_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(_, customer) {
                            $customer.append(`<option value="${customer.customer_id}">
                            ${customer.customer_name} (${customer.customer_code}) → ${customer.contact_number}
                        </option>`);
                        });
                        $customer.trigger('change');
                    }
                });
            }
        });

        /* ==========================================================
           4. MAIN HEADING / SUBHEADING DYNAMIC HANDLERS
        ========================================================== */

        const productsList = <?= json_encode($all_products) ?>;
        const unitsList = <?= json_encode($active_units) ?>;
        let mainIndex = 0;
        let rowCounter = 0;


        // ✅ Create the first row on load
        addMainHeading();

        $('#addMainHeading').on('click', function() {
            addMainHeading();
        });

        // Functions
        function addMainHeading() {
            $("#mainMenuContainer").append(generateMainHeadingHTML(mainIndex++));
            initProductSelect2();
            initProductEditors();
        }

        $(document).on("click", ".removeMainHeading", function() {
            $("#main_heading_block_" + $(this).data("main")).remove();
        });

        $(document).on("click", ".addSubHeading", function() {
            const mainId = $(this).data("main");
            $("#subHeadingContainer_" + mainId).append(generateSubHeadingHTML(mainId));
            initProductSelect2();
                        initProductEditors();

        });

        $(document).on("click", ".removeSubHeading", function() {
            $(this).closest(".border").remove();
            calculateSubtotal();
        });

        /* ==========================================================
           5. PRODUCT ROWS ADD / REMOVE
        ========================================================== */
        $(document).on("click", ".addProductRow", function() {
            const mainId = $(this).data("main");
            const subId = $(this).data("sub");

            $(this).closest("tbody").append(generateProductRow(mainId, subId));
            $(this).removeClass("btn-success addProductRow").addClass("btn-danger removeProductRow").text("🗑");
            initProductSelect2();
            initProductEditors();
        });

        $(document).on("click", ".removeProductRow", function() {
            $(this).closest("tr").remove();
            calculateSubtotal();
        });

        /* $(document).on('input', '.qty, .unitPrice, .discount', function() {
             const row = $(this).closest('tr');
             const qty = parseFloat(row.find('.qty').val()) || 0;
             const price = parseFloat(row.find('.unitPrice').val()) || 0;
             const discount = parseFloat(row.find('.discount').val()) || 0;
             const amount = Math.max((qty * price) - discount, 0);
             row.find('.amount').val(amount.toFixed(2));
             calculateSubtotal();
         });*/
        $(document).on('input', '.discountPercent, .discountAmount, .qty, .unitPrice', function() {
            const $row = $(this).closest('tr');
            const qty = parseFloat($row.find('.qty').val()) || 0;
            const price = parseFloat($row.find('.unitPrice').val()) || 0;
            const percent = parseFloat($row.find('.discountPercent').val()) || 0;
            const discountAmountInput = $row.find('.discountAmount');

            const total = qty * price;

            // If user types percentage, calculate amount
            if ($(this).hasClass('discountPercent')) {
                const amount = total * (percent / 100);
                discountAmountInput.val(amount.toFixed(2));
            }

            // If user types amount, calculate percentage
            if ($(this).hasClass('discountAmount')) {
                const amount = parseFloat(discountAmountInput.val()) || 0;
                const percentCalc = total > 0 ? (amount / total) * 100 : 0;
                $row.find('.discountPercent').val(percentCalc.toFixed(2));
            }

            // Update final line amount
            const discountAmount = parseFloat(discountAmountInput.val()) || 0;
            const lineTotal = Math.max(total - discountAmount, 0);
            $row.find('.amount').val(lineTotal.toFixed(2));

            calculateSubtotal(); // re-run subtotal update
        });

        /* ==========================================================
           6. SELECT2 INITIALIZATION
        ========================================================== */
        function initProductSelect2() {
            $(".product-select").select2({
                placeholder: "Select Product",
                allowClear: true,
                width: '100%'
            });
        }

        /* ==========================================================
           7. TEMPLATE GENERATORS
        ========================================================== */
        // ===== MAIN HEADING BLOCK =====
        function generateMainHeadingHTML(i) {
            return `
    <div id="main_heading_block_${i}" class="border p-2 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div style="width:40%;">
                <label>Main Heading</label>
                <input type="text" name="main_heading[${i}]" class="form-control form-control-sm" placeholder="Enter Main Heading" required>
            </div>
            <div style="width:45%;">
                <label>Details</label>
                <textarea name="main_details[${i}]" class="form-control form-control-sm" placeholder="Enter Details"></textarea>
            </div>
            <div class="mt-4">
                <button type="button" class="btn btn-success btn-sm addSubHeading" data-main="${i}">+ Sub Heading</button>
                <button type="button" class="btn btn-danger btn-sm removeMainHeading" data-main="${i}">🗑</button>
            </div>
        </div>

        <div class="subHeadingContainer" id="subHeadingContainer_${i}">
            ${generateSubHeadingHTML(i, 0)}  <!-- default one sub heading -->
        </div>
    </div>`;
        }

        // ===== SUB HEADING BLOCK =====
        function generateSubHeadingHTML(mainId, subId) {
            return `
    <div class="border p-2 mb-2 sub-heading-block" data-main="${mainId}" data-sub="${subId}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sub_heading[${mainId}][${subId}]" 
                class="form-control form-control-sm w-75 " placeholder="Enter Sub Heading">

            <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
        </div>

        <table class="table table-bordered productTable mb-0">
            <thead>
                <tr style="background-color:#f8f9fa;">
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Amount</th>                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                ${generateProductRow(mainId, subId)}
            </tbody>
        </table>
    </div>`;
        }


        // Declare this at the top of your script, before any function calls

        function generateProductRow(mainId, subId) {
            const rowId = rowCounter++; // safely use and increment

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
            </select>
            <textarea 
    id="product_desc_${mainId}_${subId}_${rowId}"
    name="products[${mainId}][${subId}][${rowId}][description]"
    class="form-control form-control-sm product_editor mt-2"
    rows="4"
    placeholder="Enter product description">
</textarea>
        </td>
        <td><select name="products[${mainId}][${subId}][${rowId}][unit]" class="form-control form-control-sm">${unitOptions}</select></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][quantity]" class="form-control form-control-sm qty"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][unit_price]" class="form-control form-control-sm unitPrice"></td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][discount_percent]" class="form-control discountPercent" placeholder="%">
                <input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][discount_amount]" class="form-control discountAmount" placeholder="Amt">
            </div>
        </td>
       
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][amount]" class="form-control form-control-sm amount" readonly></td>
        <td><button type="button" class="btn btn-success btn-sm addProductRow" data-main="${mainId}" data-sub="${subId}">+</button></td>
    </tr>`;
        }




        /* ==========================================================
           8. PRODUCT AUTO-FILL ON CHANGE
        ========================================================== */
        window.onProductChange = function(selectElement) {
            const $row = $(selectElement).closest("tr");
            const productId = $(selectElement).val();
            const product = productsList.find(p => p.item_id == productId);
            if (product) {
                $row.find("select[name^='unit']").val(product.item_unit);
                $row.find("input[name^='unit_price']").val(product.unit_price);
            } else {
                $row.find("select[name^='unit']").val("");
                $row.find("input[name^='unit_price']").val("");
            }
        };

        /* ==========================================================
           9. CALCULATIONS
        ========================================================== */
        // $(document).ready(function() {

        function calculateSubtotal() {
            let subtotal = 0;
            $(".amount").each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });
            $("#subtotal").val(subtotal.toFixed(2));
            calculateTotalCost();
        }

        function getBaseTotal() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const bankCharge = parseFloat($('#bankCharge').val()) || 0;
            const travelExpense = parseFloat($('#travelExpense').val()) || 0;
            const otherExpense = parseFloat($('#otherExpense').val()) || 0;
            const inspectionCost = parseFloat($('#inspectionCost').val()) || 0;
            return subtotal + bankCharge + travelExpense + otherExpense + inspectionCost;
        }

        function calculateTotalCost() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;

            // --- Margin: honour either user-entered amount or percent
            let marginPercent = parseFloat($('#marginPercent').val());
            marginPercent = isNaN(marginPercent) ? 0 : marginPercent;
            let marginAmount = parseFloat($('#marginAmount').val());
            marginAmount = isNaN(marginAmount) ? (subtotal * marginPercent / 100) : marginAmount;
            // keep fields in sync but don't force formatting here
            $('#marginPercent').val((marginPercent || (subtotal ? (marginAmount / subtotal) * 100 : 0)).toString());

            // --- Freight: base includes marginAmount
            let freightPercent = parseFloat($('#freightPercent').val());
            freightPercent = isNaN(freightPercent) ? 0 : freightPercent;
            let freightAmount = parseFloat($('#freightAmount').val());
            const freightBase = subtotal + marginAmount;
            freightAmount = isNaN(freightAmount) ? (freightBase * freightPercent / 100) : freightAmount;
            $('#freightPercent').val((freightPercent || (freightBase ? (freightAmount / freightBase) * 100 : 0)).toString());

            // --- Other expenses
            const bankCharge = parseFloat($('#bankCharge').val()) || 0;
            const travelExpense = parseFloat($('#travelExpense').val()) || 0;
            const otherExpense = parseFloat($('#otherExpense').val()) || 0;
            const inspectionCost = parseFloat($('#inspectionCost').val()) || 0;

            // --- Total before additional discount
            const totalBeforeAddDiscount = subtotal + marginAmount + freightAmount + bankCharge + travelExpense + otherExpense + inspectionCost;

            // --- Additional discount: two-way handling
            let addDiscPercent = parseFloat($('#qtn_add_discount_percentage').val());
            addDiscPercent = isNaN(addDiscPercent) ? 0 : addDiscPercent;
            let addDiscAmount = parseFloat($('#qtn_add_discount_amount').val());
            addDiscAmount = isNaN(addDiscAmount) ? (totalBeforeAddDiscount * addDiscPercent / 100) : addDiscAmount;
            $('#qtn_add_discount_percentage').val((addDiscPercent || (totalBeforeAddDiscount ? (addDiscAmount / totalBeforeAddDiscount) * 100 : 0)).toString());
            $('#qtn_add_discount_amount').val(addDiscAmount.toString());

            // --- Final totals
            const totalAfterDiscount = totalBeforeAddDiscount - addDiscAmount;
            $('#totalCost, #total_before_vat').val(totalAfterDiscount.toFixed(2));

            updateVatAndGrandTotal();
        }


        function updateVatAndGrandTotal() {
            const applyVat = $('#qtn_apply_vat').is(':checked');
            const vatPercent = parseFloat($('#qtn_vat_percentage').val()) || 0;
            const totalBeforeVat = parseFloat($('#total_before_vat').val()) || 0;
            let vatAmount = 0,
                grandTotal = totalBeforeVat;
            if (applyVat) {
                vatAmount = totalBeforeVat * vatPercent / 100;
                grandTotal += vatAmount;
            }
            $('#qtn_vat_amount').val(vatAmount.toFixed(2));
            $('#qtn_grand_total').val(grandTotal.toFixed(2));
        }

        // -------- Event Listeners for Calculations --------
        $('#qtn_apply_vat').on('change', updateVatAndGrandTotal);
        $('#qtn_vat_percentage').on('input', updateVatAndGrandTotal);
        $('#qtn_add_discount_percentage').on('input', calculateTotalCost);
        $(document).on('input', '.amount, #marginPercent, #freightPercent, #bankCharge, #travelExpense, #otherExpense, #inspectionCost', calculateSubtotal);

        // ----------- NEW PART: Two-way conversions -----------

        // Margin % → Amount
        $('#marginPercent').on('input', function() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const percent = parseFloat($(this).val()) || 0;
            const amount = subtotal * percent / 100;
            $('#marginAmount').val(amount); // raw value; format on blur
            calculateTotalCost();
        });

        // Margin Amount → %
        $('#marginAmount').on('input', function() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const amount = parseFloat($(this).val()) || 0;
            const percent = subtotal ? (amount / subtotal) * 100 : 0;
            $('#marginPercent').val(percent.toFixed(6).replace(/\.?0+$/, '')); // keep raw precision while typing
            calculateTotalCost();
        });

        // Freight % → Amount (note: base includes margin)
        $('#freightPercent').on('input', function() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const marginAmount = parseFloat($('#marginAmount').val()) || (subtotal * (parseFloat($('#marginPercent').val()) || 0) / 100);
            const base = subtotal + marginAmount;
            const percent = parseFloat($(this).val()) || 0;
            const amount = base * percent / 100;
            $('#freightAmount').val(amount);
            calculateTotalCost();
        });

        // Freight Amount → %
        $('#freightAmount').on('input', function() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const marginAmount = parseFloat($('#marginAmount').val()) || (subtotal * (parseFloat($('#marginPercent').val()) || 0) / 100);
            const base = subtotal + marginAmount;
            const amount = parseFloat($(this).val()) || 0;
            const percent = base ? (amount / base) * 100 : 0;
            $('#freightPercent').val(percent.toFixed(6).replace(/\.?0+$/, ''));
            calculateTotalCost();
        });

        // Additional Discount % → Amount
        $('#qtn_add_discount_percentage').on('input', function() {
            const totalBefore = getBaseTotal() + (parseFloat($('#marginAmount').val()) || 0) + (parseFloat($('#freightAmount').val()) || 0);
            const percent = parseFloat($(this).val()) || 0;
            const amount = totalBefore * percent / 100;
            $('#qtn_add_discount_amount').val(amount);
            calculateTotalCost();
        });

        // Additional Discount Amount → %
        $('#qtn_add_discount_amount').on('input', function() {
            const totalBefore = getBaseTotal() + (parseFloat($('#marginAmount').val()) || 0) + (parseFloat($('#freightAmount').val()) || 0);
            const amount = parseFloat($(this).val()) || 0;
            const percent = totalBefore ? (amount / totalBefore) * 100 : 0;
            $('#qtn_add_discount_percentage').val(percent.toFixed(6).replace(/\.?0+$/, ''));
            calculateTotalCost();
        });

    });


    // ===== PRODUCT ROW =====
    /* function generateProductRow(mainId, subId, rowId = Date.now()) {
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
            </select>
            <textarea name="products[${mainId}][${subId}][${rowId}][description]" class="form-control form-control-sm estimation_edit mt-2" placeholder="Enter product description"></textarea>
        </td>

        <td><select name="products[${mainId}][${subId}][${rowId}][unit]" class="form-control form-control-sm">${unitOptions}</select></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][quantity]" class="form-control form-control-sm qty"></td>
        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][unit_price]" class="form-control form-control-sm unitPrice"></td>

        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][discount_percent]" class="form-control discountPercent" placeholder="%">
                <input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][discount_amount]" class="form-control discountAmount" placeholder="Amt">
            </div>
        </td>

        <td><input type="number" step="0.01" name="products[${mainId}][${subId}][${rowId}][amount]" class="form-control form-control-sm amount" readonly></td>
        <td><button type="button" class="btn btn-success btn-sm addProductRow" data-main="${mainId}" data-sub="${subId}">+</button></td>
    </tr>`;
        }*/

       function initProductEditors() {
    document.querySelectorAll('.product_editor').forEach(function(textarea) {
        if (!textarea.classList.contains('ckeditor-initialized')) {
            ClassicEditor.create(textarea)
                .then(editor => {
                    textarea.classList.add('ckeditor-initialized');
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
}

  // Prevent accidental form submit on Enter
$(document).on("keydown", "form input, form select", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        return false;
    }
});

</script>