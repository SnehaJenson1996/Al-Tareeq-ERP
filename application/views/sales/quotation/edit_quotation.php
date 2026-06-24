<?php
// Assumptions:
// - $qtn_master (quotation master row) available
// - $qtn_details (array of main headings each with sub_headings and products) available
// - $all_products (list of product objects with item_id, item_name, item_unit, unit_price) available
// - $active_units (list of units) available
// - Controller update_quotation expects same input names as in add page
$initialMain = isset($qtn_details) ? count($qtn_details) : 0;

// Build JS subIndex object for existing subheading counts
$subCounts = [];
if (!empty($qtn_details)) {
    $mi = 0;
    foreach ($qtn_details as $main) {
        $subCounts[$mi] = isset($main['sub_headings']) ? count($main['sub_headings']) : 0;
        $mi++;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Quotation</title>

    <!-- REQUIRED CSS (adjust included files if needed) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        label,
        h4 {
            color: black;
            font-weight: bold;
        }

        .main-heading-block {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .sub-block {
            border: 1px dashed #e0e0e0;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }

        .product-table th,
        .product-table td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: .35rem .5rem;
            font-size: .85rem;
        }

        /* Add a subtle border around the whole content if requested */
        .content-border {
            border: 1px solid #e6e6e6;
            padding: 12px;
            border-radius: 6px;
            background: #fff;
        }

        .remove-btn {
            cursor: pointer;
        }

        .cover-hide {
            display: none;
        }

        /* placeholder if you want cover-only CSS */
    </style>
</head>

<body>
    <div class="container content-border">
        <div class="x_title">
            <div class="clearfix"></div>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
        </div>


        <!-- <h4>Edit Quotation - <?= isset($qtn_master['quotation_code']) ? $qtn_master['quotation_code'] : '' ?></h4> -->

        <form action="<?= base_url('index.php/Sales/update_quotation') ?>" method="post" id="editQuotationForm">
            <!-- Hidden -->
            <input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?? '' ?>">
            <input type="hidden" name="enquiry_id" value="<?= $qtn_master['enquiry_id'] ?? '' ?>">
            <input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?? '' ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Quotation Code</label>
                    <input type="text" name="quotation_code" class="form-control" readonly value="<?= $qtn_master['quotation_code'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Quotation Date</label>
                    <input type="date" name="quotation_date" id="quotation_date" class="form-control" value="<?= date("Y-m-d", strtotime($qtn_master['quotation_date'])) ?>">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <button type="button" class="btn btn-primary btn-sm" id="addMainBtn">+ Add Main Heading</button>
                </div>
            </div>

            <!-- Container for main headings -->
            <div id="quotation_container">
                <!-- Existing main headings rendered server-side (editable) -->
                <?php if (!empty($qtn_details)): $mi = 0; ?>
                    <?php foreach ($qtn_details as $main): ?>
                        <div class="main-heading-block" data-main="<?= $mi ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div style="width:40%">
                                    <label>Main Heading</label>
                                    <input type="text" name="main_heading[<?= $mi ?>]" class="form-control" value="<?= htmlspecialchars($main['main_heading'] ?? '') ?>">
                                </div>
                                <div style="width:45%">
                                    <label>Details</label>
                                    <textarea name="main_details[<?= $mi ?>]" class="form-control" rows="2"><?= htmlspecialchars($main['main_details'] ?? '') ?></textarea>
                                </div>
                                <div class="ms-2">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-danger btn-sm removeMainHeading">🗑</button>
                                </div>
                            </div>

                            <!-- Sub-heading blocks -->
                            <div class="sub-container sub-container-<?= $mi ?>">
                                <?php if (!empty($main['sub_headings'])): $sj = 0; ?>
                                    <?php foreach ($main['sub_headings'] as $sub): ?>
                                        <div class="sub-block" data-sub="<?= $sj ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <input type="text" name="sub_heading[<?= $mi ?>][<?= $sj ?>]" class="form-control w-75" value="<?= htmlspecialchars($sub['sub_heading'] ?? '') ?>" placeholder="Sub Heading">
                                                <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
                                            </div>

                                            <table class="table table-sm table-bordered product-table mb-2">
                                                <thead>
                                                    <tr class="bg-light">
                                                        <th>Product</th>
                                                        <th style="width:100px">Unit</th>
                                                        <th style="width:100px">Qty</th>
                                                        <th style="width:120px">Unit Price</th>
                                                        <th style="width:120px">Amount</th>
                                                        <th style="width:120px">Discount</th>
                                                        <!-- <th style="width:120px">Warranty</th> -->

                                                        <th style="width:120px">Taxable</th>
                                                        <th style="width:60px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($sub['products'])): $pk = 0; ?>
                                                        <?php foreach ($sub['products'] as $prod): ?>
                                                            <tr>
                                                                <td style="min-width:200px">
                                                                    <select name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][product_id]" class="form-control product-select">
                                                                        <option value="">-- Select Product --</option>
                                                                        <?php foreach ($all_products as $p): ?>
                                                                            <option value="<?= $p->item_id ?>" <?= ($p->item_id == ($prod['product_id'] ?? $prod['prd_id'])) ? 'selected' : '' ?>>
                                                                                <?= htmlspecialchars($p->item_name) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                   <textarea 
    id="product_desc_<?= $mi ?>_<?= $sj ?>_<?= $pk ?>"
    name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][product_description]" 
    class="form-control mt-1 product_editor"><?= 
        strip_tags($prod['product_description'] ?? '') 
    ?></textarea>
                                                                </td>

                                                                <td>
                                                                    <select name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][unit]" class="form-control unit-select">
                                                                        <option value="">-- Unit --</option>
                                                                        <?php foreach ($active_units as $u): ?>
                                                                            <option value="<?= $u->unit_id ?>" <?= (isset($prod['unit_id']) && $prod['unit_id'] == $u->unit_id) ? 'selected' : '' ?>>
                                                                                <?= htmlspecialchars($u->unit_name) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </td>

                                                                <td><input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][quantity]" class="form-control qtn_qty" value="<?= $prod['quantity'] ?? $prod['qty'] ?? 0 ?>"></td>

                                                                <td><input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][unit_price]" class="form-control qtn_unitPrice" value="<?= $prod['unit_price'] ?? 0 ?>"></td>

                                                                <td><input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][amount]" class="form-control qtn_amount" readonly value="<?= $prod['amount'] ?? 0 ?>"></td>

                                                                <td>
                                                                    <input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][discount_percent]" class="form-control qtn_discount_percent" id="qtn_discount_percent" value="<?= $prod['discount_percent'] ?? $prod['discount_percent'] ?? 0 ?>">
                                                                    <input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][discount_amount]" class="form-control qtn_discount_amount" id="qtn_discount_amount" value="<?= $prod['dicount_amount'] ?? $prod['discount_amount'] ?? 0 ?>">
                                                                </td>
 



                                                                <td><input type="number" step="0.01" name="products[<?= $mi ?>][<?= $sj ?>][<?= $pk ?>][taxable_amount]" class="form-control qtn_taxable_amount" readonly value="<?= $prod['taxable_amount'] ?? 0 ?>"></td>

                                                                <td><button type="button" class="btn btn-danger btn-sm removeProductRow">🗑</button></td>
                                                            </tr>
                                                        <?php $pk++;
                                                        endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>

                                            <button type="button" class="btn btn-success btn-sm addProduct btn-sm">+ Add Product</button>
                                        </div>
                                    <?php $sj++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="mt-2">
                                <button type="button" class="btn btn-secondary btn-sm addSubHeadingBtn">+ Add Sub Heading</button>
                            </div>
                        </div>
                    <?php $mi++;
                    endforeach; ?>
                <?php endif; ?>
            </div> <!-- #quotation_container -->

            <!-- SUMMARY -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Sub Total</label>
                        <div class="col-sm-8">
                            <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control qtn_sub_total" value="<?= $qtn_master['sub_total'] ?? 0 ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Other Charges</label>
                        <div class="col-sm-8">
                            <input type="text" name="other_charges" id="other_charges" class="form-control" value="<?= $qtn_master['other_charge'] ?? 0 ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                        <div class="col-sm-4">
                            <input type="text" id="qtn_add_discount_percentage" name="qtn_add_discount_percentage" class="form-control" value="<?= $qtn_master['discount_percentage'] ?? 0 ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="qtn_add_discount_amount" name="qtn_add_discount_amount" class="form-control" value="<?= $qtn_master['discount_amount'] ?? 0 ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Apply VAT</label>
                        <div class="col-sm-8">
                            <input type="checkbox" id="qtn_apply_vat" name="qtn_apply_vat" <?= isset($qtn_master['vat_required']) && $qtn_master['vat_required'] ? 'checked' : '' ?>>
                            <input type="number" id="qtn_vat_percentage" name="qtn_vat_percentage" class="form-control mt-2" style="width:100px;" value="<?= $qtn_master['vat_percentage'] ?? 5 ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">VAT Amount</label>
                        <div class="col-sm-8">
