<style>
    .select2Width {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 240px !important;
        min-width: 240px !important;
    }

    .form-control-sm, .form-select {
        font-size: 13px !important;
    }

    label {
        font-weight: 500;
        font-size: 13px;
    }

    .x_panel {
        padding: 15px 25px;
    }

    .x_title h2 {
        font-size: 16px;
        font-weight: 600;
        margin-top: 5px;
    }

    .input-group-addon {
        background: #f7f7f7;
        border: 1px solid #ced4da;
        border-left: 0;
        display: flex;
        align-items: center;
        padding: 0 10px;
    }

    textarea.form-control-sm {
        resize: vertical;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Employee Overtime Entry</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" 
              action="<?php echo base_url() . 'index.php/Hr/add_emp_overtime_data'; ?>" 
              autocomplete="off" enctype="multipart/form-data">

            <!-- Employee Name -->
            <div class="form-group row">
                <label class="col-md-3 col-sm-3 col-xs-12 col-form-label">
                    Employee Name <span class="text-danger">*</span>
                </label>
                <div class="col-md-5 col-sm-9 col-xs-12">
                    <select class="form-control" 
                            id="employee_id" name="employee_id" required>
                        <option value="">Select</option>
                        <?php foreach ($records as $s) { ?>
                            <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <!-- Overtime Date -->
            <div class="form-group row">
                <label class="col-md-3 col-sm-3 col-xs-12 col-form-label">
                    Date <span class="text-danger">*</span>
                </label>
                <div class="col-md-5 col-sm-9 col-xs-12">
                    <div class="input-group date">
                        <input type="date" 
                               class="form-control form-control-sm" 
                               id="date_ot" name="date_ot" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                        <div class="input-group-addon">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Overtime Hours -->
            <div class="form-group row">
                <label class="col-md-3 col-sm-3 col-xs-12 col-form-label">
                    Overtime (Hours)
                </label>
                <div class="col-md-5 col-sm-9 col-xs-12">
                    <input type="number" step="0.01" min="1"
                           class="form-control form-control-sm" 
                           id="ot" name="ot" placeholder="Enter hours"
                           oninput="this.value = this.value < 0 ? 0 : this.value">
                </div>
            </div>

            <!-- Remark -->
            <div class="form-group row">
                <label class="col-md-3 col-sm-3 col-xs-12 col-form-label">Remark</label>
                <div class="col-md-5 col-sm-9 col-xs-12">
                    <textarea id="remark" name="remark" rows="2"
                              class="form-control form-control-sm" 
                              placeholder="Enter remark"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <label class="col-md-3 col-sm-3 col-xs-12 col-form-label"></label>
                <div class="col-md-5 col-sm-9 col-xs-12">
                    <button type="submit" id="add" class="btn btn-primary btn-sm">
                        <i class="fa fa-save"></i> Submit
                    </button>
                    <a href="<?php echo base_url() . 'index.php/Hr/view_emp_overtime_list'; ?>" 
                       class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

 $(document).ready(function() {
 $('#employee_id').select2({
        placeholder: "Select Employee",
        allowClear: true,
        width: '100%' // ensures it fits the Bootstrap column
    });
    });
    </script>
