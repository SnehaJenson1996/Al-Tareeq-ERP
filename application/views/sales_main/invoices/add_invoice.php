    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
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
        
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_invoice' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Sales Order <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="pi_id" id="pi_id" onchange='this.form.submit()'>
                        <option value="">Select Sales Order</option>
                        <?php foreach($sales_orders as $so) { ?>
                            <option value="<?php echo $so['pi_id'];?>" <?php if(isset($sales_order) && $so['pi_id'] == $sales_order['pi_id']) echo 'selected'; ?>><?php echo $so['pi_code'];?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($sales_order)){ ?>
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_invoice_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='pi_id' value='<?php echo $sales_order['pi_id']; ?>' />
                <input type='hidden' name='quotation_id' value='<?php echo $sales_order['quotation_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $sales_order['customer_name']; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Invoice date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y'); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Invoice Code </span>
                        <span class="value text-success"> <?php echo $invoice_code; ?> </span>
                    </li>
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:2%' style='text-align:center'>Sl No</td>
                                        <td style='width:10%'>Description</td>
                                        <td style='width:3%;text-align:center'>PI Qty</td>
                                        <td style='width:3%;text-align:center'>Stock Qty</td>
                                        <td style='width:3%;text-align:center'>Inv Qty</td>
                                        <td class='right-align' style='width:5%'>Rate(VAT Incl)</td>
                                        <td class='right-align' style='width:5%'>Rate</td>
                                        <td style='width:5%;text-align:center'>Unit</td>
                                        <td class='right-align' style='width:5%'>Amount</td>
                                        <td class='right-align' style='width:5%'>VAT</td>
                                        <td class='right-align' style='width:5%'>Taxable Value</td>
                                        <td class='right-align' style='width:5%'>VAT </td>
                                        <td class='right-align' style='width:5%'>Total (AED)</td>
                                        <td style='width:2%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;$subtotal=0;$additional_discount=0;$total_quantity=0;$total_taxable=0;$total_vat=0;$total_amt=0;
                                foreach($pi_details as $detail){ ?>
                                    <?php 
                                        $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                        $vat_percent = $sales_order['vat_percent']/100;
                                        $rate = $detail->actual_price;
                                        $discount1_percent = $detail->discount1_percent/100;  
                                        $discount2_percent = $detail->discount2_percent/100; 
                                        $quantity = $detail->pending_quantity; 
                                        $max_qty = $detail->pending_quantity; 
                                        $stock_qty = $detail->current_stock;
                                        if($stock_qty < $max_qty){
                                            $max_qty = $stock_qty;
                                        }
                                        $total_quantity += $quantity;
                                        if($discount1_percent > 0)
                                            $rate = $rate - ($rate * $discount1_percent);
                                        
                                        if($discount2_percent > 0)
                                            $discount2_amount = ($rate * $quantity) * $discount2_percent ;
                                        $rate_incl_vat = $rate + ($rate * $vat_percent);
                                        $taxable_amount = $rate * $quantity;
                                        $total_taxable += $taxable_amount;
                                        $vat_amount = $taxable_amount * $vat_percent;
                                        $total_vat += $vat_amount;
                                        $total = $taxable_amount + $vat_amount;
                                        $total_amt += $total;
                                        $additional_discount += $discount2_amount;
                                    ?>
                                    <?php if($quantity > 0){ ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?></td>
                                        <td><?php echo $detail->item_model.' '.$detail->item_description ?><input type='hidden' name='detail_id[]' id='<?php echo 'detail_id'.$i; ?>' value='<?php echo $detail->pi_detail_id; ?>' /><input type='hidden' name='item_id[]' id='<?php echo 'item_id'.$i; ?>' value='<?php echo $detail->item_id; ?>' /></td>
                                        <td style='text-align:center'><?php echo $detail->pending_quantity; ?></td>
                                        <td style='text-align:center'><?php echo $stock_qty; ?></td>
                                        <td><input type='number' max='<?= $max_qty; ?>' class='form-control quantity' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='' oninput='calculate_row_values(<?php echo $i; ?>)' /></td>
                                        <td class='right-align'><?php echo $rate_incl_vat; ?></td>
                                        <td class='right-align'><?php echo $rate; ?><input type='hidden' name='rate[]' id='<?php echo 'rate'.$i; ?>' value='<?php echo $rate; ?>' /></td>
                                        <td style='text-align:center' ><?php echo $detail->unit_name; ?></td>
                                        <td class='right-align'><span id='<?php echo 'amount_'.$i; ?>'><?php echo $taxable_amount; ?></span><input type='hidden' name='discount2_percent[]' id='<?php echo 'discount2_percent_'.$i; ?>' value='<?php echo $detail->discount2_percent; ?>' /></td>
                                        <td class='right-align'><?php echo $sales_order['vat_percent'].'%'; ?></td>
                                        <td class='right-align taxable_amount' id='<?php echo 'taxable_amount_'.$i; ?>'><?php echo $taxable_amount; ?></td>
                                        <td class='right-align vat_amount' id='<?php echo 'vat_amount_'.$i; ?>'><?php echo $vat_amount; ?></td>
                                        <td class='right-align' id='<?php echo 'total_'.$i; ?>' ><?php echo $total; ?></td>
                                        <td><a href='javascript:delete_row(<?php echo $i; ?>)' title='delete row' class='btn'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                        
                                    </tr>
                                    <?php } ?>
                                <?php $i++; }?>     
                                <tr class='right-align'>
                                    <td></td>
                                    <td>Total</td>
                                    <td></td><td></td>
                                    <td id='total_quantity' style='text-align:center'><?php echo $total_quantity; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id='total_amount'><?php echo $total_taxable; ?></td>
                                    <td></td>
                                    <td id='total_taxable'><?php echo $total_taxable; ?></td>
                                    <?php $total_vat_amount = ($total_taxable-$additional_discount)*$vat_percent; ?>
                                    <td><input type='hidden' id='vat_percent' name='vat_percent' value='<?php echo $sales_order['vat_percent']; ?>' /><span id='total_vat'><?php echo $total_vat_amount; ?></span></td>
                                    <td id='g_total'><?php echo $total_amt; ?></td>
                                </tr>                 
                                </tbody>
                    </table>     
                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <div class="clearfix"></div>
                    <br>
                    <div class="item form-group">
                                <div class="col-md-7"></div>
                                <label class="col-form-label col-md-2 col-sm-2 label-align" >Taxable value <span class="required">*</span></label>
                                <div class="col-md-2"><input type='text' name='subtotal' id='subtotal' class='form-control' readonly value='<?php echo number_format($total_taxable,2,'.',''); ?>'/></div>
                    </div>   
                    <div class="item form-group">
                                
                                <div class="col-md-7"></div>
                                <label class="col-form-label col-md-2 col-sm-2 label-align" >Additional discount <span class="required">*</span></label>
                                <div class="col-md-2"><input type='text' name='total_discount2' id='total_discount2' class='form-control' readonly value='<?php echo number_format($additional_discount,2,'.',''); ?>' /></div>
                    </div>
                    <div class="item form-group">
                                <div class="col-md-7"></div>
                                <label class="col-form-label col-md-2 col-sm-2 label-align" >VAT <span class="required">*</span></label>
                                <div class="col-md-2">
                                <input type='text' name='total_vat_amount' id='total_vat_amount' class='form-control' readonly value='<?php echo number_format($total_vat_amount,2,'.',''); ?>'/>
                                </div>
                                
                    </div>
                    <div class="item form-group">
                                <div class="col-md-8"></div>
                                    
                                <label class="col-form-label col-md-1 col-sm-1 label-align" >Grand Total <span class="required">*</span></label>
                                <div class="col-md-1">
                                    <?php $grand_total = ($total_taxable-$additional_discount)+$total_vat_amount; ?>
                                    <input type='text' name='grand_total' id='grand_total' class='form-control' readonly value='<?php echo number_format($grand_total,2,'.',''); ?>' />
                                </div>
                    </div>
                    <h6>Company Bank Details</h6>
                    <div class="item form-group">
                        <table class="table table-bordered table-hover" >
                            <thead>
                                <tr>
                                        <th title="Item">Select</th>
                                        <th title="Item">Bank Name</th>
                                        <th title="Item">Bank Account</th>    
                                        <th title="Item">Bank Branch</th>    
                                        <th title="Item">Bank IBAN</th>    
                                        <th title="Item">Bank SWIFT</th>   
                                </tr>
                                </thead>		 
                                <tbody id="mytbbody">
                                <?php foreach($bank_details as $b){?>
                                    <tr style='font-size: 13px;'>
                                    <td width='30px'>
                                    <input type='radio' name='bank' value='<?php echo $b['bid'];?>' checked />
                                    </td>
                                    <td><input type="text" name="bname_old[]"  tabindex='35' class="form-control" placeholder="" value="<?php echo $b['bank_name'];?>" readonly></td>
                                    <td><input type="text" name="bacc_old[]"  tabindex='35' class="form-control" placeholder="" value="<?php echo $b['bank_account'];?>"readonly ></td>
                                    <td><input type="text" name="bbranch_old[]"  tabindex='35' class="form-control" placeholder="" value="<?php echo $b['bank_branch'];?>" readonly></td>
                                    <td><input type="text" name="biban_old[]"  tabindex='35' class="form-control" placeholder="" value="<?php echo $b['bank_iban'];?>" readonly></td>
                                    <td><input type="text" name="bswift_old[]"  tabindex='35' class="form-control" placeholder="" value="<?php echo $b['bank_swift'];?>" readonly></td>
                                </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary" id='save_button' disabled>Generate Invoice</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
        </form>
        <?php } ?>
                                     
    

    

<script>
    function delete_row(row){
        $('#addr'+row).remove();
        var row_count = $('#row_count').val();
        row_count--;
        $('#row_count').val(row_count);
        updateSerialNumbers();
        calculate_total_quantity();
    }

    function updateSerialNumbers() {
        const rows = document.querySelectorAll("#mytbody tr");
        rows.forEach((row, index) => {
            
            if(row.querySelector(".serial-number")){
                row.querySelector(".serial-number").textContent = index + 1;
            }
        });
    }

    function calculate_row_values(row_no){
        
        var quantity = $('#quantity'+ row_no).val() || 0;
        var quote_price = $('#rate'+row_no).val() || 0; 
        var vat_percent = $('#vat_percent').val();

        // //calculating taxable amount
        var taxable_amount = (quote_price * quantity).toFixed(2);
        $('#taxable_amount_'+row_no).text(taxable_amount);
        $('#amount_'+row_no).text(taxable_amount);

        //calculating vat
        var vat_amount = taxable_amount*(vat_percent/100);
        $('#vat_amount_'+row_no).text(vat_amount.toFixed(2));

        //calculate total
        var total = (parseFloat(taxable_amount) + parseFloat(vat_amount)).toFixed(2);
        $('#total_'+row_no).text(total);

        calculate_total_quantity();
        
    }

    function calculate_total_quantity(){
        var i_value=0;i_total=0;
         $('.quantity').each(function()
        {
            i_value=$(this).val();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#total_quantity').text(i_total.toFixed(2));
        
        calculate_subtotal();
    }

    function calculate_subtotal(){
        var i_value=0;i_total=0;
        
        $('.taxable_amount').each(function()
        {
            i_value=$(this).text();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#total_amount').text(i_total.toFixed(2));
        $('#total_taxable').text(i_total.toFixed(2));
        $('#subtotal').val(i_total.toFixed(2));

        calculate_total_additional_discount();
    }

    function calculate_total_additional_discount(){
        var i_total=0;
        var row_count=$('#row_count').val();
        for(var i=0; i< row_count; i++){
            i_value=0;
            if ($('#amount_'+i).length){
                var taxable_amount = parseFloat($('#amount_'+i).text());
                var additional_disount_percent = parseFloat($('#discount2_percent_'+i).val());
                i_value = parseFloat(taxable_amount * (additional_disount_percent/100));
                i_total += i_value;
            }
            
            
        }
        $('#total_discount2').val(i_total.toFixed(2));

        calculate_total_vat_amount();
    }

    function calculate_total_vat_amount(){
        var i_value=0;i_total=0;
        $('.vat_amount').each(function()
        {
            i_value=$(this).text();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#total_vat').text(i_total.toFixed(2));

        calculate_grand_total();

    }

    function calculate_grand_total(){
        var subtotal = parseFloat($('#subtotal').val());
        var total_discount2 = parseFloat($('#total_discount2').val());
        var vat_percent = $('#vat_percent').val();
        var vat_amount = ((subtotal-total_discount2)*(vat_percent/100)).toFixed(2);
        $('#total_vat_amount').val(vat_amount);
        var grand_total = ((subtotal - total_discount2)+parseFloat(vat_amount)).toFixed(2);
        $('#grand_total').val(grand_total);
        var vat_amount2 = parseFloat($('#total_vat').text());
        $('#g_total').text(parseFloat(subtotal)+parseFloat(vat_amount2));
        if(grand_total == 0){
            $('#save_button').prop('disabled',true);
        }
        else{
            $('#save_button').prop('disabled',false);
        }

    }

    

    

    
</script>   
      
            