<input type="text"
       id="qtn_vat_amount"
       name="qtn_vat_amount"
       class="form-control"
       value="<?= number_format($qtn_master['vat_amount'] ?? 0, 2, '.', '') ?>"
       readonly>                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Total Before VAT</label>
                        <div class="col-sm-8">
                            <input type="text" id="total_before_vat" name="total_before_vat" class="form-control" value="<?= $qtn_master['total_before_vat'] ?? 0 ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Grand Total</label>
                        <div class="col-sm-8">
                            <input type="text" id="qtn_grand_total" name="qtn_grand_total" class="form-control" value="<?= $qtn_master['grand_total'] ?? 0 ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Terms -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Payment Term</label>
<textarea name="payment_term" id="payment_term" class="form-control" rows="3"><?= strip_tags($qtn_master['payment_term'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label>Validity</label>
                    <input type="text" name="validity" class="form-control" value="<?= $qtn_master['validity'] ?? '' ?>">
                </div>
            </div>

            <div class="form-group row">

    <div class="col-sm-6">
        <label class="col-form-label">Warranty</label>
        <input type="text"
               name="warranty"
               id="warranty"
               class="form-control"
               value="<?= $qtn_master['warranty'] ??  '' ?>">
    </div>

   <div class="col-sm-6">
        <label class="col-form-label">Warranty Description</label>
        <textarea name="warranty_description"
                  id="warranty_description"
                  class="form-control estimation_edit"><?= isset($qtn_master['warranty_description']) ? $qtn_master['warranty_description'] : '' ?></textarea>
    </div>

</div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Delivery Term</label>
                    <textarea name="delivery_term" id="delivery_term" class="form-control" rows="4"><?= strip_tags($qtn_master['delivery_term'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label>Terms & Conditions</label>
                    <textarea name="terms_condition" id="terms_condition" class="form-control" rows="4"><?= strip_tags($qtn_master['terms_condition'] ?? '') ?></textarea>
                </div>
            </div>


             <div class="row mt-3">
    <div class="col-md-12">
        <label>Notes</label>
        <textarea class="form-control"
                  name="notes"
                  id="notes"
                  rows="4"><?= strip_tags($qtn_master['notes'] ?? '') ?></textarea>
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
                <option value="<?php echo $s->employee_id  ?>" <?= (isset($qtn_master['prepared_by']) && $qtn_master['prepared_by'] == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
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
                <option value="<?php echo $s->employee_id  ?>" <?= (isset($qtn_master['approved_by']) && $qtn_master['approved_by'] == $s->employee_id) ? 'selected' : '' ?>><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>      
    </div>      
            <div class="text-center mt-3">

                <?php if (($qtn_master['aproval'] ?? 0) == 0): ?>

                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input" type="checkbox" id="create_revision" name="create_revision" value="1">
                        <label class="form-check-label" for="create_revision" style="font-weight:600;">
                            Create New Revision
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success" name="action" value="update">
                        Update Quotation
                    </button>
                    <? php // if (has_access_id(6)) { 
                    ?>
                 <?php if($qtn_master['grand_total'] >= 10000){ ?>

    <!-- 🔹 Step 1: Internal Approval -->
    <?php if($qtn_master['internal_approval'] == 'Pending'){ ?>

        <a href="<?= base_url('index.php/Sales/internal_approve/'.$qtn_master['quotation_id']); ?>" 
           class="btn btn-warning btn-sm">
            Internal Approval
        </a>

    <?php } ?>

    <!-- 🔹 Step 2: Client Approval (After Internal Approved) -->
    <?php if($qtn_master['internal_approval'] == 'Approved' 
            && $qtn_master['aproval'] == '0'){ ?>

        <a href="<?= base_url('index.php/Sales/approve_quotation/'.$qtn_master['quotation_id']) ?>"
           class="btn btn-primary">
            Approve Quotation with LPO
        </a>

        <a href="<?= base_url('index.php/Sales/approve_quotation_without_LPO/'.$qtn_master['quotation_id']) ?>"
           class="btn btn-primary">
            Approve Quotation without LPO
        </a>

    <?php } ?>

<?php } else { ?>

    <!-- 🔹 If Amount < 10000 → Direct Client Approval -->

    <?php if($qtn_master['aproval'] == '0'){ ?>

        <a href="<?= base_url('index.php/Sales/approve_quotation/'.$qtn_master['quotation_id']) ?>"
           class="btn btn-primary">
            Approve Quotation with LPO
        </a>

        <a href="<?= base_url('index.php/Sales/approve_quotation_without_LPO/'.$qtn_master['quotation_id']) ?>"
           class="btn btn-primary">
            Approve Quotation without LPO
        </a>

    <?php } ?>

<?php } ?>
                    <? php // } 
                    ?>
                <?php else: ?>

                    <a href="<?= base_url('index.php/Sales/add_sales_order/' .$qtn_master['quotation_id']) ?>" 
   class="btn btn-primary">
   Create Sales Order
</a>
                    <!-- <button type="submit" class="btn btn-primary ml-2" name="action" value="sales_order">Create Sales Order</button> -->
                <?php endif; ?>

            </div>

        </form>
    </div>

    <!-- Templates (hidden) -->
    <script type="text/template" id="tpl_main">
        <div class="main-heading-block" data-main="{{MAIN}}">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div style="width:40%">
                <label>Main Heading</label>
                <input type="text" name="main_heading[{{MAIN}}]" class="form-control">
            </div>
            <div style="width:45%">
                <label>Details</label>
                <textarea name="main_details[{{MAIN}}]" class="form-control" rows="2"></textarea>
            </div>
            <div class="ms-2">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-danger btn-sm removeMainHeading">🗑</button>
            </div>
        </div>

        <div class="sub-container sub-container-{{MAIN}}"></div>

        <div class="mt-2">
            <button type="button" class="btn btn-secondary btn-sm addSubHeadingBtn">+ Add Sub Heading</button>
        </div>
    </div>
</script>

    <script type="text/template" id="tpl_sub">
        <div class="sub-block">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="sub_heading[{{MAIN}}][{{SUB}}]" class="form-control w-75" placeholder="Sub Heading">
            <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
        </div>

        <table class="table table-sm table-bordered product-table mb-2">
            <thead>
                <tr class="bg-light">
                    <th>Product</th>
                    <th style="width:100px">Unit</th>
                    <th style="width:100px">Qty</th>
                    <th style="width:120px">Unit Price</th>
                    <th style="width:120px">Amount</th>
                    <th style="width:120px">Discount</th>
                     <!-- <th style="width:120px">Warranty</th> -->
                    <th style="width:120px">Taxable</th>
                    <th style="width:60px"></th>
                </tr>
            </thead>
            <tbody id="products_{{MAIN}}_{{SUB}}"></tbody>
        </table>

        <button type="button" class="btn btn-success btn-sm addProduct">+ Add Product</button>
    </div>
</script>

    <script type="text/template" id="tpl_product">
        <tr>
        <td style="min-width:200px">
            <select name="products[{{MAIN}}][{{SUB}}][{{ROW}}][product_id]" class="form-control product-select">
                <option value="">-- Select Product --</option>
                <?= str_replace("\n", " ", implode("\n", array_map(function ($p) {
                    return "<option value=\"{$p->item_id}\">" . htmlspecialchars($p->item_name) . "</option>";
                }, $all_products))) ?>
            </select>
<textarea 
    id="product_desc_{{MAIN}}_{{SUB}}_{{ROW}}"
    name="products[{{MAIN}}][{{SUB}}][{{ROW}}][product_description]" 
    class="form-control mt-1 product_editor">
</textarea>
        </td>
        <td>
            <select name="products[{{MAIN}}][{{SUB}}][{{ROW}}][unit]" class="form-control unit-select">
                <option value="">-- Unit --</option>
                <?php foreach ($active_units as $u): ?>
                    <option value="<?= $u->unit_id ?>"><?= htmlspecialchars($u->unit_name) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][quantity]" class="form-control qtn_qty" value="0"></td>
        <td><input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][unit_price]" class="form-control qtn_unitPrice" value="0"></td>
        <td><input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][amount]" class="form-control qtn_amount" readonly value="0"></td>
        <td>
             <input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][discount_percent]" class="form-control qtn_discount_percent" value="0">
            <input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][discount_amount]" class="form-control qtn_discount_amount" value="0">
        </td>

        
        <td><input type="number" step="0.01" name="products[{{MAIN}}][{{SUB}}][{{ROW}}][taxable_amount]" class="form-control qtn_taxable_amount" readonly value="0"></td>
        <td><button type="button" class="btn btn-danger btn-sm removeProductRow">🗑</button></td>
    </tr>
