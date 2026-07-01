<div class="clearfix"></div>

<div class="row">
<div class="col-md-12 col-sm-12">
<div class="x_panel">
<div class="x_content">
<br />

<form action="<?php echo base_url().'index.php/Setup/' . (isset($category) ? 'update_category/'.$category['category_id'] : 'add_category_data'); ?>" 
      method="post" autocomplete="off" id="category_form">

<div class="row">

    <!-- Category Code -->
    <div class="col-md-6">
        <label>Category Code <span class="required">*</span></label>
        <input type="text"
       name="category_code"
       class="form-control"
       value="<?= isset($category) ? $category['category_code'] : $category_code; ?>"
       readonly>
    </div>

    <!-- Category Name -->
    <div class="col-md-6">
        <label>Category Name <span class="required">*</span></label>
        <input type="text" name="category_name" class="form-control" required
            value="<?= isset($category) ? htmlspecialchars($category['category_name']) : '' ?>">
    </div>

    <!-- Description -->
    <div class="col-md-6 mt-2">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"><?= isset($category) ? htmlspecialchars($category['description']) : '' ?></textarea>
    </div>

    <!-- Status -->
    <div class="col-md-6 mt-2">

        <label>Status</label><br>

        <label style="margin-right:15px;">
            <input type="checkbox" name="is_active" value="1"
                <?= isset($category) && $category['is_active'] == 1 ? 'checked' : '' ?>>
            Active
        </label>

        <label>
            <input type="checkbox" name="is_marked_delete" value="1"
                <?= isset($category) && $category['is_marked_delete'] == 1 ? 'checked' : '' ?>>
            Mark Delete
        </label>

    </div>

</div>

<!-- Submit -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-success">
            <?= isset($category) ? 'Update' : 'Save' ?>
        </button>
    </div>
</div>

</form>

</div>
</div>
</div>
</div>