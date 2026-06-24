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
        
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_delivery_note' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Invoice <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="invoice_id" id="invoice_id" onchange='this.form.submit()'>
                        <option value="">Select Invoice</option>
                        <?php foreach($invoices as $inv) { ?>
                            <option value="<?php echo $inv['invoice_id'];?>" <?php if(isset($invoice) && $inv['invoice_id'] == $invoice['invoice_id']) echo 'selected'; ?>><?php echo $inv['invoice_code'];?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($invoice)){ 
            $vat_percent = $invoice['vat_percent']/100;?>
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_delivery_note_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='invoice_id' value='<?php echo $invoice['invoice_id']; ?>' />
                <input type='hidden' name='pi_id' value='<?php echo $invoice['pi_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Delivery date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y'); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Delivery Code </span>
                        <span class="value text-success"> <?php echo $dn_code; ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $invoice['customer_name']; ?>  </span>
                    </li>
                    
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr style='height:50px'>
                                        <td style='width:3%' style='text-align:center'>Sl No</td>
                                        <td style='width:20%'>Model/Details</td>
                                        <td style='width:5%;text-align:center'>Brand</td>
                                        <td style='width:5%;text-align:center'>Invoiced Qty</td>
                                        <td style='width:20%;text-align:center'>Allocated Serial No</td>
                                        <td style='width:10%;text-align:center'>Scan Barcode</td>
                                        <td style='width:5%;text-align:center'>Delivery Qty</td>
                                        <td style='width:20%;text-align:center'>Serial No</td>
                                        <td style='width:10%;text-align:center'>Remarks</td>
                                        <td style='width:2%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;
                                foreach($invoice_details as $detail){ 
                                        $quantity = $detail->pending_quantity;
                                    if($quantity > 0){ ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?></td>
                                        <td><?php echo $detail->item_model.' '.$detail->item_description ?><input type='hidden' name='invoice_detail_id[]' id='<?php echo 'invoice_detail_id'.$i; ?>' value='<?php echo $detail->invoice_detail_id; ?>' /></td>
                                        <td style='text-align:center'><?php echo $detail->brand_name; ?></td>
                                        <td style='text-align:center'><input type='text' step='any' class='form-control' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='<?php echo $detail->pending_quantity; ?>' readonly/></td>
                                        <td style='text-align:center'><?php echo $detail->stock_ids; ?><input type='hidden' name='allocated_serials[]' id='<?php echo 'allocated_serials'.$i; ?>' value='<?php echo $detail->stock_ids; ?>' /></td>
                                        <td style='text-align:center'>
                                            <input type="text" id='<?php echo 'scanned_serial'.$i; ?>' class='form-control scannedSerial' placeholder="Scan barcode..." autofocus />
                                            <div id='<?php echo 'scanStatus'.$i; ?>'></div>
                                        </td>
                                        <td style='text-align:center'><input type='text' class='form-control' name='delivery_quantity[]' id='<?php echo 'delivery_quantity'.$i; ?>' readonly /></td>
                                        <td><ul id='<?php echo 'scannedList'.$i; ?>'></ul><input type='hidden' name='scannedSerials[]' id='<?php echo 'scannedSerials'.$i; ?>' /></td>
                                        <td style='text-align:center'><input type='text' class='form-control' name='remarks[]' id='<?php echo 'remarks'.$i; ?>' /></td>
                                        <td><a href='javascript:delete_row(<?php echo $i; ?>)' title='delete row' class='btn'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                    </tr>
                                    <?php } ?>
                                <?php $i++; }?>     
                                               
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <div class="clearfix"></div>
                    <br>
                   
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" id='submit_button' class="btn btn-success btn-primary">Add Delivery Note</button>
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

            let allocatedSerials_str = $('#allocated_serials' + row_no).val()|| "";
            let allocatedSerials = allocatedSerials_str.split(",");
            let scannedSerials = JSON.parse($('#scannedSerials' + row_no).val() || '[]');
            //let scannedSerials = scannedSerials_str.split(",");
            if (scannedSerials.includes(scanned)) {
                 $('#' + inputId).css('background-color', '#f8d7da'); // red
            } else 
            if (allocatedSerials.includes(scanned)) {
                scannedSerials.push(scanned);
                $('#delivery_quantity'+row_no).val(scannedSerials.length);
                $('#scannedSerials'+row_no).val(JSON.stringify(scannedSerials));
                $('#'+inputId).css('background-color', '#d4edda');
                $('#scannedList'+row_no).append(`<li>${scanned}</li>`);
            } else {
                $('#'+inputId).css('background-color', '#f8d7da');
                //check if the serial number read is not allocated to any order
                // $.ajax({
                // url: '<?= base_url("index.php/Stock/get_item_detail_by_serial_number") ?>', 
                // type: 'POST',
                // data: { serial_no: scanned },
                // success: function(details) {
                //    if(details !== null){
                //         scannedSerials.push(scanned);
                //         $('#delivery_quantity'+row_no).text(scannedSerials.length);
                //         $('#scannedSerials'+row_no).val(JSON.stringify(scannedSerials));
                //         $('#scannedList'+row_no).append(`<li>${scanned}</li>`);
                        
                //    }
                //    else{
                //         alert('Item details not found in Stock!');
                //    }
                    
                // }
                // });
                
               
            }

            resetInput(row_no);
        }
    });
    

    

    
</script>   
      
            