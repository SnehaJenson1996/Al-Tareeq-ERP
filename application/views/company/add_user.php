<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />

				<form action="<?php echo base_url().'index.php/'; ?>Company/save_user" method="post" autocomplete='off'>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Name <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="text" id="user_name" name="user_name" required="required" readonly onfocus="this.removeAttribute('readonly');" class="form-control ">
						</div>
					</div>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Login ID(Company Email) <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="email" id="user_login" name="user_login" required="required" readonly onfocus="this.removeAttribute('readonly');" class="form-control">
						</div>
					</div>
					<div class="item form-group">
						<label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Password</label>
						<div class="col-md-6 col-sm-6 ">
							<input id="user_password" class="form-control" type="password" name="user_password">
						</div>
					</div>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align">Gender</label>
						<div class="col-md-6 col-sm-6 ">
							<div id="gender" class="btn-group" data-toggle="buttons">
								<label class="btn btn-secondary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
									<input type="radio" name="gender" value="M" class="join-btn"> &nbsp; Male &nbsp;
								</label>
								<label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
									<input type="radio" name="gender" value="F" class="join-btn"> Female
								</label>
							</div>
						</div>
					</div>

					<div class="item form-group">
					<label class="col-form-label col-md-3 col-sm-3 label-align">Date Of Birth <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input id="dob" name='dob' class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
							<script>
							function timeFunctionLong(input) {
								setTimeout(function() {
								input.type = 'text';}, 60000);}
							</script>
						</div>
					</div>
					
					<div class="ln_solid"></div>
					<div class="item form-group">
						<div class="col-md-6 col-sm-6 offset-md-3">
							<button class="btn btn-primary" type="reset">Reset</button>
							<button type="submit" class="btn btn-success">Submit</button>
						</div>
					</div>  
				</form>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
    $('#user_login').on('blur', function() {
        var user_login = $(this).val();

        if (user_login !== '') {
            $.ajax({
                url: '<?= base_url("index.php/Setup/check_user_login_duplicate") ?>', 
                type: 'POST',
                data: { user_login: user_login },
                success: function(response) {
                    
                    if (response==1) {
                        alert('This User Login already exists.');
                        $('#user_login').val('');
                    } 
                }
            });
        }
    });
});

</script>