<style>
  body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }
</style>
<?php 
    $page_name='Sales/list_estimations';
	$page_name2='Sales/list_quotations';
	$user = $this->session->userdata('user_id');
?>
<div class="clearfix"></div>
        
        <form id='myForm' action='<?php echo base_url().'index.php/'; ?>Sales/update_quotation_data' method='post' >
        <div class="x_content">
        <div class="item form-group">
            <label class="col-form-label col-md-2 col-sm-2 label-align" > Quotation Date</label>
			<div class="col-md-3 col-sm-3 ">
                <input type='date' name='quotation_date' class='form-control' value='<?php echo $quotation['quotation_date'];?>' />
			</div>

            <label class="col-form-label col-md-1 col-sm-1 label-align" >Quote#</label>
			<div class="col-md-3 col-sm-3 ">
                <input type='text' name='quotation_code' class='form-control' readonly value='<?php echo $quotation['quotation_code'];?>' />
			</div>

        </div>
        <div class="item form-group">
            <label class="col-form-label col-md-2 col-sm-2 label-align" >Attention</label>
			<div class="col-md-3 col-sm-3 ">
                <select name='customer_contact' class='form-control' required >
                    <option value=''>Please Select</option>
                    <?php foreach($customer_contacts as $contact){ ?>
                        <option value='<?php echo $contact->contact_id;?>' <?php if($quotation['customer_contact_id'] == $contact->contact_id) echo 'selected'; ?>><?php echo $contact->contact_name;?></option>
                    <?php } ?>
                </select>
			</div>

			

            <label class="col-form-label col-md-1 col-sm-2 label-align">Estimation</label>
			<div class="col-md-2 col-sm-2 ">
                <span class='form-control'><a  href='<?php echo base_url().'index.php/Sales/edit_estimation/'.$quotation['estimation_id'].'/0'; ?>' id='estimation_link'><?php echo $quotation['estimation_code']; ?></a></span>
			</div>
		</div>
        <div class="item form-group">
            

            <label class="col-form-label col-md-2 col-sm-2 label-align" >Valid till</label>
            <div class="col-md-3 col-sm-3 ">
                <input type='date' name='quotation_validity' class='form-control' value='<?php echo $quotation['validity']; ?>' required/> 
            </div>

			
		</div>
        <div class="item form-group">
            <input type='hidden' name='quotation_id' id='quotation_id' value='<?php echo $quotation['quotation_id']; ?>' />
            <input type='hidden' name='estimation_id' value='<?php echo $quotation['estimation_id']; ?>' />
           
        </div>
                        
        </div>
        <div class="clearfix"></div>
        

        <div class="clearfix"></div>
        <div class="x_content" id='quotation_table' style='overflow: auto;'>

                    <table id="datatable" class="table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <td style='width:2%'>Sl No</td>
                                <td style='width:10%'>Model</td>
                                <td style='width:3%'>Brand</td>
                                <td style='width:18%'>Description</td>
                                <td style='width:5%'>Qty</td>
                                <td style='width:5%'>Unit</td>
                                <td style='width:3%'>AVE Stock</td>
                                <td style='width:7%'>Actual Price</td>
                                <td style='width:5%'>Disc1 (%)</td>
                                <td style='width:5%'>Disc1 (AED)</td>
                                <td style='width:5%'>Disc2 (%)</td>
                                <td style='width:5%'>Disc2 (AED)</td>
                                <td style='width:5%'>Unit Price</td>
                                <td style='width:7%'>Taxable Amt</td>
                                <td style='width:5%'>VAT </td>
                                <td style='width:5%'>Total (AED)</td>
                                <td class='section' style='width:5%;display:none'>Sections</td>
                            </tr>
                        </thead>
                        <tbody id='mytbody'>
                            <?php $i=0;$subtotal=0;$additional_discount=0;$total_vat=0;
                            foreach($quotation_details as $detail){ ?>
                            <?php 
                                $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                $vat_percent = $quotation['vat_percent']/100;
                                $actual_price = $detail->actual_price;
                                $discount1_percent = $detail->discount1_percent/100;  
                                $discount2_percent = $detail->discount2_percent/100; 
                                $quantity = $detail->quantity; 
                                if($discount1_percent > 0)
                                    $discount1_amount = ($actual_price * $discount1_percent) * $quantity;
                                if($discount2_percent > 0)
                                    $discount2_amount = (($actual_price * $quantity)-$discount1_amount) * $discount2_percent ;
                                $unit_price = $actual_price - ($actual_price*$discount1_percent);
                                $taxable_amount = $unit_price * $quantity;
                                $vat_amount = $taxable_amount * $vat_percent;
                                $total = $taxable_amount + $vat_amount;
                                $subtotal += $taxable_amount;
                                $additional_discount += $discount2_amount;
                                $total_vat+=$vat_amount;


                            ?>
                            <tr id='<?php echo 'addr'.$i; ?>'>
                                <td class='serial-number' style='text-align:center'><?php echo $i+1; ?></td>
                                <td><?php echo $detail->item_model; ?></td>
                                <td><?php echo $detail->brand_name; ?></td>
                                <td><?php echo $detail->item_description; ?></td>
                                <td><?php echo $detail->quantity; ?></td>
                                <td><?php echo $detail->unit_name; ?></td>
                                <td><?php echo $detail->current_stock; ?></td>
                                <td><?php echo $actual_price; ?></td>
                                <td><?php echo $detail->discount1_percent; ?></td>
                                <td><?php echo $discount1_amount; ?></td>
                                <td><?php echo $detail->discount2_percent; ?></td>
                                <td><?php echo $discount2_amount; ?></td>
                                <td><?php echo $unit_price; ?></td>
                                <td><?php echo $taxable_amount; ?></td>
                                <td><?php echo $vat_amount; ?></td>
                                <td><?php echo $total; ?></td>
                                
                                <td class='section' style='display:none'><input type='text' name='section_title[]' value='<?php echo $detail->section_title; ?>' /><input type='hidden' name='detail_id[]' value='<?php echo $detail->detail_id; ?>' /></td>
                                
                            </tr>   
                            <?php $i++; }?>  
                            <tr>
                                <td colspan=13></td><td colspan=2><b>Subtotal</b></td><td colspan=2><b><?php echo number_format($subtotal,2); ?></b></td>
                            </tr> 
                            <tr>
                                <td colspan=13></td><td colspan=2><b>Additional Discount</b></td><td colspan=2><b><?php echo number_format($additional_discount,2); ?></b></td>
                            </tr> 
                            <tr>
                                <td colspan=13></td><td colspan=2><b>VAT(<?php echo $quotation['vat_percent'].'%';  ?>)</b></td><td colspan=2><b><?php echo number_format($vat_amount,2); ?></b></td>
                            </tr> 
                            <tr>
                                <td colspan=13></td><td colspan=2><b>Total(EX-DXB)</b></td><td colspan=2><b><?php echo number_format($total,2); ?></b></td>
                            </tr>   
                        </tbody>
                    </table>     
                    <br>
                    <!-- <div class="clearfix"></div> -->
                    
                    
                    
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Notes</label>
                        <div class="col-md-11 col-sm-11 ">
                            <textarea name='notes' rows=2 cols=25 class='form-control' required <?= isset($view_only) ? 'readonly' : '' ?> ><?php echo $quotation['notes']; ?></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                       <label class="col-form-label col-md-1 col-sm-1 label-align" >Payment</label>
                        <div class="col-md-5 col-sm-5 ">
                            <input type='text' name='payment' class='form-control'  value='<?php echo $quotation['notes']; ?>' required <?= isset($view_only) ? 'readonly' : '' ?> />
                        </div>
		            </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >DELIVERY <span class="required">*</span></label>
                        <div class="col-md-7">
                        <input type='text' name='delivery' class='form-control'  value='<?php echo $quotation['delivery']; ?>' required <?= isset($view_only) ? 'readonly' : '' ?> />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >AVAILABILITY </label>
                        <div class="col-md-7">
                            <input type='text' name='availability' class='form-control'  value='<?php echo $quotation['availability']; ?>' required <?= isset($view_only) ? 'readonly' : '' ?> />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Warranty</label>
                        <div class="col-md-11 col-sm-11 ">
                            <input type='text' name='warranty' class='form-control'  value='<?php echo $quotation['warranty']; ?>' required <?= isset($view_only) ? 'readonly' : '' ?> />

                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Conditions</label>
                        <div class="col-md-11 col-sm-11 ">
                        <textarea name='conditions' rows="6" cols="60" class='form-control' required <?= isset($view_only) ? 'readonly' : '' ?> ><?php echo $quotation['conditions']; ?></textarea>

                        </div>
                    </div>

                
                   
                    <?php if($view_only==0){ ?>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Print Options</label>
                        <div class="col-md-11 col-sm-11 ">                            
                            <input type="checkbox" name="print_coo" value="1" <?php if($quotation['print_coo']==1) echo 'checked';?> > Country of origin</label><br>
                            <input type="checkbox" name="print_hsc" value="1" <?php if($quotation['print_hsc']==1) echo 'checked';?> > HS Code</label><br>
                            <input type="checkbox" name="print_stock" value="1" <?php if($quotation['print_stock']==1) echo 'checked';?>> Stock availability</label><br>
                            <input type="checkbox" name="print_sections" value="1" <?php if($quotation['print_sections']==1) echo 'checked';?>> Sections</label><br>

                        </div>
                    </div>
                    <?php } ?>

                    <div class="item form-group" >  
                        <div class="col-md-2"></div>
                        
                        
                            <?php if($quotation_approval_status == 1){ ?>
                                <?php if(has_access($user,$page_name,'E')){ ?>
                                <div class="col-md-3">
                                    <button type="button" onclick="revise_po(<?php echo $quotation['quotation_id']; ?>,1)" class="btn btn-success btn-primary">Update Quotation</button>
                                </div>
                                <?php } ?>
                                <div class="col-md-3">
                                    <button type="button" onclick='print_quotation()' class="btn btn-success btn-primary">Print Quotation</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" onclick='add_so()' class="btn btn-success btn-primary">Add Sales Order</button>
                                </div>
                             <?php } 
                             else{ 
                                 if($view_only==0){ ?>
                                 <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-primary">Update Quotation</button>
                                 </div>
                                <?php } ?>
                                    <!-- check for approval status -->
                                <button type="button" onclick='approve_quotation()' class="btn btn-success btn-primary">Approve Quotation</button>
                             <?php } ?> 
                        
                        <div class="col-md-3">
                            
                        </div>
                        
                        
                    </div>
                    
                    
        </div>

        <div class="clearfix"></div>
        </div>
        </form>                                
    

    

