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
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

            </div>
            <div class="x_content">
                <?php if ($qtn_master['quotation_type'] == "sales") { ?>
                <form action="<?= base_url('index.php/Sales/Resurvey_from_qtn/' . $Quotation_id) ?>" method="post">
                    <div class="row mb-3">
                        <button type="submit" class="btn btn-warning">Re survey Enquiry</button>
                    </div>
                    <input type="hidden" name="enquiry_id_res" value="<?= $qtn_master['enquiry_id'] ?>">
                    <input type="hidden" name="estimation_id_res" value="<?= $qtn_master['estimation_id'] ?>">
                    <!-- <input type="hidden" name="quotation_id_res"  value="<?= $qtn_master['quotation_id'] ?>"> -->
                </form>
<?php } ?>
                <form action="<?= base_url('index.php/Sales/update_quotation') ?>" method="post">
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="enquiry_id" class="enquiry_id" value="<?= $qtn_master['enquiry_id'] ?>">
                    <input type="hidden" name="estimation_id" class="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">
                    <input type="hidden" name="quotation_id" class="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">

                    <!-- Action Select -->
                    <!-- <div class="row mb-3">
                        <div class="col-md-12 d-flex justify-content-end align-items-center">
                            <div class="form-group mb-0">
                                <select class="select2 form-control" name="Action_quotation" id="Action_quotation" style="width:200px;">
                                    <option value="">--Select Action--</option>
                                    <?php if (isset($qtn_master['grand_total'])): ?>
                                        <?php if ($qtn_master['grand_total'] > 10000): ?>
                                            <?php if (
                                                has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E')
                                                && isset($qtn_master['aproval'])
                                                && $qtn_master['aproval'] == 1
                                            ): ?>
                                                <option value="edit">Edit</option>
                                            <?php endif; ?>
                                            <?php if (has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E')): ?>
                                                <option value="approve">Approve</option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if (has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E')): ?>
                                                <option value="edit">Edit</option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                   
                                </select>
                            </div>
                        </div>
                    </div> -->


                    <!-- Quotation Revisions -->
                    <!-- <div class="row mt-3">
                        <div class="col-12 d-flex flex-wrap">
                            <?php if (!empty($qtn_revisions)): ?>
                                <?php foreach ($qtn_revisions as $rev): ?>
                                    <a href="<?= base_url('Sales/quotation_details/' . $rev->qtn_id) ?>" class="btn btn-outline-primary mr-2 mb-2">
                                        Revision <?= $rev->quotation_revision ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No revisions found.</p>
                            <?php endif; ?>
                        </div>
                    </div> -->

                    <!-- Basic Details Row -->
                    <?php if ($qtn_master['quotation_type'] == "sales") { ?>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Enquiry Code:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="enquiry_code" name="enquiry_code" value="<?= $qtn_master['enquiry_code'] ?>" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-4 col-form-label">Enquiry Branch:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="branch_name" name="branch_name" value="<?= $qtn_master['branch_name'] ?>" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Project Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="project_name" name="project_name" value="<?= $qtn_master['project_name'] ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Customer:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="customer_name" name="customer_name" value="<?= $qtn_master['customer_name'] ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Quotation Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="quotation_code" name="quotation_code" value="<?= $qtn_master['quotation_code'] ?>" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-4 col-form-label">Quotation Date:</label>
                                <div class="col-sm-8">
                                    <input type="date" id="quotation_date" name="quotation_date" value="<?= date("Y-m-d", strtotime($qtn_master['quotation_date'])) ?>" class="form-control quotation_edit">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Heading & Sub Headings -->
                    <?php if (isset($qtn_details)): $i = 0; ?>
                        <?php foreach ($qtn_details as $main): ?>
                            <div id="main_heading_block_<?= $i ?>" class="border p-2 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div style="width: 40%;">
                                        <label>Main Heading</label>
                                        <input type="text" name="main_heading[<?= $i ?>]" value="<?= $main['main_heading'] ?>" class="estimation_edit form-control" placeholder="Enter Main Heading" readonly>
                                    </div>
                                    <div style="width: 45%;">
                                        <label>Details</label>
                                        <textarea name="main_details[<?= $i ?>]" class="estimation_edit form-control" placeholder="Enter Details" readonly><?= $main['main_details'] ?></textarea>
                                    </div>
                                </div>

                                <?php $j = 0;
                                foreach ($main['sub_headings'] as $sub): ?>
                                    <div class="border p-2 mb-2 subHeadingContainer" data-main="<?= $i ?>" data-sub="<?= $j ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" name="sub_heading[<?= $i ?>][<?= $j ?>]" value="<?= $sub['sub_heading'] ?>" class="form-control form-control-sm w-75" placeholder="Enter Sub Heading" readonly>
                                        </div>
                                        <table class="table table-bordered qtn_productTable mb-0">
                                            <thead>
                                                <tr style="background-color:#f8f9fa;">
                                                    <th>Product</th>
                                                    <th style="width:100px;">Unit</th>
                                                    <th style="width:100px;">Qty</th>
                                                    <th style="width:120px;">Unit Price</th>
                                                    <th style="width:120px;">Amount</th>
                                                    <th style="width:120px;">Discount</th>
                                                    <th style="width:120px;">Taxable</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $k = 0;
                                                foreach ($sub['products'] as $prod): ?>
                                                    <tr>
                                                        <td>
                                                            <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]" class="form-control form-control-sm product-select" readonly>
                                                                <option value="">-- Select Product --</option>
                                                                <?php foreach ($all_products as $p): ?>
                                                                    <option value="<?= $p->item_id ?>" <?= ($p->item_id == $prod['product_id']) ? 'selected' : '' ?>><?= $p->item_name ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <br><br>
                                                            <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]" class="form-control form-control-sm" readonly><?= $prod['product_description'] ?></textarea>
                                                        </td>
                                                        <td>
                                                            <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]" class="form-control" readonly>
                                                                <option value="">-- Select Unit --</option>
                                                                <?php foreach ($active_units as $unit): ?>
                                                                    <option value="<?= $unit->unit_id ?>" <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>><?= $unit->unit_name ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]" value="<?= $prod['quantity'] ?>" class="form-control qtn_qty" readonly></td>
                                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]" value="<?= $prod['unit_price'] ?>" class="form-control quotation_edit qtn_unitPrice"></td>
                                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]" value="<?= $prod['amount'] ?>" class="form-control qtn_amount" readonly></td>
                                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][discount_amount]" value="<?= $prod['dicount_amount'] ?>" class="form-control quotation_edit qtn_discount_amount"></td>
                                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][taxable_amount]" value="<?= $prod['taxable_amount'] ?>" class="form-control quotation_edit qtn_taxable_amount" readonly></td>
                                                    </tr>
                                                <?php $k++;
                                                endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php $j++;
                                endforeach; ?>
                            </div>
                        <?php $i++;
                        endforeach; ?>
                    <?php endif; ?>

                    <!-- Summary Section -->
                    <div class="row justify-content-center mt-3">
                        <div class="col-md-10">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Sub Total</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control quotation_edit qtn_sub_total" value="<?= isset($qtn_master['sub_total']) ? $qtn_master['sub_total'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Other Charges</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="other_charges" id="other_charges" class="form-control other_charges" value="<?= isset($qtn_master['other_charge']) ? $qtn_master['other_charge'] : "0" ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="qtn_add_discount_percentage" id="qtn_add_discount_percentage" class="form-control quotation_edit" value="<?= isset($qtn_master['discount_percentage']) ? $qtn_master['discount_percentage'] : "" ?>">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="qtn_add_discount_amount" id="qtn_add_discount_amount" class="form-control quotation_edit" value="<?= isset($qtn_master['discount_amount']) ? $qtn_master['discount_amount'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Apply VAT</label>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="form-check-input" id="qtn_apply_vat" name="apply_vat" value="1" <?= isset($qtn_master['vat_required']) && $qtn_master['vat_required'] == 1 ? 'checked' : '' ?>>
                                            <input type="number" id="qtn_vat_percentage" value="5" class="form-control mt-2" style="width:100px;" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">VAT Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_vat_amount" id="vat" class="form-control estimation_edit" value="<?= isset($qtn_master['vat_amount']) ? $qtn_master['vat_amount'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Estimation Cost</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_estimation_cost" id="estimation_cost" class="form-control font-weight-bold" value="<?= isset($qtn_master['estimation_amount']) ? $qtn_master['estimation_amount'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Total Before VAT</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="<?= isset($qtn_master['total_before_vat']) ? $qtn_master['total_before_vat'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Grand Total</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="<?= isset($qtn_master['grand_total']) ? $qtn_master['grand_total'] : "" ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment & Terms -->
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Payment Term</label>
                                    <input type="text" name="payment_term" id="payment_term" class="form-control quotation_edit CKEDITOR" value="<?= isset($qtn_master['payment_term']) ? $qtn_master['payment_term'] : "" ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Validity</label>
                                    <input type="text" name="validity" id="validity" class="form-control quotation_edit" value="<?= isset($qtn_master['validity']) ? $qtn_master['validity'] : "" ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Delivery Term</label>
                                    <textarea name="delivery_term" id="delivery_term" class="form-control quotation_edit" readonly><?= isset($qtn_master['delivery_term']) ? $qtn_master['delivery_term'] : "" ?></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Terms & Conditions</label>
                                    <textarea name="terms_condition" id="terms_condition" class="form-control quotation_edit" readonly><?= isset($qtn_master['terms_condition']) ? $qtn_master['terms_condition'] : "" ?></textarea>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-3 action_button_quotation" id="action_button_quotation">
                                <div class="col-md-6 text-center">
                                    <!-- Create Revision Checkbox -->
                                    <div class="form-check form-check-inline me-3">
                                        <input class="form-check-input" type="checkbox" id="create_revision" name="create_revision" value="1">
                                        <label class="form-check-label" for="create_revision" style="font-weight:600;">
                                            Create New Revision
                                        </label>
                                    </div>

                                    <!-- Update Button -->
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </div>
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
    // initialize CKEditor instances (if not already)
    if (typeof CKEDITOR !== 'undefined') {
        try {
            CKEDITOR.replace('delivery_term');
            CKEDITOR.replace('terms_condition');
        } catch (e) {
            // ignore if already replaced
        }
    }

    (function($) {
        // Calculate all totals
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


        // Run on page ready
        $(document).ready(function() {
            // Recalculate initially (useful for edit page populated from server)
            Qtn_calculateTotals();

            // Bind events
            $(document).on("input change", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, #qtn_add_discount_percentage, #other_charges, #qtn_vat_percentage", Qtn_calculateTotals);
            $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);

            // If rows are added dynamically later, recalc when container changes
            // (MutationObserver fallback)
            const container = document.querySelector('#quotation_container');
            if (container) {
                const mo = new MutationObserver(function() {
                    Qtn_calculateTotals();
                });
                mo.observe(container, {
                    childList: true,
                    subtree: true
                });
            }

            // Update CKEditor contents into textareas before submit
            $("form").on("submit", function(e) {
                // 1) Quotation date validation
                let quotationDate = $("#quotation_date").val().trim();
                if (!quotationDate) {
                    alert("⚠️ Quotation Date is required.");
                    $("#quotation_date").focus();
                    e.preventDefault();
                    return;
                }

                // 2) If CKEditor is used, update its textarea before submit
                if (typeof CKEDITOR !== 'undefined') {
                    for (const instanceName in CKEDITOR.instances) {
                        if (CKEDITOR.instances.hasOwnProperty(instanceName)) {
                            CKEDITOR.instances[instanceName].updateElement();
                        }
                    }
                }

                // 3) Recalculate totals once more to ensure final values (in case user changed just before submit)
                Qtn_calculateTotals();
                // allow submit to continue
            });
        });
    })(jQuery);
</script>