<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
			<br />

				<form action="<?php echo base_url().'index.php/'; ?>Item/import_item_master" method="post" autocomplete='off' enctype="multipart/form-data">
					
					<div class="item form-group">
						<label class="col-form-label col-md-3 col-sm-3 label-align" for="item_file">Select File<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 ">
							<input type="file" id="item_file" name="item_file" required="required" class="form-control">
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
  

</script>