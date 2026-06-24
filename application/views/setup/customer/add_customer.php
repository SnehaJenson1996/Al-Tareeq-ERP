<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />

				<form action="<?php echo base_url().'index.php/'; ?>Setup/add_customer_data" method="post" autocomplete='off'>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="customer-name">Customer Name <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="text" id="customer_name" name="customer_name" required="required" class="form-control ">
						</div>
					</div>
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="customer_code">Customer Code <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="text" id="customer_code" name="customer_code" required="required" class="form-control">
						</div>
					</div>
					<div class="item form-group">
						<label for="customer_email" class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
						<div class="col-md-6 col-sm-6 ">
							<input id="customer_email" class="form-control" type="email" name="customer_email" readonly onfocus="this.removeAttribute('readonly');">
						</div>
					</div>
					<div class="item form-group">
						<label for="contact_number" class="col-form-label col-md-3 col-sm-3 label-align">Contact Number</label>
						<div class="col-md-6 col-sm-6 ">
                            <input id="contact_number" class="form-control" pattern="[0-9]" title="Enter a valid phone number" type="number" name="contact_number">
						</div>
					</div>

					<div class="item form-group">
					<label class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
						<div class="col-md-6 col-sm-6 ">
                        <textarea id="customer_address" class="form-control" name="customer_address"></textarea>
						</div>
					</div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" >Contacts Person</label>
                        <div class="col-md-11 col-sm-11 ">
                            <table>  
                                <tr id='addr0'>
                                    <td><input type="text" id="contact_name0" name="contact_name[]"  placeholder='Enter Name'  class="form-control " required></td>
                                    <td><input type="number" id="contact_phone0" name="contact_phone[]"  pattern="[0-9]" title="Enter a valid phone number" placeholder='Enter Contact Number'  class="form-control " required></td>
                                    <td><input type="email" id="contact_email0" name="contact_email[]"  placeholder='Enter Email ID'  class="form-control " required></td>
                                    <td><a href='javascript:add_row()' title='Add more'  class='btn btn-sm bg-blue'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></td>
                                </tr>
                            </table>
                            <input type='hidden' id='num_rows' name='num_rows' value=0 />
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
    function add_row(){
        var num_rows = $('#num_rows').val();
        var i = num_rows+1;
        var new_row = "<tr id='addr"+i+"'><td><input class='form-control' id='contact_name"+i+"' name='contact_name[]' type='text' placeholder='Enter Name'></td><td><input class='form-control' id='contact_number"+i+"' name='contact_number[]' type='text' pattern='[0-9]' title='Enter a valid phone number' placeholder='Enter Contact Number'></td><td><input class='form-control' id='contact_email"+i+"' name='contact_email[]' type='text' placeholder='Enter Email ID'></td><td> <a onclick='delete_row("+i+")' title='Delete' class='btn btn-sm bg-blue'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
        $("#addr" + num_rows).after(new_row);
        $('#num_rows').val(i);
    }
</script>