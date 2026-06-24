    <style>
        .form-control{
            padding:0 px;
        }
        .select2{
            font-size:0.8 rem;
        }
        .table-bordered{
            font-size:0.7 rem;
        }
        .table-responsive-custom {
        width: 100%;
        
    }

    .table-responsive-custom table {
      
    }
    .right-align{
        text-align:right;
    }
    body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }

    </style>
    <div class="clearfix"></div>
        
        <form action='<?php echo base_url().'index.php/'; ?>Warranty/issue_warranty_claim' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Warranty Claim <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="warranty_id" id="warranty_id" onchange='this.form.submit()'>
                        <option value="">Select Warranty Claim</option>
                        <?php foreach($warraty_claims as $war) { ?>
                            <option value="<?php echo $war->warranty_id;?>" <?php if(isset($warranty) && $war->warranty_id == $warranty['warranty_id']) echo 'selected'; ?>><?php echo $war->warranty_code;?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($warranty)){ ?>
        <form action='<?php echo base_url().'index.php/'; ?>Warranty/issue_warranty_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='warranty_id' value='<?php echo $warranty['warranty_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Warranty code </span>
                        <span class="value text-success"> <?php echo $warranty['warranty_code']; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Warranty date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y',strtotime($warranty['warranty_date'])); ?>   </span>
                    </li>
                    <!-- <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php //echo $request['customer_name']; ?>  </span>
                    </li> -->
                    
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:5%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Item Model</td>
                                        <td style='width:20%'>Description</td>
                                        <td style='width:5%;text-align:center'>Quantity</td>
                                        <td style='width:20%;text-align:center'>Serial No</td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                    <?php $i=0;
                                foreach($warranty_details as $detail){ ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='warranty_detail_id[]' value='<?php echo $detail->warranty_detail_id; ?>' /></td>
                                        <td class='serial-number'><?php echo $detail->item_model; ?></td>
                                        <td><?php echo $detail->item_description ?><input type='hidden' name='item_id[]' id='<?php echo 'item_id'.$i; ?>' value='<?php echo $detail->item_id; ?>' /></td>
                                        <td style='text-align:center' >1</td>
                                        <td style='text-align:center'>
                                            <input type="text" id='<?php echo 'scanned_serial'.$i; ?>' name='scanned_serial[]' class='form-control scannedSerial' placeholder="Scan barcode..." required />
                                            
                                        </td>
                                    </tr>
                                <?php $i++; }?> 
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=<?= $i; ?> />
                    <div class="clearfix"></div>
                    <br>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Issue Status</label>
                        <div class="col-md-2">
                        <select class='form-control' name='issue_status' id='issue_status' required>
                            <option value=''>Select</option>
                            <option value='1'>Complete</option>
                            <option value='0'>Incomplete</option>
                        </select>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary">Issue Stock</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
        </form>
        <?php } ?>
                                     
    

    

<script>

 $(document).on('keypress', '.scannedSerial', function(e){
        if (e.which === 13) {  // Enter key pressed (barcode scanners usually add Enter)
            event.preventDefault();
            const inputId = $(this).attr('id');  
            const row_no = inputId.substring(14);
            const scanned = $(this).val().trim().toUpperCase();  // Normalize serial

            if (!scanned) return;
                //check if the serial number is invoiced
                $.ajax({
                url: '<?= base_url("index.php/Stock/get_item_detail_by_serial_number") ?>', 
                type: 'POST',
                data: { serial_no: scanned },
                dataType:'json',
                success: function(details) {
                    if(details !== null){
                        var requested_item = $('#item_id'+row_no).val();
                        if(requested_item === details.item_id){
                            if(details.status === '0'){
                                if(details.inv_type=='Warranty'){
                                    $('#'+inputId).css('background-color', '#d4edda');
                                }else{
                                    alert('Item is not a warranty stock!');
                                }
                            }
                            else{
                                alert('Item Already Allocated');
                                return;
                            }

                        }else{
                            alert('Scanned Item does not match with requested Item');
                        }
                    }
                    else{
                        alert('Item details not found in Stock!');
                    }
                    
                
                    
                }
                });
        }
});
</script>   
      
            