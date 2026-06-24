<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form action="<?php echo base_url().'index.php/Company/update_user'; ?>" 
                      method="post" autocomplete="off">

                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

                    <!-- Name -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" name="user_name" class="form-control"
                                   required value="<?php echo $user['user_name']; ?>">
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Login ID (Company Email) <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" name="user_login" class="form-control"
                                   required value="<?php echo $user['user_login']; ?>">
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Gender</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div id="gender" class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-secondary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default"><input type="radio" name="gender" value="M" class="join-btn" <?php if($user['gender'] == 'M') echo 'checked'; ?>> &nbsp; Male &nbsp;</label>
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default"><input type="radio" name="gender" value="F" class="join-btn" <?php if($user['gender'] == 'F') echo 'checked'; ?>> Female</label>
                                </div>
                            </div>
                        </div>
                    <!-- DOB -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Date Of Birth <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input name="dob" class="form-control"
                                   required value="<?php echo $user['dob']; ?>"
                                   type="text"
                                   onfocus="this.type='date'"
                                   onblur="this.type='text'">
                        </div>
                    </div>

                    <!-- Reset Password -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Reset Password (Optional)
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="password" name="new_password"
                                   class="form-control"
                                   placeholder="New Password">
                            <small class="text-muted">
                                Leave blank if you do not want to change the password
                            </small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Confirm Password
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="password" name="confirm_password"
                                   class="form-control"
                                   placeholder="Confirm New Password">
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <!-- Submit -->
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" class="btn btn-success">
                                Update User
                            </button>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

                </form>

            </div>
        </div>
    </div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let pwd = document.querySelector('input[name="new_password"]').value;
    let cpwd = document.querySelector('input[name="confirm_password"]').value;

    if (pwd !== '' && pwd.length < 6) {
        alert('Password must be at least 6 characters long.');
        e.preventDefault();
        return;
    }

    if (pwd !== '' && pwd !== cpwd) {
        alert('Reset password and confirm password do not match.');
        e.preventDefault();
        return;
    }
});
</script>