</script>

    <!-- Required scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- CKEditor (if you want WYSIWYG) -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <script>
        // Prepare product list for autofill
        const productsList = <?= json_encode(array_map(function ($p) {
                                    return [
                                        'item_id' => $p->item_id,
                                        'item_name' => $p->item_name,
                                        'item_unit' => $p->item_unit ?? '',
                                        'unit_price' => $p->unit_price ?? 0
                                    ];
                                }, $all_products)) ?>;

        $(function() {
            // init select2 for any existing selects
            $('.product-select').select2({
                width: '100%'
            });

            // main/sub counters
            let mainIndex = <?= (int)$initialMain ?>;
            let subIndex = <?= json_encode($subCounts) ?> || {};

            // helper to generate unique row id for new products
            function newRowId() {
                return 'n' + Date.now() + Math.floor(Math.random() * 999);
            }
initProductEditors();
            // Add main heading
            $('#addMainBtn').on('click', function() {
                const tpl = $('#tpl_main').html().replace(/{{MAIN}}/g, mainIndex);
                $('#quotation_container').append(tpl);
                subIndex[mainIndex] = 0;
                mainIndex++;
            });

            // Delegate remove main heading
            $(document).on('click', '.removeMainHeading', function() {
                $(this).closest('.main-heading-block').remove();
                recalcAll();
            });

            // Add sub heading (delegated)
            $(document).on('click', '.addSubHeadingBtn', function() {
                const mainBlock = $(this).closest('.main-heading-block');
                const main = mainBlock.data('main') ?? (function() {
                    // attempt to find by index among existing blocks
                    return $('.main-heading-block').index(mainBlock);
                })();
                if (typeof subIndex[main] === 'undefined') subIndex[main] = 0;
                const sub = subIndex[main]++;
                const tpl = $('#tpl_sub').html().replace(/{{MAIN}}/g, main).replace(/{{SUB}}/g, sub);
                mainBlock.find('.sub-container').append(tpl);
                initSubElements(main, sub);
            });

            // Initialize sub elements for existing blocks loaded server-side
            function initSubElements(main, sub) {
                // bind add product button inside the newly added sub-block
                const container = $('.sub-container-' + main).last(); // new sub appended
                container.find('.addProduct').off('click').on('click', function() {
                    const rowId = newRowId();
                    const tpl = $('#tpl_product').html()
                        .replace(/{{MAIN}}/g, main)
                        .replace(/{{SUB}}/g, sub)
                        .replace(/{{ROW}}/g, rowId);
                    container.find('tbody').append(tpl);
                    // initialize select2 on the newly added product-select
                    container.find('select.product-select').last().select2({
                        width: '100%'
                    });
                    initProductEditors();
                });
            }

            // If page loaded with server-side sub-blocks, attach addProduct handlers for them
            $('.sub-block').each(function(idx) {
                const subEl = $(this);
                subEl.find('.addProduct').off('click').on('click', function() {
                    // find associated MAIN and SUB indices by traversing name attributes or container
                    // Using simple approach: find closest .sub-container index
                    const mainBlock = subEl.closest('.main-heading-block');
                    const main = mainBlock.data('main');
                    // find sub index by counting previous sub-blocks within same main
                    const sub = mainBlock.find('.sub-block').index(subEl);
                    const rowId = newRowId();
                    const tpl = $('#tpl_product').html()
                        .replace(/{{MAIN}}/g, main)
                        .replace(/{{SUB}}/g, sub)
                        .replace(/{{ROW}}/g, rowId);
                    subEl.find('tbody').append(tpl);
                    subEl.find('select.product-select').last().select2({
                        width: '100%'
                    });
                });
            });

            // Delegate remove sub heading
            $(document).on('click', '.removeSubHeading', function() {
                $(this).closest('.sub-block').remove();
                recalcAll();
            });

            // Delegate remove product row
            $(document).on('click', '.removeProductRow', function() {
                $(this).closest('tr').remove();
                recalcAll();
            });

            // When product selected, autofill unit and unit price
            $(document).on('change', 'select.product-select', function() {
                const pid = $(this).val();
                const product = productsList.find(p => p.item_id == pid);
                const $row = $(this).closest('tr');
                if (product) {
                    // Attempt to set unit select if option value matches product.item_unit
                    if (product.item_unit) {
                        const unitSelect = $row.find('select.unit-select');
                        if (unitSelect.length) {
                            unitSelect.val(product.item_unit).trigger('change');
                        }
                    }
                    // unit price
                    $row.find('.qtn_unitPrice').val(parseFloat(product.unit_price || 0).toFixed(2));
                } else {
                    $row.find('.qtn_unitPrice').val('');
                }
                recalcRow($row);
                recalcAll();
            });

            // Row-level calculations (qty, unit price, discount input)
            $(document).on('input', '.qtn_qty, .qtn_unitPrice', function() {
                const $row = $(this).closest('tr');
                recalcRow($row);
                recalcAll();
            });
            //  let discountLock = false;

            $(document).on('input', '.qtn_discount_percent', function() {
                const $row = $(this).closest('tr');
                const per = parseFloat($(this).val()) || 0;

                if (per > 0) {
                    $row.find('.qtn_discount_amount').val('');
                }

                convertDiscount($row);
                discountLock = false;
            });

            $(document).on('input', '.qtn_discount_amount', function() {
                const $row = $(this).closest('tr');
                const amt = parseFloat($(this).val()) || 0;

                if (amt > 0) {
                    $row.find('.qtn_discount_percent').val('');
                }

                convertDiscount($row);
                discountLock = false;
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

            function recalcRow($row) {
                const qty = parseFloat($row.find('.qtn_qty').val()) || 0;
                const price = parseFloat($row.find('.qtn_unitPrice').val()) || 0;
                const disc = parseFloat($row.find('.qtn_discount_amount').val()) || 0;
                const amount = qty * price;
                const discount = disc > amount ? amount : disc;
                const taxable = Math.max(amount - discount, 0);
                $row.find('.qtn_amount').val(amount.toFixed(2));
                $row.find('.qtn_taxable_amount').val(taxable.toFixed(2));
            }

            // Full recalculation
           function recalcAll() {

    let subtotal = 0;

    // ✅ ALWAYS recalc from row data (NOT stored field)
    $('.product-table tbody tr').each(function () {

        const qty = parseFloat($(this).find('.qtn_qty').val()) || 0;
        const price = parseFloat($(this).find('.qtn_unitPrice').val()) || 0;

        const amount = qty * price;

        let discountAmt = parseFloat($(this).find('.qtn_discount_amount').val()) || 0;

        if (discountAmt > amount) discountAmt = amount;

        const taxable = amount - discountAmt;

        subtotal += taxable;

        // sync UI field (optional but useful)
        $(this).find('.qtn_taxable_amount').val(taxable.toFixed(2));
    });

    $('#qtn_sub_total').val(subtotal.toFixed(2));

    const otherCharges = parseFloat($('#other_charges').val()) || 0;

    let addDiscAmt = parseFloat($('#qtn_add_discount_amount').val());

    if (!addDiscAmt || addDiscAmt == 0) {
        const addDiscPerc = parseFloat($('#qtn_add_discount_percentage').val()) || 0;
        addDiscAmt = ((subtotal + otherCharges) * addDiscPerc) / 100;
        $('#qtn_add_discount_amount').val(addDiscAmt.toFixed(2));
    }

    const totalBeforeVatRaw = subtotal + otherCharges - addDiscAmt;
    $('#total_before_vat').val(totalBeforeVatRaw.toFixed(2));

    const applyVat = $('#qtn_apply_vat').is(':checked');
    const vatPerc = parseFloat($('#qtn_vat_percentage').val()) || 0;

    let vatAmt = 0;
    if (applyVat) {
        vatAmt = (totalBeforeVatRaw * vatPerc) / 100;
    }

    $('#qtn_vat_amount').val(vatAmt.toFixed(2));

    const grand = totalBeforeVatRaw + vatAmt;
    $('#qtn_grand_total').val(grand.toFixed(2));
}

            // recalc when discount %, other charges or VAT toggles change
            $(document).on('input change', '#qtn_add_discount_percentage, #other_charges', function() {
                recalcAll();
            });
            $(document).on('change', '#qtn_apply_vat', function() {
                recalcAll();
            });

            // Make sure totals are correct on load
            recalcAll();

            // Initialize CKEditor if present
            if (typeof CKEDITOR !== 'undefined') {
                try {
                    CKEDITOR.replace('delivery_term');
                    CKEDITOR.replace('terms_condition');
                    CKEDITOR.replace('payment_term');
                    CKEDITOR.replace('notes');


                } catch (e) {
                    /* ignore if already replaced */
                }
            }

            // Before submit: update CKEditor fields and recalc once more and validate date
            $('#editQuotationForm').on('submit', function(e) {
                // Validate date
                const qDate = $('#quotation_date').val().trim();
                if (!qDate) {
                    alert('Quotation Date is required.');
                    $('#quotation_date').focus();
                    e.preventDefault();
                    return false;
                }

                // Update CKEditor
                if (typeof CKEDITOR !== 'undefined') {
                    for (const ins in CKEDITOR.instances) {
                        if (CKEDITOR.instances.hasOwnProperty(ins)) {
                            CKEDITOR.instances[ins].updateElement();
                        }
                    }
                }

                // final recalc
                recalcAll();
                return true;
            });
        });

        function convertDiscount($row) {
            const qty = parseFloat($row.find(".qtn_qty").val()) || 0;
            const price = parseFloat($row.find(".qtn_unitPrice").val()) || 0;
            const amount = qty * price;

            let discAmt = parseFloat($row.find(".qtn_discount_amount").val()) || 0;
            let discPer = parseFloat($row.find(".qtn_discount_percent").val()) || 0;

            // If user enters %
            if ($row.find(".qtn_discount_percent").is(":focus")) {
                discAmt = (amount * discPer) / 100;
                $row.find(".qtn_discount_amount").val(discAmt.toFixed(2));
            }

            // If user enters amount
            if ($row.find(".qtn_discount_amount").is(":focus")) {
                discPer = amount ? (discAmt / amount) * 100 : 0;
                $row.find(".qtn_discount_percent").val(discPer.toFixed(2));
            }

            // Prevent over discount
            if (discAmt > amount) {
                discAmt = amount;
                $row.find(".qtn_discount_amount").val(amount.toFixed(2));
                $row.find(".qtn_discount_percent").val("100.00");
            }

            const taxable = amount - discAmt;
            $row.find(".qtn_taxable_amount").val(taxable.toFixed(2));
            recalcAll();
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
            // Qtn_calculateTotals();
            recalcAll();
        }
        // Initialize Product Description Editor
function initProductEditors() {

    $('.product_editor').each(function () {

        if (!this.id) {
            this.id = 'editor_' + Math.random().toString(36).substr(2, 9);
        }

        if (CKEDITOR.instances[this.id]) {
            CKEDITOR.instances[this.id].destroy(true);
        }

        CKEDITOR.replace(this.id, {
            height: 80,   // 👈 reduced size
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] }
            ]
        });
    });
}

function forceRecalc() {
    recalcAll();
}

setTimeout(forceRecalc, 500);
setTimeout(forceRecalc, 1000);
    </script>
    
</body>

</html>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%'
    });
});
</script>