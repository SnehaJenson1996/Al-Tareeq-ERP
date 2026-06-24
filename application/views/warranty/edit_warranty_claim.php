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
        
    <form action='<?php echo base_url().'index.php/'; ?>Warranty/update_warranty_claim_data' method='post' >
         <div class="x_content">
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Warranty Code </span>
                        <span class="value text-success"> <?php echo $warranty['warranty_code']; ?> </span>
                    </li>
                    <li>
                        <span class="name"> Warranty date </span>
                        <span class="value text-success"> <?php echo date('d-m-Y',strtotime($warranty['warranty_date'])); ?>   </span>
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

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px;text-align:center">
                                <thead>
                                    <tr>
                                        <td style='width:5%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Item</td>
                                        <td style='width:20%'>Description</td>
                                        <td style='width:5%;text-align:center'>Quantity</td>
                                        <td style='width:20%;text-align:center'>Invoice Code</td>
                                        <td style='width:25%;text-align:center'>Remarks</td>
                                        <td style='width:5%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                    <?php $i=0;foreach($warranty_details as $detail){ ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='warranty_detail_id[]' value='<?php echo $detail->warranty_detail_id; ?>' /></td>
                                        <td><input type="text" id='<?php echo 'scanned_serial'.$i; ?>' name='scanned_serial[]' class='form-control scannedSerial' value="<?php echo $detail->serial_number; ?>" required /></td>
                                        <td><span id='<?php echo 'description'.$i; ?>'><?php echo $detail->item_description; ?></span></td>
                                        <td><span id='<?php echo 'quantity'.$i; ?>' >1</span></td>
                                        <td><span id='<?php echo 'invoice_code'.$i; ?>'><?php echo $detail->invoice_code; ?></span><input type='hidden' name='invoice_id[]' id='<?php echo 'invoice_id'.$i; ?>' value='<?php echo $detail->invoice_id; ?>' /></td>
                                        <td><input type='text' class='form-control form-control-sm' name='remarks[]' id='<?php echo 'remarks'.$i; ?>' value='<?php echo $detail->remarks; ?>' /></td>
                                        <td style='text-align:center'><a href='javascript:remove_row(<?= $i ?>)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
                                    </tr>
                                    <?php $i++; } ?>
                                  <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?><input type='hidden' name='warranty_detail_id[]' /></td>
                                        <td><input type="text" id='<?php echo 'scanned_serial'.$i; ?>' class='form-control scannedSerial' required /></td>
                                        <td><span id='<?php echo 'description'.$i; ?>'></span></td>
                                        <td><span id='<?php echo 'quantity'.$i; ?>' ></span></td>
                                        <td><span id='<?php echo 'invoice_code'.$i; ?>'></span><input type='hidden' name='invoice_id[]' id='<?php echo 'invoice_id'.$i; ?>' value='' /></td>
                                        <td><input type='text' class='form-control form-control-sm' name='remarks[]' id='<?php echo 'remarks'.$i; ?>' value='' /></td>
                                        <td style='text-align:center'><a href='javascript:remove_row(<?= $i ?>)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
                                    </tr>
                                                
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=<?= $i; ?> />
                    <input type='hidden' id='next_id' name='next_id' value=<?= $i+1; ?> />
                    <div class="clearfix"></div>
                    <br>
                    
                </div>
                
            </div>
            
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary">Update Warranty Claim</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
    </form>
        
                                     
    

    

<script>

  
function add_row(){

    var row_count = $('#row_count').val();
    var i = parseInt($('#next_id').val());
    // Build the row HTML
    let rowContent = `
        <tr id='addr${i}'>
            <td style='text-align:center'><span class='serial-number'></span></td>
            <td><input type="text" id= 'scanned_serial${i}' class='form-control scannedSerial' placeholder="Scan barcode" autofocus required /></td>
            <td><span id='description${i}'></span><input type='hidden' name='item_id[]' id='item_id${i}' /></td>
            <td><span id='quantity${i}'></span></td>
            <td><span id='invoice_code${i}'></span><input type='hidden' name='invoice_id[]' id='invoice_id${i}' /></td>
            <td><input type='text'class='form-control form-control-sm' name='remarks[]' id='remarks${i}' /></td>
            <td><a href='javascript:remove_row(${i})' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
        </tr>`;

    $('#mytbody').append(rowContent);
    

     row_count++;
    $('#row_count').val(row_count);
    i++;
    $('#next_id').val(i);
    updateSerialNumbers();
}

function updateSerialNumbers() {
    const rows = document.querySelectorAll("#mytbody tr");
    rows.forEach((row, index) => {
		
        if(row.querySelector(".serial-number")){
			row.querySelector(".serial-number").textContent = index + 1;
		}
    });
    
}

    


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
                    if(details !== null && details.invoice_code !== null){
                        $('#'+inputId).css('background-color', '#d4edda');
                        $('#description'+row_no).text(details.item_model+' '+details.item_description);
                        $('#quantity'+row_no).text(1);
                        $('#invoice_code'+row_no).text(details.invoice_code);
                        $('#item_id'+row_no).val(details.item_id);
                        $('#invoice_id'+row_no).val(details.invoice_id);
                         var nextRow = document.getElementById('addr'+row_no).nextElementSibling;
                    
                        if(!nextRow )
                            add_row();
                    }
                    else{
                        $('#'+inputId).css('background-color', '#f3644eff');
                         $('#warranty_detail_id'+row_no).val('');
                        $('#description'+row_no).text('');
                        $('#quantity'+row_no).text('');
                        $('#invoice_code'+row_no).text('');
                    }
                    
                
                    
                }
                });
                
               
            //}

            
        }
});

function remove_row(row){
        var num_rows = $('#row_count').val();
        $('#addr'+row).remove();
        num_rows--;
        $('#row_count').val(num_rows);
    }
</script>   
      
            