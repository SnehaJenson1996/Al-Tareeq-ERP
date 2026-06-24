<div class="form-group">
    <label for="survey_comments">Survey Revisions</label>
    <?php if (isset($estimation_revisions) && (!empty($estimation_revisions))) { ?>
        <div class="revisions-list">
            <?php $i = 1; foreach ($estimation_revisions as $revision_id): ?>
                <a href="<?= base_url('index.php/Sales/estimation_revision/' . $revision_id . '/' . $enquiry_data['enquiry_id']) ?>"
                   target="_blank"
                   class="revision-link">
                    Revision <?= $i; ?>
                </a>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    <?php } ?>
</div>

<?php if (!empty($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] == 3): ?>    

    <!-- ===================== DRAFT (Status = 3) ===================== -->

    <!-- Survey Comments -->
    <div class="form-group">
        <label for="survey_comments">Survey Comments</label>
        <textarea name="survey_comments" id="survey_comments" class="form-control" rows="3">
            <?= !empty($survey_data['survey_comments']) ? $survey_data['survey_comments'] : '' ?>
        </textarea>
    </div>

    <!-- Material Details -->
    <div class="form-group">
        <label for="material_details">Material Details</label>
        <textarea name="material_details" id="material_details" class="form-control" rows="3">
            <?= !empty($survey_data['material_details']) ? $survey_data['material_details'] : '' ?>
        </textarea>
    </div>

    <!-- Main Heading Button -->
    <div class="mt-2">
        <button type="button" class="btn btn-primary btn-sm" id="addMainHeading">
            + Add Main Heading
        </button>
    </div>

    <form action="<?= base_url() ?>index.php/Sales/save_estimation" id="estimationForm" method="post">
        <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">

        <div class="form-group d-flex justify-content-end">
            <input type="button" 
                   class="btn btn-primary m-b-0"
                   name="Restore" id="restore"
                   value="Restore Latest"
                   onclick="restoreProducts();">
        </div>

        <div id="mainMenuContainer"></div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="row">
                        
                        <!-- Sub Total & Margin -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Sub Total</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="subtotal" name="sub_total" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Margin (%)</label>
                                <div class="col-sm-4">
                                    <input type="number" step="0.01" id="marginPercent" name="margin_percent" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" step="0.01" id="marginAmount" name="margin_amount" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Freight & Bank Charge -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Freight (%)</label>
                                <div class="col-sm-4">
                                    <input type="number" step="0.01" id="freightPercent" name="freight_percent" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" step="0.01" id="freightAmount" name="freight_amount" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Bank Charge</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="bankCharge" name="bank_charge" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Travel & Other -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Travel Expense</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="travelExpense" name="travel_expense" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Other Expense</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="otherExpense" name="other_expense" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Inspection & Total -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Inspection Cost</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="inspectionCost" name="inspection_cost" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 bg-light font-weight-bold">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Total Cost</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" id="totalCost" name="total_cost" class="form-control font-weight-bold" readonly>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /.row -->
                </div> <!-- /.card -->
            </div> <!-- /.col-md-12 -->
        </div> <!-- /.row -->

        <button type="submit" class="btn btn-primary">Save Estimation</button>
    </form>

<?php endif; ?>


