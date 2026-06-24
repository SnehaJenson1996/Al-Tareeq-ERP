<form action="<?= base_url('index.php/Sales/update_quotation') ?>" method="post">

<div class="row">
    <div class="col-md-12 d-flex justify-content-end align-items-center">
        <div class="form-group mb-0">
           <select class="select2 form-control" name="Action_quotation" id="Action_quotation" style="width:200px;">
                <option value="">--Select Action--</option>

                <?php if (isset($qtn_master['grand_total'])): ?>

                    <?php if ($qtn_master['grand_total'] > 10000): ?>
                        <!-- Edit only if approval is done -->
                        <?php if (
                            has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E') 
                            && isset($qtn_master['aproval']) 
                            && $qtn_master['aproval'] == 1
                        ): ?>
                            <option value="edit">Edit</option>
                        <?php endif; ?>

                        <!-- Approve option only if > 10000 -->
                        <?php if (has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E')): ?>
                            <option value="approve">Approve</option>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- If <= 10000 → allow edit without approval -->
                        <?php if (has_access($this->session->userdata('user_id'), 'Sales/list_enquiries', 'E')): ?>
                            <option value="edit">Edit</option>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php endif; ?>

                <option value="resurvey">Resurvey</option>
            </select>


        </div>&nbsp;&nbsp;
          <!-- Convert to Sales Order -->
    <button type="button" class="btn btn-success me-2"
        onclick="window.location.href='<?= base_url('index.php/Sales/convert_to_order/' . $qtn_master['quotation_id'] . '/' . $enquiry_data['enquiry_id']) ?>'">
        Convert to Sales Order
    </button>

        <!-- Added margin-left for spacing -->
        <button type="button" class="btn btn-primary ms-2"
             onclick="window.open('<?= base_url('index.php/Sales/print_quotation/' . $qtn_master['quotation_id'] . '/' . $enquiry_data['enquiry_id']) ?>', '_blank');">
            Print
        </button>
    </div>
</div>
    <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
     <input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">
      <input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
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
                        value="<?= $qtn_master['quotation_code'] ?>" class="form-control" readonly>
                </div>
            </div>
        </div>

        <!-- Quotation Date -->
        <div class="col-md-6">
            <div class="form-group row align-items-center">
                <label class="col-sm-4 col-form-label">Quotation Date:</label>
                <div class="col-sm-8">
                    <input type="date" id="quotation_date" name="quotation_date"
                        value="<?= date("Y-m-d", strtotime($qtn_master['quotation_date'])) ?>" class="form-control quotation_edit" readonly>
                </div>
            </div>
        </div>
    </div>
     <?php if (isset($qtn_details)):$i=0;?>
            <?php foreach ($qtn_details as  $main ): ?>
                <div id="main_heading_block_<?= $i ?>" class="border p-2 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                            <div style="width: 40%;">
                                <label>Main Heading</label>
                                <input type="text"
                                        name="main_heading[<?= $i ?>]"
                                        value="<?= $main['main_heading'] ?>"
                                        class="estimation_edit form-control"
                                        placeholder="Enter Main Heading"
                                        readonly >
                            </div>
                            <div style="width: 45%;">
                                    <label>Details</label>
                                    <textarea name="main_details[<?= $i ?>]"
                                            class="estimation_edit form-control"
                                            placeholder="Enter Details" readonly>
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
                                            foreach ($sub['products'] as $prod):
                                            ?>
                                            <tr>
                                                <td>
                                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]" 
                                                        class="form-control form-control-sm  product-select" readonly>
                                                    <option value="">-- Select Product --</option>
                                                    <?php foreach ($all_products as $p): ?>
                                                        <option value="<?= $p->item_id  ?>" 
                                                            <?= ($p->item_id  == $prod['product_id']) ? 'selected' : '' ?>>
                                                            <?= $p->item_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select><br><br>

                                            <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]" class="form-control form-control-sm " readonly><?= $prod['product_description'] ?></textarea>
                                        </td>
                                        <td>
                                            <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]" class="form-control " readonly>
                                                <option value="">-- Select Unit --</option>
                                                <?php foreach($active_units as $unit): ?>
                                                    <option value="<?= $unit->unit_id?>" 
                                                        <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>>
                                                        <?= $unit->unit_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        
                                        <td><input type="number" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]" value="<?= $prod['quantity'] ?>" class="form-control  qtn_qty" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]" value="<?= $prod['unit_price'] ?>" class="form-control  quotation_edit qtn_unitPrice" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]" value="<?= $prod['amount'] ?>" class="form-control  qtn_amount" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][discount_amount]" value="<?= $prod['dicount_amount'] ?>" class="form-control  quotation_edit qtn_discount_amount" readonly></td>
                                        <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][taxable_amount]" value="<?= $prod['taxable_amount'] ?>" class="form-control  quotation_edit qtn_taxable_amount" readonly></td>
                                    </tr>
                                    <?php $k++; endforeach; ?>
                                </tbody>
                            </table>
                    <!-- </div>                       -->
                           
                    <?php $j++; endforeach; ?>
                <!-- </div> -->
            <?php $i++; 
        endforeach; ?>
    <?php endif; ?>

    <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sub Total</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control quotation_edit qtn_sub_total" value="<?= isset($qtn_master['sub_total'])?$qtn_master['sub_total']:"" ?>"  readonly>
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">additional Discount (%)</label>
                            <div class="col-sm-4">
                                <input type="text" name="qtn_add_discount_percentage" id="qtn_add_discount_percentage" class="form-control quotation_edit" value="<?= isset($qtn_master['discount_percentage'])?$qtn_master['discount_percentage']:"" ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="qtn_add_discount_amount" id="qtn_add_discount_amount" class="form-control quotation_edit" value="<?= isset($qtn_master['discount_amount'])?$qtn_master['discount_amount']:"" ?>"  readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Apply VAT</label>
                        <div class="col-sm-8">
                            <input type="checkbox" 
                                class="form-check-input" 
                                id="qtn_apply_vat"
                                name="apply_vat"
                                value="1"
           <?= isset($qtn_master['vat_required']) && $qtn_master['vat_required'] == 1 ? 'checked' : '' ?>>
                            <input type="number" id="qtn_vat_percentage" value="5" class="form-control mt-2 " style="width:100px;" readonly> 
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">VAT Amount</label>
                        <div class="col-sm-8">
                            <input type="text" name="qtn_vat_amount" id="vat" class="form-control estimation_edit" value="<?= isset($qtn_master['vat_amount'])?$qtn_master['vat_amount']:"" ?>" readonly>
                        </div>
                    </div>

                        
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Estimation Cost</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_estimation_cost" id="estimation_cost" class="form-control font-weight-bold" value="<?= isset($qtn_master['estimation_amount'])?$qtn_master['estimation_amount']:"" ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">total before vat</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="<?= isset($qtn_master['total_before_vat'])?$qtn_master['total_before_vat']:"" ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Grand  Total</label>
                            <div class="col-sm-8">
                                <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="<?= isset($qtn_master['grand_total'])?$qtn_master['grand_total']:"" ?>" readonly >
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
                                class="form-control quotation_edit CKEDITOR" 
                                value="<?= isset($qtn_master['payment_term'])?$qtn_master['payment_term']:"" ?>" readonly>
                        </div>
                         <!-- Validity -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Validity</label>
                            <input type="text" name="validity" id="validity" 
                                class="form-control quotation_edit" 
                                value="<?= isset($qtn_master['validity'])?$qtn_master['validity']:"" ?>" readonly>
                        </div>
                       
                    </div>

                    <!-- Row 2 -->
                    <div class="form-group row">
                       
                         <!-- Delivery Term (CKEditor) -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Delivery Term</label>
                            <textarea name="delivery_term" id="delivery_term" 
                                    class="form-control quotation_edit" readonly><?= isset($qtn_master['delivery_term'])?$qtn_master['delivery_term']:"" ?></textarea>
                        </div>

                        <!-- Terms & Conditions (CKEditor) -->
                        <div class="col-sm-6">
                            <label class="col-form-label">Terms & Conditions</label>
                            <textarea name="terms_condition" id="terms_condition" 
                                    class="form-control quotation_edit" readonly><?= isset($qtn_master['terms_condition'])?$qtn_master['terms_condition']:"" ?></textarea>
                        </div>
                    </div>



                

                <!-- Centered Button -->
                <div class="row justify-content-center mt-3 action_button_quotation" id="action_button_quotation">
                </div>

            </div>
        </div>

    </form>
    