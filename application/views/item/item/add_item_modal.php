<form id="addItemForm" action="<?= base_url('index.php/Item/add_item_data_purchase') ?>" method="post" enctype="multipart/form-data">
    <!-- <div class="item form-group">
      <label class="col-form-label col-md-3 col-sm-3 label-align" for="unit">Brand<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6">
        <select id="brand" name="brand" required class="form-control">
          <option value="">-- Select Brand --</option>
          <?php foreach ($active_brand as $brands): ?>
            <option value="<?= htmlspecialchars($brands->brand_id ) ?>"
              <?= isset($item) && $item['item_brand'] == $brands->brand_id  ? 'selected' : '' ?>>
              <?= htmlspecialchars($brands->brand_name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div> -->
    <div class="item form-group">
      <label class="col-form-label col-md-3 col-sm-3 label-align" for="item_name">Item Name<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6">
        <input type="text" id="item_name" name="item_name" required class="form-control"
          value="<?= isset($item) ? htmlspecialchars($item['item_name']) : '' ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="col-form-label col-md-3 col-sm-3 label-align" for="item_code">Item Code<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6">
        <input type="text" id="item_code" name="item_code" required class="form-control"
          value="<?= isset($item) ? htmlspecialchars($item['item_code']) : '' ?>">
      </div>
    </div>

    <div class="item form-group">
      <label class="col-form-label col-md-3 col-sm-3 label-align" for="unit">Unit<span class="required">*</span></label>
      <div class="col-md-6 col-sm-6">
        <select id="unit" name="unit" required class="form-control">
          <option value="">-- Select Unit --</option>
          <?php foreach ($active_units as $unit): ?>
            <option value="<?= htmlspecialchars($unit->unit_id) ?>"
              <?= isset($item) && $item['item_unit'] == $unit->unit_id ? 'selected' : '' ?>>
              <?= htmlspecialchars($unit->unit_name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="item form-group">
      <label for="unit_price" class="col-form-label col-md-3 col-sm-3 label-align">Unit Price</label>
      <div class="col-md-6 col-sm-6">
        <input type="number" step="any" id="unit_price" name="unit_price" required class="form-control"
          value="<?= isset($item) ? htmlspecialchars($item['unit_price']) : '' ?>">
      </div>
    </div>

    <div class="item form-group">
      <label for="item_description" class="col-form-label col-md-3 col-sm-3 label-align">Item Description</label>
      <div class="col-md-6 col-sm-6">
        <textarea id="item_description" name="item_description" class="form-control"><?= isset($item) ? htmlspecialchars($item['item_description']) : '' ?></textarea>
      </div>
    </div>

    <div class="item form-group">
      <label for="item_image" class="col-form-label col-md-3 col-sm-3 label-align">Upload Image</label>
      <div class="col-md-6 col-sm-6">
        <input type="file" id="item_image" name="item_image" accept="image/*" class="form-control">
        <?php if (isset($item) && !empty($item['item_image'])) { ?>
          <p>Current Image:</p>
          <img src="<?= base_url('public/items/'.$item['item_image']) ?>" style="width:80px;">
        <?php } ?>
      </div>
    </div>

    <?php if (isset($this->security) && $this->security->get_csrf_hash()) : ?>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
    <?php endif; ?>
    <div class="item form-group">
      <div class="col-md-6 col-sm-6 offset-md-3">
        <button type="submit" class="btn btn-success"><?= isset($item) ? 'Update' : 'Submit' ?></button>
      </div>
    </div>
</form>