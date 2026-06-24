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
        
    <form action='<?php echo base_url().'index.php/'; ?>Warranty/add_warranty_claim_data' method='post' >
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
                                    <tr id='addr0'>
                                    <td style='text-align:center'><span class='serial-number'>1</span></td>
                                    <td><input type="text"  name='scanned_serial[]' id='<?php echo 'scanned_serial0'; ?>' class='form-control scannedSerial' placeholder="Scan barcode" autofocus required /></td>
                                    <td><span id='description0'></span></td>
                                    <td><span id='quantity0'></span></td>
                                    <td><span id='invoice_code0'></span><input type='hidden' name='invoice_id[]' id='invoice_id0' /></td>
                                    <td><input type='text' class='form-control form-control-sm' name='remarks[]' id='remarks0' /></td>
                                    <td style='text-align:center'><a href='javascript:remove_row(0)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
                                   
                                </tr>
                                  
                                                
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=0 />
                    <input type='hidden' id='next_id' name='next_id' value=1 />
                    <div class="clearfix"></div>
                    <br>
                    
                </div>
                
            </div>
            
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary">Add Warranty Claim</button>
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
            <td><input type="text" name='scanned_serial[]' id= 'scanned_serial${i}' class='form-control scannedSerial' placeholder="Scan barcode" autofocus /></td>
            <td><span id='description${i}'></span></td>
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
                        $('#invoice_id'+row_no).val(details.invoice_id);
                        add_row();
                    }
                    else{
                        

                        $('#'+inputId).css('background-color', '#f3644eff');
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
      
            