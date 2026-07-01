<div class="clearfix"></div>
<div class="row">
<div class="col-md-12 col-sm-12">
<div class="x_panel">
<div class="x_content">
<br />

<form action="<?php echo base_url().'index.php/Setup/' . (isset($product) ? 'update_item/'.$product['product_id'] : 'add_item_data'); ?>" 
      method="post" autocomplete="off" id="product" enctype="multipart/form-data">

<div class="row">

    <!-- Product Code -->
    <div class="col-md-6">
        <label>Product Code <span class="required">*</span></label>
        <input type="text" id="product_code" name="product_code" required class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['product_code']) : '' ?>">
    </div>

    <!-- Product Name -->
    <div class="col-md-6">
        <label>Product Name <span class="required">*</span></label>
        <input type="text" id="product_name" name="product_name" required class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['product_name']) : '' ?>">
    </div>

    <!-- Description -->
    <div class="col-md-6 mt-2">
        <label>Description</label>
        <textarea name="description" class="form-control"><?= isset($product) ? htmlspecialchars($product['description']) : '' ?></textarea>
    </div>

    <!-- Unit -->
    <div class="col-md-6 mt-2">
        <label>Unit <span class="required">*</span></label>
        <select name="unit_id" class="form-control" required>
            <option value="">-- Select Unit --</option>
            <?php foreach ($active_units as $unit): ?>
                <option value="<?= $unit->unit_id ?>"
                    <?= isset($product) && $product['unit_id'] == $unit->unit_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($unit->unit_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Retail Price -->
    <div class="col-md-6 mt-2">
        <label>Retail Price</label>
        <input type="number" step="any" name="retail_price" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['retail_price']) : '' ?>">
    </div>

    <!-- Group Code -->
    <div class="col-md-6 mt-2">
        <label>Group Code</label>
        <input type="text" name="group_code" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['group_code']) : '' ?>">
    </div>

    <!-- Category Code -->
  <div class="col-md-6 mt-2">
    <label>Category </label>
    <select name="category_id" class="form-control" >
        <option value="">-- Select Category --</option>

        <?php foreach($categories as $cat){ ?>
            <option value="<?= $cat->category_id; ?>"
                <?= (isset($product) && $product['category_id'] == $cat->category_id) ? 'selected' : ''; ?>>
                <?= $cat->category_code; ?> - <?= htmlspecialchars($cat->category_name); ?>
            </option>
        <?php } ?>

    </select>
</div>

    <!-- Min Level -->
    <div class="col-md-6 mt-2">
        <label>Min Level</label>
        <input type="number" name="min_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['min_level']) : '' ?>">
    </div>

    <!-- Max Level -->
    <div class="col-md-6 mt-2">
        <label>Max Level</label>
        <input type="number" name="max_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['max_level']) : '' ?>">
    </div>

    <!-- Re-order Level -->
    <div class="col-md-6 mt-2">
        <label>Re-order Level</label>
        <input type="number" name="reorder_level" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['reorder_level']) : '' ?>">
    </div>

    <!-- HS Code -->
    <div class="col-md-6 mt-2">
        <label>HS Code</label>
        <input type="text" name="hs_code" class="form-control"
            value="<?= isset($product) ? htmlspecialchars($product['hs_code']) : '' ?>">
    </div>

    <!-- Tax Applicable -->
    <div class="col-md-6 mt-2">
        <label>Tax Applicable</label><br>
        <input type="checkbox" name="tax_applicable" value="1"
            <?= isset($product) && $product['tax_applicable'] == 1 ? 'checked' : '' ?>>

			
    </div>

    <div class="col-md-6 mt-2">

        <label>Product Type</label>

        <div>
            <label>
                <input type="checkbox" name="is_finished_product" value="1"
                    <?= isset($product) && $product['is_finished_product'] == 1 ? 'checked' : '' ?>>
                Finished
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_custom_made" value="1"
                    <?= isset($product) && $product['is_custom_made'] == 1 ? 'checked' : '' ?>>
                Custom Made
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_non_standard" value="1"
                    <?= isset($product) && $product['is_non_standard'] == 1 ? 'checked' : '' ?>>
                Non Standard
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_inactive" value="1"
                    <?= isset($product) && $product['is_inactive'] == 1 ? 'checked' : '' ?>>
                Inactive
            </label>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_marked_delete" value="1"
                    <?= isset($product) && $product['is_marked_delete'] == 1 ? 'checked' : '' ?>>
                Marked Delete
            </label>
        </div>        </div>


    <!-- Image -->
    <div class="col-md-6 mt-2">
        <label>Product Image</label>
        <input type="file" name="product_image" class="form-control" accept="image/*">

        <?php if (isset($product) && !empty($product['product_image'])) { ?>
            <img src="<?= base_url('public/items/'.$product['product_image']) ?>" style="width:80px;margin-top:5px;">
        <?php } ?>
    </div>
	

</div>

<!-- Submit -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <button type="submit" id="saveBtn" class="btn btn-success">
            <?= isset($product) ? 'Update' : 'Submit' ?>
        </button>
    </div>
</div>

</form>

</div>
</div>
</div>
</div>

<script>
document.getElementById("product").addEventListener("submit", function (e) {
    var btn = document.getElementById("saveBtn");

    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    btn.disabled = true;
    btn.innerHTML = "Processing...";
});
</script>