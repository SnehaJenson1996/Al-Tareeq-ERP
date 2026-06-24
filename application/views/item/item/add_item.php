<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />

				<form action="<?php echo base_url().'index.php/Item/' . (isset($item) ? 'update_item/'.$item['item_id'] : 'add_item_data'); ?>" method="post" autocomplete="off" id="item" enctype="multipart/form-data">

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
    <label class="col-form-label col-md-3 col-sm-3 label-align">
        Brand <span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6">
        <select name="item_brand" class="form-control" required>
            <option value="">-- Select Brand --</option>
            <?php foreach ($active_brands as $brand): ?>
                <option value="<?= $brand->brand_id ?>"
                    <?= isset($item) && $item['item_brand'] == $brand->brand_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand->brand_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
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

						<div class="item form-group">
							<div class="col-md-6 col-sm-6 offset-md-3">
								<button type="submit" id="saveBtn" class="btn btn-success"><?= isset($item) ? 'Update' : 'Submit' ?></button>
							</div>
						</div>
					</form>


			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
    $('#item_code').on('blur', function() {
        var item_code = $(this).val();

        if (item_code !== '') {
            $.ajax({
                url: '<?= base_url("index.php/Item/check_item_code_duplicate") ?>', // update with your controller path
                type: 'POST',
                data: { item_code: item_code },
                success: function(response) {
                    
                    if (response==1) {
                        alert('This Item Code already exists.');
                        $('#item_code').val('');
                    } 
                }
            });
        }
    });
});

document.getElementById("item").addEventListener("submit", function (e) {

    var btn = document.getElementById("saveBtn");

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    // Disable immediately
    btn.disabled = true;
    btn.innerHTML = "Processing...";

});
</script>