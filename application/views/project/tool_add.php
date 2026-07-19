<div class="x_panel">
    

    <div class="x_content">



<form method="post" action="<?php echo base_url().'index.php/Project/add_Tool_data'; ?>">

            <div class="form-group row">

             <label class="col-md-2 col-form-label">
    Tool Code
</label>

<div class="col-md-3">
    <input type="text" name="tool_code" class="form-control" 
           value="<?= isset($auto_code) ? $auto_code : '' ?>" readonly>
</div>

                <label class="col-md-2 col-form-label">
                    Tool <span class="text-danger">*</span>
                </label>

                <div class="col-md-3">
                    <input type="text" name="tool_name" class="form-control" required>
                </div>

            </div>

            <div class="form-group row">

                <label class="col-md-2 col-form-label">
                    Description
                </label>

                <div class="col-md-6">
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>

        </form>

    </div>
</div>