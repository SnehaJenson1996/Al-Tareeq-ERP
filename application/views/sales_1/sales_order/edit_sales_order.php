<form action='<?php echo base_url().'index.php/'; ?>Sales/update_sales_order_data' method='post' >
        
            <div class="x_content">
                <ul class="stats-overview">
                    <li>
                        <span class="name"> Customer </span>
                        <span class="value text-success"> <?php echo $sales_order['customer_name']; ?>  </span>
                    </li>
                    <li>
                        <span class="name"> Invoice date </span>
                        <span class="value text-success"> <?php echo date('d-m-Y',strtotime($sales_order['pi_date'])); ?>   </span>
                    </li>
                    <li>
                        <span class="name"> Invoice Code </span>
                        <span class="value text-success"> <?php echo $sales_order['pi_code']; ?> </span>
                    </li>
                </ul>
                <input type='hidden' name='pi_id' value='<?php echo $sales_order['pi_id']; ?>' />
                <input type='hidden' name='allocation_id' value='<?php echo $sales_order['allocation_id']; ?>' />
                <input type='hidden' name='quotation_id' value='<?php echo $sales_order['quotation_id']; ?>' />
            </div>

            <div class="clearfix"></div>
            <div class='table-responsive-custom'>
                <div class="x_content">

                    <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <td style='width:2%'>Sl No</td>
                                        <td style='width:10%'>Model</td>
                                        <td style='width:25%'>Description</td>
                                        <td style='width:5%'>Qty</td>
                                        <!-- <td style='width:5%'>Alloc Qty</td> -->
                                        <td class='right-align' style='width:5%'>Rate(VAT Incl)</td>
                                        <td class='right-align' style='width:5%'>Rate</td>
                                        <td style='width:5%;text-align:center'>Unit</td>
                                        <td class='right-align' style='width:10%'>Amount</td>
                                        <td class='right-align' style='width:5%'>VAT</td>
                                        <td class='right-align' style='width:10%'>Taxable Value</td>
                                        <td class='right-align' style='width:5%'>VAT </td>
                                        <td class='right-align' style='width:10%'>Total (AED)</td>
                                        <td style='width:5%;text-align:center'>Availability</td>
                                        <td style='width:20%'></td>
                                    </tr>
                                </thead>
                                <tbody id='mytbody'>
                                <?php $i=0;$subtotal=0;$additional_discount=0;$total_quantity=0;$total_taxable=0;$total_vat=0;$total_amt=0;$vat_percent=0;
                                foreach($pi_details as $detail){ ?>
                                    <?php 
                                        $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                        $vat_percent = $sales_order['vat_percent']/100;
                                        $rate = $detail->actual_price;
                                        $discount1_percent = $detail->discount1_percent/100;  
                                        $discount2_percent = $detail->discount2_percent/100; 
                                        $quantity = $detail->pi_quantity; 
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
                                        $additional_discount += $discount2_amount;
                                        $total_amt += $total;

                                        
                                    ?>
                                    <tr id='<?php echo 'addr'.$i; ?>'>
                                        <td class='serial-number' style='text-align:center'><?php echo $i+1; ?></td>
                                        <td class='serial-number' style='text-align:center'>
                                        <select class="form-control item-select2" name="item[]" id='<?php echo 'item'.$i; ?>' onchange='get_item_by_id(<?php echo $i; ?>)' disabled>
                                        <option value=''>Select</option>
                                        <option value='<?php echo $detail->item_id ?>'  selected><?php echo $detail->item_model; ?></option>
                                        </select>
                                        </td>
                                        <td><?php echo $detail->item_description ?><input type='hidden' name='item_id[]' id='<?php echo 'item_id'.$i; ?>' value='<?php echo $detail->item_id; ?>' /><input type='hidden' name='allocation_detail_id[]' id='<?php echo 'allocation_detail_id'.$i; ?>' value='<?php echo $detail->allocation_detail_id; ?>' /><input type='hidden' name='pi_detail_id[]' id='<?php echo 'pi_detail_id_'.$i; ?>' value='<?php echo $detail->pi_detail_id; ?>' /></td>
                                        <td><input type='number' min='<?= $detail->invoiced_qty; ?>' max='<?= $detail->pi_quantity + $detail->pending_pi_qty ?>' class='form-control quantity' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' value='<?php echo $detail->pi_quantity; ?>' oninput='calculate_row_values(<?php echo $i; ?>)' /></td>
                                         <!-- <td class='right-align'><input type='number' min=0 max='<?php //echo $allocated_stock+$detail->current_stock ?>' class='form-control' name='allocated_quantity[]' id='<?php echo 'allocated_quantity'.$i; ?>' value='<?php echo $detail->allocated_quantity; ?>' readonly /></td> -->
                                        <td class='right-align'><?php echo number_format($rate_incl_vat,2,'.',''); ?></td>
                                        <td class='right-align'><?php echo $rate; ?><input type='hidden' name='rate[]' id='<?php echo 'rate'.$i; ?>' value='<?php echo $rate; ?>' /></td>
                                        <td style='text-align:center' ><?php echo $detail->unit_name; ?></td>
                                        <td class='right-align'><span id='<?php echo 'amount_'.$i; ?>'><?php echo $taxable_amount; ?></span><input type='hidden' name='discount2_percent[]' id='<?php echo 'discount2_percent_'.$i; ?>' value='<?php echo $detail->discount2_percent; ?>' /></td>
                                        <td class='right-align'><?php echo $sales_order['vat_percent'].'%'; ?></td>
                                        <td class='right-align taxable_amount' id='<?php echo 'taxable_amount_'.$i; ?>'><?php echo $taxable_amount; ?></td>
                                        <td class='right-align vat_amount' id='<?php echo 'vat_amount_'.$i; ?>'><?php echo number_format($vat_amount,2,'.',''); ?></td>
                                        <td class='right-align' id='<?php echo 'total_'.$i; ?>' ><?php echo number_format($total,2,'.',''); ?></td>
                                        <td style='text-align:center'><?php if($detail->allocated_quantity>0){if($detail->allocated_quantity >= $detail->pi_quantity) echo 'Ex-Stock';else if($detail->allocated_quantity < $detail->pi_quantity) echo 'Partial';}else { echo 'No-Stock';} ?></td>
                                        <td><a href='javascript:edit_row(<?php echo $i; ?>)' title='Edit row' class='btn'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                    </tr>
                                <?php $i++; }?>    

                                <tr class='right-align'>
                                    <td></td>
                                    <td colspan=2>Total</td>
                                    <td id='total_quantity' style='text-align:center'><?php echo $total_quantity; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id='total_amount'><?php echo number_format($total_taxable,2,'.',''); ?></td>
                                    <td></td>
                                    <td id='total_taxable'><?php echo number_format($total_taxable,2,'.',''); ?></td>
                                    <td><input type='hidden' id='vat_percent' name='vat_percent' value='<?php echo $sales_order['vat_percent']; ?>' /><span id='total_vat'><?php echo $total_vat; ?></span></td>
                                    <td id='g_total'><?php echo number_format($total_amt,2,'.',''); ?></td>
                                </tr>     
                                            
                                </tbody>
                    </table>     
                    <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                    <input type='hidden' name='deleted_detail_ids' id='deleted_detail_ids' value='' />
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
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Supplier Ref <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type='text' name='supplier_ref' class='form-control'  value='<?php echo $sales_order['supplier_ref']; ?>' />
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Other Reference <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type='text' name='other_ref' class='form-control'  value='<?php echo $sales_order['other_ref']; ?>' />
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Dispatch document No <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type='text' name='dispatch_document_number' class='form-control'  value='<?php echo $sales_order['dispatch_document_number']; ?>' />
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Mode/Terms of Payment <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type='text' name='payment_terms' class='form-control'  value='<?php echo $sales_order['payment_terms']; ?>' />
                    </div>
                </div>
                       
                <div class="item form-group">
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Dispatch through</label>
                    <div class="col-md-10 col-sm-10 ">
                        <input type='text' name='dispatch_through' class='form-control'  value='<?php echo $sales_order['dispatch_through']; ?>' />
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-2 col-sm-1 label-align" >Destination</label>
                    <div class="col-md-10 col-sm-10 ">
                        <input type='text' name='destination' class='form-control'  value='<?php echo $sales_order['destination']; ?>' />
                    </div>
                </div>

                         <div class="item form-group">
                            <label class="col-form-label col-md-2 col-sm-1 label-align" >Delivery Terms </label>
                            <div class="col-md-10">
                                <input type='text' name='delivery_terms' class='form-control'  value='<?php echo $sales_order['delivery_terms']; ?>' />
                            </div>
                        </div>
                        
                        <br>
                        <div class="item form-group">  
                            <div class="col-md-6"> 
                            
                            </div>         
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-primary">Save Changes</button>
                            </div>
                        </div>
                
            </div>

            <div class="clearfix"></div>
            </div>
        </form>

