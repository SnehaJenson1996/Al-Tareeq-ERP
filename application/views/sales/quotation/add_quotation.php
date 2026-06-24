<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }
</style>

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="clearfix"></div>

            </div>
            <div class="x_content">

                <form action="<?= base_url() ?>index.php/Sales/save_quotation" method="post">
                    <input type="hidden" name="estimation_id" id="estimation_id" value="">

                    <input type="hidden" name="quotation_customer" id="quotation_customer">
                    <input type="hidden" name="quotation_branch_id" id="quotation_branch_id">
                    <input type="hidden" name="project_name_hidden" id="project_name_hidden">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Select Enquiry:</label>
                        <div class="col-sm-4">
                            <select name="enquiry_id" id="enquiry_select" class="form-control select2">
                                <option value="">-- Select Enquiry --</option>
                                <?php foreach ($enquiry_list as $e): ?>
                                    <option value="<?= $e->enquiry_id ?>">
                                        <?= $e->enquiry_code ?> - <?= $e->project_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Enquiry Code -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Enquiry Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="enquiry_code" name="enquiry_code"
                                        value=""
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Enquiry Branch -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Enquiry Branch:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="branch_name" name="branch_name"
                                        value=""
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Project Name -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Project Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="project_name" name="project_name"
                                        value="<?= isset($enquiry_data['project_name']) ? $enquiry_data['project_name'] : "" ?>"
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Name -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Customer:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="customer_name" name="customer_name"
                                        value="<?= isset($enquiry_data['customer_name']) ? $enquiry_data['customer_name'] : "" ?>"
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quotation Code -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Quotation Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="quotation_code" name="quotation_code"
                                        value="<?= isset($quotation_code) ? $quotation_code : "" ?>"
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Quotation Date -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Quotation Date:</label>
                                <div class="col-sm-8">
                                    <input type="date" id="quotation_date" name="quotation_date"
                                        value="<?= date('Y-m-d') ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="quotation_container" class="mt-4"></div>

                    <!-- Summary -->
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Sub Total</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control qtn_sub_total" value="<?= isset($master['sub_total']) ? $master['sub_total'] : "" ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Other Charges</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="other_charges" id="other_charges" class="form-control other_charges" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="qtn_add_discount_percentage" id="qtn_add_discount_percentage" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="qtn_add_discount_amount" id="qtn_add_discount_amount" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Apply VAT</label>
                                        <div class="col-sm-8">
                                            <input type="checkbox" id="qtn_apply_vat" name="qtn_apply_vat">
                                            <input type="number" name="qtn_vat_percentage" id="qtn_vat_percentage" value="5" class="form-control mt-2" style="width:100px;" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">VAT Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_vat_amount" id="qtn_vat_amount" class="form-control estimation_edit" value="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Estimation Cost</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_estimation_cost" id="estimation_cost" class="form-control font-weight-bold" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Total Before VAT</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Grand Total</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment and Terms -->
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Payment Term</label>
                                    <textarea name="payment_term" id="payment_term"
    class="form-control estimation_edit"><?= isset($master['payment_term']) ? $master['payment_term'] : "" ?></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Validity</label>
                                    <input type="text" name="validity" id="validity"
                                        class="form-control estimation_edit"
                                        value="<?= isset($master['validity']) ? $master['validity'] : "" ?>">
                                </div>
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
                                <div class="col-sm-6">
                                    <label class="col-form-label">Delivery Term</label>
                                    <textarea name="delivery_term" id="delivery_term"
                                        class="form-control estimation_edit"></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-form-label">Terms & Conditions</label>
                                    <textarea name="terms_condition" id="terms_condition"
                                        class="form-control estimation_edit"></textarea>
                                </div>
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


                            <div class="row justify-content-center mt-3">
                                <button type="submit" class="btn btn-success" name="action" value="quotation">Create Quotation</button>
                                <!-- <button type="submit" class="btn btn-primary ml-2" name="action" value="sales_order">Save & Create Sales Order</button> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('delivery_term');
  var termsEditor = CKEDITOR.replace('terms_condition');
  CKEDITOR.replace('payment_term', {
    height: 120
});

CKEDITOR.replace('notes', {
    height: 120
});

