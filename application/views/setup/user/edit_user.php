<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_content">
                    <br />
                    <form action="<?php echo base_url().'index.php/'; ?>Setup/edit_user_data" method="post" autocomplete='off'>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Name <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="user_name" name="user_name" required="required" value='<?php echo $user['user_name']; ?>' class="form-control ">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Login ID(Company Email) <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" id="user_login" name="user_login" required="required" value='<?php echo $user['user_login']; ?>' class="form-control">
                            </div>
                        </div>                            
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Gender</label>
                            <div class="col-md-6 col-sm-6 ">
                                <div id="gender" class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-secondary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default"><input type="radio" name="gender" value="M" class="join-btn" <?php if($user['gender'] == 'M') echo 'checked'; ?>> &nbsp; Male &nbsp;</label>
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default"><input type="radio" name="gender" value="F" class="join-btn" <?php if($user['gender'] == 'F') echo 'checked'; ?>> Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Date Of Birth <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                            <input id="dob" name='dob' class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" value='<?php echo $user['dob']; ?>' type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" >
                            <script>
                            function timeFunctionLong(input) {
                                setTimeout(function() {
                                input.type = 'text';}, 60000);}
                            </script>
                        </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <input type="hidden" id="user_id" name="user_id" value='<?php echo $user['user_id']; ?>'>
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>