<script>
    function edit_row(row){
        var pi_detail_id = $('#pi_detail_id_'+row).val();
        $.ajax({
                url: '<?= base_url("index.php/Sales/get_pi_detail_status") ?>', 
                type: 'POST',
                data: { pi_detail_id: pi_detail_id },
                dataType:"json",
                success: function(response) {
                   if(response !== null && response.invoice_status >= 0){
                    alert('This item is invoiced.Cannot edit');
                   }
                   else{
                    $('#quantity'+row).removeAttr('readonly');
                    // $('#allocated_quantity'+row).removeAttr('readonly');
                    //$('#item'+row).removeAttr('disabled');
                   }
                    
                }
            });
		
        
    }
    function delete_row(row){
        var dlt = confirm("Are you sure to delete this record?");
		if(dlt){
			var pi_detail_id = $('#pi_detail_id_'+row).val();
			var deleted = $('#deleted_detail_ids').val();
			if(deleted == ''){
				deleted = pi_detail_id;
			}
			else{
				deleted = deleted +','+pi_detail_id;
			}
			$('#deleted_detail_ids').val(deleted);
			
            var row_count = $('#row_count').val();
            row_count--;
            $('#row_count').val(row_count);
			$('#addr'+row).remove();
            calculate_total_quantity();
            updateSerialNumbers();
		}
        
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
            var taxable_amount = parseFloat($('#amount_'+i).text());
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