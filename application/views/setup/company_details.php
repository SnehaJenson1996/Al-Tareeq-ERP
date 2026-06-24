<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form action="<?= base_url('index.php/Setup/add_company_details_data') ?>"
      method="post"
      enctype="multipart/form-data"
      autocomplete="off">

                    <!-- COMPANY NAME -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">
                            Company Name <span class="required">*</span>
                        </label>

                        <div class="col-md-8">
                            <input type="text"
                                   name="company_name"
                                   class="form-control"
                                   required
                                   value="<?= $company_details['company_name'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">
                            Address <span class="required">*</span>
                        </label>

                        <div class="col-md-8">
                            <textarea name="company_address"
                                      class="form-control"
                                      required
                                      rows="3"><?= $company_details['company_address'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <!-- CITY + STATE -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">City</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_city"
                                   class="form-control"
                                   value="<?= $company_details['company_city'] ?? '' ?>">
                        </div>

                        <label class="col-md-1 col-form-label">State</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_state"
                                   class="form-control"
                                   value="<?= $company_details['company_state'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- PO BOX + COUNTRY -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">PO Box</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_po"
                                   class="form-control"
                                   value="<?= $company_details['company_po'] ?? '' ?>">
                        </div>

                        <label class="col-md-1 col-form-label">Country</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_country"
                                   class="form-control"
                                   value="<?= $company_details['company_country'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- EMAIL + PHONE -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Email</label>

                        <div class="col-md-3">
                            <input type="email"
                                   name="company_email_id"
                                   class="form-control"
                                   value="<?= $company_details['company_email_id'] ?? '' ?>">
                        </div>

                       
                    </div>

                    <div class="form-group row">
    <label class="col-md-2 col-form-label">Phone</label>

    <div class="col-md-3">
        <input type="text"
               name="company_telephone"
               class="form-control"
               value="<?= $company_details['company_telephone'] ?? '' ?>">
    </div>

    <label class="col-md-1 col-form-label">Alt Phone</label>

    <div class="col-md-3">
        <input type="text"
               name="company_telephone_alt"
               class="form-control"
               value="<?= $company_details['company_telephone_alt'] ?? '' ?>">
    </div>
</div>

                    <!-- WEBSITE + TRN -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Website</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_website"
                                   class="form-control"
                                   value="<?= $company_details['company_website'] ?? '' ?>">
                        </div>

                        <label class="col-md-1 col-form-label">TRN</label>

                        <div class="col-md-3">
                            <input type="text"
                                   name="company_trn"
                                   class="form-control"
                                   value="<?= $company_details['company_trn'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group row">
  <label class="col-md-2 col-form-label">Company Logo</label>
<div class="col-md-3">
    <input type="file" name="company_logo" class="form-control">

    <?php 
    if (!empty($company_details['company_logo']) && file_exists(FCPATH . $company_details['company_logo'])) { 
    ?>
        <div style="margin-top:5px;">
            <small>Current:</small><br>

            <a href="<?= base_url($company_details['company_logo']) ?>" target="_blank">
                <img src="<?= base_url($company_details['company_logo']) ?>" height="40" style="cursor:pointer;">
            </a>

        </div>
    <?php } ?>
</div>

    <label class="col-md-1 col-form-label">Header</label>
  <div class="col-md-3">
    <input type="file" name="company_header" class="form-control">

    <?php 
    if (!empty($company_details['company_header']) && file_exists(FCPATH . $company_details['company_header'])) { 
    ?>
        <div style="margin-top:5px;">
            <small>Current Header:</small><br>

            <a href="<?= base_url($company_details['company_header']) ?>" target="_blank">
                <img src="<?= base_url($company_details['company_header']) ?>" height="40" style="cursor:pointer;">
            </a>

        </div>
    <?php } ?>
</div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label">Footer</label>
   <div class="col-md-3">
    <input type="file" name="company_footer" class="form-control">

    <?php 
    if (!empty($company_details['company_footer']) && file_exists(FCPATH . $company_details['company_footer'])) { 
    ?>
        <div style="margin-top:5px;">
            <small>Current Footer:</small><br>

            <a href="<?= base_url($company_details['company_footer']) ?>" target="_blank">
                <img src="<?= base_url($company_details['company_footer']) ?>" height="40" style="cursor:pointer;">
            </a>

        </div>
    <?php } ?>
</div>
</div>


<div class="form-group row">
		  	<table class="table table-bordered table-hover" id="tab_logic">
				   <thead>
				    <tr>
				    	    <th title="Item">Bank Name</th>
				    	    <th title="Item">Bank Account</th>    
				    	    <th title="Item">Bank Branch</th>    
				    	    <th title="Item">Bank IBAN</th>    
				    	    <th title="Item">Bank SWIFT</th>    
				    	    <th width='30px'><a id="add_row" title="Add" class="btn btn-xs bg-orange" ><span class="fa fa-plus"></span></a></th>
					</tr>
				    </thead>		 
				    <tbody id="mytbbody">
	     				<?php foreach($bank_details as $r){?>
				    	<tr style='font-size: 13px;'>
						<td><input type="text" tabindex="11" name="bname_old[]" id="bname" tabindex='2' class="form-control" placeholder="" value="<?php echo $r->bank_name;?>" required></td>
						<td><input type="text" tabindex="11" name="bacc_old[]" id="bacc" tabindex='3' class="form-control" placeholder="" value="<?php echo $r->bank_account;?>" ></td>
						<td><input type="text" tabindex="11" name="bbranch_old[]" id="bbranch" tabindex='3' class="form-control" placeholder="" value="<?php echo $r->bank_branch;?>" ></td>
						<td><input type="text" tabindex="11" name="biban_old[]" id="biban" tabindex='3' class="form-control" placeholder="" value="<?php echo $r->bank_iban;?>" ></td>
						<td><input type="text" tabindex="11" name="bswift_old[]" id="bswift" tabindex='3' class="form-control" placeholder="" value="<?php echo $r->bank_swift;?>" ></td>
						<td width='30px'>
						<input type="hidden"  name="trans_id[]" value="<?php echo $r->bid;?>" >
						<a  href="javascript:confirmcancel(<?php echo $r->bid;?>)" title="Delete" class="btn btn-xs bg-orange"><span class="fa fa-trash"></span></a></td>
					</tr>
	     				<?php } ?>
                        <?php if(empty($bank_details)) { ?>
					<tr id='addr0' style='font-size: 13px;'>
						<td><input type="text" tabindex="11" name="bname[]" id="bname" tabindex='2' class="form-control" placeholder=""  ></td>
						<td><input type="text" tabindex="11" name="bacc[]" id="bacc" tabindex='3' class="form-control" placeholder=""  ></td>
						<td><input type="text" tabindex="11" name="bbranch[]" id="bbranch" tabindex='3' class="form-control" placeholder=""  ></td>
						<td><input type="text" tabindex="11" name="biban[]" id="biban" tabindex='3' class="form-control" placeholder=""  ></td>
						<td><input type="text" tabindex="11" name="bswift[]" id="bswift" tabindex='3' class="form-control" placeholder=""  ></td>
						<td width='30px'><a id='delete_row' title="Delete" onclick='remove_row(0)' class="btn btn-xs bg-orange remove1"><span class="fa fa-trash"></span></a></td>
					</tr>
                    <?php } ?>
					<tr id='addr1'></tr>
					</tbody>
				</table>
		</div>
		

                    <!-- SUBMIT -->
                    <div class="ln_solid"></div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-2">
                            <button type="submit" class="btn btn-success">
                                Submit
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
	var i=1;
	$("#add_row").click(function()
	{
	     $('#addr'+i).html("<td><input type='text' name='bname[]' id='bname' tabindex='2' class='form-control' placeholder=''  required></td><td><input type='text' name='bacc[]' id='bacc' tabindex='3' class='form-control' placeholder='' ></td><td><input type='text' name='bbranch[]' id='bbranch' tabindex='3' class='form-control' placeholder='' ></td><td><input type='text' name='biban[]' id='biban' tabindex='3' class='form-control' placeholder='' ></td><td><input type='text' name='bswift[]' id='bswift' tabindex='3' class='form-control' placeholder='' ></td><td><a onclick='remove_row("+i+");' id='delete_row' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	    $('#mytbbody tr:last').after('<tr id="addr'+(i+1)+'"></tr>');
	      i++; 	     	
	});
        $("#delete_row").click(function(){
    		 if(i>1){
			 $("#addr"+(i-1)).html('');
			 i--;
		 }
	 });
	 
	 var j=1;
	$("#add_new_row").click(function()
	{
	     $('#new_addr'+j).html("<td><input type='text' name='image_name[]' id='image_name' class='form-control' required></td><td><input type='file' name='stamp_image[]' id='stamp_image' class='form-control' ></td><td><a onclick='remove_stamp_row("+j+");' id='delete_row1' title='Delete' class='btn btn-xs bg-orange remove1'><span class='fa fa-trash'></span></a></td>");
	    $('#mystamp tr:last').after('<tr id="new_addr'+(j+1)+'"></tr>');
	      j++; 	     	
	});
        $("#delete_row1").click(function(){

    		 if(i>1){
			 $("#new_addr"+(j-1)).html('');
			 j--;
		 }
	 });
   });   
   function remove_row(append_id)
   {    	 
        $('#addr'+append_id).attr("id","addr"+append_id+"x");

        $('#addr'+append_id+"x").remove();
   }  
   function remove_stamp_row(append_id)
   {    	 
        $('#new_addr'+append_id).attr("id","new_addr"+append_id+"x");

        $('#new_addr'+append_id+"x").remove();
   }
   </script>
   
   <script>
function confirmcancel(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");
	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",
     		type: "POST",
     		data: {table_name:'company_bank_details', where_key:'bid', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	
			         alert("Record deleted"); 				
        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
			}
		        else {
			      	alert("Can't Delete record. Data already used!!!");
		       }
		    },
		});
      		return true;
      	}
        else
        	return false;
	    	
}
function confirmcancel_image(id)
{   
	var r= confirm("Are you sure you want to Delete Record?");

	if(r == true) 
        {
      		$.ajax({
     		url: "<?php echo base_url()?>index.php/Ajax/delete_record",

     		type: "POST",
     		data: {table_name:'company_stamp_image', where_key:'img_id', where_val:id} ,
     		success: function(msg) {
     			if(msg==1) 
     			{     	

			         alert("Record deleted"); 				

        			 window.location.href="<?php echo $_SERVER['PHP_SELF']?>";   		                    		  
			}
		        else {
					
			      	alert("Can't Delete record. Data already used!!!");
		       }
		    },
		});
      		return true;

      	}
        else
        	return false;
	    	
}


</script>
