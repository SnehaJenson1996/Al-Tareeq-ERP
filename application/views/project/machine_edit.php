<div class="x_panel">
    <div class="x_title">
        <h2>Edit Machine</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>

        <form method="post" action="<?= base_url('index.php/Project/update_machine_data/'.$area->machine_id) ?>">

            <div class="form-group row">

                <label class="col-md-2 col-form-label">Machine Code</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"
                           value="<?= $area->machine_code ?>" readonly>
                </div>

                <label class="col-md-2 col-form-label">Machine Name</label>
                <div class="col-md-3">
                    <input type="text" name="machine_name"
                           class="form-control"
                           value="<?= $area->machine_name ?>" required>
                </div>

            </div>

            <div class="form-group row">

                <label class="col-md-2 col-form-label">Description</label>

                <div class="col-md-6">
                    <textarea name="description" class="form-control" rows="3"><?= $area->description ?></textarea>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="<?= base_url('index.php/Project/list_machine') ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>

        </form>

    </div>
</div>