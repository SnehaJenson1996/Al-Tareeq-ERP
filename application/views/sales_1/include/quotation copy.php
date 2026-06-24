<?php if (!empty($enquiry_data['enquiry_status']) && ($enquiry_data['enquiry_status'] == 5)): ?>    
    <form action="<?= base_url()?>index.php/Sales/add_quotation" method="post">  
    <input type="hidden" name="enquiry_id" value="<?= isset($enquiry_id)?$enquiry_id:"" ?>">
    <input type="hidden" name="estimation_id" value="<?= isset($master['estimation_id'])?$master['estimation_id']:"" ?>">

    <div class="row">
            <!-- Enquiry Code -->
            <div class="col-md-6">
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Enquiry Code:</label>
                    <div class="col-sm-8">
                        <input type="text" id="enquiry_code" name="enquiry_code"
                            value="<?= $enquiry_data['enquiry_code'] ?>" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <!-- Enquiry Branch -->
            <div class="col-md-6">
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Enquiry Branch:</label>
                    <div class="col-sm-8">
                        <input type="text" id="branch_name" name="branch_name"
                            value="<?= $enquiry_data['branch_name'] ?>" class="form-control" readonly>
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
                        value="<?= $enquiry_data['project_name'] ?>" class="form-control" readonly>
                </div>
            </div>
        </div>

        <!-- Customer Name -->
        <div class="col-md-6">
            <div class="form-group row align-items-center">
                <label class="col-sm-4 col-form-label">Customer:</label>
                <div class="col-sm-8">
                    <input type="text" id="customer_name" name="customer_name"
                        value="<?= $enquiry_data['customer_name'] ?>" class="form-control" readonly>
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
                        value="<?= $quotation_code ?>" class="form-control" readonly>
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

    <?php if (isset($estimation)):$i=0;?>
            <?php foreach ($estimation as  $main ): ?>
                <div id="main_heading_block_<?= $i ?>" class="border p-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                            <div style="width: 40%;">
                                <label>Main Heading</label>
                                <input type="text"
                                        name="main_heading[<?= $i ?>]"
                                        value="<?= $main['main_heading'] ?>"
                                        class="estimation_edit form-control"
                                        placeholder="Enter Main Heading"
                                        >
                            </div>
                            <div style="width: 45%;">
                                    <label>Details</label>
                                    <textarea name="main_details[<?= $i ?>]"
                                            class="estimation_edit form-control"
                                            placeholder="Enter Details">
                                            <?= $main['main_details'] ?></textarea>
                            </div>
                               
                                
                    </div>
                            
                </div>
                <?php $j = 0; 
                        foreach ($main['sub_headings'] as $sub): ?>
                                <div class="border p-2 mb-2 subHeadingContainer" data-main="<?= $i ?>" data-sub="<?= $j ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <input type="text" name="sub_heading[<?= $i ?>][<?= $j ?>]" value="<?= $sub['sub_heading'] ?>" class="form-control form-control-sm w-75" placeholder="Enter Sub Heading">
                                    </div>
                            
                                    <table class="table table-bordered qtn_productTable mb-0"  cellspacing="0" width="100%">
                                        <thead>
                                            <tr style="background-color:#f8f9fa;">
                                                <th >Product</th>
                                                <th style="width:100px;">Unit</th>
                                                <th style="width:100px;">Qty</th>
                                                <th style="width:120px;">Unit Price</th>
                                                <th style="width:120px;">Amount</th>
                                                <th style="width:60px;">discount %</th>
                                                <th style="width:60px;">Taxable amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $k = 0; foreach ($sub['products'] as $prod): ?>
                                            <tr>
                                                <td>
                                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]" 
                                                        class="form-control form-control-sm estimation_edit product-select">
                                                    <option value="">-- Select Product --</option>
                                                    <?php foreach ($all_products as $p): ?>
                                                        <option value="<?= $p->item_id  ?>" 
                                                            <?= ($p->item_id  == $prod['product_id']) ? 'selected' : '' ?>>
                                                            <?= $p->item_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                    </select><br><br>

                                                    <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]" class="form-control form-control-sm estimation_edit"><?= $prod['product_description'] ?></textarea>
                                                </td>
                                        <td>
                                            <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]" class="form-control estimation_edit ">
                                                <option value="">-- Select Unit --</option>
                                                <?php foreach($active_units as $unit): ?>
                                                    <option value="<?= $unit->unit_id?>" 
                                                        <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>>
                                                        <?= $unit->unit_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        
                                        <td><input type="number" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]" value="<?= $prod['quantity'] ?>" class="form-control quotation_edit qtn_qty" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]" value="<?= $prod['unit_price'] ?>" class="form-control quotation_edit qtn_unitPrice" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]" value="<?= $prod['amount'] ?>" class="form-control quotation_edit qtn_amount" readonly></td>
                                        <td>
                                            <input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][dicount_percentage]" value="" class="form-control quotation_edit qtn_discount_percentage"><br>
                                            <input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][dicount_amount]" value="" class="form-control quotation_edit qtn_discount_amount" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][taxable_amount]" value="" class="form-control quotation_edit qtn_taxable_amount" readonly>

                                        </td>
                                        </tr>
                                        <?php $k++; endforeach; ?>
                                    </tbody>
                                </table>
                           
                    <?php $j++; endforeach; ?>
                <!-- </div> -->
            <?php $i++; 
        endforeach; ?>
        <?php endif; ?>
    <!-- </div> -->
  <!-- </div>     -->


