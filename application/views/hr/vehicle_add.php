<div class="x_panel">
    <div class="x_title">
        <h2>Add Vehicle Details</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Hr/add_vehicles_details" autocomplete="off" enctype="multipart/form-data">

            <!-- Vehicle Basic Info -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Vehicle Name <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <input type="text" name="vehicle_name" id="vehicle_name" class="form-control form-control-sm" placeholder="Enter vehicle name" tabindex="1" required>
                </div>

                <label class="col-md-2 col-form-label">Traffic Plate Number <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <input type="text" name="vehicle_no" id="vehicle_no" class="form-control form-control-sm" placeholder="Enter plate number" tabindex="2" required>
                </div>
            </div>

            <!-- License Info -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Vehicle License Expiry <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <div class="input-group date datepicker1">
                        <input type="text" class="form-control form-control-sm datepicker1" id="vl_exp" name="vl_exp" value="<?php echo date('d-m-Y') ?>" tabindex="3" required>
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>

                <label class="col-md-2 col-form-label">License Expiry Reminder</label>
                <div class="col-md-3">
                    <select name="exp_reminder" id="exp_reminder" class="form-select form-control-sm" tabindex="4">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>

            <!-- Insurance Details -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Insurance Issue Date</label>
                <div class="col-md-3">
                    <div class="input-group date datepicker1">
                        <input type="text" class="form-control form-control-sm datepicker1" id="insurance_date" name="insurance_date" value="<?php echo date('d-m-Y') ?>" tabindex="5">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>

                <label class="col-md-2 col-form-label">Insurance Expiry Date</label>
                <div class="col-md-3">
                    <div class="input-group date datepicker1">
                        <input type="text" class="form-control form-control-sm datepicker1" id="insurance_expdate" name="insurance_expdate" value="<?php echo date('d-m-Y') ?>" tabindex="6">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

            <!-- Insurance Number & Remark -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Insurance No.</label>
                <div class="col-md-3">
                    <input type="text" name="insurance_no" id="insurance_no" class="form-control form-control-sm" tabindex="7" placeholder="Enter insurance number">
                </div>

                <label class="col-md-2 col-form-label">Remark</label>
                <div class="col-md-3">
                    <textarea name="remark" id="remark" rows="1" class="form-control form-control-sm" placeholder="Enter remark" tabindex="8"></textarea>
                </div>
            </div>

            <!-- File Upload -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Upload Document</label>
                <div class="col-md-3">
                    <input type="file" name="file_doc" id="file_doc" class="form-control form-control-sm" tabindex="9" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm" id="add" tabindex="10">Submit</button>
                </div>
            </div>

        </form>
    </div>
</div>
