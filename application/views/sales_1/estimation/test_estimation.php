<div <?= (isset($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 4) ? '' : 'style="display:none;"' ?>>
<div class="form-group row align-items-center">
    <label class="col-sm-2 col-form-label">Estimation Date:</label>
    <div class="col-sm-2">
        <input type="date" id="estimation_date" name="estimation_date"
            value="<?= isset($master['estimation_date']) ? date('Y-m-d', strtotime($master['estimation_date'])) : '' ?>"
            class="form-control" readonly>
    </div>
    <div class="col-sm-4">&nbsp;
    </div>
    <!-- Dropdown -->
    <div class="col-sm-2">
        <select class="select2 form-control">
                <option value="">--Select Action--</option>
                <option value="edit">Edit</option>
                <option value="approve">Approve</option>
                <option value="reject">Reject</option>
        </select>
    </div>

    <!-- Print Button -->
    <div class="col-sm-2">
        <button type="button" class="btn btn-primary" onclick="window.print();">
            Print
        </button>
    </div>
</div>


<?php 
if(isset($estimation)){
foreach ($estimation as $main): ?>
     <div id="main_heading_block_${i}" class="border p-2 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div style="width: 40%;">
                <label>Main Heading</label>
                <input type="text" name="main_heading[${i}]" value="<?= $main['main_heading'] ?>" class="estimation_edit form-control"  placeholder="Enter Main Heading" readonly>
            </div>
            <div style="width: 45%;">
                <label>Details</label>
                <textarea name="main_details[${i}]" value="<?= $main['main_details'] ?>" class="estimation_edit form-control"   placeholder="Enter Details" readonly></textarea>
            </div>
            <div class="mt-4">
                <button type="button" class="btn btn-success btn-sm addSubHeading" data-main="${i}">+ Sub Heading</button>
                <button type="button" class="btn btn-danger btn-sm removeMainHeading" data-main="${i}">🗑</button>
            </div>
        </div>

    <?php foreach ($main['sub_headings'] as $sub): ?>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sub_heading" class="estimation_edit form-control"  placeholder="Enter Sub Heading" value="<?= $sub['sub_heading'] ?>" readonly>
            <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
        </div>
        <table class="table table-bordered productTable mb-0">
            <thead>
                <tr style="background-color:#f8f9fa;">
                <th>Product</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
    </thead>
    <tbody>
            <?php foreach ($sub['products'] as $prod): ?>
              <tr>
                <td>
                    <input type="text" name="products[]" value="<?= $prod['item_name'] ?>" 
                        class="estimation_edit form-control" readonly>
                </td>
                <td>
                    <input type="text" name="unit[]" value="<?= $prod['unit_name'] ?>" 
                        class="estimation_edit form-control" readonly>
                </td>
                <td>
                    <input type="number" name="quantity[]" value="<?= $prod['quantity'] ?>" 
                        class="estimation_edit form-control" readonly>
                </td>
                <td>
                    <input type="number" step="0.01" name="unit_price[]" value="<?= $prod['unit_price'] ?>" 
                        class="estimation_edit form-control" readonly>
                </td>
                <td>
                    <input type="number" step="0.01" name="amount[]" value="<?= $prod['amount'] ?>" 
                        class="estimation_edit form-control" readonly>
                </td>
            </tr>

        </tbody>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
<?php endforeach; } ?>
<div class="row mt-12">
        <div class="col-md-12">
           <div class="card p-3">                 
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Sub Total</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" id="subtotal" name="sub_total" 
                                value="<?= isset($master['sub_total']) ? $master['sub_total'] : '' ?>" 
                                class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Margin (%)</label>
                            <div class="col-sm-4">
                                <input type="number" step="0.01" id="marginPercent" name="margin_percent" 
                                    value="<?= isset($master['margin_percentage']) ? $master['margin_percentage'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="0.01" id="marginAmount" name="margin_amount" 
                                    value="<?= isset($master['margin_amount']) ? $master['margin_amount'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Freight (%)</label>
                            <div class="col-sm-4">
                                <input type="number" step="0.01" id="freightPercent" name="freight_percent" 
                                    value="<?= isset($master['freight_percentage']) ? $master['freight_percentage'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="0.01" id="freightAmount" name="freight_amount" 
                                    value="<?= isset($master['freight_amount']) ? $master['freight_amount'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Bank Charge</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" id="bankCharge" name="bank_charge" 
                                    value="<?= isset($master['bank_charge']) ? $master['bank_charge'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Travel Expense</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" id="travelExpense" name="travel_expense" 
                                    value="<?= isset($master['travel_expense']) ? $master['travel_expense'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Other Expense</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" id="otherExpense" name="other_expense" 
                                    value="<?= isset($master['other_expense']) ? $master['other_expense'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Inspection Cost</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" id="inspectionCost" name="inspection_cost" 
                                    value="<?= isset($master['inspection_cost']) ? $master['inspection_cost'] : '' ?>" 
                                    class="form-control estimation_edit" readonly>
                            </div>
                    </div>

                    <div class="form-group row bg-light font-weight-bold p-2">
                            <label class="col-sm-4 col-form-label">Total Cost</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" id="totalCost" name="total_cost" 
                                    value="<?= isset($master['grand_total']) ? $master['grand_total'] : '' ?>" 
                                    class="form-control font-weight-bold estimation_edit" readonly>
                            </div>
                        </div>
                    </div>

        </div> <!-- /.card -->
    </div> <!-- /.col-md-12 -->
  </div> <!-- /.row mt-12 -->
</div>