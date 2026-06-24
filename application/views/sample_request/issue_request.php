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
        
        <form action='<?php echo base_url().'index.php/'; ?>SampleRequest/issue_request' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Sample Request <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="request_id" id="request_id" onchange='this.form.submit()'>
                        <option value="">Select Approved Request</option>
                        <?php foreach($approved_requests as $req) { ?>
                            <option value="<?php echo $req['request_id'];?>" <?php if(isset($request) && $req['request_id'] == $request['request_id']) echo 'selected'; ?>><?php echo $req['request_code'];?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($request)){ ?>
        <form action='<?php echo base_url().'index.php/'; ?>SampleRequest/issue_request_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='request_id' value='<?php echo $request['request_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Request code </span>
                        <span class="value text-success"> <?php echo $request['request_code']; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Request date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y'); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $request['customer_name']; ?>  </span>
                    </li>
                    
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:5%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Item</td>
                                        <td style='width:20%'>Description</td>
                                        <td style='width:5%;text-align:center'>Requested Qty</td>
                                        <td style='width:5%;text-align:center'>Stock</td>
                                        <td style='width:10%;text-align:center'>Scan Barcode</td>
                                        <td style='width:5%;text-align:center'>Issue Qty</td>
                                        <td style='width:20%;text-align:center'>Serial No</td>
                                        <td style='width:10%;text-align:center'>Unit</td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                    <?php $i=0;
                                foreach($request_details as $detail){ 
                                    $max_issue = $detail->quantity-$detail->issued_qty; 
                                    if($detail->stock < $max_issue)
                                        $max_issue = $detail->stock;
                                    ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='request_detail_id[]' value='<?php echo $detail->request_detail_id; ?>' /></td>
                                        <td class='serial-number' style='text-align:center'>
                                            <select class="form-control item-select2" name="item[]" id='<?php echo 'item'.$i; ?>' onchange='get_item_by_id(<?php echo $i; ?>)' disabled>
                                        <option value=''>Select</option>
                                        <option value='<?php echo $detail->item_id ?>'  selected><?php echo $detail->item_model; ?></option>
                                        </select>
                                        </td>
                                        <td><?php echo $detail->item_description ?><input type='hidden' name='item_id[]' value='<?php echo $detail->item_id; ?>' /></td>
                                        <td style='text-align:center' ><?php echo $detail->quantity-$detail->issued_qty;?></td>
                                        <td style='text-align:center' ><input type='text' class='form-control' name='stock_quantity[]' id='<?php echo 'stock_quantity'.$i; ?>' value='<?php echo $detail->stock; ?>' /></td>
                                        <td style='text-align:center'>
                                            <input type="text" id='<?php echo 'scanned_serial'.$i; ?>' class='form-control scannedSerial' placeholder="Scan barcode..." autofocus />
                                            <div id='<?php echo 'scanStatus'.$i; ?>'></div>
                                        </td>
                                        <td style='text-align:center'><input type='number' max='<?php echo $detail->stock; ?>' class='form-control' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value=0 readonly /><input type='hidden' name='max_issue[]' id='<?php echo 'max_issue'.$i; ?>' value='<?php echo $max_issue; ?>' /></td>
                                        <td><ul id='<?php echo 'scannedList'.$i; ?>'></ul><input type='hidden' name='scannedSerials[]' id='<?php echo 'scannedSerials'.$i; ?>' /></td>
                                        
                                        
                                        <td style='text-align:center' ><?php echo $detail->unit_name; ?></td>
                                    </tr>
                                <?php $i++; }?> 
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=<?= $i; ?> />
                    <div class="clearfix"></div>
                    <br>
                    
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
   

   function resetInput(row_no) {
        setTimeout(function() {
            $('#scanned_serial'+row_no).val('').focus();
            $('#inputId').css('background-color', '#ffffff');
        }, 1000);
       
      
    }

    $('.scannedSerial').on('keypress', function(e) {
        if (e.which === 13) {  // Enter key pressed (barcode scanners usually add Enter)
            event.preventDefault();
            const inputId = $(this).attr('id');  
            const row_no = inputId.substring(14);
            const scanned = $(this).val().trim().toUpperCase();  // Normalize serial

            if (!scanned) return;
            
            let scannedSerials = JSON.parse($('#scannedSerials' + row_no).val() || '[]');
            if (scannedSerials.includes(scanned)) {
                 $('#' + inputId).css('background-color', '#f8d7da'); // red
            } else  {
                $('#'+inputId).css('background-color', '#f8d7da');
                //check if the serial number read is not allocated to any order
                $.ajax({
                url: '<?= base_url("index.php/Stock/get_item_detail_by_serial_number") ?>', 
                type: 'POST',
                data: { serial_no: scanned },
                success: function(details) {
                   if(details !== null){
                        var max_issue = $('#max_issue'+row_no).val();
                        if(max_issue > scannedSerials.length){
                            scannedSerials.push(scanned);
                            $('#quantity'+row_no).val(scannedSerials.length);
                            $('#scannedSerials'+row_no).val(JSON.stringify(scannedSerials));
                            $('#'+inputId).css('background-color', '#d4edda');
                            $('#scannedList'+row_no).append(`<li>${scanned}</li>`);
                        }
                        else{
                            alert("Already issued maximum qunaity");
                        }
                        
                   }
                   else{
                     alert('Item details not found in Stock!');
                   }
                    
                }
                });
                
               
            }

            resetInput(row_no);
        }
    });
    

    

  </script>   
      
            