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
        
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_sales_return' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-1 label-align" >Delivery Note <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="dn_id" id="dn_id" onchange='this.form.submit()'>
                        <option value="">Select Delivery Note</option>
                        <?php foreach($delivery_notes as $dn) { ?>
                            <option value="<?php echo $dn['dn_id'];?>" <?php if(isset($dnote) && $dn['dn_id'] == $dnote['dn_id']) echo 'selected'; ?>><?php echo $dn['dn_code'];?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        </form>
        <div class="clearfix"></div>
        <?php if(isset($dnote)){ ?>
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_sales_return_data' method='post' >
            <div class="x_content">
                <input type='hidden' name='dn_id' value='<?php echo $dnote['dn_id']; ?>' />
                <input type='hidden' name='invoice_id' value='<?php echo $dnote['invoice_id']; ?>' />
                <input type='hidden' name='pi_id' value='<?php echo $dnote['pi_id']; ?>' />
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $dnote['customer_name']; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Return date </span>
                        <span class="value text-success"> <?php echo date('d-M-Y'); ?>   </span>
                    </li>
                    
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table id="datatable" class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:2%' style='text-align:center'>Sl No</td>
                                        <td style='width:10%'>Description</td>
                                        <td style='width:3%;text-align:center'>Inv Qty</td>
                                        <td style='width:3%;text-align:center'>Return Qty</td>
                                        <td class='right-align' style='width:5%'>Rate(VAT Incl)</td>
                                        <td class='right-align' style='width:5%'>Rate</td>
                                        <td style='width:5%;text-align:center'>Unit</td>
                                        <td class='right-align' style='width:5%'>Amount</td>
                                        <td class='right-align' style='width:5%'>VAT</td>
                                        <td class='right-align' style='width:5%'>Taxable Value</td>
                                        <td class='right-align' style='width:5%'>VAT </td>
                                        <td class='right-align' style='width:5%'>Total (AED)</td>
                                        <td style='width:5%;text-align:center'>Availability</td>
                                        <td style='width:2%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;$subtotal=0;$additional_discount=0;$total_quantity=0;$total_taxable=0;$total_vat=0;$total_amt=0;
                                foreach($dnote_details as $detail){ ?>
                                    <?php 
                                        $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                        $vat_percent = $dnote['vat_percent']/100;
                                        $rate = $detail->actual_price;
                                        $discount1_percent = $detail->discount1_percent/100;  
                                        $discount2_percent = $detail->discount2_percent/100; 
                                        $quantity = $detail->delivery_quantity; 
                                        $total_quantity += $quantity;
                                        if($discount1_percent > 0)
                                            $rate = $rate - ($rate * $discount1_percent);
                                        
                                        if($discount2_percent > 0)
                                            $discount2_amount = ($rate * $quantity) * $discount2_percent ;
                                        $rate_incl_vat = $rate + ($rate * $vat_percent);
                                        $taxable_amount = round($rate * $quantity,2);
                                        $total_taxable += $taxable_amount;
                                        $vat_amount = round($taxable_amount * $vat_percent,2);
                                        $total_vat += $vat_amount;
                                        $total = $taxable_amount + $vat_amount;
                                        $total_amt += $total;
                                        $additional_discount += $discount2_amount;
                                    ?>
                                    <?php if($quantity > 0){ ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?></td>
                                        <td><?php echo $detail->item_model.' '.$detail->item_description ?><input type='hidden' name='dn_detail_id[]' id='<?php echo 'dn_detail_id'.$i; ?>' value='<?php echo $detail->dn_detail_id; ?>' /><input type='hidden' name='invoice_detail_id[]' id='<?php echo 'invoice_detail_id'.$i; ?>' value='<?php echo $detail->invoice_detail_id; ?>' /><input type='hidden' name='pi_detail_id[]' id='<?php echo 'pi_detail_id'.$i; ?>' value='<?php echo $detail->pi_detail_id; ?>' /></td>
                                        <td style='text-align:center'><input type='hidden' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='<?php echo $detail->delivery_quantity; ?>' /><?php echo $detail->delivery_quantity; ?></td>
                                        <td><input type='text' class='form-control' name='return_quantity[]' id='<?php echo 'return_quantity'.$i; ?>' value='' oninput='calculate_row_values(<?php echo $i; ?>)' required /></td>
                                        <td class='right-align'><?php echo $rate_incl_vat; ?></td>
                                        <td class='right-align'><?php echo $rate; ?><input type='hidden' name='rate[]' id='<?php echo 'rate'.$i; ?>' value='<?php echo $rate; ?>' /></td>
                                        <td style='text-align:center' ><?php echo $detail->unit_name; ?></td>
                                        <td class='right-align'><span id='<?php echo 'amount_'.$i; ?>'><?php echo $taxable_amount; ?></span><input type='hidden' name='discount2_percent[]' id='<?php echo 'discount2_percent_'.$i; ?>' value='<?php echo $detail->discount2_percent; ?>' /></td>
                                        <td class='right-align'><?php echo $dnote['vat_percent'].'%'; ?></td>
                                        <td class='right-align taxable_amount' ><span id='<?php echo 'taxable_amount_'.$i; ?>'><?php echo $taxable_amount; ?></span><input type='hidden' name='item_total[]' id='<?php echo 'item_total_'.$i; ?>' value='<?php echo $taxable_amount; ?>' /></td>
                                        <td class='right-align vat_amount' id='<?php echo 'vat_amount_'.$i; ?>'><?php echo $vat_amount; ?></td>
                                        <td class='right-align' id='<?php echo 'total_'.$i; ?>' ><?php echo $total; ?></td>
                                        <td style='text-align:center'>Stock</td>
                                        <td></td>
                                    </tr>
                                    <?php } ?>
                                <?php $i++; }?>     
                                <tr class='right-align'>
                                    <td></td>
                                    <td>Total</td>
                                    <td id='total_quantity' style='text-align:center'><?php echo $total_quantity; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id='total_amount'><?php echo $total_taxable; ?></td>
                                    <td></td>
                                    <td id='total_taxable'><?php echo $total_taxable; ?></td>
                                    <td><input type='hidden' id='vat_percent' name='vat_percent' value='<?php echo $dnote['vat_percent']; ?>' /><span id='total_vat'><?php echo $total_vat; ?></span></td>
                                    <td id='g_total'><?php echo $total_amt; ?></td>
                                </tr>                 
                                </tbody>
                    </table>     
                    
                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <div class="clearfix"></div>
                    <br>
                    <div class="item form-group">
                                <div class="col-md-7">
                                    <label class="col-form-label col-md-1 col-sm-1 label-align" >Note<span class="required">*</span></label>
                                    <div class="col-md-8">
                                    <textarea name='notes' rows=2 cols=25 class='form-control' required></textarea>
                                    </div>
                                </div>
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
                                <?php $total_vat_amount = ($total_taxable-$additional_discount)*$vat_percent; ?>
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
                </div>
            </div>
            <div class="x_content">
                        
                        <div class="item form-group">  
                            <div class="col-md-4"> 
                            
                            </div>         
                            <div class="col-md-8">
                            <button type="submit" class="btn btn-success btn-primary">Add Sales Return</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
        </form>
        <?php } ?>
                                     
    

    

<script>
function calculate_row_values(row_no){
        
        var current_quantity = parseFloat($('#quantity'+row_no).val()||0) - parseFloat($('#return_quantity'+row_no).val()||0);
        var quote_price = $('#rate'+row_no).val() || 0; 
        var vat_percent = $('#vat_percent').val();
    
        //calculating taxable amount
        var taxable_amount = (quote_price * current_quantity).toFixed(2);
        $('#taxable_amount_'+row_no).text(taxable_amount);
        $('#amount_'+row_no).text(taxable_amount);
        var additional_disount_percent = parseFloat($('#discount2_percent_'+row_no).val());
        var item_total = parseFloat(taxable_amount-(taxable_amount * (additional_disount_percent/100)));
        $('#item_total_'+row_no).val(item_total.toFixed(2));

        //calculating vat
        var vat_amount = (taxable_amount*(vat_percent/100)).toFixed(2);
        $('#vat_amount_'+row_no).text(vat_amount);

        //calculate total
        var total = (parseFloat(taxable_amount) + parseFloat(vat_amount));
        $('#total_'+row_no).text(total.toFixed(2));

        calculate_total_quantity();
    }

    function calculate_total_quantity(){
        
        var row_count = $('#row_count').val();var i_total=0;
        for(var i=0;i<row_count;i++){
            var curr_qty = parseFloat($('#quantity'+i).val()||0) - parseFloat($('#return_quantity'+i).val()||0);
            if(curr_qty=='')
                curr_qty = 0;
            else{
                i_total+=parseFloat(curr_qty);
            }
            $('#total_quantity').text(i_total.toFixed(2));
        }
        
        calculate_subtotal();
    }

    function calculate_subtotal()
    {
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

    function calculate_total_additional_discount()
    {
        var i_total=0;
        var row_count=$('#row_count').val();
        for(var i=0; i< row_count; i++){
            i_value=0;
            var taxable_amount = parseFloat($('#taxable_amount_'+i).text());
            var additional_disount_percent = parseFloat($('#discount2_percent_'+i).val());
            i_value = parseFloat(taxable_amount * (additional_disount_percent/100));
            i_total += i_value;
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
    }
    

    

    
</script>   
      
            