<?php if (!empty($enquiry_data['enquiry_status']) && ($enquiry_data['enquiry_status'] >= 4)): ?>    

    <!-- ===================== FINAL (Status = 4) ===================== -->

    <!-- <div> -->
    <div class="form-group row align-items-center">
        <label class="col-sm-2 col-form-label">Estimation Date:</label>
        <div class="col-sm-2">
            <input type="date" id="estimation_date" name="estimation_date"
                   value="<?= isset($master['estimation_date']) ? date('Y-m-d', strtotime($master['estimation_date'])) : '' ?>"
                   class="form-control" readonly>
        </div>

        <div class="col-sm-4">&nbsp;</div>

        <!-- Dropdown -->
        <div class="col-sm-2">
            <select class="select2 form-control" name="Action_estimation" id="Action_estimation">
                <option value="">--Select Action--</option>
                <option value="edit">Edit</option>
                <option value="approve">Approve</option>
                <option value="reject">Reject</option>
            </select>
        </div>

        <!-- Print Button -->
        <div class="text-end mb-3">
            <button type="button" 
                    class="btn btn-primary" 
                    onclick="window.open('<?= base_url('index.php/Sales/print_estimation/'.$master['estimation_id'].'/'.$enquiry_data['enquiry_id']) ?>/1', '_blank');">
                🖨 Print
            </button>
        </div>

    </div> <!-- /.form-group row -->

    <form action="<?= base_url()?>index.php/Sales/update_estimation" method="post">

        <!-- Add Heading -->
        <div class="mt-2">
            <button type="button" class="btn btn-primary btn-sm" id="addMainHeading">
                + Add Main Heading
            </button>
        </div>

        <div id="mainMenuContainer">
            <input type="hidden" name="estimation_id" value="<?= isset($master['estimation_id']) ? $master['estimation_id'] : "" ?>">

            <?php if (isset($estimation)) : $i = 0; ?>
                <?php foreach ($estimation as $main): ?>
                    <div id="main_heading_block_<?= $i ?>" class="border p-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div style="width: 40%;">
                                <label>Main Heading</label>
                                <input type="text"
                                       name="main_heading[<?= $i ?>]"
                                       value="<?= $main['main_heading'] ?>"
                                       class="estimation_edit form-control"
                                       placeholder="Enter Main Heading"
                                       readonly>
                            </div>
                            <div style="width: 45%;">
                                <label>Details</label>
                                <textarea name="main_details[<?= $i ?>]"
                                          class="estimation_edit form-control"
                                          placeholder="Enter Details"
                                          readonly><?= $main['main_details'] ?></textarea>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="btn btn-success btn-sm addSubHeading" data-main="<?= $i ?>">+ Sub Heading</button>
                                <button type="button" class="btn btn-danger btn-sm removeMainHeading" data-main="<?= $i ?>">🗑</button>
                            </div>
                        </div>
                    </div>

                    <?php $j = 0; foreach ($main['sub_headings'] as $sub): ?>
                        <div class="border p-2 mb-2 subHeadingContainer" data-main="<?= $i ?>" data-sub="<?= $j ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <input type="text"
                                       name="sub_heading[<?= $i ?>][<?= $j ?>]"
                                       value="<?= $sub['sub_heading'] ?>"
                                       class="form-control form-control-sm w-75"
                                       placeholder="Enter Sub Heading">
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
                                    <?php $k = 0; foreach ($sub['products'] as $prod): ?>
                                        <tr>
                                            <td>
                                                <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]" 
                                                        class="form-control form-control-sm estimation_edit product-select">
                                                    <option value="">-- Select Product --</option>
                                                    <?php foreach ($all_products as $p): ?>
                                                        <option value="<?= $p->item_id ?>" 
                                                            <?= ($p->item_id == $prod['product_id']) ? 'selected' : '' ?>>
                                                            <?= $p->item_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <br><br>
                                                <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]"
                                                          class="form-control form-control-sm estimation_edit"><?= $prod['product_description'] ?></textarea>
                                            </td>

                                            <td>
                                                <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]" class="form-control estimation_edit">
                                                    <option value="">-- Select Unit --</option>
                                                    <?php foreach ($active_units as $unit): ?>
                                                        <option value="<?= $unit->unit_id ?>" 
                                                            <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>>
                                                            <?= $unit->unit_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]"
                                                       value="<?= $prod['quantity'] ?>"
                                                       class="form-control estimation_edit qty"
                                                       readonly>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                       name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]"
                                                       value="<?= $prod['unit_price'] ?>"
                                                       class="form-control estimation_edit unitPrice"
                                                       readonly>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                       name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]"
                                                       value="<?= $prod['amount'] ?>"
                                                       class="form-control estimation_edit amount"
                                                       readonly>
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-success btn-sm addProductRow">+</button>
                                            </td>
                                        </tr>
                                        <?php $k++; endforeach; ?>
                                </tbody>
                            </table>

                            <div class="subHeadingContainer" id="subHeadingContainer_<?= $i ?>"></div>
                        </div>
                        <?php $j++; endforeach; ?>

                        <!-- </div> ? extra closing -->
                <?php $i++; endforeach; ?>
            <?php endif; ?>
        </div> <!-- /#mainMenuContainer -->

        <!-- </div> ? extra closing -->
        
        <!-- </div> ? extra closing -->

        <!-- Summary -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sub Total</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="sub_total"
                                       id="subtotal"
                                       class="form-control"
                                       value="<?= isset($master['sub_total']) ? $master['sub_total'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Freight (%)</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       name="freight_percentage"
                                       id="freightPercent"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['freight_percentage']) ? $master['freight_percentage'] : "" ?>"
                                       readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text"
                                       name="freight_amount"
                                       id="freightAmount"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['freight_amount']) ? $master['freight_amount'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Travel Expense</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="travel_expense"
                                       id="travelExpense"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['travel_expense']) ? $master['travel_expense'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Inspection Cost</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="inspection_cost"
                                       id="inspectionCost"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['inspection_cost']) ? $master['inspection_cost'] : "" ?>"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Margin (%)</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       name="margin_percentage"
                                       id="marginPercent"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['margin_percentage']) ? $master['margin_percentage'] : "" ?>"
                                       readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text"
                                       name="margin_amount"
                                       id="marginAmount"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['margin_amount']) ? $master['margin_amount'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Bank Charge</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="bank_charge"
                                       id="bankCharge"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['bank_charge']) ? $master['bank_charge'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Other Expense</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="other_expense"
                                       id="otherExpense"
                                       class="form-control estimation_edit"
                                       value="<?= isset($master['other_expense']) ? $master['other_expense'] : "" ?>"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Total Cost</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="total_cost"
                                       id="totalCost"
                                       class="form-control font-weight-bold"
                                       value="<?= isset($master['grand_total']) ? $master['grand_total'] : "" ?>"
                                       readonly>
                            </div>
                        </div>
                    </div>

                </div> <!-- /.row -->

                <!-- Centered Button -->
                <div class="row justify-content-center mt-3">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </div> <!-- /.col-md-10 -->
        </div> <!-- /.row -->
    <!-- </div> -->
<?php endif; ?>
</form>
