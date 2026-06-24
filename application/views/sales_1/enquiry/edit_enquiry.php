<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form action="<?php echo base_url().'index.php/'; ?>Sales/update_enquiry_data" method="post" autocomplete='off' enctype="multipart/form-data">
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="enquiry_code">Enq Code <span class="required">*</span></label>
                        <div class="col-md-5 col-sm-5 ">
                            <input type="text" id="enquiry_code" name="enquiry_code" readonly value='<?php echo $enquiry['enquiry_code']; ?>' class="form-control ">
                        </div>

                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="enquiry_date">Date <span class="required">*</span></label>
                        <div class="col-md-5 col-sm-5 ">
                            <input type="text" id="enquiry_date" name="enquiry_date" required="required" value=<?php echo $enquiry['enquiry_date']; ?> class="form-control ">
                        </div>
                    </div> 
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="enquiry_customer">Customer <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select id="enquiry_customer" name="enquiry_customer" required="required"  class="form-control ">
                                <option value=''>Select</option>
                                <?php foreach($active_customers as $customer){ ?>
                                    <option value='<?php echo $customer->customer_id ?>' <?php if($customer->customer_id == $enquiry['enquiry_customer']) echo 'selected'; ?>><?php echo $customer->customer_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="enquiry_scope">Project name</label>
                        <div class="col-md-6 col-sm-6 ">
                            <input  id="project_name" name="project_name" required="required"  class="form-control " value='<?php echo $enquiry['project_name'] ?>'/>
                        </div>
                       
                    </div>  
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="enquiry_scope">Scope</label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea  id="enquiry_scope" name="enquiry_scope" required="required"  class="form-control "><?php echo $enquiry['enquiry_scope'] ?></textarea>
                        </div>
                       
                    </div>  
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Attachments</label>
                        <div class="col-md-11 col-sm-11 ">
                            <table>  
                                
                                <?php $i=0;foreach($enquiry_files as $enquiry_file){ ?>
                                <tr id="<?php echo 'addr'.$i; ?>">
                                    <td><input type="text" readonly  value="<?php echo $enquiry_file->file_title; ?>"  class="form-control "><input type='hidden' id='<?php echo 'attachment_id'.$i; ?>' name='attachment_id[]' value='<?php echo $enquiry_file->attachment_id; ?>' /></td>
                                    <td><a class="form-control " download href="<?php echo base_url() ?>public/uploaded_documents/enquiry_files/<?php echo $enquiry_file->file_path; ?>">View</a></td>
                                    <td><a href='javascript:delete_row(<?php echo $i; ?>)' title='Delete'  class='btn btn-sm bg-blue'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                </tr>
                                <?php $i++;} ?>
                                <tr id="<?php echo 'addr'.$i; ?>">
                                <td><input type="text" id="file_title0" name="file_title[]"  placeholder='Enter file title'  class="form-control "></td>
                                    <td><input type="file" id="enquiry_file0" name="enquiry_file[]" class="form-control "></td>
                                    <td><a href='javascript:add_row()' title='Add more'  class='btn btn-sm bg-blue'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></td>
                                </tr>
                               
                            </table>
                            <input type='hidden' id='num_rows' name='num_rows' value=<?php echo $i; ?> />
                            <input type='hidden' id='deleted_attachments' name='deleted_attachments'  />
                        </div>
                    </div>    
                    
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" for="requested_by">Requested by</label>
                        <div class="col-md-6 col-sm-6 ">
                            <select  id="sales_person" name="sales_person" required="required"  class="form-control ">
                                <option value=''>Select</option>
                                <?php foreach($active_users as $user){ ?>
                                    <option value='<?php echo $user->user_id; ?>' <?php if($user->user_id == $enquiry['sales_person']) echo 'selected'; ?>><?php echo $user->user_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                    
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                    <input type='hidden' id='enquiry_id' name='enquiry_id' value=<?php echo $enquiry['enquiry_id']; ?> />
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            
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
        var new_row = "<tr id='addr"+i+"'><td><input class='form-control' id='file_title"+i+"' name='file_title[]' type='text'></td><td><input class='form-control' id='enquiry_file"+i+"' name='enquiry_file[]' type='file'></td><td> <a onclick='delete_row("+i+")' title='Delete' class='btn btn-sm bg-blue'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
        $("#addr" + num_rows).after(new_row);
        $('#num_rows').val(i);
    }

    function delete_row(row){
        var attachment_id = $('#attachment_id'+row).val();
        var deleted_attachments = $('#deleted_attachments').val();
		if(deleted_attachments == ''){
					deleted_attachments = attachment_id;
		}
		else{
			deleted_attachments = deleted_attachments +','+attachment_id;
		}
		$('#deleted_attachments').val(deleted_attachments);
        $('#addr'+row).remove();
    }
</script>