<div class="x_panel">
    <div class="x_title">
        <h2>Edit Sales Rep</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">

        <form method="post" action="<?= base_url('index.php/Setup/update_sales_rep_data/'.$rep->sales_rep_id) ?>">

            <div class="form-group row">

                <label class="col-md-2">Code</label>
                <div class="col-md-3">
                    <input type="text" class="form-control"
                           value="<?= $rep->sales_rep_code ?>" readonly>
                </div>

                <label class="col-md-2">Sales Rep Name</label>
                <div class="col-md-3">
                    <input type="text" name="sales_rep_name"
                           class="form-control"
                           value="<?= $rep->sales_rep_name ?>">
                </div>

            </div>

            <div class="form-group row">

                <label class="col-md-2">Employee</label>
                <div class="col-md-3">
                    <select name="emp_id" class="form-control">
                        <?php foreach ($employees as $e) { ?>
                            <option value="<?= $e->employee_id ?>"
                                <?= ($rep->emp_id == $e->user_code) ? 'selected' : '' ?>>
                                <?= $e->user_code ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <label class="col-md-2">Commission Group</label>
                <div class="col-md-3">
                    <select name="commission_group_id" class="form-control">
                        <?php foreach ($commission_group as $cg) { ?>
                            <option value="<?= $cg->commission_group_id ?>"
                                <?= ($rep->commission_group_id == $cg->commission_group_id) ? 'selected' : '' ?>>
                                <?= $cg->commission_group_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

            </div>

            <div class="form-group row">

                <label class="col-md-2">Target</label>
                <div class="col-md-3">
                    <input type="number" name="target_amount"
                           class="form-control"
                           value="<?= $rep->target_amount ?>">
                </div>

                <label class="col-md-2">Commission %</label>
                <div class="col-md-3">
                    <input type="number" name="commission_percent"
                           class="form-control"
                           value="<?= $rep->commission_percent ?>">
                </div>

            </div>

            <div class="form-group row">

                <label class="col-md-2">Discount %</label>
                <div class="col-md-3">
                    <input type="number" name="sales_discount_percent"
                           class="form-control"
                           value="<?= $rep->sales_discount_percent ?>">
                </div>

                <label class="col-md-2">Blocked</label>
                <div class="col-md-3">
                    <input type="checkbox" name="is_blocked" value="1"
                        <?= ($rep->is_blocked == 1) ? 'checked' : '' ?>>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="<?= base_url('index.php/Setup/list_sales_rep') ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>

        </form>

    </div>
</div>