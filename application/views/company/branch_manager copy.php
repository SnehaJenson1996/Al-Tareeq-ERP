<form action="<?php echo base_url().'index.php/Company/add_branch_data'; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
    <!-- Flash error -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <!-- Branch Code & Branch Name -->
    <div class="row">
        <div class="col-md-6">
            <label>Branch Code <span class="required">*</span></label>
            <input type="text" id="branch_code" name="branch_code" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Branch Name <span class="required">*</span></label>
            <input type="text" id="branch_name" name="branch_name" class="form-control">
        </div>
    </div>
    <br>

    <!-- Branch Manager & Contact Number -->
    <div class="row">
        <div class="col-md-6">
            <label>Branch Manager <span class="required">*</span></label>
            <input type="text" id="branch_manager" name="branch_manager" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Branch Contact Number <span class="required">*</span></label>
            <input type="text" id="branch_contact" name="branch_contact" class="form-control">
        </div>
    </div>
    <br>

    <!-- Email & Location -->
    <div class="row">
        <div class="col-md-6">
            <label>Branch Email <span class="required">*</span></label>
            <input type="text" id="branch_email" name="branch_email" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Branch Location <span class="required">*</span></label>
            <select name="branch_location" id="branch_location" class="form-control">
                <option value="">Select Location</option>
                <option value="AbuDhabi">Abu Dhabi</option>
                <option value="Dubai">Dubai</option>
                <option value="Sharjah">Sharjah</option>
                <option value="Ajman">Ajman</option>
                <option value="UmmAlQuwain">Umm Al Quwain</option>
                <option value="RasAlKhaimah">Ras Al Khaimah</option>
                <option value="Fujairah">Fujairah</option>
            </select>
        </div>
    </div>
    <br>

    <!-- Branch Address (full width) -->
    <div class="row">
        <div class="col-md-12">
            <label>Branch Address<span class="required">*</span></label></label>
            <textarea name="branch_address" id="branch_address" class="form-control"></textarea>
        </div>
    </div>
    <br>

    <!-- Logo & Header -->
    <div class="row">
        <div class="col-md-6">
            <label>Branch Logo<span class="required">*</span></label></label>
            <input type="file" id="branch_logo" name="branch_logo" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Branch Header</label>
            <input type="file" id="branch_header" name="branch_header" class="form-control">
        </div>
    </div>
    <br>

    <!-- Footer & Stamp -->
    <div class="row">
        <div class="col-md-6">
            <label>Branch Footer<span class="required">*</span></label></label>
            <input type="file" id="branch_footer" name="branch_footer" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Branch Stamp</label>
            <input type="file" id="branch_stamp" name="branch_stamp" class="form-control">
        </div>
    </div>
    <br>

    <!-- Submit -->
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>