termsEditor.on('instanceReady', function () {
    if (termsEditor.getData().trim() === '') {
        termsEditor.setData(`We trust that our proposal meets your requirements and look forward to the opportunity to execute your order with the highest priority and
commitment.<br><br>
Should you require any further clarification or additional information, please do not hesitate to contact our team at any time. We remain at
your disposal.<br><br>
We assure you of our best services and continuous support at all times.`);
    }
});
    $(function() {

        // --- Calculate Quotation Totals ---
        function Qtn_calculateTotals() {
            let subtotal = 0;

            // Loop through all product rows
            $(".qtn_productTable tbody tr").each(function() {
                const $row = $(this);

                const qty = parseFloat($row.find(".qtn_qty").val()) || 0;
                const price = parseFloat($row.find(".qtn_unitPrice").val()) || 0;
                const lineDiscount = parseFloat($row.find(".qtn_discount_amount").val()) || 0;

                // Calculate line amount and taxable
                const amount = qty * price;
                const validDiscount = lineDiscount > amount ? amount : lineDiscount;
                const taxable = amount - validDiscount;

                // Update row fields
                $row.find(".qtn_amount").val(amount.toFixed(2));
                $row.find(".qtn_taxable_amount").val(taxable.toFixed(2));

                // Add to subtotal
                subtotal += taxable;
            });

            // Display subtotal
            $(".qtn_sub_total").val(subtotal.toFixed(2));

            // Other inputs
            const otherCharges = parseFloat($("#other_charges").val()) || 0;
            const addDiscountPercent = parseFloat($("#qtn_add_discount_percentage").val()) || 0;

            // Calculate additional discount
            const addDiscountAmount = $("#qtn_add_discount_amount").val();

            // Total before VAT
            const totalBeforeVat = subtotal + otherCharges - addDiscountAmount;
            $("#total_before_vat").val(totalBeforeVat.toFixed(2));

            // VAT calculation
            const isVatApplied = $("#qtn_apply_vat").is(":checked");
            const vatPercent = parseFloat($("#qtn_vat_percentage").val()) || 0;
            const vatAmount = isVatApplied ? (totalBeforeVat * vatPercent) / 100 : 0;
            $("#qtn_vat_amount").val(vatAmount.toFixed(2));

            // Grand total
            const grandTotal = totalBeforeVat + vatAmount;
            $("#qtn_grand_total").val(grandTotal.toFixed(2));
        }


        // --- Event listeners for totals ---
        // $(document).on("input", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, #qtn_add_discount_percentage, #qtn_vat_percentage", Qtn_calculateTotals);
        $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);
        // Qtn_calculateTotals();

        $(document).on("input", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, .qtn_discount_percentage, #qtn_vat_percentage", function() {
            const $row = $(this).closest("tr");

            // Calculate discount and taxable

            convertDiscount($row);

            // Now update totals
            Qtn_calculateTotals();
        });
        $('#qtn_add_discount_percentage').on('blur', function() {
            if ($(this).val() !== "") {
                $('#qtn_add_discount_amount').val(''); // empty instead of 0 avoids confusion
                convertAdditionalDiscount();
            }
        });

        $('#qtn_add_discount_amount').on('blur', function() {
            if ($(this).val() !== "") {
                $('#qtn_add_discount_percentage').val(''); // clear instead of 0
                convertAdditionalDiscount();
            }
        });


        // --- Load enquiry details via AJAX ---
        $(function() {

            const selectedEnquiry = "<?= $selected_enquiry_id ?>";

            // --- Load enquiry details via AJAX ---
            $("#enquiry_select").on("change", function() {
                let enquiry_id = $(this).val();
                if (!enquiry_id) {
                    $("#quotation_container").empty();
                    return;
                }

                $.ajax({
                    url: "<?= base_url('index.php/Sales/get_enquiry_details') ?>",
                    type: "POST",
                    data: {
                        enquiry_id
                    },
                    dataType: "json",
                    beforeSend: () =>
                        $("#quotation_container").html("<p>Loading data...</p>"),
                    success: function(res) {
                        let estimationCost = parseFloat(res.estimation_cost) || 0;
                        let subTotal = parseFloat(res.sub_total) || 0;
                        let otherCharges = estimationCost - subTotal;

                        $("#enquiry_code").val(res.enquiry_code);
                        $("#branch_name").val(res.branch_name);
                        $("#project_name").val(res.project_name);
                        $("#customer_name").val(res.customer_name);

                        $("#quotation_customer").val(res.customer_id);
                        $("#quotation_branch_id").val(res.branch_id);
                        $("#project_name_hidden").val(res.project_name);
                        $("#quotation_code").val(res.quotation_code);
                        $("#qtn_sub_total").val(subTotal.toFixed(2));
                        $("#estimation_cost").val(estimationCost.toFixed(2));
                        $("#total_before_vat").val(estimationCost.toFixed(2));
                        $("#other_charges").val(otherCharges.toFixed(2));
                        $("#qtn_grand_total").val(estimationCost.toFixed(2));
                        $("#estimation_id").val(res.estimation_id);

                        $("#quotation_container").html(res.html);



// =============================
// ADD THIS CODE BELOW
// =============================

setTimeout(function () {

    document.querySelectorAll('#quotation_container textarea.product_editor').forEach(function(el){

        if (!el.id) {
            el.id = 'editor_' + Math.random().toString(36).substr(2, 9);
        }

        if (CKEDITOR.instances[el.id]) {
            CKEDITOR.instances[el.id].destroy(true);
        }

        CKEDITOR.replace(el.id, {
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] }
            ],
            height: 100
        });

    });

}, 300);
                    }
                });
            });

            // ✅ NOW trigger after handler exists
            if (selectedEnquiry) {
                $("#enquiry_select")
                    .val(selectedEnquiry)
                    .trigger("change");
            }
        });

        // --- Quotation Date Validation ---
        $("form").on("submit", function(e) {
            let quotationDate = $("#quotation_date").val().trim();
            if (!quotationDate) {
                alert("⚠️ Quotation Date is required.");
                $("#quotation_date").focus();
                e.preventDefault();
            }
        });



        function convertDiscount($row) {
            const qty = parseFloat($row.find(".qtn_qty").val()) || 0;
            const price = parseFloat($row.find(".qtn_unitPrice").val()) || 0;
            const amount = qty * price;

            let discAmt = parseFloat($row.find(".qtn_discount_amount").val()) || 0;
            let discPer = parseFloat($row.find(".qtn_discount_percentage").val()) || 0;

            // --- If user enters % ---
            if ($row.find(".qtn_discount_percentage").is(":focus")) {
                discAmt = (amount * discPer) / 100;
                $row.find(".qtn_discount_amount").val(discAmt.toFixed(2));
            }

            // --- If user enters Amount ---
            if ($row.find(".qtn_discount_amount").is(":focus")) {
                discPer = amount ? (discAmt / amount) * 100 : 0;
                $row.find(".qtn_discount_percentage").val(discPer.toFixed(2));
            }

            // --- Prevent over discount ---
            if (discAmt > amount) {
                discAmt = amount;
                $row.find(".qtn_discount_amount").val(amount.toFixed(2));
                $row.find(".qtn_discount_percentage").val("100.00");
            }

            // --- Update taxable ---
            const taxable = amount - discAmt;
            $row.find(".qtn_taxable_amount").val(taxable.toFixed(2));
        }

        function convertAdditionalDiscount() {
            const total_before_vat = parseFloat($("#total_before_vat").val()) || 0;
            let per = parseFloat($("#qtn_add_discount_percentage").val()) || 0;
            let amt = parseFloat($("#qtn_add_discount_amount").val()) || 0;

            if (per > 0) {
                // User typed percentage → calculate amount
                amt = (total_before_vat * per) / 100;
                $("#qtn_add_discount_amount").val(amt.toFixed(2));
            } else if (amt != 0) {
                // User typed amount → calculate percentage
                per = total_before_vat ? (amt / total_before_vat) * 100 : 0;
                $("#qtn_add_discount_percentage").val(per.toFixed(2));
            }
            // Recalculate totals
            Qtn_calculateTotals();
        }
    });

    // Prevent accidental form submit on Enter
$(document).on("keydown", "form input, form select", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        return false;
    }
});

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%'
    });
});
</script>