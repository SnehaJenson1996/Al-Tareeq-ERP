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

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Select Enquiry:</label>
                        <div class="col-sm-4">
                            <select name="enquiry_id" id="enquiry_select" class="form-control">
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
                                        value="" class="form-control">
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
                                            <input type="text" name="qtn_add_discount_percentage" id="qtn_add_discount_percentage" class="form-control" value="">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="qtn_add_discount_amount" id="qtn_add_discount_amount" class="form-control" value="" readonly>
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
                                    <input type="text" name="payment_term" id="payment_term"
                                        class="form-control estimation_edit"
                                        value="<?= isset($master['payment_term']) ? $master['payment_term'] : "" ?>">
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

                            <div class="row justify-content-center mt-3">
                                <button type="submit" class="btn btn-success" name="action" value="quotation">Create Quotation</button>
                                <button type="submit" class="btn btn-primary ml-2" name="action" value="sales_order">Save & Create Sales Order</button>
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
    CKEDITOR.replace('terms_condition');

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
            const addDiscountAmount = ((subtotal + otherCharges) * addDiscountPercent) / 100;
            $("#qtn_add_discount_amount").val(addDiscountAmount.toFixed(2));

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
        $(document).on("input", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, #qtn_add_discount_percentage, #qtn_vat_percentage", Qtn_calculateTotals);
        $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);
        Qtn_calculateTotals();

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
                beforeSend: () => $("#quotation_container").html("<p>Loading data...</p>"),
                success: function(res) {
                    let estimationCost = parseFloat(res.estimation_cost) || 0;
                    let subTotal = parseFloat(res.sub_total) || 0;
                    let otherCharges = estimationCost - subTotal;

                    $("#enquiry_code").val(res.enquiry_code);
                    $("#branch_name").val(res.branch_name);
                    $("#project_name").val(res.project_name);
                    $("#customer_name").val(res.customer_name);
                    $("#quotation_code").val(res.quotation_code);
                    $("#qtn_sub_total").val(subTotal.toFixed(2));
                    $("#estimation_cost").val(estimationCost.toFixed(2));
                    $("#total_before_vat").val(estimationCost.toFixed(2));
                    $("#other_charges").val(otherCharges.toFixed(2));
                    $("#qtn_grand_total").val(estimationCost.toFixed(2));
                    $("#estimation_id").val(res.estimation_id);

                    $("#quotation_container").html(res.html);
                    $('#quotation_container .select2').select2({
                        width: '100%',
                        placeholder: "-- Select --",
                        allowClear: true
                    });
                },
                error: () => $("#quotation_container").html("<div class='alert alert-danger'>Error loading data.</div>")
            });
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

    });
</script>