<script>
    const base_url = "<?= base_url(); ?>";
    const estimation_id = "<?= $quotation['estimation_id'] ?? ''; ?>";

    function approve_quotation(){
        var quotation_id = $('#quotation_id').val();
        window.location.href = base_url+"index.php/Sales/approve_quotation/" + quotation_id;
    }

    function print_quotation(){
        var quotation_id = $('#quotation_id').val();
        //window.location.href = base_url + "index.php/Sales/print_quotation/" + quotation_id;
        var url = base_url + "index.php/Sales/print_quotation/" + quotation_id;
        window.open(url, '_blank');
    }

    function revise_po(quotation_id,type){
        $.ajax({
                url: base_url +"index.php/Sales/get_sales_order_status_for_quotation", 
                type: 'POST',
                data: { quotation_id: quotation_id },
                dataType:"json",
                success: function(response) {
                    if(response.status == 0){
                        if(type==1){
                            window.location.href = base_url+"index.php/Sales/edit_estimation/"+estimation_id+"/0";
                        }
                        // else{
                        //     cancel_po(quotation_id);
                        // }
                    }
                    else if(response.status == 1){
                        alert(response.message);
                    }
                    else if(response.status == 2){
                        var conf = confirm(response.message);
                        if(conf){
                            $.ajax({
                                url: base_url+"index.php/Sales/cancel_sales_orders_by_quotation", 
                                type: 'POST',
                                data: { quotation_id: quotation_id },
                                dataType:"json",
                                success: function(response) {
                                    if(type==1){
                                        if(response){
                                        window.location.href = base_url+"index.php/Sales/edit_estimation/"+estimation_id+"/0";
                                        }
                                        else{
                                        alert('Something went wrong!');
                                        }
                                    }
                                    // else{
                                    //     cancel_po(quotation_id);
                                    // }
                                }
                            });
                        }
                        
                    }
                    if(type==2 && response.status != 1){
                        cancel_po(quotation_id);
                    }

                    
                }
        });

       
    }

    function cancel_po(quotation_id){
        $.ajax({
            url: base_url+"index.php/Sales/cancel_quotation_by_id", 
            type: 'POST',
            data: { quotation_id: quotation_id },
            success: function(response) {
                console.log(123);
                
               //window.location.href = base_url+"index.php/Sales/list_quotations";
               
                }
            }
        );
    }

    function add_so(){
         var quotation_id = $('#quotation_id').val();
         window.location.href = base_url+"index.php/Sales/add_sales_order/" + quotation_id;
    }

    


   
</script>   
      
            