<!-- Summary -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sub Total</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control" value="<?= isset($master['sub_total'])?$master['sub_total']:"" ?>" >
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                            <div class="col-sm-4">
                                <input type="text" name="qtn_discount_percentage" id="qtn_discount_percentage" class="form-control " value="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="qtn_discount_amount" id="qtn_discount_amount" class="form-control " value="" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Apply VAT</label>
                            <div class="col-sm-8">
                                <input type="checkbox" id="apply_vat" name="apply_vat">
                                <input type="number" id="vat_percentage" value="5" class="form-control mt-2" style="width:100px;" readonly> 
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">VAT Amount</label>
                            <div class="col-sm-8">
                                <input type="text" name="vat_amount" id="vat" class="form-control estimation_edit" value="" readonly>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Estimation Cost</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_estimation_cost" id="estimation_cost" class="form-control font-weight-bold" value="<?= isset($master['grand_total'])?$master['grand_total']:"" ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">total before vat</label>
                            <div class="col-sm-8">
                                <input type="text" name="total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="<?= isset($master['grand_total'])?$master['grand_total']:"" ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Grand  Total</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="<?= isset($master['sub_total'])?$master['sub_total']:"" ?>" >
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row 1 -->
                <div class="form-group row">
                        <!-- Payment Term -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Payment Term</label>
                            <input type="text" name="payment_term" id="payment_term" 
                                class="form-control estimation_edit" 
                                value="<?= isset($master['payment_term'])?$master['payment_term']:"" ?>">
                        </div>
                         <!-- Validity -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Validity</label>
                            <input type="text" name="validity" id="validity" 
                                class="form-control estimation_edit" 
                                value="<?= isset($master['validity'])?$master['validity']:"" ?>">
                        </div>
                       
                </div>

                <!-- Row 2 -->
                <div class="form-group row">
                       
                         <!-- Delivery Term (CKEditor) -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Delivery Term</label>
                            <textarea name="delivery_term" id="delivery_term" 
                                    class="form-control estimation_edit"><?= isset($master['delivery_term'])?$master['delivery_term']:"" ?></textarea>
                        </div>

                        <!-- Terms & Conditions (CKEditor) -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Terms & Conditions</label>
                            <textarea name="terms_condition" id="terms_condition" 
                                    class="form-control estimation_edit"><?= isset($master['terms_condition'])?$master['terms_condition']:"" ?></textarea>
                        </div>
                    </div>               

                <!-- Centered Button -->
                <div class="row justify-content-center mt-3">
                    <button type="submit" class="btn btn-success">Create Quotation</button>
                </div>

            </div>
        </div>
    </form>
<?php elseif (!empty($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] >= 6): ?>
   <?php $this->load->view('sales/include/quotation_edit.php') ?>
<?php endif; ?>
 </div>
 <script>
    $(document).on("input", ".qtn_discount_percentage", function () {
    let row = $(this).closest("tr");

    // Get values
    let amount = parseFloat(row.find(".qtn_amount").val()) || 0;
    let discountPercentage = parseFloat($(this).val()) || 0;

    // Calculate
    let discountAmount = (amount * discountPercentage) / 100;
    let taxableAmount = amount - discountAmount;

    // Set values
    row.find(".qtn_discount_amount").val(discountAmount.toFixed(2));
    row.find(".qtn_taxable_amount").val(taxableAmount.toFixed(2));
});

$(document).ready(function () {
    // Bind events when DOM is ready
    $(".qtn_qty, .qtn_unitPrice, #qtn_discount_percentage, #qtn_vat_percentage").on("input", function () {
        Qtn_calculateTotals();
    });

    $("#qtn_apply_vat").on("change", function () {
        Qtn_calculateTotals();
    });

    // Initial calculation
   // Qtn_calculateTotals();
});

//$(document).ready(function () {
   
    function Qtn_calculateTotals() {
        let subtotal = 0;
        // Loop through each product row
        $(".qtn_productTable tbody tr").each(function () {
            let qty = parseFloat($(this).find(".qtn_qty").val()) || 0;
            let price = parseFloat($(this).find(".qtn_unitPrice").val()) || 0;
            let amount = qty * price;

            //Update amount field
            $(this).find(".qtn_amount").val(amount.toFixed(2));

            subtotal += amount;
        });

        // Update Subtotal
        $("#qtn_sub_total").val(subtotal.toFixed(2));

        // Discount
        let discountPercent = parseFloat($("#qtn_discount_percentage").val()) || 0;
        let discountAmount = subtotal * (discountPercent / 100);
        $("#qtn_discount_amount").val(discountAmount.toFixed(2));

        let totalAfterDiscount = subtotal - discountAmount;

        // VAT
        let applyVat = $("#qtn_apply_vat").is(":checked");
        let vatPercent = parseFloat($("#qtn_vat_percentage").val()) || 0;
        let vatAmount = applyVat ? totalAfterDiscount * (vatPercent / 100) : 0;
        $("#vat").val(vatAmount.toFixed(2));

        // Total before VAT
        $("#total_before_vat").val(totalAfterDiscount.toFixed(2));

        // Grand Total
        let grandTotal = totalAfterDiscount + vatAmount;
        $("#qtn_grand_total").val(grandTotal.toFixed(2));
    }

    // Trigger recalculation on qty / unit price / discount / VAT changes
    $(document).on("input", ".qtn_qty, .qtn_unitPrice, #qtn_discount_percentage, #qtn_vat_percentage", Qtn_calculateTotals);
    $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);



 </script>